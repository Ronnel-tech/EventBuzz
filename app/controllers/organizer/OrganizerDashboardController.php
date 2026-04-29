<?php

require_once APP_ROOT . '/app/models/OrganizerEventModel.php';

$event_model = new OrganizerEventModel();
$user = $_SESSION['user'] ?? [];
$organizer_id = (int) ($user['id'] ?? 0);

$dashboard_summary = $event_model->getDashboardSummary($organizer_id);
$today_ticket_sales = $event_model->getTodayTicketSalesByEvent($organizer_id);
$revenue_over_time = $event_model->getRevenueOverTime($organizer_id);
$sales_distribution = $event_model->getSalesDistributionByPaymentMethod($organizer_id);

include APP_ROOT . '/app/views/organizer/organizer_dashboard.php';
