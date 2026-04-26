<?php

require_once APP_ROOT . '/app/models/OrganizerTicketModel.php';

$ticket_model = new OrganizerTicketModel();
$organizer_id = (int) ($_SESSION['user']['id'] ?? 0);
$event_id = (int) ($_SESSION['current_event_id'] ?? 0);
$creation_in_progress = !empty($_SESSION['event_creation_in_progress']);

if ($event_id <= 0 || !$creation_in_progress) {
    unset($_SESSION['current_event_id'], $_SESSION['event_creation_in_progress']);
    set_flash('error', 'Create an event first before adding ticket types.');
    header('Location: ' . url('/organizer/create-event'));
    exit;
}

$event = $ticket_model->getEventForOrganizer($event_id, $organizer_id);

if (!$event) {
    unset($_SESSION['current_event_id'], $_SESSION['event_creation_in_progress']);
    set_flash('error', 'The selected event could not be found.');
    header('Location: ' . url('/organizer/create-event'));
    exit;
}

$old = [
    'ticket_name' => '',
    'quantity' => '',
    'price' => '',
    'start_date' => '',
    'start_time' => '',
    'end_date' => '',
    'end_time' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? 'add');

    if ($action === 'delete') {
        $ticket_type_id = (int) ($_POST['ticket_type_id'] ?? 0);

        if ($ticket_type_id <= 0) {
            set_flash('error', 'Invalid ticket type selected.');
            header('Location: ' . url('/organizer/add-ticket'));
            exit;
        }

        $deleted = $ticket_model->deleteTicketTypeByEvent($ticket_type_id, $event_id);

        if ($deleted <= 0) {
            set_flash('error', 'Ticket type could not be deleted.');
            header('Location: ' . url('/organizer/add-ticket'));
            exit;
        }

        set_flash('success', 'Ticket type deleted successfully.');
        header('Location: ' . url('/organizer/add-ticket'));
        exit;
    }

    $old = [
        'ticket_name' => trim($_POST['ticket_name'] ?? ''),
        'quantity' => trim($_POST['quantity'] ?? ''),
        'price' => trim($_POST['price'] ?? ''),
        'start_date' => trim($_POST['start_date'] ?? ''),
        'start_time' => trim($_POST['start_time'] ?? ''),
        'end_date' => trim($_POST['end_date'] ?? ''),
        'end_time' => trim($_POST['end_time'] ?? ''),
    ];

    $required_fields = ['ticket_name', 'quantity', 'price', 'start_date', 'start_time', 'end_date', 'end_time'];

    foreach ($required_fields as $field) {
        if ($old[$field] === '') {
            set_flash('error', 'Please fill in all ticket details.');
            $_SESSION['add_ticket_old'] = $old;
            header('Location: ' . url('/organizer/add-ticket'));
            exit;
        }
    }

    $quantity = (int) $old['quantity'];
    $price = (float) $old['price'];
    $start_datetime = $old['start_date'] . ' ' . $old['start_time'] . ':00';
    $end_datetime = $old['end_date'] . ' ' . $old['end_time'] . ':00';

    if ($quantity <= 0) {
        set_flash('error', 'Ticket quantity must be greater than zero.');
        $_SESSION['add_ticket_old'] = $old;
        header('Location: ' . url('/organizer/add-ticket'));
        exit;
    }

    if ($price < 0) {
        set_flash('error', 'Ticket price cannot be negative.');
        $_SESSION['add_ticket_old'] = $old;
        header('Location: ' . url('/organizer/add-ticket'));
        exit;
    }

    if (strtotime($end_datetime) <= strtotime($start_datetime)) {
        set_flash('error', 'Ticket sale end must be later than the start.');
        $_SESSION['add_ticket_old'] = $old;
        header('Location: ' . url('/organizer/add-ticket'));
        exit;
    }

    if ($ticket_model->countTicketTypesByEventId($event_id) >= 5) {
        set_flash('error', 'You can only add up to 5 ticket types per event.');
        $_SESSION['add_ticket_old'] = $old;
        header('Location: ' . url('/organizer/add-ticket'));
        exit;
    }

    $ticket_model->createTicketType([
        'event_id' => $event_id,
        'name' => $old['ticket_name'],
        'price' => number_format($price, 2, '.', ''),
        'quantity' => $quantity,
        'start_datetime' => $start_datetime,
        'end_datetime' => $end_datetime,
        'status' => 'open',
    ]);

    unset($_SESSION['add_ticket_old']);
    set_flash('success', 'Ticket type added successfully.');
    header('Location: ' . url('/organizer/add-ticket'));
    exit;
}

if (isset($_SESSION['add_ticket_old']) && is_array($_SESSION['add_ticket_old'])) {
    $old = array_merge($old, $_SESSION['add_ticket_old']);
    unset($_SESSION['add_ticket_old']);
}

$ticket_types = $ticket_model->getTicketTypesByEventId($event_id);

include APP_ROOT . '/app/views/organizer/add_ticket.php';
