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
<link rel="stylesheet" href="../public/assets/css/output.css">
<title>EventBuzz | Signup</title>
<link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
</head>


<body class="flex items-center justify-center min-h-screen w-full bg-[#151419]">

    <div class="flex overflow-hidden rounded-3xl shadow-lg outline outline-[#2a2a2e] bg-surface justify-around align-center w-200 h-125">

        <!-- LEFT: Image -->
        <div class="w-96 h-125 p-4 bg-surface">
            <div class="w-full h-full  bg-[url('/public/assets/images/signup_bg.jpg')] bg-cover bg-center rounded-3xl outline outline-offset-3 outline-[#2a2a2e] shadow-soft"></div>
        </div>

        <!-- RIGHT: Form -->
        <div class="bg-[#1c2029] p-10 w-96 flex flex-col justify-center bg-surface ">

            <h2 class="text-white text-center text-2xl font-semibold mb-6 ">
                Create an Account
            </h2>

            <form method="POST">
                <?php csrf_field(); ?>

                <input name="first_name" placeholder="First Name"
                    class="w-full mb-3 p-2 rounded-full card text-white border border-[#2a2a2e] " required>

                <input name="last_name" placeholder="Last Name"
                    class="w-full mb-3 p-2 rounded-full card text-white border border-[#2a2a2e]" required>

                <input name="email" placeholder="Email"
                    class="w-full mb-3 p-2 rounded-full card text-white border border-[#2a2a2e]" required>

                <input type="password" name="password" placeholder="Password"
                    class="w-full mb-3 p-2 rounded-full card text-white border border-[#2a2a2e]" required>

                <select name="role"
                    class="w-full mb-4 p-2 rounded-full card text-white border border-[#2a2a2e]" required>
                    <option value="attendee">Attendee</option>
                    <option value="organizer">Organizer</option>
                </select>

                <button class="btn btn-primary w-full p-2 rounded-full">
                    Register
                </button>
            </form>

            <p class="text-center mt-6 text-sm text-gray-300">
                <a href="<?= url('/login') ?>" class="hover:text-white">
                    Already have an account? <strong>Login</strong>
                </a>
            </p>

            

        </div>

    </div>

</body>
</html>