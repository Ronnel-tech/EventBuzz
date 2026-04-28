<?php

require_once APP_ROOT . '/app/models/OrganizerEventModel.php';

$event_model = new OrganizerEventModel();
$organizer_id = (int) ($_SESSION['user']['id'] ?? 0);
$event_id = (int) ($_GET['event_id'] ?? 0);
$order_id = (int) ($_GET['order_id'] ?? 0);

if ($event_id <= 0 || $order_id <= 0) {
    set_flash('error', 'Please select a valid attendee payment record.');
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
    $status_value = trim((string) ($_POST['status'] ?? ''));
    $allowed_statuses = ['pending', 'done'];

    if (!in_array($status_value, $allowed_statuses, true)) {
        set_flash('error', 'Please choose a valid attendee payment status.');
        header('Location: ' . url('/organizer/qr_page?event_id=' . $event_id . '&order_id=' . $order_id));
        exit;
    }

    $updated = $event_model->updateAttendeeOrderStatus($order_id, $event_id, $organizer_id, $status_value);

    if ($updated) {
        set_flash('success', 'Attendee payment status updated successfully.');
    } else {
        set_flash('error', 'Attendee payment status could not be updated.');
    }

    header('Location: ' . url('/organizer/attendee-list?id=' . $event_id));
    exit;
}

$payment_details = $event_model->getAttendeePaymentDetailsByOrder($order_id, $event_id, $organizer_id);

if (!$payment_details) {
    set_flash('error', 'The selected attendee payment record could not be found.');
    header('Location: ' . url('/organizer/attendee-list?id=' . $event_id));
    exit;
}

include APP_ROOT . '/app/views/organizer/qr_page.php';
