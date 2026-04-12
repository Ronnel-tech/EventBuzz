<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Create Event</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>


<body class="flex h-screen">


    <aside class="flex flex-col items-center bg-surface w-24 justify-between p-5 shadow-soft">

        <img src="/public/assets/images/logo.png" alt="" class="size-7 ">

        <div class="flex flex-col align-center gap-5 -mt-120">
            <button><img src="/public/assets/images/organizer/org_create.svg" alt="create event" class="size-7 fill=[white]"></button>
            <button><img src="/public/assets/images/organizer/org_ticket.svg" alt="tickets" class="size-7 text-primary"></button>
            <button><img src="/public/assets/images/organizer/org_payment.svg" alt="profile" class="size-7 text-primary"></button>
        </div>

        <form method="POST" action="<?= url('logout') ?>"> 
            <button ><img src="/public/assets/images/organizer/logout.svg" alt="" class=""></button>
        </form>

    </aside>


<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"><path fill="currentColor" d="M5.754 5a3.75 3.75 0 0 0-3.75 3.75v.75H26v-.75A3.75 3.75 0 0 0 22.25 5zm-3.75 14.25V11H26v8.25A3.75 3.75 0 0 1 22.25 23H5.755a3.75 3.75 0 0 1-3.75-3.75M18.25 16.5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5z"/></svg>
</body>
</html>


