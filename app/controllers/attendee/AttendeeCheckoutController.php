<?php

require_once APP_ROOT . '/app/models/AttendeeEventModel.php';

$event_model = new AttendeeEventModel();
$user = $_SESSION['user'] ?? [];
$user_id = (int) ($user['id'] ?? 0);
$event_id = (int) ($_GET['id'] ?? 0);

if ($event_id <= 0) {
    set_flash('error', 'Please select an event first.');
    header('Location: ' . url('/attendee'));
    exit;
}

$event = $event_model->getEventDetailsById($event_id);

if (!$event) {
    set_flash('error', 'The selected event could not be found.');
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

    $attendee_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
    if ($attendee_name === '') {
        $attendee_name = (string) ($user['email'] ?? 'Attendee');
    }

    $purchase_result = $event_model->createOrderWithItems(
        $user_id,
        $event_id,
        (string) ($event['payment_type'] ?? 'free'),
        $attendee_name,
        $quantities
    );

    if ($purchase_result['success']) {
        set_flash('success', $purchase_result['message']);
    } else {
        set_flash('error', $purchase_result['message']);
    }

    header('Location: ' . url('/attendee/checkout?id=' . $event_id));
    exit;
}

$ticket_types = $event_model->getAvailableTicketTypesByEventId($event_id);

include APP_ROOT . '/app/views/attendee/checkout.php';
