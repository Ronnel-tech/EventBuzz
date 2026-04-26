<?php

class OrganizerTicketModel
{
    private Database $database;

    public function __construct()
    {
        $this->database = db();
    }

    public function getEventForOrganizer(int $event_id, int $organizer_id): array|null
    {
        return $this->database
            ->table('events')
            ->where('id', $event_id)
            ->where('organizer_id', $organizer_id)
            ->get() ?: null;
    }

    public function getTicketTypesByEventId(int $event_id): array
    {
        return $this->database
            ->table('ticket_types')
            ->where('event_id', $event_id)
            ->get_all() ?: [];
    }

    public function countTicketTypesByEventId(int $event_id): int
    {
        return count($this->getTicketTypesByEventId($event_id));
    }

    public function createTicketType(array $data): int
    {
        $this->database->table('ticket_types')->insert([
            'event_id' => $data['event_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'sold' => 0,
            'start_datetime' => $data['start_datetime'],
            'end_datetime' => $data['end_datetime'],
            'status' => $data['status'],
        ]);

        return $this->database->last_id();
    }

    public function deleteTicketTypeByEvent(int $ticket_type_id, int $event_id): int
    {
        return $this->database
            ->table('ticket_types')
            ->where('id', $ticket_type_id)
            ->where('event_id', $event_id)
            ->delete();
    }
}
