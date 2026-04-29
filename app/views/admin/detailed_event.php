<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Detailed Event</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<body class="app-shell">
    <aside class="app-sidebar">
        <img src="/public/assets/images/logo.png" alt="" class="size-7 ">

        <div class="app-sidebar-nav">
            <a href="<?= url('/admin/dashboard') ?>">
                <button><svg class="icon-disabled" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path fill-opacity="0.16" d="M18.6 3H5.4A2.4 2.4 0 0 0 3 5.4v13.2A2.4 2.4 0 0 0 5.4 21h13.2a2.4 2.4 0 0 0 2.4-2.4V5.4A2.4 2.4 0 0 0 18.6 3"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M12 21V3m0 7h9M5.4 3h13.2A2.4 2.4 0 0 1 21 5.4v13.2a2.4 2.4 0 0 1-2.4 2.4H5.4A2.4 2.4 0 0 1 3 18.6V5.4A2.4 2.4 0 0 1 5.4 3"/></g></svg></button>
            </a>
            <a href="<?= url('/admin/event-management') ?>">
                <button><svg class="icon-primary" xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 0 24 24"><path d="M21 17V8H7v9zm0-14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1V1h2v2h8V1h2v2zM3 21h14v2H3a2 2 0 0 1-2-2V9h2zm16-6h-4v-4h4z"/></svg></button>
            </a>
        </div>

        <form method="POST" action="<?= url('logout') ?>">
            <button><svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg></button>
        </form>
    </aside>

    <div class="app-main">
        <div class="flex flex-col">
            <h3>Detailed Event</h3>
            <p>Review all the event details saved by the organizer.</p>
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

            <div class="w-full overflow-hidden rounded-3xl outline outline-[#2a2a2e] shadow-soft">
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

                    <div class="mt-8 rounded-2xl bg-[#2a2a2e] p-6">
                        <h3>Summary</h3>
                        <p class="pt-4 text-secondary"><?= nl2br(esc($event['description'])) ?></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
