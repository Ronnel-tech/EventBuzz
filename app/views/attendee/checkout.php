<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Checkout</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<?php
$display_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
if ($display_name === '') {
    $display_name = (string) ($user['email'] ?? 'Attendee');
}

$banner_image = (string) ($event['banner_image'] ?? '/public/assets/images/logo.png');
$payment_type = (string) ($event['payment_type'] ?? '');
$is_free_event = $payment_type === 'free';
$starting_total = 0;

if (!function_exists('format_checkout_price')) {
    function format_checkout_price($amount, bool $is_free_event = false): string
    {
        if ($is_free_event && ((float) $amount) <= 0) {
            return 'Free';
        }

        return 'PHP ' . number_format((float) $amount, 2);
    }
}
?>
<body class="min-h-screen bg-[#151419] p-6">

<nav class="sticky top-5 z-10 mb-8 flex justify-between rounded-full bg-surface p-4 pl-5 pr-5 shadow-soft">
    <div class="flex items-center gap-3">
        <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
        <h4>EventBuzz</h4>
    </div>

    <div class="flex items-center gap-5">
        <a href="<?= url('/attendee/detailed_event?id=' . $event['id']) ?>" class="text-secondary transition hover:text-white">Back to Event</a>
        <a href="<?= url('/attendee/ticket') ?>" class="text-secondary transition hover:text-white">My Tickets</a>
        <h4><?= esc($display_name) ?></h4>
        <form method="POST" action="<?= url('logout') ?>">
            <button>
                <svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg>
            </button>
        </form>
    </div>
</nav>

<div class="min-h-screen flex items-center justify-center">
    <div class="grid w-full max-w-7xl grid-cols-1 gap-6 lg:grid-cols-3">
        <form id="checkoutForm" method="POST" action="<?= url('/attendee/checkout?id=' . $event['id']) ?>" class="rounded-2xl border border-gray-800 bg-surface p-6 lg:col-span-2">
            <?= csrf_field() ?>

            <div class="mb-6 border-b border-[#2a2a2e] pb-6 text-center">
                <h2 class="text-2xl font-semibold"><?= esc($event['title']) ?></h2>
                <p class="text-sm text-secondary"><?= esc(date('l, F d, Y', strtotime($event['start_datetime']))) ?></p>
                <p class="pt-2 text-sm text-secondary">
                    <?= esc($event['organizer_name'] ?? 'Organizer') ?> •
                    <?= esc($event['city']) ?>, <?= esc($event['province']) ?>
                </p>
            </div>

            <?php if ($msg = get_flash('success')): ?>
            <div class="mb-5 rounded-2xl border border-green-400/30 bg-green-500/10 p-4 text-green-200">
                <?= esc($msg) ?>
            </div>
            <?php endif; ?>

            <?php if ($msg = get_flash('error')): ?>
            <div class="mb-5 rounded-2xl border border-red-400/30 bg-red-500/10 p-4 text-red-200">
                <?= esc($msg) ?>
            </div>
            <?php endif; ?>

            <div class="space-y-4">
                <?php if ($ticket_types): ?>
                    <?php foreach ($ticket_types as $ticket): ?>
                    <?php
                    $ticket_price = (float) $ticket['price'];
                    $ticket_left = (int) $ticket['tickets_left'];
                    ?>
                    <div class="flex flex-col justify-between gap-4 rounded-2xl border border-[#2a2a2e] bg-[#151419] p-5 md:flex-row md:items-center">
                        <div>
                            <h3 class="text-lg"><?= esc($ticket['name']) ?></h3>
                            <p class="text-sm text-gray-300"><?= esc(format_checkout_price($ticket['price'], $is_free_event)) ?></p>
                            <p class="text-xs text-gray-500">Sales start: <?= esc(date('M d, Y h:i A', strtotime($ticket['start_datetime']))) ?></p>
                            <p class="text-xs text-gray-500">Sales end: <?= esc(date('M d, Y h:i A', strtotime($ticket['end_datetime']))) ?></p>
                        </div>

                        <div class="text-left md:text-right">
                            <p class="text-sm text-gray-400">Tickets Left: <?= esc((string) $ticket_left) ?></p>
                            <div class="mt-4 flex items-center gap-3 md:justify-end">
                                <label for="quantity_<?= esc((string) $ticket['id']) ?>" class="text-sm text-gray-300">Quantity</label>
                                <input
                                    id="quantity_<?= esc((string) $ticket['id']) ?>"
                                    type="number"
                                    name="quantities[<?= esc((string) $ticket['id']) ?>]"
                                    value="<?= esc((string) ((int) ($selected_quantities[$ticket['id']] ?? 0))) ?>"
                                    min="0"
                                    max="<?= esc((string) $ticket_left) ?>"
                                    data-ticket-quantity
                                    data-ticket-name="<?= esc($ticket['name']) ?>"
                                    data-ticket-price="<?= esc((string) $ticket_price) ?>"
                                    class="w-24 rounded-full border border-[#2a2a2e] bg-surface px-4 py-2 text-white"
                                >
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="rounded-2xl border border-dashed border-[#2a2a2e] bg-[#151419] p-6 text-center text-gray-400">
                        No open ticket types are available for this event yet.
                    </div>
                <?php endif; ?>
            </div>
        </form>

        <div class="flex flex-col overflow-hidden rounded-2xl border border-gray-800 bg-[#151821]">
            <img src="<?= esc($banner_image) ?>" class="h-40 w-full object-cover" alt="<?= esc($event['title']) ?>">

            <div class="flex flex-1 flex-col justify-between p-6">
                <div>
                    <h3 class="mb-4 text-lg">Order Summary</h3>

                    <div id="selectedTicketsSummary" class="space-y-2 text-sm text-gray-300">
                        <div class="flex justify-between text-gray-500">
                            <span>No tickets selected yet</span>
                            <span><?= esc(format_checkout_price(0, $is_free_event)) ?></span>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-700">

                    <div class="space-y-2 text-sm text-gray-300">
                        <div class="flex justify-between">
                            <span>Event</span>
                            <span><?= esc($event['title']) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Date</span>
                            <span><?= esc(date('M d, Y', strtotime($event['start_datetime']))) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Organizer</span>
                            <span><?= esc($event['organizer_name'] ?? 'Organizer') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Location</span>
                            <span><?= esc($event['city']) ?>, <?= esc($event['province']) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Available Ticket Types</span>
                            <span><?= esc((string) count($ticket_types)) ?></span>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-700">

                    <div class="flex justify-between text-lg font-semibold">
                        <span>TOTAL</span>
                        <span id="checkoutTotal"><?= esc(format_checkout_price($starting_total, $is_free_event)) ?></span>
                    </div>
                </div>

                <?php if ($ticket_types): ?>
                    <button type="submit" form="checkoutForm" class="mt-6 rounded-full bg-yellow-400 py-3 font-semibold text-black transition hover:bg-yellow-300">
                        Continue to Payment
                    </button>
                <?php else: ?>
                    <button class="mt-6 cursor-not-allowed rounded-full bg-[#2a2a2e] py-3 font-semibold text-secondary opacity-70" disabled>
                        No Tickets Available
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const quantityInputs = Array.from(document.querySelectorAll('[data-ticket-quantity]'));
    const summaryContainer = document.getElementById('selectedTicketsSummary');
    const totalElement = document.getElementById('checkoutTotal');
    const isFreeEvent = <?= $is_free_event ? 'true' : 'false' ?>;

    function formatAmount(amount) {
        if (isFreeEvent && amount <= 0) {
            return 'Free';
        }

        return 'PHP ' + amount.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function renderSummary() {
        let total = 0;
        const selectedLines = [];

        quantityInputs.forEach(function (input) {
            const quantity = Math.max(0, parseInt(input.value || '0', 10));
            const max = Math.max(0, parseInt(input.max || '0', 10));

            if (quantity > max) {
                input.value = max;
            }

            if (quantity > 0) {
                const price = parseFloat(input.dataset.ticketPrice || '0');
                const subtotal = price * quantity;
                total += subtotal;
                selectedLines.push({
                    name: input.dataset.ticketName || 'Ticket',
                    quantity: quantity,
                    subtotal: subtotal
                });
            }
        });

        if (!selectedLines.length) {
            summaryContainer.innerHTML = '<div class="flex justify-between text-gray-500"><span>No tickets selected yet</span><span>' + formatAmount(0) + '</span></div>';
            totalElement.textContent = formatAmount(0);
            return;
        }

        summaryContainer.innerHTML = selectedLines.map(function (line) {
            return '<div class="flex justify-between"><span>' +
                line.quantity + 'x ' + line.name +
                '</span><span>' + formatAmount(line.subtotal) + '</span></div>';
        }).join('');

        totalElement.textContent = formatAmount(total);
    }

    quantityInputs.forEach(function (input) {
        input.addEventListener('input', renderSummary);
    });

    renderSummary();
});
</script>
</body>
</html>
