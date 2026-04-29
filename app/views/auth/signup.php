<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('/?auth=signup'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($email) || empty($password) || empty($role)) {
        set_flash('error', 'All fields required');
        header('Location: ' . url('/?auth=signup'));
        exit;
    }

    $existing = db()->table('users')->where('email', $email)->get();

    if ($existing) {
        set_flash('error', 'Email already exists');
        header('Location: ' . url('/?auth=signup'));
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    db()->table('users')->insert([
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'password' => $hashed,
        'role' => $role,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    set_flash('success', 'Account created. You can now log in.');
    header('Location: ' . url('/?auth=login'));
    exit;
}
