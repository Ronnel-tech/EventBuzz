<?php

use PHPUnit\Framework\TestCase;

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require_once APP_ROOT . '/app/models/AttendeeEventModel.php';
require_once APP_ROOT . '/scheme/Database.php';

final class AttendeeEventModelTest extends TestCase
{
    public function testSummarizeSelectedTicketsCalculatesTotals(): void
    {
        $model = new FakeAttendeeEventModel([
            [
                'id' => 10,
                'event_id' => 99,
                'name' => 'Regular',
                'price' => 150.00,
                'quantity' => 100,
                'sold' => 20,
                'status' => 'open',
                'tickets_left' => 80,
            ],
            [
                'id' => 11,
                'event_id' => 99,
                'name' => 'VIP',
                'price' => 300.00,
                'quantity' => 50,
                'sold' => 10,
                'status' => 'open',
                'tickets_left' => 40,
            ],
        ]);

        $result = $model->summarizeSelectedTickets(99, [
            10 => 2,
            11 => 1,
        ]);

        $this->assertTrue($result['success']);
        $this->assertCount(2, $result['summary']['line_items']);
        $this->assertSame(600.0, $result['summary']['total_amount']);
    }

    public function testSummarizeSelectedTicketsRejectsOverLimitRequest(): void
    {
        $model = new FakeAttendeeEventModel([
            [
                'id' => 10,
                'event_id' => 99,
                'name' => 'Regular',
                'price' => 150.00,
                'quantity' => 100,
                'sold' => 98,
                'status' => 'open',
                'tickets_left' => 2,
            ],
        ]);

        $result = $model->summarizeSelectedTickets(99, [
            10 => 3,
        ]);

        $this->assertFalse($result['success']);
        $this->assertSame('Only 2 ticket(s) are left for Regular.', $result['message']);
    }

    public function testCreateOrderWithItemsStoresOrderItemsAndTickets(): void
    {
        $database = new FakeDatabase();
        $model = new FakeAttendeeEventModel([
            [
                'id' => 10,
                'event_id' => 99,
                'name' => 'Regular',
                'price' => 150.00,
                'quantity' => 100,
                'sold' => 20,
                'status' => 'open',
                'tickets_left' => 80,
            ],
        ], $database);

        $result = $model->createOrderWithItems(
            7,
            99,
            'gcash',
            'Jane Doe',
            [10 => 2],
            [
                'gcash_reference' => 'REF123',
                'gcash_screenshot' => '/public/assets/images/attendee/sample.png',
            ]
        );

        $this->assertTrue($result['success']);
        $this->assertSame(300.0, $result['total_amount']);
        $this->assertTrue($database->transactionStarted);
        $this->assertTrue($database->committed);
        $this->assertFalse($database->rolledBack);

        $this->assertCount(1, $database->records['orders']);
        $this->assertCount(1, $database->records['order_items']);
        $this->assertCount(2, $database->records['tickets']);
        $this->assertSame(2, $database->increments['ticket_types'][10] ?? 0);
        $this->assertSame('pending', $database->records['orders'][0]['status']);
        $this->assertSame('Jane Doe', $database->records['tickets'][0]['attendee_name']);
    }

    public function testGetTicketOrderDetailByUserIdReturnsOrderWithTicketItems(): void
    {
        $database = new FakeDatabase();
        $database->rawResults = [
            [
                'matcher' => 'MIN(tickets.ticket_code) AS primary_ticket_code',
                'type' => 'fetch',
                'result' => [
                    'id' => 15,
                    'event_id' => 99,
                    'event_title' => 'Music Fest',
                    'payment_status' => 'done',
                    'tickets_bought' => 2,
                    'primary_ticket_code' => 'ABC123',
                ],
            ],
            [
                'matcher' => 'ticket_types.name AS ticket_name',
                'type' => 'fetchAll',
                'result' => [
                    ['ticket_name' => 'Regular', 'quantity' => 1, 'subtotal' => 150.00],
                    ['ticket_name' => 'VIP', 'quantity' => 1, 'subtotal' => 300.00],
                ],
            ],
        ];

        $model = new FakeAttendeeEventModel([], $database);

        $order = $model->getTicketOrderDetailByUserId(7, 15);

        $this->assertNotNull($order);
        $this->assertSame('Music Fest', $order['event_title']);
        $this->assertSame('ABC123', $order['primary_ticket_code']);
        $this->assertCount(2, $order['ticket_items']);
        $this->assertSame('VIP', $order['ticket_items'][1]['ticket_name']);
    }
}

final class FakeAttendeeEventModel extends AttendeeEventModel
{
    public function __construct(
        private array $ticketRows,
        ?Database $database = null
    ) {
        $reflection = new ReflectionProperty(AttendeeEventModel::class, 'database');
        $reflection->setValue($this, $database ?? new FakeDatabase());
    }

    public function getAvailableTicketTypesByIds(int $event_id, array $ticket_type_ids): array
    {
        $ticket_type_ids = array_map('intval', $ticket_type_ids);

        return array_values(array_filter(
            $this->ticketRows,
            static fn(array $ticket): bool => in_array((int) $ticket['id'], $ticket_type_ids, true)
        ));
    }
}

final class FakeDatabase extends Database
{
    public bool $transactionStarted = false;
    public bool $committed = false;
    public bool $rolledBack = false;
    public array $records = [
        'orders' => [],
        'order_items' => [],
        'tickets' => [],
    ];
    public array $increments = [
        'ticket_types' => [],
    ];
    public array $rawResults = [];

    private ?string $currentTable = null;
    private array $whereConditions = [];
    private int $lastInsertedId = 0;
    private array $idCounters = [
        'orders' => 0,
        'order_items' => 0,
        'tickets' => 0,
    ];

    public function __construct()
    {
    }

    public function transaction()
    {
        $this->transactionStarted = true;
        return true;
    }

    public function commit()
    {
        $this->committed = true;
        return true;
    }

    public function roll_back()
    {
        $this->rolledBack = true;
        return true;
    }

    public function table($table_name)
    {
        $this->currentTable = $table_name;
        $this->whereConditions = [];
        return $this;
    }

    public function raw($query, $args = array())
    {
        foreach ($this->rawResults as $index => $entry) {
            if (str_contains($query, $entry['matcher'])) {
                unset($this->rawResults[$index]);
                return new FakePdoStatementResult(
                    $entry['type'] === 'fetch' ? $entry['result'] : null,
                    $entry['type'] === 'fetchAll' ? $entry['result'] : []
                );
            }
        }

        return new FakePdoStatementResult(null, []);
    }

    public function insert($fields = [])
    {
        $table = $this->currentTable;
        $this->idCounters[$table] = ($this->idCounters[$table] ?? 0) + 1;
        $fields['id'] = $this->idCounters[$table];
        $this->records[$table][] = $fields;
        $this->lastInsertedId = $fields['id'];
        return $this->lastInsertedId;
    }

    public function last_id()
    {
        return $this->lastInsertedId;
    }

    public function where($where, $op = null, $val = null, $type = '', $andOr = 'AND')
    {
        $value = in_array($op, ['=', '!=', '<', '>', '<=', '>=', '<>'], true) ? $val : $op;
        $this->whereConditions[$where] = $value;
        return $this;
    }

    public function increment($column, $amount = 1)
    {
        if ($this->currentTable === 'ticket_types' && isset($this->whereConditions['id'])) {
            $id = (int) $this->whereConditions['id'];
            $this->increments['ticket_types'][$id] = ($this->increments['ticket_types'][$id] ?? 0) + $amount;
        }
    }
}

final class FakePdoStatementResult
{
    public function __construct(
        private mixed $fetchResult,
        private array $fetchAllResult
    ) {
    }

    public function fetch($mode = null): mixed
    {
        return $this->fetchResult;
    }

    public function fetchAll($mode = null): array
    {
        return $this->fetchAllResult;
    }
}
