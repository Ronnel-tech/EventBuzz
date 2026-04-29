<?php

require_once APP_ROOT . '/app/models/AttendeeEventModel.php';

$event_model = new AttendeeEventModel();
$user = $_SESSION['user'] ?? [];
$search = trim((string) ($_GET['search'] ?? ''));
$today_events = $event_model->getTodayEvents($search);
$category_sections = $event_model->getCategorySections($search);

include APP_ROOT . '/app/views/attendee/attendee_landing.php';
