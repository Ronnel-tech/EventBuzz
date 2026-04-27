<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | My Events</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>
<body class="flex h-screen w-full">
        <aside class="flex flex-col items-center bg-surface w-24 justify-between p-5 shadow-soft fixed top-0 left-0 h-screen">

        <img src="/public/assets/images/logo.png" alt="" class="size-7 ">

        <div class="flex flex-col align-center gap-5 -mt-120">
            <a href="<?= url('/organizer/dashboard') ?>">
                <button><svg class="icon-disabled" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path  fill-opacity="0.16" d="M18.6 3H5.4A2.4 2.4 0 0 0 3 5.4v13.2A2.4 2.4 0 0 0 5.4 21h13.2a2.4 2.4 0 0 0 2.4-2.4V5.4A2.4 2.4 0 0 0 18.6 3"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="1.5" d="M12 21V3m0 7h9M5.4 3h13.2A2.4 2.4 0 0 1 21 5.4v13.2a2.4 2.4 0 0 1-2.4 2.4H5.4A2.4 2.4 0 0 1 3 18.6V5.4A2.4 2.4 0 0 1 5.4 3"/></g></svg></button>
            </a>
            <a href="<?= url('/organizer/events') ?>">
                <button><svg class="icon-primary" xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 0 24 24"><path  d="M21 17V8H7v9zm0-14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1V1h2v2h8V1h2v2zM3 21h14v2H3a2 2 0 0 1-2-2V9h2zm16-6h-4v-4h4z"/></svg></button>
            </a>
        </div>  

        <form method="POST" action="<?= url('logout') ?>"> 
            <button ><svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path  fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg></button>
        </form>

    </aside>


    <div class="flex flex-col p-5 w-full ml-24"> 

    <div class="flex flex-col ">
        <h3>My Events</h3> 
        <p>Manage all your events in one page</p>
    </div>


    <div class="flex flex-col pr-15">

      <div class="flex items-center justify-end align-center gap-3">

        <!-- Create Button -->
         <a href="<?= url('/organizer/create-event') ?>">
        <button class= "btn btn-primary px-4 py-2 rounded-full">
          Create +
        </button>
        </a>

        <!-- Filter -->
        <select class="bg-surface text-secondary px-3 py-2 rounded-full ">
          <option>Upcoming event</option>
          <option>Past event</option>
          <option>All event</option>
        </select>

        <!-- Search -->
        <input type="text" placeholder="Search..."
          class="bg-surface text-secondary px-3 py-2 rounded-full ">
      </div>



          <section class="flex flex-col p-10 w-full  ">
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

    <div class="bg-surface w-full outline  outline-[#2a2a2e] shadow-soft mt-5 rounded-2xl overflow-hidden pb-30">
        <h3 class="px-10 pt-10">Events Table</h3>

        <div class="px-10 py-8">
            <div class="grid grid-cols-[2fr_1fr_1fr_80px] gap-4 border-b border-[#2a2a2e] pb-4 text-sm text-gray-400">
                <div>Event Name</div>
                <div>Date</div>
                <div>Tickets Sold</div>
                <div class="text-right">Action</div>
            </div>

            <?php if ($events): ?>
                <div class="divide-y divide-[#2a2a2e]">
                    <?php foreach ($events as $event): ?>
                    <div class="grid cursor-pointer grid-cols-[2fr_1fr_1fr_80px] gap-4 py-5 text-white items-center hover:bg-[#151419]"
                        onclick="window.location='<?= url('/organizer/attendee-list?id=' . $event['id']) ?>'">
                        <div><?= esc($event['title']) ?></div>
                        <div><?= esc(date('M d, Y', strtotime($event['start_datetime']))) ?></div>
                        <div><?= esc((string) $event['tickets_sold']) ?></div>
                        <div class="flex justify-end" onclick="event.stopPropagation()">
                            <details class="relative" onclick="event.stopPropagation()">
                                <summary class="flex cursor-pointer list-none items-center justify-center rounded-full p-2 hover:bg-[#2a2a2e]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="12" cy="5" r="2"/>
                                        <circle cx="12" cy="12" r="2"/>
                                        <circle cx="12" cy="19" r="2"/>
                                    </svg>
                                </summary>
                                <div class="absolute right-0 top-10 z-10 w-36 rounded-2xl border border-[#2a2a2e] bg-surface p-2 shadow-soft">
                                    <a href="<?= url('/organizer/detailed-event?id=' . $event['id']) ?>" class="block rounded-xl px-4 py-2 text-sm text-white hover:bg-[#2a2a2e]">View</a>
                                    <a href="<?= url('/organizer/edit-event?id=' . $event['id']) ?>" class="block rounded-xl px-4 py-2 text-sm text-white hover:bg-[#2a2a2e]">Edit</a>
                                    <form method="POST" action="<?= url('/organizer/events') ?>">
                                        <?php csrf_field(); ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="event_id" value="<?= esc((string) $event['id']) ?>">
                                        <button type="submit" class="block w-full rounded-xl px-4 py-2 text-left text-sm text-red-300 hover:bg-[#2a2a2e]">Delete</button>
                                    </form>
                                </div>
                            </details>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="py-12 text-center text-gray-400">
                    No events found yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
    </section>



    </div>





    </div>
         
    </div>








    
</body>
</html>


