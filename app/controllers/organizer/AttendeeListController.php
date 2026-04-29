<?php

require_once APP_ROOT . '/app/models/OrganizerEventModel.php';

$event_model = new OrganizerEventModel();
$organizer_id = (int) ($_SESSION['user']['id'] ?? 0);
$event_id = (int) ($_GET['id'] ?? 0);
$search = trim((string) ($_GET['search'] ?? ''));
$filter = trim((string) ($_GET['filter'] ?? 'all'));

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int) ($_POST['order_id'] ?? 0);
    $status_value = trim((string) ($_POST['status'] ?? ''));
    $allowed_statuses = ['pending', 'done'];

    if ($order_id <= 0 || !in_array($status_value, $allowed_statuses, true)) {
        set_flash('error', 'Please choose a valid attendee payment status.');
        header('Location: ' . url('/organizer/attendee-list?id=' . $event_id . '&filter=' . urlencode($filter) . '&search=' . urlencode($search)));
        exit;
    }

    $updated = $event_model->updateAttendeeOrderStatus($order_id, $event_id, $organizer_id, $status_value);

    if ($updated) {
        set_flash('success', 'Attendee payment status updated successfully.');
    } else {
        set_flash('error', 'Attendee payment status could not be updated.');
    }

    header('Location: ' . url('/organizer/attendee-list?id=' . $event_id . '&filter=' . urlencode($filter) . '&search=' . urlencode($search)));
    exit;
}

$attendees = $event_model->getAttendeesByEvent($event_id, $organizer_id, $search, $filter);

include APP_ROOT . '/app/views/organizer/attendee_list.php';
