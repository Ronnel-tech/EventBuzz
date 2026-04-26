<?php

class OrganizerEventModel
{
    private Database $database;

    public function __construct()
    {
        $this->database = db();
    }

    public function ensureDefaultCategories(): array
    {
        $default_categories = [
            'Music',
            'Education',
            'Art and Culture',
            'Sports and Fitness',
            'Gaming and Esports',
        ];

        $categories = $this->database->table('categories')->get_all() ?: [];
        $existing_names = [];

        foreach ($categories as $category) {
            $name = trim((string) ($category['name'] ?? ''));
            if ($name !== '' && $name !== '0') {
                $existing_names[] = strtolower($name);
            }
        }

        foreach ($default_categories as $default_category) {
            if (!in_array(strtolower($default_category), $existing_names, true)) {
                $this->database->table('categories')->insert([
                    'name' => $default_category,
                ]);
            }
        }

        $categories = $this->database->table('categories')->get_all() ?: [];

        return array_values(array_filter($categories, function ($category) {
            $name = trim((string) ($category['name'] ?? ''));
            return $name !== '' && $name !== '0';
        }));
    }

    public function findCategoryById(int $category_id): array|null
    {
        return $this->database->table('categories')->where('id', $category_id)->get() ?: null;
    }

    public function createEvent(array $data): int
    {
        $this->database->table('events')->insert([
            'organizer_id' => $data['organizer_id'],
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'banner_image' => $data['banner_image'],
            'start_datetime' => $data['start_datetime'],
            'end_datetime' => $data['end_datetime'],
            'street' => $data['street'],
            'city' => $data['city'],
            'province' => $data['province'],
            'country' => $data['country'],
            'payment_type' => $data['payment_type'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->database->last_id();
    }
}
