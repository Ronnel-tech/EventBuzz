<?php

require_once APP_ROOT . '/app/models/OrganizerEventModel.php';

$event_model = new OrganizerEventModel();
$organizer_id = (int) ($_SESSION['user']['id'] ?? 0);
$event_id = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? (int) ($_POST['event_id'] ?? 0)
    : (int) ($_GET['id'] ?? 0);

if ($event_id <= 0) {
    set_flash('error', 'Please select an event to edit.');
    header('Location: ' . url('/organizer/events'));
    exit;
}

$event = $event_model->getEventDetailsByOrganizer($event_id, $organizer_id);

if (!$event) {
    set_flash('error', 'The selected event could not be found.');
    header('Location: ' . url('/organizer/events'));
    exit;
}

$old = [
    'title' => (string) ($event['title'] ?? ''),
    'category' => (string) ($event['category_id'] ?? ''),
    'summary' => (string) ($event['description'] ?? ''),
    'date' => !empty($event['start_datetime']) ? date('Y-m-d', strtotime($event['start_datetime'])) : '',
    'start_time' => !empty($event['start_datetime']) ? date('H:i', strtotime($event['start_datetime'])) : '',
    'end_time' => !empty($event['end_datetime']) ? date('H:i', strtotime($event['end_datetime'])) : '',
    'street' => (string) ($event['street'] ?? ''),
    'city' => (string) ($event['city'] ?? ''),
    'province' => (string) ($event['province'] ?? ''),
    'country' => (string) ($event['country'] ?? ''),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $redirect_path = '/organizer/edit-event?id=' . $event_id;

    $old = [
        'title' => trim($_POST['title'] ?? ''),
        'category' => (string) ($_POST['category'] ?? ''),
        'summary' => trim($_POST['summary'] ?? ''),
        'date' => trim($_POST['date'] ?? ''),
        'start_time' => trim($_POST['start_time'] ?? ''),
        'end_time' => trim($_POST['end_time'] ?? ''),
        'street' => trim($_POST['street'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'province' => trim($_POST['province'] ?? ''),
        'country' => trim($_POST['country'] ?? ''),
    ];

    $required_fields = ['title', 'category', 'summary', 'date', 'start_time', 'end_time', 'street', 'city', 'province', 'country'];

    foreach ($required_fields as $field) {
        if ($old[$field] === '') {
            set_flash('error', 'Please fill in all event details.');
            $_SESSION['edit_event_old'][$event_id] = $old;
            header('Location: ' . url($redirect_path));
            exit;
        }
    }

    $start_datetime = $old['date'] . ' ' . $old['start_time'] . ':00';
    $end_datetime = $old['date'] . ' ' . $old['end_time'] . ':00';

    if (strtotime($end_datetime) <= strtotime($start_datetime)) {
        set_flash('error', 'End time must be later than the start time.');
        $_SESSION['edit_event_old'][$event_id] = $old;
        header('Location: ' . url($redirect_path));
        exit;
    }

    $category_id = (int) $old['category'];
    $category = $event_model->findCategoryById($category_id);

    if (!$category) {
        set_flash('error', 'Please choose a valid category.');
        $_SESSION['edit_event_old'][$event_id] = $old;
        header('Location: ' . url($redirect_path));
        exit;
    }

    $banner_image = $event['banner_image'] ?? null;

    if (!empty($_FILES['picture']['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed_extensions, true)) {
            set_flash('error', 'Banner image must be a JPG, PNG, GIF, or WEBP file.');
            $_SESSION['edit_event_old'][$event_id] = $old;
            header('Location: ' . url($redirect_path));
            exit;
        }

        $upload_directory = APP_ROOT . '/public/assets/images/events';
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0777, true);
        }

        $filename = uniqid('event_', true) . '.' . $extension;
        $destination = $upload_directory . '/' . $filename;

        if (!move_uploaded_file($_FILES['picture']['tmp_name'], $destination)) {
            set_flash('error', 'Failed to upload the banner image.');
            $_SESSION['edit_event_old'][$event_id] = $old;
            header('Location: ' . url($redirect_path));
            exit;
        }

        $banner_image = '/public/assets/images/events/' . $filename;
    }

    $event_model->updateEventByOrganizer($event_id, $organizer_id, [
        'category_id' => $category_id,
        'title' => $old['title'],
        'description' => $old['summary'],
        'banner_image' => $banner_image,
        'start_datetime' => $start_datetime,
        'end_datetime' => $end_datetime,
        'street' => $old['street'],
        'city' => $old['city'],
        'province' => $old['province'],
        'country' => $old['country'],
    ]);

    unset($_SESSION['edit_event_old'][$event_id]);

    set_flash('success', 'Event details updated successfully.');
    header('Location: ' . url('/organizer/detailed-event?id=' . $event_id));
    exit;
}

if (isset($_SESSION['edit_event_old'][$event_id]) && is_array($_SESSION['edit_event_old'][$event_id])) {
    $old = array_merge($old, $_SESSION['edit_event_old'][$event_id]);
    unset($_SESSION['edit_event_old'][$event_id]);
}

$categories = $event_model->ensureDefaultCategories();

include APP_ROOT . '/app/views/organizer/edit_event.php';
