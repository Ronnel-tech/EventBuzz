<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Dashboard</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<?php
$summary_cards = [
    [
        'label' => 'Total Users',
        'value' => (int) ($dashboard_summary['total_users'] ?? 0),
    ],
    [
        'label' => 'Total Organizers',
        'value' => (int) ($dashboard_summary['total_organizers'] ?? 0),
    ],
    [
        'label' => 'Total Revenue',
        'value' => 'PHP ' . number_format((float) ($dashboard_summary['total_revenue'] ?? 0), 2),
    ],
    [
        'label' => 'Total Events',
        'value' => (int) ($event_summary['total_events'] ?? 0),
    ],
];

$event_creation_chart = [];
foreach ($event_creation_trend as $row) {
    $event_creation_chart[] = [
        'label' => (string) ($row['period_label'] ?? ''),
        'value' => (int) ($row['events_created'] ?? 0),
    ];
}

$tickets_sold_chart = [];
foreach ($tickets_sold_by_event as $row) {
    $tickets_sold_chart[] = [
        'label' => (string) ($row['title'] ?? 'Event'),
        'value' => (int) ($row['tickets_sold'] ?? 0),
    ];
}
?>

<body class="public-page bg-[#151419]">
    <nav class="page-nav">
        <div class="flex items-center gap-3">
            <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
            <h4>EventBuzz</h4>
        </div>

        <div class="page-nav-links justify-end">
            <a href="<?= url('/admin/dashboard') ?>" class="text-primary transition hover:text-white">Dashboard</a>
            <a href="<?= url('/admin/event-management') ?>"
                class="text-secondary transition hover:text-white">Events</a>
            <a href="<?= url('/admin/organizer-management') ?>"
                class="text-secondary transition hover:text-white">Organizers</a>
            <a href="<?= url('/admin/attendee-management') ?>"
                class="text-secondary transition hover:text-white">Attendees</a>
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

    <div class="mx-auto max-w-8xl">
        <div class="mb-8 flex items-end justify-between gap-6">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-yellow-300/80">Admin Analytics</p>
                <h1 class="pt-3 text-4xl font-semibold text-white">Dashboard</h1>
                <p class="pt-3 text-secondary">Platform-wide user, event, revenue, and ticket performance overview.</p>
            </div>
            <div class="rounded-2xl border border-[#2a2a2e] bg-surface px-5 py-4 text-right shadow-soft">
                <p class="text-sm text-secondary">Today</p>
                <p class="pt-1 text-lg text-white"><?= esc(date('F d, Y')) ?></p>
            </div>
        </div>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <?php foreach ($summary_cards as $card): ?>
                <article class="rounded-3xl border border-[#2a2a2e] bg-surface p-6 shadow-soft">
                    <p class="text-sm text-secondary"><?= esc($card['label']) ?></p>
                    <h2 class="pt-4 text-3xl font-semibold text-yellow-300"><?= esc((string) $card['value']) ?></h2>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="mt-8 grid gap-6 xl:grid-cols-2">
            <article class="rounded-3xl border border-[#2a2a2e] bg-surface p-6 shadow-soft">
                <div class="mb-5">
                    <h3>Event Creation Trend</h3>
                    <p class="pt-2 text-sm text-secondary">Bar graph of event creation volume over time.</p>
                </div>
                <div class="rounded-2xl bg-[#111117] p-4">
                    <svg id="eventCreationChart" viewBox="0 0 760 320" class="h-80 w-full" role="img"
                        aria-label="Event creation trend chart"></svg>
                </div>
            </article>

            <article class="rounded-3xl border border-[#2a2a2e] bg-surface p-6 shadow-soft">
                <div class="mb-5">
                    <h3>Total Tickets Sold</h3>
                    <p class="pt-2 text-sm text-secondary">Bar graph of sold tickets per event.</p>
                </div>
                <div class="rounded-2xl bg-[#111117] p-4">
                    <svg id="ticketsSoldChart" viewBox="0 0 760 320" class="h-80 w-full" role="img"
                        aria-label="Total tickets sold chart"></svg>
                </div>
            </article>
        </section>

        <section class="mt-8 rounded-3xl bg-surface p-8 outline outline-[#2a2a2e] shadow-soft">
            <div class="mb-6">
                <h3>Top Performing Events By Tickets Sold</h3>
                <p class="pt-2 text-sm text-secondary">Events ranked by sold ticket volume across the platform.</p>
            </div>

            <div class="table-scroll rounded-2xl border border-[#2a2a2e]">
                <div class="grid gap-4 border-b border-[#2a2a2e] bg-[#151419] px-6 py-4 text-sm text-secondary"
                    style="grid-template-columns: 80px minmax(240px, 1.4fr) minmax(180px, 1fr) minmax(140px, 0.8fr) minmax(160px, 0.9fr);">
                    <div>Rank</div>
                    <div>Event</div>
                    <div>Organizer</div>
                    <div>Tickets Sold</div>
                    <div>Revenue</div>
                </div>

                <?php if ($top_performing_events): ?>
                    <div class="divide-y divide-[#2a2a2e] bg-surface">
                        <?php foreach ($top_performing_events as $index => $event): ?>
                            <div class="grid items-center gap-4 px-6 py-5 text-sm text-white"
                                style="grid-template-columns: 80px minmax(240px, 1.4fr) minmax(180px, 1fr) minmax(140px, 0.8fr) minmax(160px, 0.9fr);">
                                <div>#<?= esc((string) ($index + 1)) ?></div>
                                <div><?= esc($event['title']) ?></div>
                                <div><?= esc($event['organizer_name']) ?></div>
                                <div><?= esc((string) $event['tickets_sold']) ?></div>
                                <div>PHP <?= esc(number_format((float) $event['revenue'], 2)) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-surface px-6 py-12 text-center text-secondary">
                        No event sales data found.
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <script>
        const eventCreationData = <?= json_encode($event_creation_chart, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
        const ticketsSoldData = <?= json_encode($tickets_sold_chart, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderEmptyChart(svg, message) {
            svg.innerHTML = '<rect x="0" y="0" width="100%" height="100%" rx="18" fill="#111117"></rect>' +
                '<text x="50%" y="50%" fill="#7b7b85" text-anchor="middle" dominant-baseline="middle" font-size="16">' +
                escapeHtml(message) + '</text>';
        }

        function renderBarChart(svgId, inputData, options) {
            const svg = document.getElementById(svgId);
            if (!svg) {
                return;
            }

            const data = inputData.filter(function (item) {
                return Number(item.value) > 0;
            }).slice(0, options.limit || inputData.length);

            if (!data.length) {
                renderEmptyChart(svg, options.emptyMessage);
                return;
            }

            const width = 760;
            const height = 320;
            const padding = { top: 20, right: 24, bottom: 84, left: 56 };
            const innerWidth = width - padding.left - padding.right;
            const innerHeight = height - padding.top - padding.bottom;
            const maxValue = Math.max.apply(null, data.map(function (item) { return Number(item.value); }));
            const denominator = maxValue === 0 ? 1 : maxValue;
            const gap = 18;
            const barWidth = Math.max(24, (innerWidth - (gap * (data.length - 1))) / data.length);

            let html = '';
            html += '<rect x="0" y="0" width="' + width + '" height="' + height + '" rx="18" fill="#111117"></rect>';

            for (let i = 0; i <= 4; i++) {
                const y = padding.top + (innerHeight * i / 4);
                const value = Math.round(denominator - ((denominator * i) / 4));
                html += '<line x1="' + padding.left + '" y1="' + y + '" x2="' + (width - padding.right) + '" y2="' + y + '" stroke="#2a2a2e" stroke-width="1"></line>';
                html += '<text x="' + (padding.left - 12) + '" y="' + (y + 5) + '" fill="#8c8c96" font-size="12" text-anchor="end">' + value + '</text>';
            }

            html += '<line x1="' + padding.left + '" y1="' + padding.top + '" x2="' + padding.left + '" y2="' + (height - padding.bottom) + '" stroke="#3a3a42" stroke-width="1.5"></line>';
            html += '<line x1="' + padding.left + '" y1="' + (height - padding.bottom) + '" x2="' + (width - padding.right) + '" y2="' + (height - padding.bottom) + '" stroke="#3a3a42" stroke-width="1.5"></line>';

            data.forEach(function (item, index) {
                const value = Number(item.value);
                const barHeight = (value / denominator) * innerHeight;
                const x = padding.left + (index * (barWidth + gap));
                const y = padding.top + innerHeight - barHeight;
                const label = String(item.label || '');
                const shortLabel = label.length > 14 ? label.slice(0, 12) + '..' : label;

                html += '<rect x="' + x + '" y="' + y + '" width="' + barWidth + '" height="' + barHeight + '" rx="10" fill="' + options.barColor + '"></rect>';
                html += '<text x="' + (x + (barWidth / 2)) + '" y="' + (y - 8) + '" fill="#ffffff" font-size="12" text-anchor="middle">' + value + '</text>';
                html += '<text x="' + (x + (barWidth / 2)) + '" y="' + (height - padding.bottom + 22) + '" fill="#b7b7c2" font-size="11" text-anchor="middle">' + escapeHtml(shortLabel) + '</text>';
            });

            svg.innerHTML = html;
        }

        renderBarChart('eventCreationChart', eventCreationData, {
            barColor: '#facc15',
            emptyMessage: 'No event creation data yet',
            limit: 12
        });

        renderBarChart('ticketsSoldChart', ticketsSoldData, {
            barColor: '#38bdf8',
            emptyMessage: 'No ticket sales data yet',
            limit: 8
        });
    </script>
</body>

</html>