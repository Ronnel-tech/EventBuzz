<?php

require_once APP_ROOT . '/app/models/AdminUserModel.php';

$user_model = new AdminUserModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $organizer_id = (int) ($_POST['organizer_id'] ?? 0);
    $action = trim((string) ($_POST['action'] ?? ''));

    if ($action !== 'delete' || $organizer_id <= 0) {
        set_flash('error', 'Please select a valid organizer to delete.');
        header('Location: ' . url('/admin/organizer-management'));
        exit;
    }

    $deleted = $user_model->deleteOrganizerById($organizer_id);

    if ($deleted) {
        set_flash('success', 'Organizer deleted successfully.');
    } else {
        set_flash('error', 'Organizer could not be deleted.');
    }

    header('Location: ' . url('/admin/organizer-management'));
    exit;
}

$organizers = $user_model->getOrganizersWithEventCounts();

include APP_ROOT . '/app/views/admin/organizer_management.php';
