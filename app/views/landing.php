<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/assets/css/output.css">
    <title>EventBuzz</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
</head>
<?php
$active_auth_modal = trim((string) ($_GET['auth'] ?? ''));
if (!in_array($active_auth_modal, ['login', 'signup'], true)) {
    $active_auth_modal = '';
}

$flash_error = get_flash('error');
$flash_success = get_flash('success');
?>
<body class="public-page-wide bg-marquee">



<!-- -------------------------------------------------------------------------------------------------------------------------------- -->
<div class="mb-1 min-h-[70vh] rounded-4xl rounded-b-none bg-[url('/public/assets/images/bg_img.jpg')] bg-cover bg-center p-4 outline outline-offset-2 outline-[#2a2a2e] shadow-soft sm:p-5">
    
    <nav class="page-nav bg-surface-hover">

        <div class="flex items-center">
            <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
            <h4>EventBuzz</h4>
        </div>

        <div class="page-nav-links justify-end">
            <button type="button" class="text-white transition hover:text-primary" data-auth-open="signup">Signup</button>
            <h3>|</h3>
            <button type="button" class="text-white transition hover:text-primary" data-auth-open="login">Login</button>
        </div> 
    </nav>

    <h1 class="px-4 pt-24 text-center text-black blur-[2px] sm:px-8 sm:pt-32 md:px-16 md:pt-48 lg:pl-20 lg:pt-72 lg:text-left">Organizer create, <br> Organizer connect.</h1>
</div>  
<!-- -------------------------------------------------------------------------------------------------------------------------------- -->



<!-- -------------------------------------------------------------------------------------------------------------------------------- -->
<section class="overflow-hidden whitespace-nowrap bg-amber-50 p-7">
  <div class="marquee text-4xl font-bold text-primary  bg-surface">
    <div class="marquee-group" aria-hidden="true">
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
    </div>
  </div>
</section>
<!-- -------------------------------------------------------------------------------------------------------------------------------- -->



<!-- -------------------------------------------------------------------------------------------------------------------------------- -->
<section class="mt-2 grid gap-8 bg-surface px-6 py-12 outline outline-offset-2 outline-[#2a2a2e] shadow-soft lg:grid-cols-[1.2fr_auto_1fr] lg:items-center lg:px-10 xl:px-16">

    <h1 class="text-center text-6xl leading-none sm:text-7xl lg:text-left lg:text-[160px] lg:leading-[0.8]">Event<br>Buzz</h1>
    <hr class="mx-auto hidden h-48 w-1 rounded-full bg-white lg:block">
    <p class="text-center text-lg leading-relaxed text-primary lg:pr-6 lg:text-left xl:pr-20 xl:text-xl">EventBuzz is a modern <br> platform for managing and <br> promoting events. <br>Organizers can create and <br> manage events in one place, <br>while attendees can discover <br> events and secure their tickets <br> effortlessly.</p>

</section >
<!-- -------------------------------------------------------------------------------------------------------------------------------- -->



<!-- -------------------------------------------------------------------------------------------------------------------------------- -->
<section class="bg-white h-2 w-full flex justify-center items-center mt-1">
    <button type="button" class="btn btn-primary rounded-full z-1" data-auth-open="signup">Get Started</button>
</section>
<!-- -------------------------------------------------------------------------------------------------------------------------------- -->




<!-- -------------------------------------------------------------------------------------------------------------------------------- -->
<section class="relative w-full overflow-hidden shadow-soft outline outline-offset-2 outline-[#2a2a2e] mt-1">

    <!-- Blurred Background -->
    <div class="absolute inset-0 bg-[url('/public/assets/images/gradient_bg.jpg')] 
                bg-cover bg-center blur-3xl scale-110">
    </div>

    <!-- Content (NOT blurred) -->
    <div class="relative flex flex-col items-center justify-center text-center py-25">

        <div class="w-full flex justify-center pb-5">
            <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="w-32 h-32 ">
        </div>

        <p class="text-lg text-primary">
            EventBuzz brings organizers and attendees together through a platform <br>
            designed to simplify event promotion, ticketing, and discovery.
        </p>

    </div>

</section>
<!-- -------------------------------------------------------------------------------------------------------------------------------- -->



<!-- -------------------------------------------------------------------------------------------------------------------------------- -->


<section class="grid gap-6 px-6 py-10 shadow-soft outline outline-offset-2 outline-[#2a2a2e] sm:px-10 lg:grid-cols-2 lg:px-20 ">



        <div class="card flex min-h-80 flex-col justify-around bg-[url('/public/assets/images/bg_light.jpg')] bg-cover bg-center text-center shadow-soft outline outline-offset-2 outline-[#2a2a2e]">
            <h3 class="mb-2 text-sm font-bold lg:pr-40 xl:pr-72">Create and manage events with ease</h3>
            <h3 class="text-sm mb-2 shadow-soft border-defaut">Publish events, manage ticket sales, and reach your audience <br> through a streamlined platform.</h3>
            <h3 class="text-md lg:pl-40 xl:pl-72">Become an Organizer</h3>
        </div>


        <div class="card flex min-h-80 flex-col justify-around bg-[url('/public/assets/images/bg_light2.jpg')] bg-cover bg-center text-center shadow-soft outline outline-offset-2 outline-[#2a2a2e]">
            <h3 class="mb-2 text-sm font-bold lg:pr-40 xl:pr-72">Discover events worth attending</h3>
            <h3 class="text-sm mb-2  shadow-soft">Browse upcoming events, secure your tickets, and keep track of <br> everything in one place.</h3>
            <h3 class="text-md lg:pl-40 xl:pl-72">Become an Attendee</h3>
        </div>


</section>
 <!-- -------------------------------------------------------------------------------------------------------------------------------- -->


 <!-- -------------------------------------------------------------------------------------------------------------------------------- -->

<section class="flex justify-around align-center pt-10">
    <hr class="bg-white w-full mt-5">
    <h1 class = "w-full text-center">Plan. Publish. Celebrate</h1>
    <hr class="bg-white w-full mt-5">
</section>

 <!-- -------------------------------------------------------------------------------------------------------------------------------- -->


 <!-- -------------------------------------------------------------------------------------------------------------------------------- -->

    <section class="mt-10 min-h-[70vh] w-full overflow-hidden shadow-soft outline outline-offset-2 outline-[#2a2a2e]">
        <div class="flex min-h-[70vh] w-full items-end justify-end bg-[url('/public/assets/images/bg_l.jpg')] bg-cover bg-center shadow-soft outline outline-offset-2 outline-[#2a2a2e]">
            <h1 class="px-6 pb-6 text-right sm:px-10 sm:pb-10">Plan events people love.</h1>
        </div>
        
    </section>
    


 <!-- -------------------------------------------------------------------------------------------------------------------------------- -->

<footer class="mt-1 shadow-soft outline outline-offset-2 outline-[#2a2a2e] rounded-b-4xl ">
        <div class="bg-[#2a2a2e] flex justify-center items-center p-5 mt-1 rounded-b-4xl">
            <p class="text-sm text-primary ">© 2026 EventBuzz | All rights reserved | Dev: Ronnel L. Antaran</p>
        </div>

        
</footer>

<div class="pb-20 "></div>

<div id="authOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-6">
    <div class="relative w-full max-w-4xl">
        <div id="loginModal" class="auth-modal hidden overflow-hidden rounded-3xl bg-surface shadow-lg outline outline-[#2a2a2e]">
            <div class="flex min-h-140 flex-col md:grid md:grid-cols-[0.95fr_0.85fr]">
                <div class="bg-surface p-4">
                    <div class="h-full min-h-55 w-full rounded-3xl bg-[url('/public/assets/images/login_bg.jpg')] bg-cover bg-center outline outline-offset-3 outline-[#2a2a2e] shadow-soft"></div>
                </div>

                <div class="flex w-full flex-col justify-center bg-surface px-8 py-10 md:px-10">
                    <h2 class="mb-6 text-center text-2xl font-semibold text-white">Welcome Back!</h2>

                    <?php if ($active_auth_modal === 'login' && $flash_error): ?>
                    <div class="mb-4 rounded-2xl border border-red-400/30 bg-red-500/10 p-3 text-center text-red-200"><?= esc($flash_error) ?></div>
                    <?php endif; ?>

                    <?php if ($active_auth_modal === 'login' && $flash_success): ?>
                    <div class="mb-4 rounded-2xl border border-green-400/30 bg-green-500/10 p-3 text-center text-green-200"><?= esc($flash_success) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= url('/login') ?>" class="mx-auto w-full max-w-xs">
                        <?php csrf_field(); ?>
                        <input name="email" type="email" placeholder="Email" class="card mb-3 w-full rounded-full border border-[#2a2a2e] p-2 text-white" required>
                        <input type="password" name="password" placeholder="Password" class="card mb-3 w-full rounded-full border border-[#2a2a2e] p-2 text-white" required>
                        <button class="btn btn-primary mt-5 w-full rounded-full p-2">Login</button>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-300">
                        <button type="button" class="hover:text-white" data-auth-open="signup">
                            Don't have an account? <strong>Signup</strong>
                        </button>
                    </p>
                </div>
            </div>
        </div>

        <div id="signupModal" class="auth-modal hidden overflow-hidden rounded-3xl bg-surface shadow-lg outline outline-[#2a2a2e]">
            <div class="flex min-h-140 flex-col md:grid md:grid-cols-[0.95fr_0.85fr]">
                <div class="bg-surface p-4">
                    <div class="h-full min-h-55 w-full rounded-3xl bg-[url('/public/assets/images/signup_bg.jpg')] bg-cover bg-center outline outline-offset-3 outline-[#2a2a2e] shadow-soft"></div>
                </div>

                <div class="flex w-full flex-col justify-center bg-surface px-8 py-10 md:px-10">
                    <h2 class="mb-6 text-center text-2xl font-semibold text-white">Create an Account</h2>

                    <?php if ($active_auth_modal === 'signup' && $flash_error): ?>
                    <div class="mb-4 rounded-2xl border border-red-400/30 bg-red-500/10 p-3 text-center text-red-200"><?= esc($flash_error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= url('/signup') ?>" class="mx-auto w-full max-w-xs">
                        <?php csrf_field(); ?>
                        <input name="first_name" placeholder="First Name" class="card mb-3 w-full rounded-full border border-[#2a2a2e] p-2 text-white" required>
                        <input name="last_name" placeholder="Last Name" class="card mb-3 w-full rounded-full border border-[#2a2a2e] p-2 text-white" required>
                        <input name="email" type="email" placeholder="Email" class="card mb-3 w-full rounded-full border border-[#2a2a2e] p-2 text-white" required>
                        <input type="password" name="password" placeholder="Password" class="card mb-3 w-full rounded-full border border-[#2a2a2e] p-2 text-white" required>
                        <select name="role" class="card mb-4 w-full rounded-full border border-[#2a2a2e] p-2 text-white" required>
                            <option value="attendee">Attendee</option>
                            <option value="organizer">Organizer</option>
                        </select>
                        <button class="btn btn-primary w-full rounded-full p-2">Register</button>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-300">
                        <button type="button" class="hover:text-white" data-auth-open="login">
                            Already have an account? <strong>Login</strong>
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('authOverlay');
    const loginModal = document.getElementById('loginModal');
    const signupModal = document.getElementById('signupModal');
    const activeModal = <?= json_encode($active_auth_modal, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

    function hideModals() {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        loginModal.classList.add('hidden');
        signupModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openModal(type) {
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        loginModal.classList.toggle('hidden', type !== 'login');
        signupModal.classList.toggle('hidden', type !== 'signup');
    }

    document.querySelectorAll('[data-auth-open]').forEach(function (button) {
        button.addEventListener('click', function () {
            openModal(button.getAttribute('data-auth-open'));
        });
    });

    overlay.addEventListener('click', function (event) {
        if (event.target === overlay) {
            hideModals();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            hideModals();
        }
    });

    if (activeModal === 'login' || activeModal === 'signup') {
        openModal(activeModal);
    }
});
</script>

</body>
</html>

