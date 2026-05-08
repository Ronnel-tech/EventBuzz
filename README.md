# EventBuzz

EventBuzz is a web-based event promotion and ticketing system built to help organizers publish events, manage ticket sales, and monitor attendees in one place. It also gives attendees a simple way to discover events, reserve or purchase tickets, and keep track of their bookings online.

## Documentation

Full project documentation is available on Notion:

[EventBuzz Documentation](https://abstracted-word-26d.notion.site/EventBuzz-A-Web-Based-Event-Promotion-and-Ticketing-System-314da4fdbefa80699829c29721828e81?source=copy_link)

## Live Website

The deployed EventBuzz website is available here:

[https://eventbuzz.great-site.net/](https://eventbuzz.great-site.net/)

## What The System Does

EventBuzz supports the full flow of event participation, from event creation to ticket claiming:

- Organizers can create events, upload banner images, define ticket types, and manage payment options.
- Attendees can browse available events, view event details, choose ticket quantities, complete checkout, and access their purchased tickets.
- Admins can oversee users, organizers, events, and attendee activity from a dedicated management area.

The system is designed around three main user roles so each type of user sees only the tools relevant to them.

## Main Users

### Admin

Admins oversee the platform and monitor system-wide activity. Their tools focus on supervision and management rather than event participation.

- View dashboard summaries
- Manage organizers
- Manage attendees
- Monitor events and event-related activity

### Organizer

Organizers are the event owners in the platform. They use EventBuzz to publish and operate events.

- Create and edit events
- Upload event banners
- Add ticket types and pricing
- Set payment details
- View attendee lists
- Track ticket sales and revenue

### Attendee

Attendees use the platform to discover and join events.

- Browse current and upcoming events
- Search by category or event details
- View event information
- Select ticket quantities
- Complete free or paid checkout
- View booked tickets and ticket details

## Core Features

- Role-based access for admin, organizer, and attendee accounts
- Event publishing and management
- Category-based event browsing
- Free and paid ticket flows
- GCash and cash payment support
- Order and ticket generation
- Organizer-side attendee monitoring
- Admin-side event and user oversight

## Typical System Flow

1. An organizer creates an event and adds ticket information.
2. The event becomes visible to attendees in the event listing pages.
3. An attendee opens the event details page and selects tickets.
4. The attendee completes checkout and payment submission.
5. The system creates the order, records purchased ticket items, and generates individual tickets.
6. Organizers and admins can later review attendance and transaction data.

## Project Structure

The system follows a lightweight PHP MVC-style structure:

- `app/controllers/` handles request flow for admin, organizer, and attendee pages
- `app/models/` contains data access and business logic
- `app/views/` contains the server-rendered interface
- `app/middlewares/` protects routes by role
- `scheme/` contains the custom routing, helpers, and database utilities
- `public/` stores CSS, JavaScript, fonts, and uploaded images

## Technology Overview

EventBuzz is built as a custom PHP web application with:

- PHP
- MySQL
- A lightweight custom router and database layer
- Server-rendered views

The project uses a simple custom architecture so the focus stays on the event management workflow rather than heavy framework conventions.

## Purpose Of The System

EventBuzz is intended for scenarios where event organizers need a centralized platform to promote events and handle ticketing digitally. It reduces manual coordination by moving event posting, ticket reservation, payment submission, and attendee tracking into one system.

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.
