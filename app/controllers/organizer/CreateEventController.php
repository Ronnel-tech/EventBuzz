<?php

require_once APP_ROOT . '/app/models/OrganizerEventModel.php';

$event_model = new OrganizerEventModel();

$old = [
    'title' => '',
    'category' => '',
    'summary' => '',
    'date' => '',
    'start_time' => '',
    'end_time' => '',
    'street' => '',
    'city' => '',
    'province' => '',
    'country' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $_SESSION['create_event_old'] = $old;
            header('Location: ' . url('/organizer/create-event'));
            exit;
        }
    }

    $start_datetime = $old['date'] . ' ' . $old['start_time'] . ':00';
    $end_datetime = $old['date'] . ' ' . $old['end_time'] . ':00';

    if (strtotime($end_datetime) <= strtotime($start_datetime)) {
        set_flash('error', 'End time must be later than the start time.');
        $_SESSION['create_event_old'] = $old;
        header('Location: ' . url('/organizer/create-event'));
        exit;
    }

    $category_id = (int) $old['category'];
    $category = $event_model->findCategoryById($category_id);

    if (!$category) {
        set_flash('error', 'Please choose a valid category.');
        $_SESSION['create_event_old'] = $old;
        header('Location: ' . url('/organizer/create-event'));
        exit;
    }

    $banner_image = null;

    if (empty($_FILES['picture']['name'])) {
        set_flash('error', 'Please upload a banner image.');
        $_SESSION['create_event_old'] = $old;
        header('Location: ' . url('/organizer/create-event'));
        exit;
    }

    if (!empty($_FILES['picture']['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed_extensions, true)) {
            set_flash('error', 'Banner image must be a JPG, PNG, GIF, or WEBP file.');
            $_SESSION['create_event_old'] = $old;
            header('Location: ' . url('/organizer/create-event'));
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
            $_SESSION['create_event_old'] = $old;
            header('Location: ' . url('/organizer/create-event'));
            exit;
        }

        $banner_image = '/public/assets/images/events/' . $filename;
    }

    $event_id = $event_model->createEvent([
        'organizer_id' => $_SESSION['user']['id'],
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
        'payment_type' => 'free',
    ]);

    $_SESSION['current_event_id'] = $event_id;
    unset($_SESSION['create_event_old']);

    set_flash('success', 'Event details saved successfully.');
    header('Location: ' . url('/organizer/add-ticket'));
    exit;
}

if (isset($_SESSION['create_event_old']) && is_array($_SESSION['create_event_old'])) {
    $old = array_merge($old, $_SESSION['create_event_old']);
    unset($_SESSION['create_event_old']);
}

$categories = $event_model->ensureDefaultCategories();

include APP_ROOT . '/app/views/organizer/create_event.php';
