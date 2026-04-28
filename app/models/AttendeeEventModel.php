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

    public function getEventDetailsById(int $event_id): array|null
    {
        $query = "
            SELECT
                events.*,
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
                ) AS starting_price,
                COALESCE(
                    SUM(
                        CASE
                            WHEN ticket_types.status = 'open'
                                 AND ticket_types.sold < ticket_types.quantity
                            THEN ticket_types.quantity - ticket_types.sold
                            ELSE 0
                        END
                    ),
                    0
                ) AS available_tickets
            FROM events
            LEFT JOIN categories ON categories.id = events.category_id
            LEFT JOIN users ON users.id = events.organizer_id
            LEFT JOIN ticket_types ON ticket_types.event_id = events.id
            WHERE events.id = ?
            GROUP BY
                events.id,
                events.organizer_id,
                events.category_id,
                events.title,
                events.description,
                events.banner_image,
                events.start_datetime,
                events.end_datetime,
                events.street,
                events.city,
                events.province,
                events.country,
                events.payment_type,
                events.created_at,
                categories.name,
                users.first_name,
                users.last_name,
                users.email
            LIMIT 1
        ";

        $event = $this->database->raw($query, [$event_id])->fetch(PDO::FETCH_ASSOC);

        return $event ?: null;
    }

    public function getEventPaymentDetailsById(int $event_id): array|null
    {
        $query = "
            SELECT
                events.*,
                categories.name AS category_name,
                COALESCE(
                    NULLIF(TRIM(CONCAT(users.first_name, ' ', users.last_name)), ''),
                    users.email
                ) AS organizer_name,
                organizer_profiles.gcash_name,
                organizer_profiles.gcash_number,
                organizer_profiles.gcash_qr
            FROM events
            LEFT JOIN categories ON categories.id = events.category_id
            LEFT JOIN users ON users.id = events.organizer_id
            LEFT JOIN organizer_profiles ON organizer_profiles.user_id = events.organizer_id
            WHERE events.id = ?
            LIMIT 1
        ";

        $event = $this->database->raw($query, [$event_id])->fetch(PDO::FETCH_ASSOC);

        return $event ?: null;
    }

    public function getAvailableTicketTypesByEventId(int $event_id): array
    {
        $query = "
            SELECT
                ticket_types.id,
                ticket_types.event_id,
                ticket_types.name,
                ticket_types.price,
                ticket_types.quantity,
                ticket_types.sold,
                ticket_types.start_datetime,
                ticket_types.end_datetime,
                ticket_types.status,
                GREATEST(ticket_types.quantity - ticket_types.sold, 0) AS tickets_left
            FROM ticket_types
            WHERE ticket_types.event_id = ?
              AND ticket_types.status = 'open'
              AND ticket_types.sold < ticket_types.quantity
            ORDER BY ticket_types.price ASC, ticket_types.end_datetime ASC
        ";

        return $this->database->raw($query, [$event_id])->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAvailableTicketTypesByIds(int $event_id, array $ticket_type_ids): array
    {
        $ticket_type_ids = array_values(array_filter(array_map('intval', $ticket_type_ids), function ($id) {
            return $id > 0;
        }));

        if (!$ticket_type_ids) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($ticket_type_ids), '?'));
        $params = array_merge([$event_id], $ticket_type_ids);

        $query = "
            SELECT
                ticket_types.id,
                ticket_types.event_id,
                ticket_types.name,
                ticket_types.price,
                ticket_types.quantity,
                ticket_types.sold,
                ticket_types.start_datetime,
                ticket_types.end_datetime,
                ticket_types.status,
                GREATEST(ticket_types.quantity - ticket_types.sold, 0) AS tickets_left
            FROM ticket_types
            WHERE ticket_types.event_id = ?
              AND ticket_types.id IN ($placeholders)
              AND ticket_types.status = 'open'
        ";

        return $this->database->raw($query, $params)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function summarizeSelectedTickets(int $event_id, array $selected_tickets): array
    {
        $ticket_type_ids = array_keys($selected_tickets);
        $ticket_rows = $this->getAvailableTicketTypesByIds($event_id, $ticket_type_ids);

        if (!$ticket_rows) {
            return ['success' => false, 'message' => 'No valid ticket types were selected.'];
        }

        $ticket_map = [];
        foreach ($ticket_rows as $ticket_row) {
            $ticket_map[(int) $ticket_row['id']] = $ticket_row;
        }

        $line_items = [];
        $total_amount = 0.0;

        foreach ($selected_tickets as $ticket_type_id => $requested_quantity) {
            $ticket_type_id = (int) $ticket_type_id;
            $requested_quantity = (int) $requested_quantity;

            if ($requested_quantity <= 0) {
                continue;
            }

            if (!isset($ticket_map[$ticket_type_id])) {
                return ['success' => false, 'message' => 'One of the selected ticket types is no longer available.'];
            }

            $ticket = $ticket_map[$ticket_type_id];
            $tickets_left = (int) $ticket['tickets_left'];

            if ($requested_quantity > $tickets_left) {
                return [
                    'success' => false,
                    'message' => 'Only ' . $tickets_left . ' ticket(s) are left for ' . $ticket['name'] . '.',
                ];
            }

            $subtotal = ((float) $ticket['price']) * $requested_quantity;
            $line_items[] = [
                'ticket' => $ticket,
                'quantity' => $requested_quantity,
                'subtotal' => $subtotal,
            ];
            $total_amount += $subtotal;
        }

        if (!$line_items) {
            return ['success' => false, 'message' => 'Please select at least one ticket.'];
        }

        return [
            'success' => true,
            'summary' => [
                'line_items' => $line_items,
                'total_amount' => $total_amount,
            ],
        ];
    }

    public function createOrderWithItems(
        int $user_id,
        int $event_id,
        string $payment_method,
        string $attendee_name,
        array $selected_tickets,
        array $payment_details = []
    ): array {
        $summary_result = $this->summarizeSelectedTickets($event_id, $selected_tickets);

        if (!$summary_result['success']) {
            return $summary_result;
        }

        $line_items = $summary_result['summary']['line_items'];
        $total_amount = (float) $summary_result['summary']['total_amount'];

        $order_status = $payment_method === 'free' ? 'done' : 'pending';
        $gcash_reference = (string) ($payment_details['gcash_reference'] ?? '');
        $gcash_screenshot = (string) ($payment_details['gcash_screenshot'] ?? '');

        try {
            $this->database->transaction();

            $this->database->table('orders')->insert([
                'user_id' => $user_id,
                'event_id' => $event_id,
                'total_amount' => $total_amount,
                'payment_method' => $payment_method,
                'gcash_reference' => $gcash_reference,
                'gcash_screenshot' => $gcash_screenshot,
                'status' => $order_status,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $order_id = $this->database->last_id();

            foreach ($line_items as $line_item) {
                $ticket = $line_item['ticket'];
                $quantity = $line_item['quantity'];
                $subtotal = $line_item['subtotal'];

                $this->database->table('order_items')->insert([
                    'order_id' => $order_id,
                    'ticket_type_id' => $ticket['id'],
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
                $order_item_id = $this->database->last_id();

                $this->database
                    ->table('ticket_types')
                    ->where('id', $ticket['id'])
                    ->increment('sold', $quantity);

                for ($index = 0; $index < $quantity; $index++) {
                    $this->database->table('tickets')->insert([
                        'order_item_id' => $order_item_id,
                        'ticket_code' => strtoupper(bin2hex(random_bytes(6))),
                        'attendee_name' => $attendee_name,
                        'status' => 'not_used',
                        'scanned_at' => '1970-01-01 00:00:00',
                    ]);
                }
            }

            $this->database->commit();

            return [
                'success' => true,
                'message' => 'Tickets purchased successfully.',
                'order_id' => $order_id,
                'total_amount' => $total_amount,
            ];
        } catch (Throwable $exception) {
            $this->database->roll_back();

            return ['success' => false, 'message' => 'We could not complete your ticket purchase right now.'];
        }
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
