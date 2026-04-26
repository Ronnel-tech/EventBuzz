<?php

class OrganizerPaymentModel
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

    public function getOrganizerProfileByUserId(int $user_id): array|null
    {
        return $this->database
            ->table('organizer_profiles')
            ->where('user_id', $user_id)
            ->get() ?: null;
    }

    public function updateEventPaymentType(int $event_id, string $payment_type): int
    {
        return $this->database
            ->table('events')
            ->where('id', $event_id)
            ->update([
                'payment_type' => $payment_type,
            ]);
    }

    public function saveOrganizerProfile(int $user_id, array $data): int
    {
        $profile = $this->getOrganizerProfileByUserId($user_id);

        if ($profile) {
            return $this->database
                ->table('organizer_profiles')
                ->where('user_id', $user_id)
                ->update([
                    'gcash_name' => $data['gcash_name'],
                    'gcash_number' => $data['gcash_number'],
                    'gcash_qr' => $data['gcash_qr'],    
                ]);
        }

        return $this->database
            ->table('organizer_profiles')
            ->insert([
                'user_id' => $user_id,
                'gcash_name' => $data['gcash_name'],
                'gcash_number' => $data['gcash_number'],
                'gcash_qr' => $data['gcash_qr'],
            ]);
    }
}
