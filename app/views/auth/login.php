<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('/?auth=login'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 🔐 ADMIN LOGIN
    if ($email === 'admin@gmail.com' && $password === '12345678') {

        $_SESSION['user'] = [
            'id' => 1,
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin'
        ];

        header('Location: ' . url('admin/dashboard'));
        exit;
    }

    // 👤 USER LOGIN
    $user = db()->table('users')
        ->where('email', $email)
        ->get();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user'] = $user;

        if ($user['role'] === 'organizer') {
            header('Location: ' . url('organizer/dashboard'));
        } else {
            header('Location: ' . url('attendee'));
        }

        exit;
    }

    set_flash('error', 'Invalid credentials');
    header('Location: ' . url('/?auth=login'));
    exit;
}
