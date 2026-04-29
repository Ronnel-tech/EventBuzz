<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Home</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>


<?php
$display_name = trim((string) (($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')));
if ($display_name === '') {
    $display_name = (string) ($user['email'] ?? 'Attendee');
}

$first_name = trim((string) ($user['first_name'] ?? ''));
if ($first_name === '') {
    $first_name = $display_name;
}

$search_value = trim((string) ($search ?? ''));

$hero_image = '/public/assets/images/logo.png';
if (!empty($today_events[0]['banner_image'])) {
    $hero_image = (string) $today_events[0]['banner_image'];
}

if (!function_exists('format_attendee_price')) {
    function format_attendee_price($starting_price, string $payment_type): string
    {
        if ($starting_price !== null && $starting_price !== '') {
            return 'PHP ' . number_format((float) $starting_price, 2);
        }

        if ($payment_type === 'free') {
            return 'Free';
        }

        return 'Price unavailable';
    }
}
?>

<body class="p-15 w-full min-h-screen">
    
    <nav class="bg-surface flex justify-between pl-5 pr-5 card rounded-full sticky top-5  z-11  shadow-soft p-4 bg-surface-hover ">

        <div class="flex items-center">
            <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
            <h4>EventBuzz</h4>
        </div>

        <div class="flex items-center gap-5">

        <form method="GET" action="<?= url('/attendee') ?>" class="flex items-center gap-2">
            <input
                type="text"
                name="search"
                value="<?= esc($search_value) ?>"
                placeholder="Search events..."
                class="bg-surface text-secondary px-4 py-2 rounded-full min-w-64"
            >
            <button type="submit" class="rounded-full bg-yellow-400 px-4 py-2 text-sm font-semibold text-black transition hover:bg-yellow-300">
                Search
            </button>
            <?php if ($search_value !== ''): ?>
                <a href="<?= url('/attendee') ?>" class="rounded-full border border-[#2a2a2e] px-4 py-2 text-sm text-secondary transition hover:text-white">
                    Clear
                </a>
            <?php endif; ?>
        </form>
            
            <a href="<?= url('/attendee/ticket') ?>">
                <button class="flex justify-center align-center"><svg class="icon-primary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M3 10h-.75c0 .414.336.75.75.75zm0 4v-.75a.75.75 0 0 0-.75.75zm18-4v.75a.75.75 0 0 0 .75-.75zm0 4h.75a.75.75 0 0 0-.75-.75zM5 5.75h5v-1.5H5zm5 0h9v-1.5h-9zm9 12.5h-9v1.5h9zm-9 0H5v1.5h5zM9.25 5v14h1.5V5zm-5.366 6.116a1.25 1.25 0 0 1 0 1.768l1.06 1.06a2.75 2.75 0 0 0 0-3.889zm16.232 1.768a1.25 1.25 0 0 1 0-1.768l-1.06-1.06a2.75 2.75 0 0 0 0 3.889zM3 10.75c.321 0 .64.122.884.366l1.06-1.06A2.74 2.74 0 0 0 3 9.25zm.75-.75V7h-1.5v3zm0 7v-3h-1.5v3zm.134-4.116A1.24 1.24 0 0 1 3 13.25v1.5c.703 0 1.408-.269 1.945-.806zm16.232-1.768c.244-.244.563-.366.884-.366v-1.5c-.703 0-1.408.269-1.945.806zM20.25 7v3h1.5V7zm0 7v3h1.5v-3zm.75-.75c-.321 0-.64-.122-.884-.366l-1.06 1.06A2.74 2.74 0 0 0 21 14.75zm-16 5c-.69 0-1.25-.56-1.25-1.25h-1.5A2.75 2.75 0 0 0 5 19.75zm14 1.5A2.75 2.75 0 0 0 21.75 17h-1.5c0 .69-.56 1.25-1.25 1.25zm0-14c.69 0 1.25.56 1.25 1.25h1.5A2.75 2.75 0 0 0 19 4.25zM5 4.25A2.75 2.75 0 0 0 2.25 7h1.5c0-.69.56-1.25 1.25-1.25z"/></svg></button>
            </a>

            <h4 class="pr-5"><?= esc($display_name) ?></h4>

            <h3>|</h3>

            <form class="flex justify-center align-center" method="POST" action="<?= url('logout') ?>"> 
            <button ><svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path  fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg></button>
            </form>
        </div> 
    </nav>

<div class="relative mb-1 min-h-[70vh] overflow-hidden rounded-4xl pt-30">

    <!-- Blurred background -->
    <div class="absolute inset-0 bg-cover bg-center blur-3xl" style="background-image: url('<?= esc($hero_image) ?>');"></div>
    <div class="absolute inset-0 bg-black/45"></div>
    
    <!-- Content (NOT blurred) -->
<div class="relative z-10 p-5 flex flex-col items-center justify-center h-full text-center pt-30">
    
    <nav>
        <h1 class="text-7xl">Welcome, <?= esc($first_name) ?></h1>
    </nav>

    <h2>
        Find what is happening today and browse upcoming events by category
    </h2>


    <section class="bg-white h-2 w-full flex justify-center items-center mt-30">
    <a href="#events-list" class="btn btn-primary rounded-full z-1 px-20">Browse Events</a>
    </section>

</div>



</div>

<section class="overflow-hidden whitespace-nowrap bg-amber-50 p-7">
  <div class="marquee text-4xl font-bold text-primary  bg-surface">
    <div class="marquee-group" aria-hidden="true">
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
      <span>EVENTBUZZ</span>
    </div>
  </div>
</section>

    <div id="events-list" class="w-full bg-surface px-6 py-12 rounded-b-4xl">
        <?php if ($search_value !== ''): ?>
            <div class="mb-8 rounded-3xl bg-[#1c2029] p-5 text-white outline outline-[#2a2a2e]">
                Showing results for "<span class="text-yellow-300"><?= esc($search_value) ?></span>".
            </div>
        <?php endif; ?>

        <section class="mb-12">
            <div class="mb-6 flex items-end justify-between">
                <div>
                    <h3>Happening Today</h3>
                    <p>Events scheduled for today.</p>
                </div>
            </div>

            <?php if ($today_events): ?>
                <div class="grid gap-6 md:grid-cols-3 xl:grid-cols-5">
                    <?php foreach ($today_events as $event): ?>
                        <a href="<?= url('/attendee/detailed_event?id=' . $event['id']) ?>" class="overflow-hidden rounded-3xl bg-[#1c2029] shadow-soft outline outline-[#2a2a2e] transition hover:-translate-y-1">
                            <div class="h-52 w-full bg-[#151419] bg-cover bg-center" style="background-image: url('<?= esc((string) ($event['banner_image'] ?: '/public/assets/images/logo.png')) ?>');"></div>
                            <div class="space-y-3 p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs text-gray-300"><?= esc(date('M d, Y g:i A', strtotime($event['start_datetime']))) ?></span>
                                    <span class="text-sm text-primary"><?= esc($event['category_name']) ?></span>
                                </div>
                                <h4 class="text-xl text-white"><?= esc($event['title']) ?></h4>
                                <p class="text-sm text-secondary">By <?= esc($event['organizer_name']) ?></p>
                                <p class="text-lg font-semibold text-white"><?= esc(format_attendee_price($event['starting_price'], (string) $event['payment_type'])) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="rounded-3xl bg-[#1c2029] p-8 text-secondary outline outline-[#2a2a2e]">
                    No events are scheduled for today yet.
                </div>
            <?php endif; ?>
        </section>

        <?php foreach ($category_sections as $section): ?>
            <section class="mb-12">
                <div class="mb-6 flex items-end justify-between">
                    <div>
                        <h3><?= esc($section['name']) ?></h3>
                        <p>Upcoming events in this category.</p>
                    </div>
                </div>

                <?php if ($section['events']): ?>
                    <div class="grid gap-6 md:grid-cols-3 xl:grid-cols-5">

                        <?php foreach ($section['events'] as $event): ?>
                            <a href="<?= url('/attendee/detailed_event?id=' . $event['id']) ?>" class="overflow-hidden rounded-3xl bg-[#1c2029] shadow-soft outline outline-[#2a2a2e] transition hover:-translate-y-1">
                                <div class="h-52 w-full bg-[#151419] bg-cover bg-center" style="background-image: url('<?= esc((string) ($event['banner_image'] ?: '/public/assets/images/logo.png')) ?>');"></div>
                                <div class="space-y-3 p-5">
                                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs text-gray-300"><?= esc(date('M d, Y g:i A', strtotime($event['start_datetime']))) ?></span>
                                    <h4 class="text-xl text-white"><?= esc($event['title']) ?></h4>
                                    <p class="text-sm text-secondary">By <?= esc($event['organizer_name']) ?></p>
                                    <p class="text-lg font-semibold text-white"><?= esc(format_attendee_price($event['starting_price'], (string) $event['payment_type'])) ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="rounded-3xl bg-[#1c2029] p-8 text-secondary outline outline-[#2a2a2e]">
                        No upcoming events in this category yet.
                    </div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    </div>

</body>

</html>
