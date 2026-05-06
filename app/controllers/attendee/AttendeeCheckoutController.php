<?php

require_once APP_ROOT . '/app/models/AttendeeEventModel.php';

$event_model = new AttendeeEventModel();
$user = $_SESSION['user'] ?? [];
$event_id = (int) ($_GET['id'] ?? 0);

if ($event_id <= 0) {
    set_flash('error', 'Please select an event first.');
    header('Location: ' . url('/attendee'));
    exit;
}

$event = $event_model->getEventDetailsById($event_id);

if (!$event) {
    set_flash('error', 'The selected event is no longer available for ticket purchase.');
    header('Location: ' . url('/attendee'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw_quantities = $_POST['quantities'] ?? [];
    $quantities = [];

    if (is_array($raw_quantities)) {
        foreach ($raw_quantities as $ticket_type_id => $quantity) {
            $ticket_type_id = (int) $ticket_type_id;
            $quantity = (int) $quantity;

            if ($ticket_type_id > 0 && $quantity > 0) {
                $quantities[$ticket_type_id] = $quantity;
            }
        }
    }

    $summary_result = $event_model->summarizeSelectedTickets($event_id, $quantities);

    if (!$summary_result['success']) {
        set_flash('error', $summary_result['message']);
        header('Location: ' . url('/attendee/checkout?id=' . $event_id));
        exit;
    }

    $_SESSION['attendee_checkout'] = [
        'event_id' => $event_id,
        'quantities' => $quantities,
        'created_at' => time(),
    ];

    header('Location: ' . url('/attendee/payment?id=' . $event_id));
    exit;
}

$ticket_types = $event_model->getAvailableTicketTypesByEventId($event_id);
$selected_quantities = [];

if (
    isset($_SESSION['attendee_checkout']) &&
    is_array($_SESSION['attendee_checkout']) &&
    (int) ($_SESSION['attendee_checkout']['event_id'] ?? 0) === $event_id
) {
    $selected_quantities = (array) ($_SESSION['attendee_checkout']['quantities'] ?? []);
}

include APP_ROOT . '/app/views/attendee/checkout.php';
