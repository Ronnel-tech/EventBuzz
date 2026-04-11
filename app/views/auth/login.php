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
<link rel="stylesheet" href="../public/assets/css/output.css">
<title>EventBuzz | Login</title>
<link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
</head>



<body class="flex items-center justify-center min-h-screen w-full bg-[#151419]">

    <div class="flex overflow-hidden rounded-3xl shadow-lg outline outline-[#2a2a2e] bg-surface justify-around align-center w-200 h-125">

        <!-- LEFT: Image -->
        <div class="w-96 h-125 p-4 bg-surface">
            <div class="w-full h-full  bg-[url('/public/assets/images/login_bg.jpg')] bg-cover bg-center rounded-3xl outline outline-offset-3 outline-[#2a2a2e] shadow-soft"></div>
        </div>

        <!-- RIGHT: Form -->
        <div class="bg-[#1c2029] p-10 w-96 flex flex-col justify-around bg-surface ">

            <h2 class="text-white text-center text-2xl font-semibold mb-6 ">
                Welcome Back!
            </h2>

<?php if ($msg = get_flash('error')): ?>
<div class="tag-error text-primary p-2 rounded-2xl text-center"><?= esc($msg) ?></div>
<?php endif; ?>

<form method="POST">
<?php csrf_field(); ?>

<input name="email" placeholder="Email" class="w-full mb-3 p-2 rounded-full card text-white border border-[#2a2a2e]" required>
<input type="password" name="password" placeholder="Password" class="w-full mb-3 p-2 rounded-full card text-white border border-[#2a2a2e]" required>

<button class="btn btn-primary w-full p-2 rounded-full mt-5">Login</button>
</form>

            <p class="text-center mt-6 text-sm text-gray-300">
                <a href="<?= url('/signup') ?>" class="hover:text-white">
                    Don't have an account? <strong>Signup</strong>
                </a>
            </p>

            

        </div>

    </div>

</body>
</html>