<?php

use PHPUnit\Framework\TestCase;

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require_once APP_ROOT . '/app/models/AdminEventModel.php';
require_once APP_ROOT . '/scheme/Database.php';

final class AdminEventModelTest extends TestCase
{
    public function testDeleteEventByIdRemovesEventAndRelatedRecords(): void
    {
        $database = new FakeAdminDatabase();
        $model = new FakeAdminEventModel($database);

        $deleted = $model->deleteEventById(99);

        $this->assertTrue($deleted);
        $this->assertTrue($database->transactionStarted);
        $this->assertTrue($database->committed);
        $this->assertFalse($database->rolledBack);
        $this->assertCount(0, $database->records['events']);
        $this->assertCount(0, $database->records['orders']);
        $this->assertCount(0, $database->records['order_items']);
        $this->assertCount(0, $database->records['tickets']);
        $this->assertCount(0, $database->records['ticket_types']);
    }

    public function testDeleteEventByIdReturnsFalseWhenEventDoesNotExist(): void
    {
        $database = new FakeAdminDatabase();
        $model = new FakeAdminEventModel($database);

        $deleted = $model->deleteEventById(404);

        $this->assertFalse($deleted);
        $this->assertFalse($database->transactionStarted);
    }
}

final class FakeAdminEventModel extends AdminEventModel
{
    public function __construct(Database $database)
    {
        $reflection = new ReflectionProperty(AdminEventModel::class, 'database');
        $reflection->setValue($this, $database);
    }
}

final class FakeAdminDatabase extends Database
{
    public bool $transactionStarted = false;
    public bool $committed = false;
    public bool $rolledBack = false;
    public array $records = [
        'events' => [
            ['id' => 99, 'title' => 'Music Fest'],
        ],
        'orders' => [
            ['id' => 20, 'event_id' => 99],
        ],
        'order_items' => [
            ['id' => 30, 'order_id' => 20, 'ticket_type_id' => 7],
        ],
        'tickets' => [
            ['id' => 40, 'order_item_id' => 30],
            ['id' => 41, 'order_item_id' => 30],
        ],
        'ticket_types' => [
            ['id' => 7, 'event_id' => 99],
        ],
    ];

    private ?string $currentTable = null;
    private array $whereConditions = [];

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

    public function select($columns)
    {
        return $this;
    }

    public function left_join($table_name, $cond)
    {
        return $this;
    }

    public function where($where, $op = null, $val = null, $type = '', $andOr = 'AND')
    {
        $value = in_array($op, ['=', '!=', '<', '>', '<=', '>=', '<>'], true) ? $val : $op;
        $this->whereConditions[$where] = $value;
        return $this;
    }

    public function get($mode = PDO::FETCH_ASSOC)
    {
        $rows = $this->filterRows($this->currentTable);
        return $rows[0] ?? false;
    }

    public function get_all($mode = PDO::FETCH_ASSOC)
    {
        if ($this->currentTable === 'order_items' && isset($this->whereConditions['orders.event_id'])) {
            $eventId = (int) $this->whereConditions['orders.event_id'];
            $orderIds = array_column(
                array_filter(
                    $this->records['orders'],
                    static fn(array $order): bool => (int) $order['event_id'] === $eventId
                ),
                'id'
            );

            return array_values(array_filter(
                $this->records['order_items'],
                static fn(array $item): bool => in_array((int) $item['order_id'], $orderIds, true)
            ));
        }

        return $this->filterRows($this->currentTable);
    }

    public function delete()
    {
        $before = count($this->records[$this->currentTable] ?? []);
        $this->records[$this->currentTable] = array_values(array_filter(
            $this->records[$this->currentTable] ?? [],
            fn(array $row): bool => !$this->matchesConditions($row)
        ));

        return $before - count($this->records[$this->currentTable]);
    }

    private function filterRows(string $table): array
    {
        return array_values(array_filter(
            $this->records[$table] ?? [],
            fn(array $row): bool => $this->matchesConditions($row)
        ));
    }

    private function matchesConditions(array $row): bool
    {
        foreach ($this->whereConditions as $field => $expected) {
            $normalizedField = str_contains($field, '.') ? substr($field, strrpos($field, '.') + 1) : $field;

            if (!array_key_exists($normalizedField, $row) || (string) $row[$normalizedField] !== (string) $expected) {
                return false;
            }
        }

        return true;
    }
}
