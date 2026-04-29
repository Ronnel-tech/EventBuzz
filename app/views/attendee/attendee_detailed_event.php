<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Event Details</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<?php
$display_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
if ($display_name === '') {
    $display_name = (string) ($user['email'] ?? 'Attendee');
}

$starting_price = $event['starting_price'] ?? null;
$payment_type = (string) ($event['payment_type'] ?? '');
$available_tickets = (int) ($event['available_tickets'] ?? 0);

if (!function_exists('format_attendee_detail_price')) {
    function format_attendee_detail_price($starting_price, string $payment_type): string
    {
        if ($starting_price !== null && $starting_price !== '') {
            return 'PHP ' . number_format((float) $starting_price, 2);
        }

        if ($payment_type === 'free') {
            return 'Free';
        }

        return 'Price unavailable';
    }
}
?>
<body class="public-page bg-[#151419]">
<nav class="page-nav">
        <div class="flex items-center gap-3">
            <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
            <h4>EventBuzz</h4>
        </div>

    <div class="page-nav-links justify-end">
            <a href="<?= url('/attendee') ?>" class="text-secondary transition hover:text-white">Browse Events</a>
            <a href="<?= url('/attendee/ticket') ?>">
                <button class="flex justify-center align-center">
                    <svg class="icon-primary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M3 10h-.75c0 .414.336.75.75.75zm0 4v-.75a.75.75 0 0 0-.75.75zm18-4v.75a.75.75 0 0 0 .75-.75zm0 4h.75a.75.75 0 0 0-.75-.75zM5 5.75h5v-1.5H5zm5 0h9v-1.5h-9zm9 12.5h-9v1.5h9zm-9 0H5v1.5h5zM9.25 5v14h1.5V5zm-5.366 6.116a1.25 1.25 0 0 1 0 1.768l1.06 1.06a2.75 2.75 0 0 0 0-3.889zm16.232 1.768a1.25 1.25 0 0 1 0-1.768l-1.06-1.06a2.75 2.75 0 0 0 0 3.889zM3 10.75c.321 0 .64.122.884.366l1.06-1.06A2.74 2.74 0 0 0 3 9.25zm.75-.75V7h-1.5v3zm0 7v-3h-1.5v3zm.134-4.116A1.24 1.24 0 0 1 3 13.25v1.5c.703 0 1.408-.269 1.945-.806zm16.232-1.768c.244-.244.563-.366.884-.366v-1.5c-.703 0-1.408.269-1.945.806zM20.25 7v3h1.5V7zm0 7v3h1.5v-3zm.75-.75c-.321 0-.64-.122-.884-.366l-1.06 1.06A2.74 2.74 0 0 0 21 14.75zm-16 5c-.69 0-1.25-.56-1.25-1.25h-1.5A2.75 2.75 0 0 0 5 19.75zm14 1.5A2.75 2.75 0 0 0 21.75 17h-1.5c0 .69-.56 1.25-1.25 1.25zm0-14c.69 0 1.25.56 1.25 1.25h1.5A2.75 2.75 0 0 0 19 4.25zM5 4.25A2.75 2.75 0 0 0 2.25 7h1.5c0-.69.56-1.25 1.25-1.25z"/></svg>
                </button>
            </a>
            <h4 class="pr-2"><?= esc($display_name) ?></h4>
            <form method="POST" action="<?= url('logout') ?>">
                <button>
                    <svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg>
                </button>
            </form>
        </div>
    </nav>

    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex flex-col">
            <h3>Detailed Event</h3>
            <p>Review the full event information before reserving your ticket.</p>
        </div>

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

        <div class="overflow-hidden rounded-3xl outline outline-[#2a2a2e] shadow-soft">
            <div class="h-72 bg-cover bg-center" style="background-image: url('<?= esc($event['banner_image'] ?: '/public/assets/images/signup_bg.jpg') ?>');"></div>

            <div class="bg-surface p-10">
                <div class="flex flex-wrap items-start justify-between gap-6 border-b border-[#2a2a2e] pb-8">
                    <div>
                        <p class="text-sm text-secondary"><?= esc($event['organizer_name'] ?? 'Organizer') ?></p>
                        <h2 class="pt-3"><?= esc($event['title']) ?></h2>
                    </div>
                    <div class="rounded-2xl bg-[#2a2a2e] px-5 py-3 text-right">
                        <p class="text-sm text-secondary">Date</p>
                        <p><?= esc(date('F d, Y', strtotime($event['start_datetime']))) ?></p>
                        <p class="pt-2 text-sm text-secondary">Time</p>
                        <p><?= esc(date('g:i A', strtotime($event['start_datetime']))) ?> - <?= esc(date('g:i A', strtotime($event['end_datetime']))) ?></p>
                    </div>
                </div>

                <div class="grid gap-5 pt-8 md:grid-cols-2">
                    <div class="rounded-2xl bg-[#2a2a2e] p-6">
                        <h3>Event Details</h3>
                        <div class="grid gap-4 pt-5 text-secondary">
                            <div>
                                <p class="text-sm">Category</p>
                                <p class="pt-1 text-white"><?= esc($event['category_name'] ?? 'Uncategorized') ?></p>
                            </div>
                            <div>
                                <p class="text-sm">Event Title</p>
                                <p class="pt-1 text-white"><?= esc($event['title']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm">Start</p>
                                <p class="pt-1 text-white"><?= esc(date('F d, Y g:i A', strtotime($event['start_datetime']))) ?></p>
                            </div>
                            <div>
                                <p class="text-sm">End</p>
                                <p class="pt-1 text-white"><?= esc(date('F d, Y g:i A', strtotime($event['end_datetime']))) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-[#2a2a2e] p-6">
                        <h3>Location</h3>
                        <p class="pt-4 text-secondary"><?= esc($event['street']) ?></p>
                        <p class="text-secondary"><?= esc($event['city']) ?>, <?= esc($event['province']) ?></p>
                        <p class="text-secondary"><?= esc($event['country']) ?></p>
                    </div>
                </div>

                <div class="mt-8 grid gap-5 md:grid-cols-[2fr_1fr]">
                    <div class="rounded-2xl bg-[#2a2a2e] p-6">
                        <h3>Summary</h3>
                        <p class="pt-4 text-secondary"><?= nl2br(esc($event['description'])) ?></p>
                    </div>

                    <div class="rounded-2xl bg-[#2a2a2e] p-6">
                        <h3>Ticket Details</h3>
                        <div class="grid gap-4 pt-5 text-secondary">
                            <div>
                                <p class="text-sm">Starting Price</p>
                                <p class="pt-1 text-white"><?= esc(format_attendee_detail_price($starting_price, $payment_type)) ?></p>
                            </div>
                            <div>
                                <p class="text-sm">Available Tickets</p>
                                <p class="pt-1 text-white"><?= esc((string) $available_tickets) ?></p>
                            </div>
                            <div>
                                <p class="text-sm">Payment Type</p>
                                <p class="pt-1 text-white"><?= esc(ucfirst($payment_type ?: 'Unavailable')) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 text-center">
                    <?php if ($available_tickets > 0 || $payment_type === 'free'): ?>
                        <a href="<?= url('/attendee/checkout?id=' . $event['id']) ?>" class="btn btn-primary inline-flex rounded-full px-10 py-3">Buy Ticket</a>
                    <?php else: ?>
                        <button type="button" class="inline-flex cursor-not-allowed rounded-full bg-[#2a2a2e] px-10 py-3 text-secondary opacity-70" disabled>Tickets Unavailable</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
