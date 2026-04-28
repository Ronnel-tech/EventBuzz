<?php

require_once APP_ROOT . '/app/models/AttendeeEventModel.php';

$event_model = new AttendeeEventModel();
$user = $_SESSION['user'] ?? [];
$user_id = (int) ($user['id'] ?? 0);
$order_id = (int) ($_GET['order_id'] ?? 0);

if ($order_id <= 0) {
    set_flash('error', 'Please select a valid ticket first.');
    header('Location: ' . url('/attendee/ticket'));
    exit;
}

$ticket_order = $event_model->getTicketOrderDetailByUserId($user_id, $order_id);

if (!$ticket_order) {
    set_flash('error', 'The selected ticket could not be found.');
    header('Location: ' . url('/attendee/ticket'));
    exit;
}

$organizer_lookup_url = url('/organizer/qr_page?event_id=' . $ticket_order['event_id'] . '&order_id=' . $ticket_order['id']);

include APP_ROOT . '/app/views/attendee/ticket_detail.php';
