<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Attendee Management</title>
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
        <a href="<?= url('/admin/event-management') ?>" class="text-secondary transition hover:text-white">Events</a>
        <a href="<?= url('/admin/organizer-management') ?>" class="text-secondary transition hover:text-white">Organizers</a>
        <a href="<?= url('/admin/attendee-management') ?>" class="text-white">Attendees</a>
        <form method="POST" action="<?= url('logout') ?>">
            <button>
                <svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg>
            </button>
        </form>
    </div>
</nav>

<div class="mx-auto max-w-7xl">
    <div class="mb-6">
        <h3>Attendee Management</h3>
        <p>View all attendees, their joined date, and ticket purchase totals.</p>
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
            <h3>Attendees</h3>
            <p class="pt-2 text-sm text-secondary">All registered attendee accounts in the platform.</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-[#2a2a2e]">
            <div class="grid gap-4 border-b border-[#2a2a2e] bg-[#151419] px-6 py-4 text-sm text-secondary" style="grid-template-columns: minmax(200px, 1.2fr) minmax(220px, 1.2fr) minmax(140px, 0.8fr) minmax(120px, 0.7fr) minmax(120px, 0.7fr);">
                <div>Attendee</div>
                <div>Email</div>
                <div>Joined On</div>
                <div>Tickets Bought</div>
                <div class="text-right">Action</div>
            </div>

            <?php if ($attendees): ?>
                <div class="divide-y divide-[#2a2a2e] bg-surface">
                    <?php foreach ($attendees as $attendee): ?>
                    <?php
                    $full_name = trim((string) (($attendee['first_name'] ?? '') . ' ' . ($attendee['last_name'] ?? '')));
                    if ($full_name === '') {
                        $full_name = 'Unnamed attendee';
                    }
                    ?>
                    <div class="grid items-center gap-4 px-6 py-5 text-sm text-white" style="grid-template-columns: minmax(200px, 1.2fr) minmax(220px, 1.2fr) minmax(140px, 0.8fr) minmax(120px, 0.7fr) minmax(120px, 0.7fr);">
                        <div><?= esc($full_name) ?></div>
                        <div><?= esc($attendee['email']) ?></div>
                        <div><?= esc(date('M d, Y', strtotime($attendee['created_at']))) ?></div>
                        <div><?= esc((string) $attendee['tickets_bought']) ?></div>
                        <div class="text-right">
                            <form method="POST" action="<?= url('/admin/attendee-management') ?>" class="inline-block" onsubmit="return confirm('Delete this attendee and all related ticket purchases?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="attendee_id" value="<?= esc((string) $attendee['id']) ?>">
                                <button type="submit" class="rounded-full border border-red-400/40 bg-red-500/10 px-4 py-2 text-xs font-semibold text-red-300 transition hover:bg-red-500/20">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-surface px-6 py-12 text-center text-secondary">
                    No attendees found.
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
</body>
</html>
