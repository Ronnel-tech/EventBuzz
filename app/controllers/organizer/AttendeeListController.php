<?php

require_once APP_ROOT . '/app/models/OrganizerEventModel.php';

$event_model = new OrganizerEventModel();
$organizer_id = (int) ($_SESSION['user']['id'] ?? 0);
$event_id = (int) ($_GET['id'] ?? 0);

if ($event_id <= 0) {
    set_flash('error', 'Please select an event to view attendees.');
    header('Location: ' . url('/organizer/events'));
    exit;
}

$event = $event_model->getEventDetailsByOrganizer($event_id, $organizer_id);

if (!$event) {
    set_flash('error', 'The selected event could not be found.');
    header('Location: ' . url('/organizer/events'));
    exit;
}

$attendees = $event_model->getAttendeesByEvent($event_id, $organizer_id);

include APP_ROOT . '/app/views/organizer/attendee_list.php';
