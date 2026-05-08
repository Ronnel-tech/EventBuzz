<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Add Ticket</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
    <script src="../public/assets/js/main.js"></script>
</head>


<body class="app-shell">

    <aside class="app-sidebar">

        <img src="/public/assets/images/logo.png" alt="" class="size-7 ">

        <div class="app-sidebar-nav">
            <button><svg id="create_event" class="icon-disabled" xmlns="http://www.w3.org/2000/svg" width="28"
                    height="28" viewBox="0 0 512 512">
                    <path
                        d="M459.94 53.25a16.06 16.06 0 0 0-23.22-.56L424.35 65a8 8 0 0 0 0 11.31l11.34 11.32a8 8 0 0 0 11.34 0l12.06-12c6.1-6.09 6.67-16.01.85-22.38M399.34 90L218.82 270.2a9 9 0 0 0-2.31 3.93L208.16 299a3.91 3.91 0 0 0 4.86 4.86l24.85-8.35a9 9 0 0 0 3.93-2.31L422 112.66a9 9 0 0 0 0-12.66l-9.95-10a9 9 0 0 0-12.71 0" />
                    <path
                        d="M386.34 193.66L264.45 315.79A41.1 41.1 0 0 1 247.58 326l-25.9 8.67a35.92 35.92 0 0 1-44.33-44.33l8.67-25.9a41.1 41.1 0 0 1 10.19-16.87l122.13-121.91a8 8 0 0 0-5.65-13.66H104a56 56 0 0 0-56 56v240a56 56 0 0 0 56 56h240a56 56 0 0 0 56-56V199.31a8 8 0 0 0-13.66-5.65" />
                </svg></button>
            <button><svg id="ticket_type" class="icon-primary" xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                    viewBox="0 0 24 24">
                    <path
                        d="M3 10h-.75c0 .414.336.75.75.75zm0 4v-.75a.75.75 0 0 0-.75.75zm18-4v.75a.75.75 0 0 0 .75-.75zm0 4h.75a.75.75 0 0 0-.75-.75zM5 5.75h5v-1.5H5zm5 0h9v-1.5h-9zm9 12.5h-9v1.5h9zm-9 0H5v1.5h5zM9.25 5v14h1.5V5zm-5.366 6.116a1.25 1.25 0 0 1 0 1.768l1.06 1.06a2.75 2.75 0 0 0 0-3.889zm16.232 1.768a1.25 1.25 0 0 1 0-1.768l-1.06-1.06a2.75 2.75 0 0 0 0 3.889zM3 10.75c.321 0 .64.122.884.366l1.06-1.06A2.74 2.74 0 0 0 3 9.25zm.75-.75V7h-1.5v3zm0 7v-3h-1.5v3zm.134-4.116A1.24 1.24 0 0 1 3 13.25v1.5c.703 0 1.408-.269 1.945-.806zm16.232-1.768c.244-.244.563-.366.884-.366v-1.5c-.703 0-1.408.269-1.945.806zM20.25 7v3h1.5V7zm0 7v3h1.5v-3zm.75-.75c-.321 0-.64-.122-.884-.366l-1.06 1.06A2.74 2.74 0 0 0 21 14.75zm-16 5c-.69 0-1.25-.56-1.25-1.25h-1.5A2.75 2.75 0 0 0 5 19.75zm14 1.5A2.75 2.75 0 0 0 21.75 17h-1.5c0 .69-.56 1.25-1.25 1.25zm0-14c.69 0 1.25.56 1.25 1.25h1.5A2.75 2.75 0 0 0 19 4.25zM5 4.25A2.75 2.75 0 0 0 2.25 7h1.5c0-.69.56-1.25 1.25-1.25z" />
                </svg></button>
            <button><svg id="payment_method" class="icon-disabled" xmlns="http://www.w3.org/2000/svg" width="28"
                    height="28" viewBox="0 0 28 28">
                    <path
                        d="M5.754 5a3.75 3.75 0 0 0-3.75 3.75v.75H26v-.75A3.75 3.75 0 0 0 22.25 5zm-3.75 14.25V11H26v8.25A3.75 3.75 0 0 1 22.25 23H5.755a3.75 3.75 0 0 1-3.75-3.75M18.25 16.5a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5z" />
                </svg></button>
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
            <h3>Add Tickets</h3>
            <p>Create and manage tickets for your event.</p>
        </div>


        <section class="app-section grid grid-cols-1 gap-6 xl:grid-cols-2 xl:gap-10">

            <?php if ($msg = get_flash('error')): ?>
                <div class="rounded-2xl border border-red-400/30 bg-red-500/10 p-4 text-red-200 xl:col-span-2">
                    <?= esc($msg) ?>
                </div>
            <?php endif; ?>

            <?php if ($msg = get_flash('success')): ?>
                <div class="rounded-2xl border border-green-400/30 bg-green-500/10 p-4 text-green-200 xl:col-span-2">
                    <?= esc($msg) ?>
                </div>
            <?php endif; ?>


            <section class="w-full modal outline  outline-[#2a2a2e] shadow-soft ">

                <h3 class="pl-5 pt-5">Ticket Types</h3>
                <h4 class="pl-10 pt-5">Add up to 5 types of tickets</h4>

                <div class="hidden grid-cols-5 gap-4 px-2 pl-15 pt-5 text-sm text-gray-400 lg:grid">
                    <div>Name</div>
                    <div>Sale Ends</div>
                    <div>Quantity</div>
                    <div>Price</div>
                    <div>Action</div>


                </div>
                <div class="table-scroll px-4 pb-4 sm:px-6 lg:pl-15">

                    <div id="ticketList" class="space-y-3 pb-8">
                        <?php if ($ticket_types): ?>
                            <?php foreach ($ticket_types as $ticket): ?>
                                <div data-ticket-row
                                    class="grid min-w-180 grid-cols-5 gap-4 rounded-2xl border border-[#2a2a2e] bg-[#151419] p-4 text-sm text-white">
                                    <div><?= esc($ticket['name']) ?></div>
                                    <div>
                                        <?= esc(date('M d, Y', strtotime($ticket['end_datetime']))) ?><br>
                                        <span
                                            class="text-xs text-gray-400"><?= esc(date('h:i A', strtotime($ticket['end_datetime']))) ?></span>
                                    </div>
                                    <div><?= esc((string) $ticket['quantity']) ?></div>
                                    <div>PHP <?= esc(number_format((float) $ticket['price'], 2)) ?></div>
                                    <div>
                                        <form method="POST" action="<?= url('/organizer/add-ticket') ?>">
                                            <?php csrf_field() ?>
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="ticket_type_id"
                                                value="<?= esc((string) $ticket['id']) ?>">
                                            <button type="submit"
                                                class="btn btn-secondary rounded-full px-4 py-1 text-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div id="emptyTicketState"
                                class="rounded-2xl border border-dashed border-[#2a2a2e] p-4 text-sm text-gray-400">
                                No ticket types added yet.
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

            </section>
            <!-- ------------------------------------------------------------------------------------------------ -->

            <section class="w-full modal outline  outline-[#2a2a2e] shadow-soft ">

                <h3 class="pl-5 pt-5 pb-5">Add Tickets</h3>

                <form id="ticketForm" action="<?= url('/organizer/add-ticket') ?>" method="POST"
                    class="px-4 sm:px-6 lg:pl-10">
                    <?php csrf_field() ?>

                    <label for="ticket_name">Ticket Name</label>
                    <input id="name" type="text" name="ticket_name" value="<?= esc($old['ticket_name']) ?>"
                        placeholder="Ticket Name" class=" mb-3  rounded-full input input:focus text-white " required>

                    <label for="quantity">Quantity</label>
                    <input id="quantity" type="number" name="quantity" value="<?= esc($old['quantity']) ?>"
                        placeholder="Quantity" class=" mb-3  rounded-full input input:focus text-white " required>

                    <label for="price">Price</label>
                    <input id="price" type="number" name="price" value="<?= esc($old['price']) ?>" min="0" step="0.01"
                        placeholder="Price" class=" mb-3  rounded-full input input:focus text-white " required>

                    <div class="grid grid-cols-1 gap-5 pt-10 md:grid-cols-2">
                        <?php csrf_field() ?>

                        <div>
                            <label for="start_date">Start Date</label>
                            <input id="startDate" type="date" name="start_date" value="<?= esc($old['start_date']) ?>"
                                class=" mb-3  rounded-full input input:focus text-white " required>
                        </div>

                        <div>
                            <label for="start_time">Start Time</label>
                            <input id="startTime" type="time" name="start_time" value="<?= esc($old['start_time']) ?>"
                                placeholder="Start Time" class=" mb-3  rounded-full input input:focus text-white "
                                required>
                        </div>

                        <div>
                            <label for="end_date">End Date</label>
                            <input name="end_date" id="endDate" type="date" value="<?= esc($old['end_date']) ?>"
                                class=" mb-3  rounded-full input input:focus text-white " required>
                        </div>

                        <div>
                            <label for="end_time">End Time</label>
                            <input name="end_time" id="endTime" type="time" value="<?= esc($old['end_time']) ?>"
                                class=" mb-3  rounded-full input input:focus text-white " required>
                        </div>

                    </div>
                    <div class="grid grid-cols-1 gap-5 pt-10 sm:grid-cols-2">
                        <a href="<?= url('/organizer/add-ticket') ?>" id="cancelBtn"
                            class="btn btn-secondary text-white py-2 px-4 rounded-full text-center">Cancel
                        </a>

                        <button type="submit" class="btn btn-primary rounded-full">Add Ticket
                        </button>
                    </div>

                </form>

                <div class="flex justify-end align-center">
                    <a href="<?= url('/organizer/payment-method') ?>" class="btn btn-primary mt-10 rounded-full">Save
                        and Continue</a>
                </div>


            </section>


        </section>




    </div>

</body>

</html>