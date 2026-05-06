<?php

require_once APP_ROOT . '/app/models/AttendeeEventModel.php';

$event_model = new AttendeeEventModel();
$user = $_SESSION['user'] ?? [];
$user_id = (int) ($user['id'] ?? 0);
$event_id = (int) ($_GET['id'] ?? 0);
$checkout_session = $_SESSION['attendee_checkout'] ?? [];

if (
    $event_id <= 0 ||
    !is_array($checkout_session) ||
    (int) ($checkout_session['event_id'] ?? 0) !== $event_id
) {
    set_flash('error', 'Please select your tickets before continuing to payment.');
    header('Location: ' . url('/attendee/checkout?id=' . $event_id));
    exit;
}

$event = $event_model->getEventPaymentDetailsById($event_id);

if (!$event) {
    unset($_SESSION['attendee_checkout'], $_SESSION['attendee_payment_old']);
    set_flash('error', 'The selected event is no longer available for ticket purchase.');
    header('Location: ' . url('/attendee'));
    exit;
}

$quantities = $checkout_session['quantities'] ?? [];
$summary_result = $event_model->summarizeSelectedTickets($event_id, $quantities);

if (!$summary_result['success']) {
    unset($_SESSION['attendee_checkout'], $_SESSION['attendee_payment_old']);
    set_flash('error', $summary_result['message']);
    header('Location: ' . url('/attendee/checkout?id=' . $event_id));
    exit;
}

$event_payment_type = (string) ($event['payment_type'] ?? 'free');
$is_free_event = $event_payment_type === 'free';
$payment_method_options = $is_free_event
    ? ['free']
    : ($event_payment_type === 'gcash' ? ['cash', 'gcash'] : ['cash']);

$old = [
    'payment_method' => $payment_method_options[0],
    'gcash_sender_name' => '',
    'gcash_reference' => '',
];

if (isset($_SESSION['attendee_payment_old']) && is_array($_SESSION['attendee_payment_old'])) {
    $old = array_merge($old, $_SESSION['attendee_payment_old']);
    unset($_SESSION['attendee_payment_old']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'payment_method' => trim((string) ($_POST['payment_method'] ?? '')),
        'gcash_sender_name' => trim((string) ($_POST['gcash_sender_name'] ?? '')),
        'gcash_reference' => trim((string) ($_POST['gcash_reference'] ?? '')),
    ];

    if (!in_array($old['payment_method'], $payment_method_options, true)) {
        $_SESSION['attendee_payment_old'] = $old;
        set_flash('error', 'Please choose a valid payment method.');
        header('Location: ' . url('/attendee/payment?id=' . $event_id));
        exit;
    }

    $payment_details = [
        'gcash_reference' => '',
        'gcash_screenshot' => '',
    ];

    if ($old['payment_method'] === 'gcash') {
        if ($old['gcash_sender_name'] === '' || $old['gcash_reference'] === '') {
            $_SESSION['attendee_payment_old'] = $old;
            set_flash('error', 'GCash sender name and reference number are required.');
            header('Location: ' . url('/attendee/payment?id=' . $event_id));
            exit;
        }

        if (empty($_FILES['gcash_receipt']['name'])) {
            $_SESSION['attendee_payment_old'] = $old;
            set_flash('error', 'Please upload your GCash receipt.');
            header('Location: ' . url('/attendee/payment?id=' . $event_id));
            exit;
        }

        $extension = strtolower(pathinfo($_FILES['gcash_receipt']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowed_extensions, true)) {
            $_SESSION['attendee_payment_old'] = $old;
            set_flash('error', 'GCash receipt must be a JPG, PNG, GIF, or WEBP file.');
            header('Location: ' . url('/attendee/payment?id=' . $event_id));
            exit;
        }

        $upload_directory = APP_ROOT . '/public/assets/images/attendee';
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0777, true);
        }

        $filename = uniqid('receipt_', true) . '.' . $extension;
        $destination = $upload_directory . '/' . $filename;

        if (!move_uploaded_file($_FILES['gcash_receipt']['tmp_name'], $destination)) {
            $_SESSION['attendee_payment_old'] = $old;
            set_flash('error', 'Failed to upload the GCash receipt.');
            header('Location: ' . url('/attendee/payment?id=' . $event_id));
            exit;
        }

        $payment_details['gcash_reference'] = $old['gcash_reference'];
        $payment_details['gcash_screenshot'] = '/public/assets/images/attendee/' . $filename;
    }

    $attendee_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
    if ($attendee_name === '') {
        $attendee_name = (string) ($user['email'] ?? 'Attendee');
    }

    $purchase_result = $event_model->createOrderWithItems(
        $user_id,
        $event_id,
        $old['payment_method'],
        $attendee_name,
        $quantities,
        $payment_details
    );

    if ($purchase_result['success']) {
        unset($_SESSION['attendee_checkout'], $_SESSION['attendee_payment_old']);
        set_flash('success', $purchase_result['message']);
        header('Location: ' . url('/attendee/detailed_event?id=' . $event_id));
        exit;
    }

    $_SESSION['attendee_payment_old'] = $old;
    set_flash('error', $purchase_result['message']);
    header('Location: ' . url('/attendee/payment?id=' . $event_id));
    exit;
}

$summary = $summary_result['summary'];

include APP_ROOT . '/app/views/attendee/payment.php';
