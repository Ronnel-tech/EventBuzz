<?php

require_once APP_ROOT . '/app/models/AdminUserModel.php';
require_once APP_ROOT . '/app/models/AdminEventModel.php';

$user_model = new AdminUserModel();
$event_model = new AdminEventModel();

$dashboard_summary = $user_model->getDashboardSummary();
$event_summary = $event_model->getDashboardSummary();
$event_creation_trend = $event_model->getEventCreationTrend();
$tickets_sold_by_event = $event_model->getTicketsSoldByEvent();
$top_performing_events = $event_model->getTopPerformingEventsByTicketsSold();

include APP_ROOT . '/app/views/admin/dashboard.php';
