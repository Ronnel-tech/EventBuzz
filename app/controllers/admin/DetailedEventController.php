<?php

require_once APP_ROOT . '/app/models/AdminEventModel.php';

$event_model = new AdminEventModel();
$event_id = (int) ($_GET['id'] ?? 0);

if ($event_id <= 0) {
    set_flash('error', 'Please select an event to view.');
    header('Location: ' . url('/admin/event-management'));
    exit;
}

$event = $event_model->getEventDetailsById($event_id);

if (!$event) {
    set_flash('error', 'The selected event could not be found.');
    header('Location: ' . url('/admin/event-management'));
    exit;
}

include APP_ROOT . '/app/views/admin/detailed_event.php';
