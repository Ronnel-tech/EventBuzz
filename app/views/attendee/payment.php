<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Payment</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<?php
$display_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
if ($display_name === '') {
    $display_name = (string) ($user['email'] ?? 'Attendee');
}

$banner_image = (string) ($event['banner_image'] ?? '/public/assets/images/logo.png');
$event_payment_type = (string) ($event['payment_type'] ?? 'free');
$is_free_event = $event_payment_type === 'free';

if (!function_exists('format_payment_price')) {
    function format_payment_price($amount, bool $is_free_event = false): string
    {
        if ($is_free_event && ((float) $amount) <= 0) {
            return 'Free';
        }

        return 'PHP ' . number_format((float) $amount, 2);
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
            <a href="<?= url('/attendee/checkout?id=' . $event['id']) ?>"
                class="text-secondary transition hover:text-white">Back to Checkout</a>
            <a href="<?= url('/attendee/ticket') ?>" class="text-secondary transition hover:text-white">My Tickets</a>
            <h4><?= esc($display_name) ?></h4>
            <form method="POST" action="<?= url('logout') ?>">
                <button>
                    <svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
        </div>
    </nav>

    <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-3">
        <form method="POST" action="<?= url('/attendee/payment?id=' . $event['id']) ?>" enctype="multipart/form-data"
            class="rounded-3xl border border-[#2a2a2e] bg-surface p-6 lg:col-span-2">
            <?= csrf_field() ?>

            <div class="mb-6 border-b border-[#2a2a2e] pb-6">
                <h2 class="text-2xl font-semibold">Payment Details</h2>
                <p class="pt-2 text-sm text-secondary">Choose how you want to pay for <?= esc($event['title']) ?>.</p>
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

            <div class="grid gap-4 md:grid-cols-<?= count($payment_method_options) > 1 ? '2' : '1' ?>">
                <?php foreach ($payment_method_options as $payment_method_option): ?>
                    <label
                        class="relative cursor-pointer rounded-2xl border border-white/10 bg-[#151419] p-5 transition hover:border-white/20">
                        <input type="radio" name="payment_method" value="<?= esc($payment_method_option) ?>"
                            class="peer sr-only" <?= $old['payment_method'] === $payment_method_option ? 'checked' : '' ?>>
                        <div
                            class="absolute right-4 top-4 h-4 w-4 rounded-md border border-white/40 bg-transparent peer-checked:border-yellow-400 peer-checked:bg-yellow-400">
                        </div>
                        <h3 class="text-base font-medium"><?= esc(ucfirst($payment_method_option)) ?></h3>
                        <p class="mt-1 text-sm text-white/45">
                            <?php if ($payment_method_option === 'gcash'): ?>
                                Upload your receipt and reference number after payment.
                            <?php elseif ($payment_method_option === 'cash'): ?>
                                Pay in cash based on the organizer's instructions.
                            <?php else: ?>
                                Confirm your free reservation.
                            <?php endif; ?>
                        </p>
                    </label>
                <?php endforeach; ?>
            </div>

            <div id="gcashFields"
                class="mt-6 rounded-3xl border border-white/10 bg-[#12121a] p-5 md:p-6 <?= $old['payment_method'] === 'gcash' ? '' : 'hidden' ?>">
                <div class="mb-5">
                    <h3 class="text-lg">GCash Payment</h3>
                    <p class="pt-1 text-sm text-white/60">Provide the sender details and upload your receipt.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <input type="text" name="gcash_sender_name" value="<?= esc($old['gcash_sender_name']) ?>"
                        placeholder="GCash Sender Name"
                        class="h-12 rounded-full border border-white/10 bg-[#0d0d14] px-5 text-sm text-white placeholder:text-white/35 outline-none transition focus:border-yellow-400/50">
                    <input type="text" name="gcash_reference" value="<?= esc($old['gcash_reference']) ?>"
                        placeholder="Reference Number"
                        class="h-12 rounded-full border border-white/10 bg-[#0d0d14] px-5 text-sm text-white placeholder:text-white/35 outline-none transition focus:border-yellow-400/50">
                </div>

                <div class="mt-6 grid gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-dashed border-white/15 bg-[#0d0d14] p-5">
                        <p class="mb-3 text-sm text-white/80">Upload your GCash receipt</p>
                        <input type="file" id="gcashReceipt" name="gcash_receipt" accept="image/*"
                            class="block w-full text-sm text-white file:mr-4 file:rounded-full file:border-0 file:bg-yellow-400 file:px-4 file:py-2 file:font-semibold file:text-black hover:file:brightness-110">
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-[#0d0d14] p-5">
                        <p class="mb-3 text-sm text-white/80">Organizer GCash details</p>
                        <p class="text-sm text-white/60">Name: <span
                                class="text-white"><?= esc($event['gcash_name'] ?: 'Not provided') ?></span></p>
                        <p class="pt-2 text-sm text-white/60">Number: <span
                                class="text-white"><?= esc($event['gcash_number'] ?: 'Not provided') ?></span></p>
                        <?php if (!empty($event['gcash_qr'])): ?>
                            <img src="<?= esc($event['gcash_qr']) ?>" alt="Organizer GCash QR"
                                class="mt-4 h-56 w-full rounded-2xl object-cover outline outline-[#2a2a2e] shadow-soft">
                        <?php else: ?>
                            <div class="mt-4 rounded-2xl border border-dashed border-white/10 p-6 text-sm text-white/50">
                                The organizer has not uploaded a GCash QR code yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="rounded-full bg-yellow-400 px-8 py-3 text-sm font-semibold text-black transition hover:brightness-110">
                    <?= $is_free_event ? 'Confirm Reservation' : 'Complete Order' ?>
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-3xl border border-[#2a2a2e] bg-[#151821]">
            <img src="<?= esc($banner_image) ?>" alt="<?= esc($event['title']) ?>" class="h-40 w-full object-cover">

            <div class="p-6">
                <h3 class="mb-4 text-lg">Order Summary</h3>

                <div class="space-y-2 text-sm text-gray-300">
                    <?php foreach ($summary['line_items'] as $line_item): ?>
                        <div class="flex justify-between gap-3">
                            <span><?= esc((string) $line_item['quantity']) ?>x
                                <?= esc($line_item['ticket']['name']) ?></span>
                            <span><?= esc(format_payment_price($line_item['subtotal'], $is_free_event)) ?></span>
                        </div>
                    <?php endforeach; ?>
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
                </div>

                <hr class="my-4 border-gray-700">

                <div class="flex justify-between text-lg font-semibold">
                    <span>TOTAL</span>
                    <span><?= esc(format_payment_price($summary['total_amount'], $is_free_event)) ?></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentInputs = Array.from(document.querySelectorAll('input[name="payment_method"]'));
            const gcashFields = document.getElementById('gcashFields');

            function toggleGcashFields() {
                const selectedInput = paymentInputs.find(function (input) {
                    return input.checked;
                });

                if (!gcashFields || !selectedInput) {
                    return;
                }

                gcashFields.classList.toggle('hidden', selectedInput.value !== 'gcash');
            }

            paymentInputs.forEach(function (input) {
                input.addEventListener('change', toggleGcashFields);
            });

            toggleGcashFields();
        });
    </script>
</body>

</html>