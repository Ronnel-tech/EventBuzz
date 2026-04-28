<?php

class OrganizerEventModel
{
    private Database $database;

    public function __construct()
    {
        $this->database = db();
    }

    public function ensureDefaultCategories(): array
    {
        $default_categories = [
            'Music',
            'Education',
            'Art and Culture',
            'Sports and Fitness',
            'Gaming and Esports',
        ];

        $categories = $this->database->table('categories')->get_all() ?: [];
        $existing_names = [];

        foreach ($categories as $category) {
            $name = trim((string) ($category['name'] ?? ''));
            if ($name !== '' && $name !== '0') {
                $existing_names[] = strtolower($name);
            }
        }

        foreach ($default_categories as $default_category) {
            if (!in_array(strtolower($default_category), $existing_names, true)) {
                $this->database->table('categories')->insert([
                    'name' => $default_category,
                ]);
            }
        }

        $categories = $this->database->table('categories')->get_all() ?: [];

        return array_values(array_filter($categories, function ($category) {
            $name = trim((string) ($category['name'] ?? ''));
            return $name !== '' && $name !== '0';
        }));
    }

    public function findCategoryById(int $category_id): array|null
    {
        return $this->database->table('categories')->where('id', $category_id)->get() ?: null;
    }

    public function createEvent(array $data): int
    {
        $this->database->table('events')->insert([
            'organizer_id' => $data['organizer_id'],
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'banner_image' => $data['banner_image'],
            'start_datetime' => $data['start_datetime'],
            'end_datetime' => $data['end_datetime'],
            'street' => $data['street'],
            'city' => $data['city'],
            'province' => $data['province'],
            'country' => $data['country'],
            'payment_type' => $data['payment_type'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->database->last_id();
    }

    public function getEventDetailsByOrganizer(int $event_id, int $organizer_id): array|null
    {
        $query = "
            SELECT
                events.*,
                categories.name AS category_name,
                COALESCE(
                    NULLIF(TRIM(CONCAT(users.first_name, ' ', users.last_name)), ''),
                    users.email
                ) AS organizer_name
            FROM events
            LEFT JOIN categories ON categories.id = events.category_id
            LEFT JOIN users ON users.id = events.organizer_id
            WHERE events.id = ?
              AND events.organizer_id = ?
            LIMIT 1
        ";

        $event = $this->database->raw($query, [$event_id, $organizer_id])->fetch(PDO::FETCH_ASSOC);

        return $event ?: null;
    }

    public function updateEventByOrganizer(int $event_id, int $organizer_id, array $data): int
    {
        return $this->database
            ->table('events')
            ->where('id', $event_id)
            ->where('organizer_id', $organizer_id)
            ->update([
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'banner_image' => $data['banner_image'],
                'start_datetime' => $data['start_datetime'],
                'end_datetime' => $data['end_datetime'],
                'street' => $data['street'],
                'city' => $data['city'],
                'province' => $data['province'],
                'country' => $data['country'],
            ]);
    }

    public function getEventsByOrganizer(int $organizer_id): array
    {
        $query = "
            SELECT 
                events.id,
                events.title,
                events.start_datetime,
                events.end_datetime,
                events.payment_type,
                COALESCE(SUM(ticket_types.sold), 0) AS tickets_sold
            FROM events
            LEFT JOIN ticket_types ON ticket_types.event_id = events.id
            WHERE events.organizer_id = ?
            GROUP BY events.id, events.title, events.start_datetime, events.end_datetime, events.payment_type
            ORDER BY events.start_datetime DESC
        ";

        return $this->database->raw($query, [$organizer_id])->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAttendeesByEvent(int $event_id, int $organizer_id): array
    {
        $query = "
            SELECT
                orders.id,
                COALESCE(
                    NULLIF(MAX(tickets.attendee_name), ''),
                    NULLIF(TRIM(CONCAT(users.first_name, ' ', users.last_name)), ''),
                    users.email
                ) AS attendee_name,
                orders.created_at AS transaction_date,
                COALESCE(SUM(order_items.quantity), 0) AS tickets_bought,
                orders.total_amount,
                orders.status
            FROM orders
            INNER JOIN events ON events.id = orders.event_id
            LEFT JOIN users ON users.id = orders.user_id
            LEFT JOIN order_items ON order_items.order_id = orders.id
            LEFT JOIN tickets ON tickets.order_item_id = order_items.id
            WHERE orders.event_id = ?
              AND events.organizer_id = ?
            GROUP BY
                orders.id,
                users.first_name,
                users.last_name,
                users.email,
                orders.created_at,
                orders.total_amount,
                orders.status
            ORDER BY orders.created_at DESC
        ";

        return $this->database->raw($query, [$event_id, $organizer_id])->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function deleteEventByOrganizer(int $event_id, int $organizer_id): bool
    {
        $event = $this->database
            ->table('events')
            ->where('id', $event_id)
            ->where('organizer_id', $organizer_id)
            ->get();

        if (!$event) {
            return false;
        }

        $this->database
            ->table('ticket_types')
            ->where('event_id', $event_id)
            ->delete();

        $deleted = $this->database
            ->table('events')
            ->where('id', $event_id)
            ->where('organizer_id', $organizer_id)
            ->delete();

        return $deleted > 0;
    }
}
