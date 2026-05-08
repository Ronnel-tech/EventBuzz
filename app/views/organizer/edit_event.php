<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Edit Event</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>

<body class="app-shell">


    <aside class="app-sidebar">

        <img src="/public/assets/images/logo.png" alt="" class="size-7 ">

        <div class="app-sidebar-nav">
            <a href="<?= url('/organizer/dashboard') ?>">
                <button><svg class="icon-disabled" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <g fill="none">
                            <path fill-opacity="0.16"
                                d="M18.6 3H5.4A2.4 2.4 0 0 0 3 5.4v13.2A2.4 2.4 0 0 0 5.4 21h13.2a2.4 2.4 0 0 0 2.4-2.4V5.4A2.4 2.4 0 0 0 18.6 3" />
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-miterlimit="10" stroke-width="1.5"
                                d="M12 21V3m0 7h9M5.4 3h13.2A2.4 2.4 0 0 1 21 5.4v13.2a2.4 2.4 0 0 1-2.4 2.4H5.4A2.4 2.4 0 0 1 3 18.6V5.4A2.4 2.4 0 0 1 5.4 3" />
                        </g>
                    </svg></button>
            </a>
            <a href="<?= url('/organizer/events') ?>">
                <button><svg class="icon-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path
                            d="M21 17V8H7v9zm0-14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1V1h2v2h8V1h2v2zM3 21h14v2H3a2 2 0 0 1-2-2V9h2zm16-6h-4v-4h4z" />
                    </svg></button>
            </a>
        </div>

        <form method="POST" action="<?= url('logout') ?>">
            <button><svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414"
                        clip-rule="evenodd" />
                </svg></button>
        </form>

    </aside>


    <div class="app-main">

        <div class="flex flex-col ">
            <h3>Edit Event</h3>
            <p>Update the details for your event.</p>
        </div>


        <section class="app-section">

            <?php if ($msg = get_flash('error')): ?>
                <div class="mb-5 rounded-2xl border border-red-400/30 bg-red-500/10 p-4 text-red-200">
                    <?= esc($msg) ?>
                </div>
            <?php endif; ?>

            <?php if ($msg = get_flash('success')): ?>
                <div class="mb-5 rounded-2xl border border-green-400/30 bg-green-500/10 p-4 text-green-200">
                    <?= esc($msg) ?>
                </div>
            <?php endif; ?>

            <form action="<?= url('/organizer/edit-event') ?>" method="POST" enctype="multipart/form-data">
                <?php csrf_field(); ?>
                <input type="hidden" name="event_id" value="<?= esc((string) $event_id) ?>">

                <div class="w-full modal outline  outline-[#2a2a2e] shadow-soft ">

                    <div class="w-full h-50 inset-0 bg-cover bg-center rounded-t-3xl outline outline-[#2a2a2e] shadow-soft mb-1 flex items-center justify-center shadow-soft"
                        style="background-image: url('<?= esc($event['banner_image'] ?? '/public/assets/images/signup_bg.jpg') ?>');">

                        <input type="file" id="fileInput" name="picture" accept="image/*" class="hidden">

                        <label for="fileInput"
                            class="size-25 bg-surface rounded-2xl flex flex-col items-center justify-center gap-4 cursor-pointer hover:bg-gray-700 transition">

                            <svg class="icon-primary -rotate-90" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414"
                                    clip-rule="evenodd" />
                            </svg>

                            <h4><?= !empty($event['banner_image']) ? 'Change photo' : 'Upload photo' ?></h4>
                        </label>

                    </div>

                    <div class="bg-surface w-full outline  outline-[#2a2a2e] shadow-soft mt-5 rounded-2xl">
                        <h2 class="px-10 pt-10">Event Overview</h2>
                        <h3 class="pl-15 pt-5">Event title</h3>
                        <h4 class="pl-20 pt-5">Be clear and descriptive with a title that tells people what your event
                            is about.</h4>

                        <div
                            class="flex flex-col gap-4 p-5 pt-5 sm:pl-10 lg:flex-row lg:flex-wrap lg:items-center lg:justify-between lg:pl-20 xl:pl-30">

                            <input name="title" value="<?= esc($old['title']) ?>" placeholder="Event Title"
                                class="mb-3 w-full rounded-full input p-2 text-white lg:flex-1" required>

                            <select name="category" class="mb-3 w-full rounded-full input p-2 text-white lg:w-65"
                                required>
                                <option value="">Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= esc((string) $category['id']) ?>" <?= $old['category'] === (string) $category['id'] ? 'selected' : '' ?>>
                                        <?= esc($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <h2 class="px-10 pt-10">Summary</h2>
                        <h4 class="pl-20 pt-5">Grab people's attention with a short description about your event.
                            Attendees will see this at the top of your event page.</h4>

                        <div class="p-5 sm:pl-10 lg:pl-20 xl:pl-30">
                            <textarea name="summary" placeholder="Event Summary"
                                class=" rounded-2xl input input:focus text-white w-full h-100"
                                required><?= esc($old['summary']) ?></textarea>
                        </div>

                    </div>


                    <div class="bg-surface w-full outline  outline-[#2a2a2e] shadow-soft mt-5 rounded-2xl">
                        <h2 class="px-10 pt-10">Date and Location</h2>
                        <h3 class="pl-15 pt-5">Date and Time</h3>

                        <div
                            class="grid grid-cols-1 gap-5 p-5 pt-5 sm:pl-10 md:grid-cols-2 lg:grid-cols-3 lg:pl-20 xl:pl-30">

                            <div class="flex flex-col">
                                <label for="date">Date</label>
                                <input name="date" value="<?= esc($old['date']) ?>" type="date" placeholder="Date"
                                    class=" p-2 rounded-full input input:focus text-white  " required>
                            </div>

                            <div class="flex flex-col">
                                <label for="start_time" class="">Start time</label>
                                <input name="start_time" value="<?= esc($old['start_time']) ?>" type="time"
                                    placeholder="Time" class=" rounded-full input input:focus text-white " required>
                            </div>

                            <div class="flex flex-col">
                                <label for="end_time" class="">End time</label>
                                <input name="end_time" value="<?= esc($old['end_time']) ?>" type="time"
                                    placeholder="Time" class=" rounded-full input input:focus text-white " required>
                            </div>
                        </div>

                        <h3 class="px-15 pt-15">Location</h3>

                        <div class="grid grid-cols-1 gap-4 p-5 sm:pl-10 md:grid-cols-2 lg:pl-20 xl:pl-30">
                            <div class="flex flex-col">
                                <label for="street" class="mb-2">Street</label>
                                <input name="street" value="<?= esc($old['street']) ?>" type="text" placeholder="Street"
                                    class=" p-2 rounded-full input input:focus text-white  w-full" required>
                            </div>


                            <div class="flex flex-col">
                                <label for="city" class="mb-2">City</label>
                                <input name="city" value="<?= esc($old['city']) ?>" type="text" placeholder="City"
                                    class=" p-2 rounded-full input input:focus text-white  w-full" required>
                            </div>

                            <div class="flex flex-col">
                                <label for="province" class="mb-2">Province</label>
                                <input name="province" value="<?= esc($old['province']) ?>" type="text"
                                    placeholder="Province"
                                    class=" p-2 rounded-full input input:focus text-white  w-full" required>
                            </div>

                            <div class="flex flex-col">
                                <label for="country" class="mb-2">Country</label>
                                <input name="country" value="<?= esc($old['country']) ?>" type="text"
                                    placeholder="Country" class=" p-2 rounded-full input input:focus text-white  w-full"
                                    required>
                            </div>
                        </div>


                        <div class="p-6 flex justify-end w-full">
                            <button type="submit" class="btn btn-primary rounded-full">Update Event</button>
                        </div>
                    </div>

                </div>
            </form>

        </section>


    </div>



</body>

</html>