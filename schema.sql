users
- id (PK, BIGINT UNSIGNED, AUTO_INCREMENT)
- first_name (VARCHAR)
- last_name (VARCHAR)
- email (VARCHAR, UNIQUE)
- password (VARCHAR)
- role (ENUM: admin, organizer, attendee)
- created_at (TIMESTAMP)


organizer_profiles
- id (PK)
- user_id (FK → users.id)
- gcash_name (VARCHAR)
- gcash_number (VARCHAR)
- gcash_qr (VARCHAR)

categories
- id (PK)
- name (VARCHAR)


events
- id (PK)
- organizer_id (FK → users.id)
- category_id (FK → categories.id)
- title (VARCHAR)
- description (TEXT)
- banner_image (VARCHAR)
- start_datetime (DATETIME)
- end_datetime (DATETIME)
- street (VARCHAR)
- city (VARCHAR)
- province (VARCHAR)
- country (VARCHAR)
- payment_type (ENUM: free, cash, gcash)
- created_at (TIMESTAMP)

ticket_types
- id (PK)
- event_id (FK → events.id)
- name (VARCHAR)
- price (DECIMAL)
- quantity (INT)
- sold (INT)
- start_datetime (DATETIME)
- end_datetime (DATETIME)
- status (ENUM: open, closed)


orders
- id (PK)
- user_id (FK → users.id)
- event_id (FK → events.id)
- total_amount (DECIMAL)
- payment_method (ENUM: cash, gcash, free)
- gcash_reference (VARCHAR)
- gcash_screenshot (VARCHAR)
- status (ENUM: pending, done)
- created_at (TIMESTAMP)

order_items
- id (PK)
- order_id (FK → orders.id)
- ticket_type_id (FK → ticket_types.id)
- quantity (INT)
- subtotal (DECIMAL)


tickets
- id (PK)
- order_item_id (FK → order_items.id)
- ticket_code (VARCHAR, UNIQUE)
- attendee_name (VARCHAR)
- status (ENUM: not_used, used)
- scanned_at (DATETIME)


organizer_profiles.user_id → users.id

events.organizer_id → users.id
events.category_id → categories.id

ticket_types.event_id → events.id

orders.user_id → users.id
orders.event_id → events.id

order_items.order_id → orders.id
order_items.ticket_type_id → ticket_types.id

tickets.order_item_id → order_items.id