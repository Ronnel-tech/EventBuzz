<?php

require_once APP_ROOT . '/app/models/AttendeeEventModel.php';

$event_model = new AttendeeEventModel();
$user = $_SESSION['user'] ?? [];
$user_id = (int) ($user['id'] ?? 0);

$active_orders = $event_model->getTicketOrdersByUserId($user_id, false);
$completed_orders = $event_model->getTicketOrdersByUserId($user_id, true);

include APP_ROOT . '/app/views/attendee/ticket.php';
