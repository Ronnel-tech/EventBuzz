<?php

class AdminUserModel
{
    private Database $database;

    public function __construct()
    {
        $this->database = db();
    }

    public function getAttendeesWithTicketCounts(): array
    {
        $query = "
            SELECT
                users.id,
                users.first_name,
                users.last_name,
                users.email,
                users.created_at,
                COALESCE(SUM(order_items.quantity), 0) AS tickets_bought
            FROM users
            LEFT JOIN orders ON orders.user_id = users.id
            LEFT JOIN order_items ON order_items.order_id = orders.id
            WHERE users.role = 'attendee'
            GROUP BY
                users.id,
                users.first_name,
                users.last_name,
                users.email,
                users.created_at
            ORDER BY users.created_at DESC
        ";

        return $this->database->raw($query)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getOrganizersWithEventCounts(): array
    {
        $query = "
            SELECT
                users.id,
                users.first_name,
                users.last_name,
                users.email,
                users.created_at,
                COALESCE(COUNT(DISTINCT events.id), 0) AS events_created
            FROM users
            LEFT JOIN events ON events.organizer_id = users.id
            WHERE users.role = 'organizer'
            GROUP BY
                users.id,
                users.first_name,
                users.last_name,
                users.email,
                users.created_at
            ORDER BY users.created_at DESC
        ";

        return $this->database->raw($query)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function deleteAttendeeById(int $attendee_id): bool
    {
        $attendee = $this->database
            ->table('users')
            ->where('id', $attendee_id)
            ->where('role', 'attendee')
            ->get();

        if (!$attendee) {
            return false;
        }

        try {
            $this->database->transaction();

            $order_items = $this->database
                ->table('order_items')
                ->select('order_items.id, order_items.ticket_type_id, order_items.quantity')
                ->left_join('orders', 'orders.id = order_items.order_id')
                ->where('orders.user_id', $attendee_id)
                ->get_all() ?: [];

            foreach ($order_items as $order_item) {
                $order_item_id = (int) ($order_item['id'] ?? 0);
                $ticket_type_id = (int) ($order_item['ticket_type_id'] ?? 0);
                $quantity = (int) ($order_item['quantity'] ?? 0);

                if ($order_item_id > 0) {
                    $this->database
                        ->table('tickets')
                        ->where('order_item_id', $order_item_id)
                        ->delete();
                }

                if ($ticket_type_id > 0 && $quantity > 0) {
                    $this->database
                        ->table('ticket_types')
                        ->where('id', $ticket_type_id)
                        ->decrement('sold', $quantity);
                }
            }

            $orders = $this->database
                ->table('orders')
                ->select('id')
                ->where('user_id', $attendee_id)
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
                ->where('user_id', $attendee_id)
                ->delete();

            $deleted = $this->database
                ->table('users')
                ->where('id', $attendee_id)
                ->where('role', 'attendee')
                ->delete();

            $this->database->commit();

            return $deleted > 0;
        } catch (Throwable $exception) {
            $this->database->roll_back();
            return false;
        }
    }

    public function deleteOrganizerById(int $organizer_id): bool
    {
        $organizer = $this->database
            ->table('users')
            ->where('id', $organizer_id)
            ->where('role', 'organizer')
            ->get();

        if (!$organizer) {
            return false;
        }

        try {
            $this->database->transaction();

            $events = $this->database
                ->table('events')
                ->select('id')
                ->where('organizer_id', $organizer_id)
                ->get_all() ?: [];

            foreach ($events as $event) {
                $event_id = (int) ($event['id'] ?? 0);
                if ($event_id <= 0) {
                    continue;
                }

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
            }

            $this->database
                ->table('events')
                ->where('organizer_id', $organizer_id)
                ->delete();

            $this->database
                ->table('organizer_profiles')
                ->where('user_id', $organizer_id)
                ->delete();

            $deleted = $this->database
                ->table('users')
                ->where('id', $organizer_id)
                ->where('role', 'organizer')
                ->delete();

            $this->database->commit();

            return $deleted > 0;
        } catch (Throwable $exception) {
            $this->database->roll_back();
            return false;
        }
    }
}
