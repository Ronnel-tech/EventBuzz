<?php

require_once APP_ROOT . '/app/models/OrganizerEventModel.php';

$event_model = new OrganizerEventModel();
$organizer_id = (int) ($_SESSION['user']['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');

    if ($action === 'delete') {
        $event_id = (int) ($_POST['event_id'] ?? 0);

        if ($event_id <= 0) {
            set_flash('error', 'Invalid event selected.');
            header('Location: ' . url('/organizer/events'));
            exit;
        }

        $deleted = $event_model->deleteEventByOrganizer($event_id, $organizer_id);

        if (!$deleted) {
            set_flash('error', 'Event could not be deleted.');
            header('Location: ' . url('/organizer/events'));
            exit;
        }

        if ((int) ($_SESSION['current_event_id'] ?? 0) === $event_id) {
            unset($_SESSION['current_event_id']);
        }

        set_flash('success', 'Event deleted successfully.');
        header('Location: ' . url('/organizer/events'));
        exit;
    }
}

$events = $event_model->getEventsByOrganizer($organizer_id);

include APP_ROOT . '/app/views/organizer/events.php';
