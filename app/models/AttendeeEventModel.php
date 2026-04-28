<?php

class AttendeeEventModel
{
    private Database $database;

    public function __construct()
    {
        $this->database = db();
    }

    public function getTodayEvents(): array
    {
        $query = "
            SELECT
                events.id,
                events.title,
                events.banner_image,
                events.start_datetime,
                events.payment_type,
                categories.id AS category_id,
                categories.name AS category_name,
                COALESCE(
                    NULLIF(TRIM(CONCAT(users.first_name, ' ', users.last_name)), ''),
                    users.email
                ) AS organizer_name,
                MIN(
                    CASE
                        WHEN ticket_types.status = 'open'
                             AND ticket_types.sold < ticket_types.quantity
                        THEN ticket_types.price
                        ELSE NULL
                    END
                ) AS starting_price
            FROM events
            INNER JOIN categories ON categories.id = events.category_id
            INNER JOIN users ON users.id = events.organizer_id
            LEFT JOIN ticket_types ON ticket_types.event_id = events.id
            WHERE DATE(events.start_datetime) = CURDATE()
            GROUP BY
                events.id,
                events.title,
                events.banner_image,
                events.start_datetime,
                events.payment_type,
                categories.id,
                categories.name,
                users.first_name,
                users.last_name,
                users.email
            ORDER BY events.start_datetime ASC
        ";

        return $this->database->raw($query)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getCategorySections(): array
    {
        $categories = $this->database->table('categories')->get_all() ?: [];
        $sections = [];

        foreach ($categories as $category) {
            $category_id = (int) ($category['id'] ?? 0);
            $category_name = trim((string) ($category['name'] ?? ''));

            if ($category_id <= 0 || $category_name === '' || $category_name === '0') {
                continue;
            }

            $sections[] = [
                'id' => $category_id,
                'name' => $category_name,
                'events' => $this->getEventsByCategory($category_id),
            ];
        }

        return $sections;
    }

    private function getEventsByCategory(int $category_id): array
    {
        $query = "
            SELECT
                events.id,
                events.title,
                events.banner_image,
                events.start_datetime,
                events.payment_type,
                COALESCE(
                    NULLIF(TRIM(CONCAT(users.first_name, ' ', users.last_name)), ''),
                    users.email
                ) AS organizer_name,
                MIN(
                    CASE
                        WHEN ticket_types.status = 'open'
                             AND ticket_types.sold < ticket_types.quantity
                        THEN ticket_types.price
                        ELSE NULL
                    END
                ) AS starting_price
            FROM events
            INNER JOIN users ON users.id = events.organizer_id
            LEFT JOIN ticket_types ON ticket_types.event_id = events.id
            WHERE events.category_id = ?
            GROUP BY
                events.id,
                events.title,
                events.banner_image,
                events.start_datetime,
                events.payment_type,
                users.first_name,
                users.last_name,
                users.email
            ORDER BY events.start_datetime ASC
        ";

        return $this->database->raw($query, [$category_id])->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
