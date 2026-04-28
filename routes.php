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
$router->get('/admin/detailed-event', 'app/controllers/admin/DetailedEventController', ['admin']);
$router->get('/admin/event-management', 'app/controllers/admin/EventManagementController', ['admin']);
$router->get('/admin/organizer-management', 'app/controllers/admin/OrganizerManagementController', ['admin']);
$router->post('/admin/organizer-management', 'app/controllers/admin/OrganizerManagementController', ['admin']);
$router->get('/admin/attendee-management', 'app/controllers/admin/AttendeeManagementController', ['admin']);
$router->post('/admin/attendee-management', 'app/controllers/admin/AttendeeManagementController', ['admin']);

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
$router->post('/organizer/attendee-list', 'app/controllers/organizer/AttendeeListController', ['organizer']);

$router->get('/organizer/detailed-event', 'app/controllers/organizer/DetailedEventController', ['organizer']);

$router->get('/organizer/payment-method', 'app/controllers/organizer/PaymentMethodController', ['organizer']);
$router->post('/organizer/payment-method', 'app/controllers/organizer/PaymentMethodController', ['organizer']);

$router->get('/organizer/qr_page', 'app/controllers/organizer/QrPageController', ['organizer']);
$router->post('/organizer/qr_page', 'app/controllers/organizer/QrPageController', ['organizer']);










// ATTENDEE
$router->get('/attendee', 'app/controllers/attendee/AttendeeLandingController', ['attendee']);
$router->get('/attendee/detailed_event', 'app/controllers/attendee/AttendeeDetailedEventController', ['attendee']);
$router->get('/attendee/checkout', 'app/controllers/attendee/AttendeeCheckoutController', ['attendee']);
$router->post('/attendee/checkout', 'app/controllers/attendee/AttendeeCheckoutController', ['attendee']);
$router->get('/attendee/payment', 'app/controllers/attendee/AttendeePaymentController', ['attendee']);
$router->post('/attendee/payment', 'app/controllers/attendee/AttendeePaymentController', ['attendee']);
$router->get('/attendee/ticket', 'app/controllers/attendee/AttendeeTicketController', ['attendee']);
$router->get('/attendee/ticket-detail', 'app/controllers/attendee/AttendeeTicketDetailController', ['attendee']);

// LOGOUT
$router->post('/logout', 'app/views/auth/logout', ['auth']);



