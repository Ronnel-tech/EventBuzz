<?php

require_once APP_ROOT . '/app/models/AdminEventModel.php';

$event_model = new AdminEventModel();
$events = $event_model->getAllEvents();

include APP_ROOT . '/app/views/admin/event_management.php';
