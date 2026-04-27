<?php
/**
 * All routes here
 */

// $router->get('/', 'app/views/homepage');
$router->get('/', 'app/views/landing');


// PUBLIC
$router->get('/login', 'app/views/auth/login');
$router->post('/login', 'app/views/auth/login');

$router->get('/signup', 'app/views/auth/signup');
$router->post('/signup', 'app/views/auth/signup');

// ADMIN
$router->get('/admin/dashboard', 'app/views/admin/dashboard', ['admin']);
$router->get('/admin/detailed_event', 'app/views/admin/detailed_event', ['admin']);
$router->get('/admin/event_management', 'app/views/admin/event_management', ['admin']);
$router->get('/admin/organizer_management', 'app/views/admin/organizer_management', ['admin']);
$router->get('/admin/user_management', 'app/views/admin/user_management', ['admin']);

// ORGANIZER
$router->get('/organizer/dashboard', 'app/views/organizer/organizer_dashboard', ['organizer']);

$router->get('/organizer/events', 'app/controllers/organizer/EventsController', ['organizer']);
$router->post('/organizer/events', 'app/controllers/organizer/EventsController', ['organizer']);

$router->get('/organizer/create-event', 'app/controllers/organizer/CreateEventController', ['organizer']);
$router->post('/organizer/create-event', 'app/controllers/organizer/CreateEventController', ['organizer']);

$router->get('/organizer/edit-event', 'app/controllers/organizer/EditEventController', ['organizer']);
$router->post('/organizer/edit-event', 'app/controllers/organizer/EditEventController', ['organizer']);

$router->get('/organizer/add-ticket', 'app/controllers/organizer/AddTicketController', ['organizer']);
$router->post('/organizer/add-ticket', 'app/controllers/organizer/AddTicketController', ['organizer']);

$router->get('/organizer/attendee-list', 'app/controllers/organizer/AttendeeListController', ['organizer']);

$router->get('/organizer/detailed-event', 'app/controllers/organizer/DetailedEventController', ['organizer']);

$router->get('/organizer/payment-method', 'app/controllers/organizer/PaymentMethodController', ['organizer']);
$router->post('/organizer/payment-method', 'app/controllers/organizer/PaymentMethodController', ['organizer']);

$router->get('/organizer/qr_page', 'app/views/organizer/qr_page', ['organizer']);










// ATTENDEE
$router->get('/attendee', 'app/views/attendee/attendee_landing', ['attendee']);
$router->get('/attendee/detailed_event', 'app/views/attendee/attendee_detailed_event', ['attendee']);
$router->get('/attendee/checkout', 'app/views/attendee/checkout', ['attendee']);
$router->get('/attendee/payment', 'app/views/attendee/payment', ['attendee']);
$router->get('/attendee/ticket', 'app/views/attendee/ticket', ['attendee']);

// LOGOUT
$router->post('/logout', 'app/views/auth/logout', ['auth']);



