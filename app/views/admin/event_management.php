<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Event Management</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<body class="min-h-screen bg-[#151419] p-6">
<nav class="sticky top-5 z-10 mb-8 flex justify-between rounded-full bg-surface p-4 pl-5 pr-5 shadow-soft">
    <div class="flex items-center gap-3">
        <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
        <h4>EventBuzz Admin</h4>
    </div>

    <div class="flex items-center gap-5">
        <a href="<?= url('/admin/dashboard') ?>" class="text-secondary transition hover:text-white">Dashboard</a>
        <a href="<?= url('/admin/event-management') ?>" class="text-white">Events</a>
        <a href="<?= url('/admin/organizer-management') ?>" class="text-secondary transition hover:text-white">Organizers</a>
        <a href="<?= url('/admin/attendee-management') ?>" class="text-secondary transition hover:text-white">Attendees</a>
        <form method="POST" action="<?= url('logout') ?>">
            <button>
                <svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg>
            </button>
        </form>
    </div>
</nav>

<div class="mx-auto max-w-7xl">
    <div class="mb-6">
        <h3>Event Management</h3>
        <p>Browse all events created by all organizers.</p>
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

    <section class="rounded-3xl bg-surface p-8 outline outline-[#2a2a2e] shadow-soft">
        <div class="mb-6">
            <h3>All Events</h3>
            <p class="pt-2 text-sm text-secondary">Click an event to open the detailed admin view.</p>
        </div>

        <?php if ($events): ?>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <?php foreach ($events as $event): ?>
                <a href="<?= url('/admin/detailed-event?id=' . $event['id']) ?>" class="overflow-hidden rounded-3xl border border-[#2a2a2e] bg-[#151419] transition hover:-translate-y-1 hover:border-yellow-400/30">
                    <div class="h-52 overflow-hidden bg-[#1c2029]">
                        <img
                            src="<?= esc((string) ($event['banner_image'] ?: '/public/assets/images/logo.png')) ?>"
                            alt="<?= esc($event['title']) ?>"
                            class="h-full w-full object-cover"
                        >
                    </div>
                    <div class="space-y-3 p-5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs text-gray-300"><?= esc(date('M d, Y g:i A', strtotime($event['start_datetime']))) ?></span>
                            <span class="text-sm text-primary"><?= esc($event['category_name'] ?: 'Uncategorized') ?></span>
                        </div>
                        <h4 class="text-xl text-white"><?= esc($event['title']) ?></h4>
                        <p class="text-sm text-secondary">By <?= esc($event['organizer_name']) ?></p>
                        <div class="flex items-center justify-between text-sm text-secondary">
                            <span><?= esc(ucfirst((string) $event['payment_type'])) ?></span>
                            <span><?= esc((string) $event['tickets_sold']) ?> sold</span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="rounded-2xl border border-dashed border-[#2a2a2e] bg-[#151419] p-8 text-center text-secondary">
                No events found.
            </div>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
