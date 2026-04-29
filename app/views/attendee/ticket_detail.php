<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Ticket Details</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<?php
$display_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
if ($display_name === '') {
    $display_name = (string) ($user['email'] ?? 'Attendee');
}
$qr_image_url = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=' . rawurlencode($organizer_lookup_url);
?>
<body class="public-page bg-[#151419]">
<nav class="page-nav">
    <div class="flex items-center gap-3">
        <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
        <h4>EventBuzz</h4>
    </div>

    <div class="page-nav-links justify-end">
        <a href="<?= url('/attendee/ticket') ?>" class="text-secondary transition hover:text-white">Back to Tickets</a>
        <h4><?= esc($display_name) ?></h4>
        <form method="POST" action="<?= url('logout') ?>">
            <button>
                <svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg>
            </button>
        </form>
    </div>
</nav>

<div class="mx-auto max-w-6xl">
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

    <div class="overflow-hidden rounded-[2rem] border border-dashed border-yellow-400/35 bg-surface shadow-soft lg:grid lg:grid-cols-[1.1fr_0.9fr]">
        <div class="p-8 lg:p-10">
            <div class="mb-8 flex items-start justify-between gap-4 border-b border-[#2a2a2e] pb-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.3em] text-yellow-300/80">EventBuzz Ticket</p>
                    <h2 class="pt-3 text-3xl font-semibold text-white"><?= esc($ticket_order['event_title']) ?></h2>
                </div>
                <span class="inline-flex rounded-full px-4 py-2 text-sm <?= $ticket_order['payment_status'] === 'done' ? 'bg-green-500/10 text-green-300' : 'bg-yellow-500/10 text-yellow-300' ?>">
                    <?= esc($ticket_order['payment_status'] === 'done' ? 'Paid' : 'Pending') ?>
                </span>
            </div>

            <div class="mb-8 overflow-hidden rounded-3xl">
                <img
                    src="<?= esc((string) ($ticket_order['banner_image'] ?: '/public/assets/images/logo.png')) ?>"
                    alt="<?= esc($ticket_order['event_title']) ?>"
                    class="h-56 w-full object-cover"
                >
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <p class="text-sm text-secondary">Event Date</p>
                    <p class="pt-1 text-white"><?= esc(date('F d, Y h:i A', strtotime($ticket_order['start_datetime']))) ?></p>
                </div>
                <div>
                    <p class="text-sm text-secondary">Order Date</p>
                    <p class="pt-1 text-white"><?= esc(date('F d, Y h:i A', strtotime($ticket_order['created_at']))) ?></p>
                </div>
                <div>
                    <p class="text-sm text-secondary">Tickets Bought</p>
                    <p class="pt-1 text-white"><?= esc((string) $ticket_order['tickets_bought']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-secondary">Payment Method</p>
                    <p class="pt-1 text-white"><?= esc(ucfirst((string) $ticket_order['payment_method'])) ?></p>
                </div>
                <div>
                    <p class="text-sm text-secondary">Total Amount</p>
                    <p class="pt-1 text-white">PHP <?= esc(number_format((float) $ticket_order['total_amount'], 2)) ?></p>
                </div>
                <div>
                    <p class="text-sm text-secondary">Reference Number</p>
                    <p class="pt-1 text-white"><?= esc($ticket_order['gcash_reference'] !== '' ? $ticket_order['gcash_reference'] : 'N/A') ?></p>
                </div>
            </div>

            <div class="mt-8">
                <p class="text-sm text-secondary">Location</p>
                <p class="pt-1 text-white">
                    <?= esc(trim((string) ($ticket_order['street'] . ', ' . $ticket_order['city'] . ', ' . $ticket_order['province'] . ', ' . $ticket_order['country']), ', ')) ?>
                </p>
            </div>

            <div class="mt-8">
                <p class="mb-4 text-sm text-secondary">Purchase Details</p>
                <div class="space-y-3">
                    <?php foreach ($ticket_order['ticket_items'] as $ticket_item): ?>
                    <div class="flex items-center justify-between rounded-2xl bg-[#151419] px-5 py-4">
                        <div>
                            <p class="text-white"><?= esc($ticket_item['ticket_name']) ?></p>
                            <p class="pt-1 text-sm text-secondary">Quantity: <?= esc((string) $ticket_item['quantity']) ?></p>
                        </div>
                        <div class="text-right text-white">
                            PHP <?= esc(number_format((float) $ticket_item['subtotal'], 2)) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="border-t border-dashed border-yellow-400/25 bg-[#111117] p-8 lg:border-l lg:border-t-0 lg:p-10">
            <div class="flex h-full flex-col items-center justify-center text-center">
                <p class="text-sm uppercase tracking-[0.25em] text-yellow-300/75">Scan For Organizer Lookup</p>
                <div class="mt-6 rounded-[2rem] bg-white p-5 shadow-soft">
                    <img src="<?= esc($qr_image_url) ?>" alt="Ticket QR Code" class="h-64 w-64 object-contain">
                </div>
                <p class="mt-6 text-sm text-secondary">Order #<?= esc((string) $ticket_order['id']) ?></p>
                <p class="pt-2 text-sm text-secondary">Ticket Code: <?= esc((string) ($ticket_order['primary_ticket_code'] ?: 'Unavailable')) ?></p>
                <p class="mt-6 max-w-xs text-sm text-secondary">
                    When the organizer scans this QR code, it opens the payment verification page for this exact purchase.
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
