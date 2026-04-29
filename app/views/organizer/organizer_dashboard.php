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
$display_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
if ($display_name === '') {
    $display_name = (string) ($user['email'] ?? 'Organizer');
}

$summary_cards = [
    [
        'label' => 'Total Events Created',
        'value' => (int) ($dashboard_summary['total_events_created'] ?? 0),
    ],
    [
        'label' => 'Total Revenue Earned',
        'value' => 'PHP ' . number_format((float) ($dashboard_summary['total_revenue_earned'] ?? 0), 2),
    ],
    [
        'label' => 'Total Tickets Sold',
        'value' => (int) ($dashboard_summary['total_tickets_sold'] ?? 0),
    ],
    [
        'label' => 'Upcoming Events',
        'value' => (int) ($dashboard_summary['upcoming_events'] ?? 0),
    ],
];

$today_ticket_sales_chart = [];
foreach ($today_ticket_sales as $row) {
    $today_ticket_sales_chart[] = [
        'label' => (string) ($row['title'] ?? 'Event'),
        'value' => (int) ($row['tickets_sold_today'] ?? 0),
    ];
}

$revenue_over_time_chart = [];
foreach ($revenue_over_time as $row) {
    $revenue_over_time_chart[] = [
        'label' => date('M d', strtotime((string) $row['revenue_date'])),
        'value' => (float) ($row['revenue_amount'] ?? 0),
    ];
}

$sales_distribution_chart = [];
foreach ($sales_distribution as $row) {
    $sales_distribution_chart[] = [
        'label' => ucfirst((string) ($row['payment_method'] ?? 'Unknown')),
        'value' => (int) ($row['tickets_sold'] ?? 0),
    ];
}
?>
<body class="min-h-screen bg-[#151419] text-white">
<div class="flex min-h-screen">
    <aside class="fixed left-0 top-0 flex h-screen w-24 flex-col items-center justify-between bg-surface p-5 shadow-soft">
        <img src="/public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">

        <div class="-mt-120 flex flex-col gap-5">
            <a href="<?= url('/organizer/dashboard') ?>">
                <button><svg class="icon-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g><path fill-opacity="0.16" d="M18.6 3H5.4A2.4 2.4 0 0 0 3 5.4v13.2A2.4 2.4 0 0 0 5.4 21h13.2a2.4 2.4 0 0 0 2.4-2.4V5.4A2.4 2.4 0 0 0 18.6 3"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M12 21V3m0 7h9M5.4 3h13.2A2.4 2.4 0 0 1 21 5.4v13.2a2.4 2.4 0 0 1-2.4 2.4H5.4A2.4 2.4 0 0 1 3 18.6V5.4A2.4 2.4 0 0 1 5.4 3"/></g></svg></button>
            </a>
            <a href="<?= url('/organizer/events') ?>">
                <button><svg class="icon-disabled" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 17V8H7v9zm0-14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1V1h2v2h8V1h2v2zM3 21h14v2H3a2 2 0 0 1-2-2V9h2zm16-6h-4v-4h4z"/></svg></button>
            </a>
        </div>

        <form method="POST" action="<?= url('logout') ?>">
            <button><svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg></button>
        </form>
    </aside>

    <main class="ml-24 flex-1 p-8">
        <div class="mb-8 flex items-end justify-between gap-6">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-yellow-300/80">Organizer Analytics</p>
                <h1 class="pt-3 text-4xl font-semibold">Dashboard</h1>
                <p class="pt-3 text-secondary">Performance summary for <?= esc($display_name) ?> and all organizer-owned events.</p>
            </div>
            <div class="rounded-2xl border border-[#2a2a2e] bg-surface px-5 py-4 text-right shadow-soft">
                <p class="text-sm text-secondary">Today</p>
                <p class="pt-1 text-lg"><?= esc(date('F d, Y')) ?></p>
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
                    <h3>Sold Tickets Today Per Event</h3>
                    <p class="pt-2 text-sm text-secondary">Line graph of today&apos;s completed ticket sales for each event.</p>
                </div>
                <div class="rounded-2xl bg-[#111117] p-4">
                    <svg id="ticketsTodayChart" viewBox="0 0 760 320" class="h-80 w-full" role="img" aria-label="Sold tickets today per event chart"></svg>
                </div>
            </article>

            <article class="rounded-3xl border border-[#2a2a2e] bg-surface p-6 shadow-soft">
                <div class="mb-5">
                    <h3>Revenue Over Time</h3>
                    <p class="pt-2 text-sm text-secondary">Line graph of completed order revenue by transaction date.</p>
                </div>
                <div class="rounded-2xl bg-[#111117] p-4">
                    <svg id="revenueChart" viewBox="0 0 760 320" class="h-80 w-full" role="img" aria-label="Revenue over time chart"></svg>
                </div>
            </article>
        </section>

        <section class="mt-8 grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <article class="rounded-3xl border border-[#2a2a2e] bg-surface p-6 shadow-soft">
                <div class="mb-5">
                    <h3>Sales Distribution</h3>
                    <p class="pt-2 text-sm text-secondary">Completed ticket sales split by payment method.</p>
                </div>
                <div class="grid items-center gap-6 lg:grid-cols-[320px_1fr]">
                    <div class="rounded-2xl bg-[#111117] p-4">
                        <svg id="salesDistributionChart" viewBox="0 0 320 320" class="h-80 w-full" role="img" aria-label="Sales distribution pie chart"></svg>
                    </div>
                    <div id="salesDistributionLegend" class="space-y-3"></div>
                </div>
            </article>

            <article class="rounded-3xl border border-[#2a2a2e] bg-surface p-6 shadow-soft">
                <div class="mb-5">
                    <h3>Snapshot</h3>
                    <p class="pt-2 text-sm text-secondary">Quick reading of the current organizer activity levels.</p>
                </div>
                <div class="space-y-4">
                    <div class="rounded-2xl bg-[#111117] p-5">
                        <p class="text-sm text-secondary">Total created events</p>
                        <p class="pt-2 text-2xl text-white"><?= esc((string) ((int) ($dashboard_summary['total_events_created'] ?? 0))) ?></p>
                    </div>
                    <div class="rounded-2xl bg-[#111117] p-5">
                        <p class="text-sm text-secondary">Revenue earned from completed payments</p>
                        <p class="pt-2 text-2xl text-white">PHP <?= esc(number_format((float) ($dashboard_summary['total_revenue_earned'] ?? 0), 2)) ?></p>
                    </div>
                    <div class="rounded-2xl bg-[#111117] p-5">
                        <p class="text-sm text-secondary">Upcoming events scheduled</p>
                        <p class="pt-2 text-2xl text-white"><?= esc((string) ((int) ($dashboard_summary['upcoming_events'] ?? 0))) ?></p>
                    </div>
                </div>
            </article>
        </section>
    </main>
</div>

<script>
const ticketsTodayData = <?= json_encode($today_ticket_sales_chart, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
const revenueOverTimeData = <?= json_encode($revenue_over_time_chart, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
const salesDistributionData = <?= json_encode($sales_distribution_chart, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

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

function renderLineChart(svgId, inputData, options) {
    const svg = document.getElementById(svgId);
    if (!svg) {
        return;
    }

    const data = inputData.filter(function (item) {
        return Number(item.value) > 0;
    });

    if (!data.length) {
        renderEmptyChart(svg, options.emptyMessage);
        return;
    }

    const width = 760;
    const height = 320;
    const padding = { top: 20, right: 24, bottom: 64, left: 56 };
    const innerWidth = width - padding.left - padding.right;
    const innerHeight = height - padding.top - padding.bottom;
    const maxValue = Math.max.apply(null, data.map(function (item) { return Number(item.value); }));
    const denominator = maxValue === 0 ? 1 : maxValue;

    const points = data.map(function (item, index) {
        const x = padding.left + (data.length === 1 ? innerWidth / 2 : (innerWidth * index / (data.length - 1)));
        const y = padding.top + innerHeight - ((Number(item.value) / denominator) * innerHeight);
        return {
            label: item.label,
            value: Number(item.value),
            x: x,
            y: y
        };
    });

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

    const polylinePoints = points.map(function (point) {
        return point.x + ',' + point.y;
    }).join(' ');

    html += '<polyline fill="none" stroke="' + options.stroke + '" stroke-width="4" points="' + polylinePoints + '"></polyline>';

    points.forEach(function (point) {
        html += '<circle cx="' + point.x + '" cy="' + point.y + '" r="5" fill="' + options.stroke + '"></circle>';
        html += '<text x="' + point.x + '" y="' + (height - padding.bottom + 24) + '" fill="#b7b7c2" font-size="12" text-anchor="middle">' + escapeHtml(point.label) + '</text>';
        html += '<text x="' + point.x + '" y="' + (point.y - 12) + '" fill="#ffffff" font-size="12" text-anchor="middle">' + point.value + '</text>';
    });

    svg.innerHTML = html;
}

function polarToCartesian(centerX, centerY, radius, angleInDegrees) {
    const angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;
    return {
        x: centerX + (radius * Math.cos(angleInRadians)),
        y: centerY + (radius * Math.sin(angleInRadians))
    };
}

function describeArc(x, y, radius, startAngle, endAngle) {
    const start = polarToCartesian(x, y, radius, endAngle);
    const end = polarToCartesian(x, y, radius, startAngle);
    const largeArcFlag = endAngle - startAngle <= 180 ? '0' : '1';

    return [
        'M', x, y,
        'L', start.x, start.y,
        'A', radius, radius, 0, largeArcFlag, 0, end.x, end.y,
        'Z'
    ].join(' ');
}

function renderPieChart(svgId, legendId, inputData) {
    const svg = document.getElementById(svgId);
    const legend = document.getElementById(legendId);
    if (!svg || !legend) {
        return;
    }

    const data = inputData.filter(function (item) {
        return Number(item.value) > 0;
    });

    if (!data.length) {
        renderEmptyChart(svg, 'No completed sales yet');
        legend.innerHTML = '<p class="text-sm text-secondary">No sales distribution data available yet.</p>';
        return;
    }

    const colors = ['#facc15', '#38bdf8', '#34d399', '#fb7185', '#a78bfa', '#f97316'];
    const total = data.reduce(function (sum, item) { return sum + Number(item.value); }, 0);
    let startAngle = 0;
    let html = '<rect x="0" y="0" width="320" height="320" rx="18" fill="#111117"></rect>';

    data.forEach(function (item, index) {
        const angle = (Number(item.value) / total) * 360;
        const endAngle = startAngle + angle;
        const color = colors[index % colors.length];
        html += '<path d="' + describeArc(160, 160, 110, startAngle, endAngle) + '" fill="' + color + '"></path>';
        startAngle = endAngle;
    });

    html += '<circle cx="160" cy="160" r="54" fill="#111117"></circle>';
    html += '<text x="160" y="150" fill="#8c8c96" font-size="13" text-anchor="middle">Tickets Sold</text>';
    html += '<text x="160" y="176" fill="#ffffff" font-size="26" font-weight="600" text-anchor="middle">' + total + '</text>';
    svg.innerHTML = html;

    legend.innerHTML = data.map(function (item, index) {
        const color = colors[index % colors.length];
        const percent = ((Number(item.value) / total) * 100).toFixed(1);
        return '<div class="flex items-center justify-between rounded-2xl bg-[#111117] px-4 py-3">' +
            '<div class="flex items-center gap-3">' +
            '<span class="h-3 w-3 rounded-full" style="background:' + color + '"></span>' +
            '<span>' + escapeHtml(item.label) + '</span>' +
            '</div>' +
            '<div class="text-right">' +
            '<div class="text-white">' + item.value + '</div>' +
            '<div class="text-xs text-secondary">' + percent + '%</div>' +
            '</div>' +
            '</div>';
    }).join('');
}

renderLineChart('ticketsTodayChart', ticketsTodayData, {
    stroke: '#facc15',
    emptyMessage: 'No completed ticket sales recorded today'
});

renderLineChart('revenueChart', revenueOverTimeData, {
    stroke: '#38bdf8',
    emptyMessage: 'No completed revenue history yet'
});

renderPieChart('salesDistributionChart', 'salesDistributionLegend', salesDistributionData);
</script>
</body>
</html>
