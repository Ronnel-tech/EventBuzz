<?php

class AdminEventModel
{
    private Database $database;

    public function __construct()
    {
        $this->database = db();
    }

    public function getAllEvents(): array
    {
        $query = "
            SELECT
                events.id,
                events.title,
                events.banner_image,
                events.start_datetime,
                events.end_datetime,
                events.payment_type,
                categories.name AS category_name,
                COALESCE(
                    NULLIF(TRIM(CONCAT(users.first_name, ' ', users.last_name)), ''),
                    users.email
                ) AS organizer_name,
                COALESCE(SUM(ticket_types.sold), 0) AS tickets_sold
            FROM events
            LEFT JOIN categories ON categories.id = events.category_id
            LEFT JOIN users ON users.id = events.organizer_id
            LEFT JOIN ticket_types ON ticket_types.event_id = events.id
            GROUP BY
                events.id,
                events.title,
                events.banner_image,
                events.start_datetime,
                events.end_datetime,
                events.payment_type,
                categories.name,
                users.first_name,
                users.last_name,
                users.email
            ORDER BY events.start_datetime DESC
        ";

        return $this->database->raw($query)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getEventDetailsById(int $event_id): array|null
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
            LIMIT 1
        ";

        $event = $this->database->raw($query, [$event_id])->fetch(PDO::FETCH_ASSOC);

        return $event ?: null;
    }
}
