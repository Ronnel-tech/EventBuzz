<?php

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

<div class="bg-[#1c2029] w-96 rounded-3xl p-8">

<h2 class="text-white text-center mb-4">Login</h2>

<?php if ($msg = get_flash('error')): ?>
<div class="bg-red-500 text-white p-2"><?= esc($msg) ?></div>
<?php endif; ?>

<form method="POST">
<?php csrf_field(); ?>

<input name="email" placeholder="Email" class="w-full mb-3 p-2" required>
<input type="password" name="password" placeholder="Password" class="w-full mb-3 p-2" required>

<button class="bg-blue-500 text-white w-full p-2">Login</button>
</form>

<p class="text-center mt-3">
<a href="<?= url('signup') ?>" class="text-white">Signup</a>
</p>

</div>
</body>
</html>