<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Attendee Payment Details</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<body class="flex h-screen w-full">
    <aside class="fixed left-0 top-0 flex h-screen w-24 flex-col items-center justify-between bg-surface p-5 shadow-soft">
        <img src="/public/assets/images/logo.png" alt="" class="size-7">

        <div class="flex flex-col align-center gap-5 -mt-120">
            <a href="<?= url('/organizer/dashboard') ?>">
                <button><svg class="icon-disabled" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path fill-opacity="0.16" d="M18.6 3H5.4A2.4 2.4 0 0 0 3 5.4v13.2A2.4 2.4 0 0 0 5.4 21h13.2a2.4 2.4 0 0 0 2.4-2.4V5.4A2.4 2.4 0 0 0 18.6 3"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M12 21V3m0 7h9M5.4 3h13.2A2.4 2.4 0 0 1 21 5.4v13.2a2.4 2.4 0 0 1-2.4 2.4H5.4A2.4 2.4 0 0 1 3 18.6V5.4A2.4 2.4 0 0 1 5.4 3"/></g></svg></button>
            </a>
            <a href="<?= url('/organizer/events') ?>">
                <button><svg class="icon-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 17V8H7v9zm0-14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1V1h2v2h8V1h2v2zM3 21h14v2H3a2 2 0 0 1-2-2V9h2zm16-6h-4v-4h4z"/></svg></button>
            </a>
        </div>

        <form method="POST" action="<?= url('logout') ?>">
            <button><svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg></button>
        </form>
    </aside>

    <div class="ml-24 flex w-full flex-col p-5">
        <div class="flex items-center justify-between">
            <div>
                <h3>Attendee Payment Details</h3>
                <p><?= esc($event['title']) ?> payment record</p>
            </div>
        </div>

        <section class="w-full p-10">
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

            <div class="grid gap-6 lg:grid-cols-[1.35fr_0.85fr]">
                <div class="rounded-2xl bg-surface p-8 outline outline-[#2a2a2e] shadow-soft">
                    <div class="mb-8 border-b border-[#2a2a2e] pb-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <h3><?= esc($payment_details['attendee_name'] ?: 'Unknown attendee') ?></h3>
                                <p class="pt-2 text-sm text-secondary">Order #<?= esc((string) $payment_details['id']) ?></p>
                            </div>

                            <form method="POST" action="<?= url('/organizer/qr_page?event_id=' . $event['id'] . '&order_id=' . $payment_details['id']) ?>" class="flex flex-wrap gap-3">
                                <?= csrf_field() ?>
                                <button
                                    type="submit"
                                    name="status"
                                    value="pending"
                                    class="rounded-full px-5 py-2 text-sm font-semibold transition <?= $payment_details['status'] === 'pending' ? 'bg-yellow-400 text-black' : 'border border-yellow-400/40 bg-yellow-500/10 text-yellow-300 hover:bg-yellow-500/20' ?>"
                                >
                                    Pending
                                </button>
                                <button
                                    type="submit"
                                    name="status"
                                    value="done"
                                    class="rounded-full px-5 py-2 text-sm font-semibold transition <?= $payment_details['status'] === 'done' ? 'bg-green-400 text-black' : 'border border-green-400/40 bg-green-500/10 text-green-300 hover:bg-green-500/20' ?>"
                                >
                                    Paid
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-secondary">Name</p>
                            <p class="pt-1 text-white"><?= esc($payment_details['attendee_name'] ?: 'Unknown attendee') ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary">Transaction Date</p>
                            <p class="pt-1 text-white"><?= esc(date('F d, Y h:i A', strtotime($payment_details['transaction_date']))) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary">Payment Method</p>
                            <p class="pt-1 text-white"><?= esc(ucfirst((string) $payment_details['payment_method'])) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary">Total Amount</p>
                            <p class="pt-1 text-white">PHP <?= esc(number_format((float) $payment_details['total_amount'], 2)) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary">Status</p>
                            <p class="pt-1 text-white"><?= esc(ucfirst((string) $payment_details['status'])) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary">Reference Number</p>
                            <p class="pt-1 text-white"><?= esc($payment_details['gcash_reference'] !== '' ? $payment_details['gcash_reference'] : 'N/A') ?></p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <p class="text-sm text-secondary">Tickets Bought</p>
                        <div class="mt-4 space-y-3">
                            <?php foreach ($payment_details['ticket_items'] as $ticket_item): ?>
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

                <div class="rounded-2xl bg-surface p-8 outline outline-[#2a2a2e] shadow-soft">
                    <h3>GCash E-Receipt</h3>
                    <p class="pt-2 text-sm text-secondary">Shown only for GCash payments.</p>

                    <?php if ((string) $payment_details['payment_method'] === 'gcash' && !empty($payment_details['gcash_screenshot'])): ?>
                    <div class="mt-6 overflow-hidden rounded-2xl bg-[#151419]">
                        <img src="<?= esc($payment_details['gcash_screenshot']) ?>" alt="GCash receipt" class="w-full object-cover">
                    </div>
                    <?php else: ?>
                    <div class="mt-6 rounded-2xl border border-dashed border-[#2a2a2e] bg-[#151419] p-8 text-center text-secondary">
                        No GCash e-receipt is available for this order.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
