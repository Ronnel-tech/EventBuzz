<?php

require_once APP_ROOT . '/app/models/AttendeeEventModel.php';

$event_model = new AttendeeEventModel();
$user = $_SESSION['user'] ?? [];
$event_id = (int) ($_GET['id'] ?? 0);

if ($event_id <= 0) {
    set_flash('error', 'Please select an event to view.');
    header('Location: ' . url('/attendee'));
    exit;
}

$event = $event_model->getEventDetailsById($event_id);

if (!$event) {
    set_flash('error', 'The selected event could not be found.');
    header('Location: ' . url('/attendee'));
    exit;
}

include APP_ROOT . '/app/views/attendee/attendee_detailed_event.php';
