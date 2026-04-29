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

    public function getEventsByOrganizer(int $organizer_id, string $search = '', string $filter = 'upcoming'): array
    {
        $where_clauses = ['events.organizer_id = ?'];
        $params = [$organizer_id];

        if ($search !== '') {
            $where_clauses[] = "
                (
                    events.title LIKE ?
                    OR events.payment_type LIKE ?
                    OR events.city LIKE ?
                    OR events.province LIKE ?
                    OR events.country LIKE ?
                )
            ";
            $search_term = '%' . $search . '%';
            $params = array_merge($params, array_fill(0, 5, $search_term));
        }

        if ($filter === 'past') {
            $where_clauses[] = 'events.end_datetime < NOW()';
        } elseif ($filter === 'all') {
            // no additional date filter
        } else {
            $filter = 'upcoming';
            $where_clauses[] = 'events.end_datetime >= NOW()';
        }

        $where_sql = implode(' AND ', $where_clauses);

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
            WHERE $where_sql
            GROUP BY events.id, events.title, events.start_datetime, events.end_datetime, events.payment_type
            ORDER BY events.start_datetime DESC
        ";

        return $this->database->raw($query, $params)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getDashboardSummary(int $organizer_id): array
    {
        $query = "
            SELECT
                (
                    SELECT COUNT(*)
                    FROM events
                    WHERE events.organizer_id = ?
                ) AS total_events_created,
                (
                    SELECT COALESCE(SUM(orders.total_amount), 0)
                    FROM orders
                    INNER JOIN events ON events.id = orders.event_id
                    WHERE events.organizer_id = ?
                      AND orders.status = 'done'
                ) AS total_revenue_earned,
                (
                    SELECT COALESCE(SUM(order_items.quantity), 0)
                    FROM order_items
                    INNER JOIN orders ON orders.id = order_items.order_id
                    INNER JOIN events ON events.id = orders.event_id
                    WHERE events.organizer_id = ?
                      AND orders.status = 'done'
                ) AS total_tickets_sold,
                (
                    SELECT COUNT(*)
                    FROM events
                    WHERE events.organizer_id = ?
                      AND events.start_datetime >= NOW()
                ) AS upcoming_events
        ";

        $summary = $this->database->raw($query, [
            $organizer_id,
            $organizer_id,
            $organizer_id,
            $organizer_id,
        ])->fetch(PDO::FETCH_ASSOC);

        return $summary ?: [
            'total_events_created' => 0,
            'total_revenue_earned' => 0,
            'total_tickets_sold' => 0,
            'upcoming_events' => 0,
        ];
    }

    public function getTodayTicketSalesByEvent(int $organizer_id): array
    {
        $query = "
            SELECT
                events.id,
                events.title,
                COALESCE(SUM(order_items.quantity), 0) AS tickets_sold_today
            FROM events
            LEFT JOIN orders
                ON orders.event_id = events.id
               AND orders.status = 'done'
               AND DATE(orders.created_at) = CURDATE()
            LEFT JOIN order_items ON order_items.order_id = orders.id
            WHERE events.organizer_id = ?
            GROUP BY events.id, events.title
            ORDER BY events.start_datetime ASC, events.title ASC
        ";

        return $this->database->raw($query, [$organizer_id])->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getRevenueOverTime(int $organizer_id): array
    {
        $query = "
            SELECT
                DATE(orders.created_at) AS revenue_date,
                COALESCE(SUM(orders.total_amount), 0) AS revenue_amount
            FROM orders
            INNER JOIN events ON events.id = orders.event_id
            WHERE events.organizer_id = ?
              AND orders.status = 'done'
            GROUP BY DATE(orders.created_at)
            ORDER BY DATE(orders.created_at) ASC
        ";

        return $this->database->raw($query, [$organizer_id])->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getSalesDistributionByPaymentMethod(int $organizer_id): array
    {
        $query = "
            SELECT
                orders.payment_method,
                COALESCE(SUM(order_items.quantity), 0) AS tickets_sold
            FROM orders
            INNER JOIN events ON events.id = orders.event_id
            LEFT JOIN order_items ON order_items.order_id = orders.id
            WHERE events.organizer_id = ?
              AND orders.status = 'done'
            GROUP BY orders.payment_method
            ORDER BY tickets_sold DESC, orders.payment_method ASC
        ";

        return $this->database->raw($query, [$organizer_id])->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAttendeesByEvent(int $event_id, int $organizer_id, string $search = '', string $filter = 'all'): array
    {
        $where_clauses = [
            'orders.event_id = ?',
            'events.organizer_id = ?',
        ];
        $params = [$event_id, $organizer_id];

        if ($filter === 'paid') {
            $where_clauses[] = "orders.status = 'done'";
        } elseif ($filter === 'pending') {
            $where_clauses[] = "orders.status = 'pending'";
        } else {
            $filter = 'all';
        }

        if ($search !== '') {
            $where_clauses[] = "
                (
                    tickets.attendee_name LIKE ?
                    OR users.first_name LIKE ?
                    OR users.last_name LIKE ?
                    OR users.email LIKE ?
                    OR orders.gcash_reference LIKE ?
                )
            ";
            $search_term = '%' . $search . '%';
            $params = array_merge($params, array_fill(0, 5, $search_term));
        }

        $where_sql = implode(' AND ', $where_clauses);

        $query = "
            SELECT
                orders.id,
                COALESCE(
                    NULLIF(MAX(tickets.attendee_name), ''),
                    NULLIF(TRIM(CONCAT(users.first_name, ' ', users.last_name)), ''),
                    users.email
                ) AS attendee_name,
                orders.created_at AS transaction_date,
                orders.payment_method,
                orders.gcash_reference,
                orders.gcash_screenshot,
                COALESCE(SUM(order_items.quantity), 0) AS tickets_bought,
                orders.total_amount,
                orders.status
            FROM orders
            INNER JOIN events ON events.id = orders.event_id
            LEFT JOIN users ON users.id = orders.user_id
            LEFT JOIN order_items ON order_items.order_id = orders.id
            LEFT JOIN tickets ON tickets.order_item_id = order_items.id
            WHERE $where_sql
            GROUP BY
                orders.id,
                users.first_name,
                users.last_name,
                users.email,
                orders.created_at,
                orders.payment_method,
                orders.gcash_reference,
                orders.gcash_screenshot,
                orders.total_amount,
                orders.status
            ORDER BY orders.created_at DESC
        ";

        return $this->database->raw($query, $params)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getAttendeePaymentDetailsByOrder(int $order_id, int $event_id, int $organizer_id): array|null
    {
        $order_query = "
            SELECT
                orders.id,
                orders.created_at AS transaction_date,
                orders.payment_method,
                orders.gcash_reference,
                orders.gcash_screenshot,
                orders.total_amount,
                orders.status,
                COALESCE(
                    NULLIF(MAX(tickets.attendee_name), ''),
                    NULLIF(TRIM(CONCAT(users.first_name, ' ', users.last_name)), ''),
                    users.email
                ) AS attendee_name
            FROM orders
            INNER JOIN events ON events.id = orders.event_id
            LEFT JOIN users ON users.id = orders.user_id
            LEFT JOIN order_items ON order_items.order_id = orders.id
            LEFT JOIN tickets ON tickets.order_item_id = order_items.id
            WHERE orders.id = ?
              AND orders.event_id = ?
              AND events.organizer_id = ?
            GROUP BY
                orders.id,
                orders.created_at,
                orders.payment_method,
                orders.gcash_reference,
                orders.gcash_screenshot,
                orders.total_amount,
                orders.status,
                users.first_name,
                users.last_name,
                users.email
            LIMIT 1
        ";

        $order = $this->database->raw($order_query, [$order_id, $event_id, $organizer_id])->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return null;
        }

        $ticket_query = "
            SELECT
                ticket_types.name AS ticket_name,
                order_items.quantity,
                order_items.subtotal
            FROM order_items
            INNER JOIN ticket_types ON ticket_types.id = order_items.ticket_type_id
            WHERE order_items.order_id = ?
            ORDER BY ticket_types.name ASC
        ";

        $order['ticket_items'] = $this->database->raw($ticket_query, [$order_id])->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return $order;
    }

    public function updateAttendeeOrderStatus(int $order_id, int $event_id, int $organizer_id, string $status): bool
    {
        $query = "
            UPDATE orders
            INNER JOIN events ON events.id = orders.event_id
            SET orders.status = ?
            WHERE orders.id = ?
              AND orders.event_id = ?
              AND events.organizer_id = ?
        ";

        $result = $this->database->raw($query, [$status, $order_id, $event_id, $organizer_id]);

        return $result->rowCount() > 0;
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
