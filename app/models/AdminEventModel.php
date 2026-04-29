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

    public function deleteEventById(int $event_id): bool
    {
        $event = $this->database
            ->table('events')
            ->where('id', $event_id)
            ->get();

        if (!$event) {
            return false;
        }

        try {
            $this->database->transaction();

            $order_items = $this->database
                ->table('order_items')
                ->select('order_items.id')
                ->left_join('orders', 'orders.id = order_items.order_id')
                ->where('orders.event_id', $event_id)
                ->get_all() ?: [];

            foreach ($order_items as $order_item) {
                $order_item_id = (int) ($order_item['id'] ?? 0);

                if ($order_item_id > 0) {
                    $this->database
                        ->table('tickets')
                        ->where('order_item_id', $order_item_id)
                        ->delete();
                }
            }

            $orders = $this->database
                ->table('orders')
                ->select('id')
                ->where('event_id', $event_id)
                ->get_all() ?: [];

            foreach ($orders as $order) {
                $order_id = (int) ($order['id'] ?? 0);

                if ($order_id > 0) {
                    $this->database
                        ->table('order_items')
                        ->where('order_id', $order_id)
                        ->delete();
                }
            }

            $this->database
                ->table('orders')
                ->where('event_id', $event_id)
                ->delete();

            $this->database
                ->table('ticket_types')
                ->where('event_id', $event_id)
                ->delete();

            $deleted = $this->database
                ->table('events')
                ->where('id', $event_id)
                ->delete();

            $this->database->commit();

            return $deleted > 0;
        } catch (Throwable $exception) {
            $this->database->roll_back();

            return false;
        }
    }
}
