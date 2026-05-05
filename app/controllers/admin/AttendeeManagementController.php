<?php


require_once APP_ROOT . '/app/models/AdminUserModel.php';

$user_model = new AdminUserModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendee_id = (int) ($_POST['attendee_id'] ?? 0);
    $action = trim((string) ($_POST['action'] ?? ''));

    if ($action !== 'delete' || $attendee_id <= 0) {
        set_flash('error', 'Please select a valid attendee to delete.');
        header('Location: ' . url('/admin/attendee-management'));
        exit;
    }

    $deleted = $user_model->deleteAttendeeById($attendee_id);

    if ($deleted) {
        set_flash('success', 'Attendee deleted successfully.');
    } else {
        set_flash('error', 'Attendee could not be deleted.');
    }

    header('Location: ' . url('/admin/attendee-management'));
    exit;
}

$attendees = $user_model->getAttendeesWithTicketCounts();

include APP_ROOT . '/app/views/admin/user_management.php';
