<?php

use PHPUnit\Framework\TestCase;

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require_once APP_ROOT . '/app/models/OrganizerEventModel.php';
require_once APP_ROOT . '/scheme/Database.php';

final class OrganizerEventModelTest extends TestCase
{
    public function testEnsureDefaultCategoriesAddsMissingCategories(): void
    {
        $database = new FakeOrganizerDatabase([
            ['id' => 1, 'name' => 'Music'],
            ['id' => 2, 'name' => 'Education'],
        ]);

        $model = new FakeOrganizerEventModel($database);

        $categories = $model->ensureDefaultCategories();

        $this->assertCount(5, $categories);
        $this->assertSame(
            ['Art and Culture', 'Sports and Fitness', 'Gaming and Esports'],
            array_column($database->insertedCategories, 'name')
        );
    }

    public function testCreateEventStoresExpectedEventFields(): void
    {
        $database = new FakeOrganizerDatabase();
        $model = new FakeOrganizerEventModel($database);

        $eventId = $model->createEvent([
            'organizer_id' => 5,
            'category_id' => 2,
            'title' => 'Campus Tech Expo',
            'description' => 'A student technology event.',
            'banner_image' => '/public/assets/images/events/expo.png',
            'start_datetime' => '2026-06-15 09:00:00',
            'end_datetime' => '2026-06-15 17:00:00',
            'street' => '123 Main St',
            'city' => 'Manila',
            'province' => 'Metro Manila',
            'country' => 'Philippines',
            'payment_type' => 'free',
        ]);

        $this->assertSame(1, $eventId);
        $this->assertCount(1, $database->records['events']);
        $this->assertSame('Campus Tech Expo', $database->records['events'][0]['title']);
        $this->assertSame('free', $database->records['events'][0]['payment_type']);
        $this->assertSame(5, $database->records['events'][0]['organizer_id']);
    }
}

final class FakeOrganizerEventModel extends OrganizerEventModel
{
    public function __construct(Database $database)
    {
        $reflection = new ReflectionProperty(OrganizerEventModel::class, 'database');
        $reflection->setValue($this, $database);
    }
}

final class FakeOrganizerDatabase extends Database
{
    public array $records = [
        'categories' => [],
        'events' => [],
    ];
    public array $insertedCategories = [];

    private ?string $currentTable = null;
    private int $lastInsertedId = 0;
    private array $idCounters = [
        'categories' => 0,
        'events' => 0,
    ];

    public function __construct(array $categories = [])
    {
        $this->records['categories'] = $categories;
        $this->idCounters['categories'] = count($categories);
    }

    public function table($table_name)
    {
        $this->currentTable = $table_name;
        return $this;
    }

    public function get_all($mode = PDO::FETCH_ASSOC)
    {
        return $this->records[$this->currentTable] ?? [];
    }

    public function insert($fields = [])
    {
        $table = $this->currentTable;
        $this->idCounters[$table] = ($this->idCounters[$table] ?? 0) + 1;
        $fields['id'] = $this->idCounters[$table];
        $this->records[$table][] = $fields;
        $this->lastInsertedId = $fields['id'];

        if ($table === 'categories') {
            $this->insertedCategories[] = $fields;
        }

        return $this->lastInsertedId;
    }

    public function last_id()
    {
        return $this->lastInsertedId;
    }
}
