<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Attendee List</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<?php
$search_value = trim((string) ($search ?? ''));
$filter_value = trim((string) ($filter ?? 'all'));
if (!in_array($filter_value, ['all', 'paid', 'pending'], true)) {
    $filter_value = 'all';
}
?>

<body class="app-shell">
    <aside class="app-sidebar">
        <img src="/public/assets/images/logo.png" alt="" class="size-7">

        <div class="app-sidebar-nav">
            <a href="<?= url('/organizer/dashboard') ?>">
                <button><svg class="icon-disabled" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <g fill="none">
                            <path fill-opacity="0.16"
                                d="M18.6 3H5.4A2.4 2.4 0 0 0 3 5.4v13.2A2.4 2.4 0 0 0 5.4 21h13.2a2.4 2.4 0 0 0 2.4-2.4V5.4A2.4 2.4 0 0 0 18.6 3" />
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-miterlimit="10" stroke-width="1.5"
                                d="M12 21V3m0 7h9M5.4 3h13.2A2.4 2.4 0 0 1 21 5.4v13.2a2.4 2.4 0 0 1-2.4 2.4H5.4A2.4 2.4 0 0 1 3 18.6V5.4A2.4 2.4 0 0 1 5.4 3" />
                        </g>
                    </svg></button>
            </a>
            <a href="<?= url('/organizer/events') ?>">
                <button><svg class="icon-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path
                            d="M21 17V8H7v9zm0-14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1V1h2v2h8V1h2v2zM3 21h14v2H3a2 2 0 0 1-2-2V9h2zm16-6h-4v-4h4z" />
                    </svg></button>
            </a>
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
        <div class="flex flex-col">
            <h3>Attendee List</h3>
            <p><?= esc($event['title']) ?> attendees and transactions</p>
        </div>

        <div class="flex flex-col pr-15">
            <form method="GET" action="<?= url('/organizer/attendee-list') ?>"
                class="flex items-center justify-end gap-3">
                <input type="hidden" name="id" value="<?= esc((string) $event['id']) ?>">
                <button type="button" id="openQrScanner" class="btn btn-primary rounded-full px-4 py-2">
                    Scan QR
                </button>

                <select name="filter" class="rounded-full bg-surface px-3 py-2 text-secondary"
                    onchange="this.form.submit()">
                    <option value="all" <?= $filter_value === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="paid" <?= $filter_value === 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="pending" <?= $filter_value === 'pending' ? 'selected' : '' ?>>Pending</option>
                </select>

                <input type="text" name="search" value="<?= esc($search_value) ?>" placeholder="Search..."
                    class="rounded-full bg-surface px-3 py-2 text-secondary">
                <button type="submit"
                    class="rounded-full bg-yellow-400 px-4 py-2 text-sm font-semibold text-black transition hover:bg-yellow-300">
                    Search
                </button>
                <?php if ($search_value !== '' || $filter_value !== 'all'): ?>
                    <a href="<?= url('/organizer/attendee-list?id=' . $event['id']) ?>"
                        class="rounded-full border border-[#2a2a2e] px-4 py-2 text-sm text-secondary transition hover:text-white">
                        Clear
                    </a>
                <?php endif; ?>
            </form>

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

                <div
                    class="mt-5 w-full overflow-hidden rounded-2xl bg-surface pb-10 sm:pb-16 lg:pb-30 outline outline-[#2a2a2e] shadow-soft">
                    <div class="px-10 pt-10">
                        <h3>Attendee List</h3>
                        <p class="pt-2 text-sm text-secondary">
                            <?= esc(date('F d, Y', strtotime($event['start_datetime']))) ?>
                            <?php if (!empty($event['category_name'])): ?>
                                | <?= esc($event['category_name']) ?>
                            <?php endif; ?>
                        </p>
                        <?php if ($search_value !== '' || $filter_value !== 'all'): ?>
                            <p class="pt-3 text-sm text-secondary">
                                Showing
                                <?= esc($filter_value === 'all' ? 'all attendees' : ($filter_value === 'paid' ? 'paid attendees' : 'pending attendees')) ?>
                                <?php if ($search_value !== ''): ?>
                                    matching "<span class="text-white"><?= esc($search_value) ?></span>"
                                <?php endif; ?>.
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="table-scroll px-4 py-6 sm:px-8 sm:py-8 lg:px-10">
                        <div class="grid gap-4 border-b border-[#2a2a2e] pb-4 text-sm text-gray-400"
                            style="grid-template-columns: minmax(180px, 1.4fr) minmax(120px, 0.9fr) minmax(110px, 0.8fr) minmax(130px, 0.9fr) minmax(110px, 0.8fr) minmax(140px, 1fr);">
                            <div>Name</div>
                            <div>Transaction Date</div>
                            <div>Tickets Bought</div>
                            <div>Total Amount</div>
                            <div>Status</div>
                            <div class="text-right">Action</div>
                        </div>

                        <?php if ($attendees): ?>
                            <div class="divide-y divide-[#2a2a2e]">
                                <?php foreach ($attendees as $attendee): ?>
                                    <div class="grid cursor-pointer items-center gap-4 py-5 text-sm text-white transition hover:bg-white/3"
                                        style="grid-template-columns: minmax(180px, 1.4fr) minmax(120px, 0.9fr) minmax(110px, 0.8fr) minmax(130px, 0.9fr) minmax(110px, 0.8fr) minmax(140px, 1fr);"
                                        data-payment-url="<?= esc(url('/organizer/qr_page?event_id=' . $event['id'] . '&order_id=' . $attendee['id'])) ?>"
                                        tabindex="0" role="button">
                                        <div><?= esc($attendee['attendee_name'] ?: 'Unknown attendee') ?></div>
                                        <div><?= esc(date('M d, Y', strtotime($attendee['transaction_date']))) ?></div>
                                        <div><?= esc((string) $attendee['tickets_bought']) ?></div>
                                        <div>PHP <?= esc(number_format((float) $attendee['total_amount'], 2)) ?></div>
                                        <div>
                                            <span
                                                class="rounded-full px-3 py-1 text-xs <?= $attendee['status'] === 'done' ? 'bg-green-500/10 text-green-300' : 'bg-yellow-500/10 text-yellow-300' ?>">
                                                <?= esc(ucfirst($attendee['status'])) ?>
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <form method="POST"
                                                action="<?= url('/organizer/attendee-list?id=' . $event['id'] . '&filter=' . urlencode($filter_value) . '&search=' . urlencode($search_value)) ?>"
                                                class="inline-block" data-row-action>
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="order_id"
                                                    value="<?= esc((string) $attendee['id']) ?>">
                                                <select name="status"
                                                    class="rounded-full border border-[#2a2a2e] bg-[#151419] px-3 py-2 text-xs text-white outline-none transition focus:border-yellow-400/50"
                                                    onchange="this.form.submit()">
                                                    <option value="done" <?= $attendee['status'] === 'done' ? 'selected' : '' ?>>
                                                        Paid</option>
                                                    <option value="pending" <?= $attendee['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="py-12 text-center text-gray-400">
                                No attendees found for this event yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div id="qrScannerModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/75 p-6">
        <div class="w-full max-w-3xl rounded-3xl border border-[#2a2a2e] bg-surface p-6 shadow-soft">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <h3>Scan Attendee QR</h3>
                    <p class="text-sm text-secondary">Scan the attendee ticket QR code to open the organizer lookup page
                        faster.</p>
                </div>
                <button type="button" id="closeQrScanner"
                    class="rounded-full border border-[#2a2a2e] px-4 py-2 text-sm text-secondary transition hover:text-white">
                    Close
                </button>
            </div>

            <div class="rounded-3xl bg-[#151419] p-4">
                <div class="overflow-hidden rounded-3xl border border-dashed border-[#2a2a2e] bg-black">
                    <div id="qrScannerReader" class="min-h-105 w-full"></div>
                </div>
                <p id="qrScannerStatus" class="pt-4 text-sm text-secondary">
                    Point the camera at an attendee QR code.
                </p>
                <div class="pt-4">
                    <input type="text" id="qrFallbackInput"
                        placeholder="Paste the scanned organizer QR link here if camera scanning is unavailable"
                        class="w-full rounded-full border border-[#2a2a2e] bg-surface px-5 py-3 text-sm text-white placeholder:text-secondary outline-none transition focus:border-yellow-400/50">
                </div>
                <div class="pt-3 flex justify-end">
                    <button type="button" id="openFallbackLink"
                        class="rounded-full bg-yellow-400 px-5 py-3 text-sm font-semibold text-black transition hover:brightness-110">
                        Open Link
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" defer></script>
    <script src="../public/assets/js/main.js" defer></script>
</body>

</html>