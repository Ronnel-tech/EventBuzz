<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($email) || empty($password) || empty($role)) {
        set_flash('error', 'All fields required');
        header('Location: ' . url('signup'));
        exit;
    }

    $existing = db()->table('users')->where('email', $email)->get();

    if ($existing) {
        set_flash('error', 'Email already exists');
        header('Location: ' . url('signup'));
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

    header('Location: ' . url('/login'));
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="./dist/output.css">
</head>
<body class="flex items-center justify-center h-screen bg-[#030712]">

<div class="bg-[#1c2029] w-96 p-8 rounded-3xl">

<h2 class="text-white text-center mb-4">Signup</h2>

<form method="POST">
<?php csrf_field(); ?>

<input name="first_name" placeholder="First Name" class="w-full mb-2 p-2" required>
<input name="last_name" placeholder="Last Name" class="w-full mb-2 p-2" required>
<input name="email" placeholder="Email" class="w-full mb-2 p-2" required>
<input type="password" name="password" placeholder="Password" class="w-full mb-2 p-2" required>

<select name="role" class="w-full mb-3 p-2" required>
    <option value="attendee">Attendee</option>
    <option value="organizer">Organizer</option>
</select>

<button class="bg-green-500 text-white w-full p-2">Register</button>
</form>

<p class="text-center mt-3">
<a href="<?= url('/') ?>" class="text-white">Back to login</a>
</p>

</div>
</body>
</html>