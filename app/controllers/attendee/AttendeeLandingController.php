<?php

require_once APP_ROOT . '/app/models/AttendeeEventModel.php';

$event_model = new AttendeeEventModel();
$user = $_SESSION['user'] ?? [];
$today_events = $event_model->getTodayEvents();
$category_sections = $event_model->getCategorySections();

include APP_ROOT . '/app/views/attendee/attendee_landing.php';
