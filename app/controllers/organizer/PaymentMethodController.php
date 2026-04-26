<?php

require_once APP_ROOT . '/app/models/OrganizerPaymentModel.php';

$payment_model = new OrganizerPaymentModel();
$organizer_id = (int) ($_SESSION['user']['id'] ?? 0);
$event_id = (int) ($_SESSION['current_event_id'] ?? 0);

if ($event_id <= 0) {
    set_flash('error', 'Create an event first before setting payment details.');
    header('Location: ' . url('/organizer/create-event'));
    exit;
}

$event = $payment_model->getEventForOrganizer($event_id, $organizer_id);

if (!$event) {
    unset($_SESSION['current_event_id']);
    set_flash('error', 'The selected event could not be found.');
    header('Location: ' . url('/organizer/create-event'));
    exit;
}

$profile = $payment_model->getOrganizerProfileByUserId($organizer_id);

$old = [
    'payment_type' => $event['payment_type'] ?? 'free',
    'gcash_name' => $profile['gcash_name'] ?? '',
    'gcash_number' => $profile['gcash_number'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'payment_type' => trim($_POST['payment_type'] ?? ''),
        'gcash_name' => trim($_POST['gcash_name'] ?? ''),
        'gcash_number' => trim($_POST['gcash_number'] ?? ''),
    ];

    $allowed_payment_types = ['free', 'cash', 'gcash'];
    if (!in_array($old['payment_type'], $allowed_payment_types, true)) {
        set_flash('error', 'Please choose a valid payment method.');
        $_SESSION['payment_method_old'] = $old;
        header('Location: ' . url('/organizer/payment-method'));
        exit;
    }

    $gcash_qr = $profile['gcash_qr'] ?? null;

    if ($old['payment_type'] === 'gcash') {
        if ($old['gcash_name'] === '' || $old['gcash_number'] === '') {
            set_flash('error', 'GCash name and number are required.');
            $_SESSION['payment_method_old'] = $old;
            header('Location: ' . url('/organizer/payment-method'));
            exit;
        }

        if (!preg_match('/^[0-9]{11}$/', $old['gcash_number'])) {
            set_flash('error', 'GCash number must be 11 digits.');
            $_SESSION['payment_method_old'] = $old;
            header('Location: ' . url('/organizer/payment-method'));
            exit;
        }

        if (!empty($_FILES['gcash_qr']['name'])) {
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $extension = strtolower(pathinfo($_FILES['gcash_qr']['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, $allowed_extensions, true)) {
                set_flash('error', 'GCash QR must be a JPG, PNG, GIF, or WEBP file.');
                $_SESSION['payment_method_old'] = $old;
                header('Location: ' . url('/organizer/payment-method'));
                exit;
            }

            $upload_directory = APP_ROOT . '/public/assets/images/gcash';
            if (!is_dir($upload_directory)) {
                mkdir($upload_directory, 0777, true);
            }

            $filename = uniqid('gcash_', true) . '.' . $extension;
            $destination = $upload_directory . '/' . $filename;

            if (!move_uploaded_file($_FILES['gcash_qr']['tmp_name'], $destination)) {
                set_flash('error', 'Failed to upload the GCash QR code.');
                $_SESSION['payment_method_old'] = $old;
                header('Location: ' . url('/organizer/payment-method'));
                exit;
            }

            $gcash_qr = '/public/assets/images/gcash/' . $filename;
        }

        if (empty($gcash_qr)) {
            set_flash('error', 'Please upload a GCash QR code.');
            $_SESSION['payment_method_old'] = $old;
            header('Location: ' . url('/organizer/payment-method'));
            exit;
        }

        $payment_model->saveOrganizerProfile($organizer_id, [
            'gcash_name' => $old['gcash_name'],
            'gcash_number' => $old['gcash_number'],
            'gcash_qr' => $gcash_qr,
        ]);
    }

    $payment_model->updateEventPaymentType($event_id, $old['payment_type']);

    unset($_SESSION['payment_method_old']);
    set_flash('success', 'Payment method saved successfully.');
    header('Location: ' . url('/organizer/events'));
    exit;
}

if (isset($_SESSION['payment_method_old']) && is_array($_SESSION['payment_method_old'])) {
    $old = array_merge($old, $_SESSION['payment_method_old']);
    unset($_SESSION['payment_method_old']);
}

$profile = $payment_model->getOrganizerProfileByUserId($organizer_id);

include APP_ROOT . '/app/views/organizer/payment_method.php';
