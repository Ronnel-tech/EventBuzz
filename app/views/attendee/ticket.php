<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | My Tickets</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<?php
$display_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
if ($display_name === '') {
    $display_name = (string) ($user['email'] ?? 'Attendee');
}
?>
<body class="min-h-screen bg-[#151419] p-6">
<nav class="sticky top-5 z-10 mb-8 flex justify-between rounded-full bg-surface p-4 pl-5 pr-5 shadow-soft">
    <div class="flex items-center gap-3">
        <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
        <h4>EventBuzz</h4>
    </div>

    <div class="flex items-center gap-5">
        <a href="<?= url('/attendee') ?>" class="text-secondary transition hover:text-white">Browse Events</a>
        <h4><?= esc($display_name) ?></h4>
        <form method="POST" action="<?= url('logout') ?>">
            <button>
                <svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg>
            </button>
        </form>
    </div>
</nav>

<div class="mx-auto max-w-7xl">
    <div class="mb-6">
        <h3>My Tickets</h3>
        <p>Tap any card to open the full ticket and purchase details.</p>
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

    <section class="mb-10 rounded-3xl bg-surface p-8 outline outline-[#2a2a2e] shadow-soft">
        <div class="mb-6">
            <h3>Purchased Events</h3>
            <p class="pt-2 text-sm text-secondary">Only the event banner, title, and status are shown here.</p>
        </div>

        <?php if ($active_orders): ?>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <?php foreach ($active_orders as $order): ?>
                <a href="<?= url('/attendee/ticket-detail?order_id=' . $order['id']) ?>" class="overflow-hidden rounded-3xl border border-[#2a2a2e] bg-[#151419] transition hover:-translate-y-1 hover:border-yellow-400/30">
                    <div class="h-48 overflow-hidden">
                        <img
                            src="<?= esc((string) ($order['banner_image'] ?: '/public/assets/images/logo.png')) ?>"
                            alt="<?= esc($order['event_title']) ?>"
                            class="h-full w-full object-cover"
                        >
                    </div>
                    <div class="p-5">
                        <h4 class="text-xl text-white"><?= esc($order['event_title']) ?></h4>
                        <div class="pt-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs <?= $order['payment_status'] === 'done' ? 'bg-green-500/10 text-green-300' : 'bg-yellow-500/10 text-yellow-300' ?>">
                                <?= esc($order['payment_status'] === 'done' ? 'Paid' : 'Pending') ?>
                            </span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="rounded-2xl border border-dashed border-[#2a2a2e] bg-[#151419] p-8 text-center text-secondary">
                You have not purchased any upcoming event tickets yet.
            </div>
        <?php endif; ?>
    </section>

    <section class="rounded-3xl bg-surface p-8 outline outline-[#2a2a2e] shadow-soft">
        <div class="mb-6">
            <h3>Completed Or Attended Events</h3>
            <p class="pt-2 text-sm text-secondary">Past events are kept below in the same simplified format.</p>
        </div>

        <?php if ($completed_orders): ?>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <?php foreach ($completed_orders as $order): ?>
                <a href="<?= url('/attendee/ticket-detail?order_id=' . $order['id']) ?>" class="overflow-hidden rounded-3xl border border-[#2a2a2e] bg-[#151419] transition hover:-translate-y-1 hover:border-yellow-400/30">
                    <div class="h-48 overflow-hidden">
                        <img
                            src="<?= esc((string) ($order['banner_image'] ?: '/public/assets/images/logo.png')) ?>"
                            alt="<?= esc($order['event_title']) ?>"
                            class="h-full w-full object-cover"
                        >
                    </div>
                    <div class="p-5">
                        <h4 class="text-xl text-white"><?= esc($order['event_title']) ?></h4>
                        <div class="pt-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs <?= $order['payment_status'] === 'done' ? 'bg-green-500/10 text-green-300' : 'bg-yellow-500/10 text-yellow-300' ?>">
                                <?= esc($order['payment_status'] === 'done' ? 'Paid' : 'Pending') ?>
                            </span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="rounded-2xl border border-dashed border-[#2a2a2e] bg-[#151419] p-8 text-center text-secondary">
                No completed or attended events to show yet.
            </div>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
