<?php

require_once APP_ROOT . '/app/models/AdminEventModel.php';

$event_model = new AdminEventModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = (int) ($_POST['event_id'] ?? 0);
    $action = trim((string) ($_POST['action'] ?? ''));

    if ($action !== 'delete' || $event_id <= 0) {
        set_flash('error', 'Please select a valid event to delete.');
        header('Location: ' . url('/admin/event-management'));
        exit;
    }

    $deleted = $event_model->deleteEventById($event_id);

    if ($deleted) {
        set_flash('success', 'Event deleted successfully.');
    } else {
        set_flash('error', 'Event could not be deleted.');
    }

    header('Location: ' . url('/admin/event-management'));
    exit;
}

$events = $event_model->getAllEvents();

include APP_ROOT . '/app/views/admin/event_management.php';
