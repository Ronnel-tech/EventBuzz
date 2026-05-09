<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Ticket Details</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<?php
$display_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
if ($display_name === '') {
    $display_name = (string) ($user['email'] ?? 'Attendee');
}

$to_data_url = static function (string $path_or_url): ?string {
    if ($path_or_url === '') {
        return null;
    }

    $mime = 'image/png';
    $binary = false;

    if (str_starts_with($path_or_url, '/')) {
        $absolute_path = APP_ROOT . $path_or_url;

        if (!is_file($absolute_path)) {
            return null;
        }

        $extension = strtolower((string) pathinfo($absolute_path, PATHINFO_EXTENSION));
        $mime = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/png',
        };

        $binary = @file_get_contents($absolute_path);
    } else {
        $binary = @file_get_contents($path_or_url);
    }

    if ($binary === false || $binary === '') {
        return null;
    }

    return 'data:' . $mime . ';base64,' . base64_encode($binary);
};

$banner_image = (string) ($ticket_order['banner_image'] ?: '/public/assets/images/logo.png');
$qr_image_url = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=' . rawurlencode($organizer_lookup_url);
$qr_image_source = $to_data_url($qr_image_url) ?: $qr_image_url;
$banner_image_source = $to_data_url($banner_image) ?: $banner_image;

$location_parts = array_filter([
    trim((string) ($ticket_order['street'] ?? '')),
    trim((string) ($ticket_order['city'] ?? '')),
    trim((string) ($ticket_order['province'] ?? '')),
    trim((string) ($ticket_order['country'] ?? '')),
], static fn($value) => $value !== '');
$location_text = implode(', ', $location_parts);

$ticket_items_for_export = array_map(
    static fn(array $ticket_item): array => [
        'name' => (string) $ticket_item['ticket_name'],
        'quantity' => (int) $ticket_item['quantity'],
        'subtotal' => 'PHP ' . number_format((float) $ticket_item['subtotal'], 2),
    ],
    $ticket_order['ticket_items']
);

$ticket_export_data = [
    'eventTitle' => (string) $ticket_order['event_title'],
    'paymentStatus' => $ticket_order['payment_status'] === 'done' ? 'Paid' : 'Pending',
    'eventDate' => date('F d, Y h:i A', strtotime($ticket_order['start_datetime'])),
    'orderDate' => date('F d, Y h:i A', strtotime($ticket_order['created_at'])),
    'ticketsBought' => (string) $ticket_order['tickets_bought'],
    'paymentMethod' => ucfirst((string) $ticket_order['payment_method']),
    'totalAmount' => 'PHP ' . number_format((float) $ticket_order['total_amount'], 2),
    'referenceNumber' => $ticket_order['gcash_reference'] !== '' ? (string) $ticket_order['gcash_reference'] : 'N/A',
    'location' => $location_text,
    'orderId' => (string) $ticket_order['id'],
    'ticketCode' => (string) ($ticket_order['primary_ticket_code'] ?: 'Unavailable'),
    'bannerImage' => $banner_image_source,
    'qrImage' => $qr_image_source,
    'ticketItems' => $ticket_items_for_export,
];
?>

<body class="public-page bg-[#151419]">
    <nav class="page-nav">
        <div class="flex items-center gap-3">
            <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
            <h4>EventBuzz</h4>
        </div>

        <div class="page-nav-links justify-end">
            <a href="<?= url('/attendee/ticket') ?>" class="text-secondary transition hover:text-white">Back to
                Tickets</a>
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

    <div class="mx-auto max-w-6xl">
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

        <div id="ticketDetailCard"
            class="overflow-hidden rounded-4xl border border-dashed border-yellow-400/35 bg-surface shadow-soft lg:grid lg:grid-cols-[1.1fr_0.9fr]">
            <div class="p-8 lg:p-10">
                <div class="mb-8 flex items-start justify-between gap-4 border-b border-[#2a2a2e] pb-6">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-yellow-300/80">EventBuzz Ticket</p>
                        <h2 class="pt-3 text-3xl font-semibold text-white"><?= esc($ticket_order['event_title']) ?></h2>
                    </div>
                    <span
                        class="inline-flex rounded-full px-4 py-2 text-sm <?= $ticket_order['payment_status'] === 'done' ? 'bg-green-500/10 text-green-300' : 'bg-yellow-500/10 text-yellow-300' ?>">
                        <?= esc($ticket_order['payment_status'] === 'done' ? 'Paid' : 'Pending') ?>
                    </span>
                </div>

                <div class="mb-8 overflow-hidden rounded-3xl">
                    <img src="<?= esc($banner_image_source) ?>"
                        alt="<?= esc($ticket_order['event_title']) ?>" class="h-56 w-full object-cover">
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <p class="text-sm text-secondary">Event Date</p>
                        <p class="pt-1 text-white">
                            <?= esc(date('F d, Y h:i A', strtotime($ticket_order['start_datetime']))) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-secondary">Order Date</p>
                        <p class="pt-1 text-white">
                            <?= esc(date('F d, Y h:i A', strtotime($ticket_order['created_at']))) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-secondary">Tickets Bought</p>
                        <p class="pt-1 text-white"><?= esc((string) $ticket_order['tickets_bought']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-secondary">Payment Method</p>
                        <p class="pt-1 text-white"><?= esc(ucfirst((string) $ticket_order['payment_method'])) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-secondary">Total Amount</p>
                        <p class="pt-1 text-white">PHP
                            <?= esc(number_format((float) $ticket_order['total_amount'], 2)) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-secondary">Reference Number</p>
                        <p class="pt-1 text-white">
                            <?= esc($ticket_order['gcash_reference'] !== '' ? $ticket_order['gcash_reference'] : 'N/A') ?>
                        </p>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-sm text-secondary">Location</p>
                    <p class="pt-1 text-white"><?= esc($location_text) ?></p>
                </div>

                <div class="mt-8">
                    <p class="mb-4 text-sm text-secondary">Purchase Details</p>
                    <div class="space-y-3">
                        <?php foreach ($ticket_order['ticket_items'] as $ticket_item): ?>
                            <div class="flex items-center justify-between rounded-2xl bg-[#151419] px-5 py-4">
                                <div>
                                    <p class="text-white"><?= esc($ticket_item['ticket_name']) ?></p>
                                    <p class="pt-1 text-sm text-secondary">Quantity:
                                        <?= esc((string) $ticket_item['quantity']) ?></p>
                                </div>
                                <div class="text-right text-white">
                                    PHP <?= esc(number_format((float) $ticket_item['subtotal'], 2)) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="border-t border-dashed border-yellow-400/25 bg-[#111117] p-8 lg:border-l lg:border-t-0 lg:p-10">
                <div class="flex h-full flex-col items-center justify-center text-center">
                    <button type="button" id="downloadTicketPng"
                        class="mb-6 inline-flex items-center rounded-full bg-yellow-300 px-5 py-2 text-sm font-medium text-[#111117] transition hover:bg-yellow-200">
                        Download Ticket PNG
                    </button>
                    <p class="text-sm uppercase tracking-[0.25em] text-yellow-300/75">Scan For Organizer Lookup</p>
                    <div class="mt-6 rounded-4xl bg-white p-5 shadow-soft">
                        <img src="<?= esc($qr_image_source) ?>" alt="Ticket QR Code" class="h-64 w-64 object-contain">
                    </div>
                    <p class="mt-6 text-sm text-secondary">Order #<?= esc((string) $ticket_order['id']) ?></p>
                    <p class="pt-2 text-sm text-secondary">Ticket Code:
                        <?= esc((string) ($ticket_order['primary_ticket_code'] ?: 'Unavailable')) ?></p>
                    <p class="mt-6 max-w-xs text-sm text-secondary">
                        When the organizer scans this QR code, it opens the payment verification page for this exact
                        purchase.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ticketExportData = <?= json_encode($ticket_export_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

        const wrapCanvasText = (context, text, maxWidth) => {
            const words = String(text || '').split(/\s+/).filter(Boolean);
            const lines = [];
            let currentLine = '';

            words.forEach((word) => {
                const candidate = currentLine ? `${currentLine} ${word}` : word;

                if (context.measureText(candidate).width <= maxWidth || currentLine === '') {
                    currentLine = candidate;
                    return;
                }

                lines.push(currentLine);
                currentLine = word;
            });

            if (currentLine) {
                lines.push(currentLine);
            }

            return lines;
        };

        const loadCanvasImage = (source) => new Promise((resolve, reject) => {
            if (!source) {
                resolve(null);
                return;
            }

            const image = new Image();
            image.onload = () => resolve(image);
            image.onerror = reject;
            image.src = source;
        });

        const drawLabelValue = (context, label, value, x, y) => {
            context.font = '16px Arial, sans-serif';
            context.fillStyle = '#8d8d99';
            context.fillText(label, x, y);
            context.font = '20px Arial, sans-serif';
            context.fillStyle = '#ffffff';
            context.fillText(value, x, y + 30);
        };

        document.getElementById('downloadTicketPng')?.addEventListener('click', async () => {
            const button = document.getElementById('downloadTicketPng');
            const originalLabel = button.textContent;
            button.disabled = true;
            button.textContent = 'Preparing PNG...';

            try {
                const canvas = document.createElement('canvas');
                canvas.width = 1080;
                canvas.height = 1800;

                const context = canvas.getContext('2d');
                const [bannerImage, qrImage] = await Promise.all([
                    loadCanvasImage(ticketExportData.bannerImage).catch(() => null),
                    loadCanvasImage(ticketExportData.qrImage),
                ]);

                context.fillStyle = '#151419';
                context.fillRect(0, 0, canvas.width, canvas.height);

                context.fillStyle = '#1d1c23';
                context.strokeStyle = 'rgba(250, 204, 21, 0.35)';
                context.lineWidth = 3;
                context.setLineDash([12, 10]);
                context.beginPath();
                context.roundRect(50, 50, 980, 1700, 36);
                context.fill();
                context.stroke();
                context.setLineDash([]);

                context.fillStyle = '#111117';
                context.beginPath();
                context.roundRect(50, 1090, 980, 660, 36);
                context.fill();

                context.strokeStyle = 'rgba(250, 204, 21, 0.25)';
                context.lineWidth = 2;
                context.setLineDash([10, 8]);
                context.beginPath();
                context.moveTo(50, 1090);
                context.lineTo(1030, 1090);
                context.stroke();
                context.setLineDash([]);

                context.fillStyle = '#fde68a';
                context.font = '20px Arial, sans-serif';
                context.fillText('EVENTBUZZ TICKET', 100, 120);

                context.fillStyle = '#ffffff';
                context.font = 'bold 46px Arial, sans-serif';
                const titleLines = wrapCanvasText(context, ticketExportData.eventTitle, 720);
                titleLines.slice(0, 3).forEach((line, index) => {
                    context.fillText(line, 100, 180 + (index * 52));
                });

                context.fillStyle = ticketExportData.paymentStatus === 'Paid' ? '#86efac' : '#fde68a';
                context.font = 'bold 28px Arial, sans-serif';
                context.fillText(ticketExportData.paymentStatus, 760, 122);

                if (bannerImage) {
                    context.save();
                    context.beginPath();
                    context.roundRect(100, 310, 880, 260, 28);
                    context.clip();
                    context.drawImage(bannerImage, 100, 310, 880, 260);
                    context.restore();
                }

                drawLabelValue(context, 'Event Date', ticketExportData.eventDate, 100, 645);
                drawLabelValue(context, 'Order Date', ticketExportData.orderDate, 560, 645);
                drawLabelValue(context, 'Tickets Bought', ticketExportData.ticketsBought, 100, 745);
                drawLabelValue(context, 'Payment Method', ticketExportData.paymentMethod, 560, 745);
                drawLabelValue(context, 'Total Amount', ticketExportData.totalAmount, 100, 845);
                drawLabelValue(context, 'Reference Number', ticketExportData.referenceNumber, 560, 845);

                context.font = '16px Arial, sans-serif';
                context.fillStyle = '#8d8d99';
                context.fillText('Location', 100, 945);
                context.font = '20px Arial, sans-serif';
                context.fillStyle = '#ffffff';
                wrapCanvasText(context, ticketExportData.location, 860).slice(0, 3).forEach((line, index) => {
                    context.fillText(line, 100, 975 + (index * 28));
                });

                context.font = '16px Arial, sans-serif';
                context.fillStyle = '#8d8d99';
                context.fillText('Purchase Details', 100, 1065);

                let ticketItemY = 1015;
                ticketExportData.ticketItems.slice(0, 4).forEach((item) => {
                    context.fillStyle = '#151419';
                    context.beginPath();
                    context.roundRect(330, ticketItemY, 650, 64, 18);
                    context.fill();

                    context.font = '18px Arial, sans-serif';
                    context.fillStyle = '#ffffff';
                    context.fillText(item.name, 350, ticketItemY + 28);
                    context.font = '14px Arial, sans-serif';
                    context.fillStyle = '#8d8d99';
                    context.fillText(`Qty: ${item.quantity}`, 350, ticketItemY + 48);
                    context.fillStyle = '#ffffff';
                    context.fillText(item.subtotal, 780, ticketItemY + 48);
                    ticketItemY -= 78;
                });

                context.fillStyle = '#fde68a';
                context.font = '18px Arial, sans-serif';
                context.fillText('SCAN FOR ORGANIZER LOOKUP', 320, 1165);

                context.fillStyle = '#ffffff';
                context.beginPath();
                context.roundRect(280, 1205, 520, 520, 32);
                context.fill();
                context.drawImage(qrImage, 330, 1255, 420, 420);

                context.font = '18px Arial, sans-serif';
                context.fillStyle = '#8d8d99';
                context.fillText(`Order #${ticketExportData.orderId}`, 350, 1740);
                context.fillText(`Ticket Code: ${ticketExportData.ticketCode}`, 350, 1770);

                context.font = '16px Arial, sans-serif';
                wrapCanvasText(
                    context,
                    'When the organizer scans this QR code, it opens the payment verification page for this exact purchase.',
                    180,
                ).forEach((line, index) => {
                    context.fillText(line, 835, 1310 + (index * 28));
                });

                const link = document.createElement('a');
                const safeTitle = ticketExportData.eventTitle.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
                link.href = canvas.toDataURL('image/png');
                link.download = `${safeTitle || 'eventbuzz-ticket'}-${ticketExportData.orderId}.png`;
                link.click();
            } catch (error) {
                window.alert('We could not generate the ticket PNG right now. Please try again.');
            } finally {
                button.disabled = false;
                button.textContent = originalLabel;
            }
        });
    </script>
</body>

</html>
