<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuzz | Home</title>
    <link rel="icon" href="../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/assets/css/output.css">
</head>


<body class="p-15 w-full h-screen">
    
    <nav class="bg-surface flex justify-between pl-5 pr-5 card rounded-full sticky top-5  z-11  shadow-soft p-4 bg-surface-hover ">

        <div class="flex items-center">
            <img src="../public/assets/images/logo.png" alt="EventBuzz Logo" class="size-7">
            <h4>EventBuzz</h4>
        </div>

        <div class="flex items-center gap-5">

        <input type="text" placeholder="Search..."
          class="bg-surface text-secondary px-3 py-1 rounded-full ">
            
            <a href="<?= url('/attendee/ticket') ?>">
                <button class="flex justify-center align-center"><svg class="icon-primary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M3 10h-.75c0 .414.336.75.75.75zm0 4v-.75a.75.75 0 0 0-.75.75zm18-4v.75a.75.75 0 0 0 .75-.75zm0 4h.75a.75.75 0 0 0-.75-.75zM5 5.75h5v-1.5H5zm5 0h9v-1.5h-9zm9 12.5h-9v1.5h9zm-9 0H5v1.5h5zM9.25 5v14h1.5V5zm-5.366 6.116a1.25 1.25 0 0 1 0 1.768l1.06 1.06a2.75 2.75 0 0 0 0-3.889zm16.232 1.768a1.25 1.25 0 0 1 0-1.768l-1.06-1.06a2.75 2.75 0 0 0 0 3.889zM3 10.75c.321 0 .64.122.884.366l1.06-1.06A2.74 2.74 0 0 0 3 9.25zm.75-.75V7h-1.5v3zm0 7v-3h-1.5v3zm.134-4.116A1.24 1.24 0 0 1 3 13.25v1.5c.703 0 1.408-.269 1.945-.806zm16.232-1.768c.244-.244.563-.366.884-.366v-1.5c-.703 0-1.408.269-1.945.806zM20.25 7v3h1.5V7zm0 7v3h1.5v-3zm.75-.75c-.321 0-.64-.122-.884-.366l-1.06 1.06A2.74 2.74 0 0 0 21 14.75zm-16 5c-.69 0-1.25-.56-1.25-1.25h-1.5A2.75 2.75 0 0 0 5 19.75zm14 1.5A2.75 2.75 0 0 0 21.75 17h-1.5c0 .69-.56 1.25-1.25 1.25zm0-14c.69 0 1.25.56 1.25 1.25h1.5A2.75 2.75 0 0 0 19 4.25zM5 4.25A2.75 2.75 0 0 0 2.25 7h1.5c0-.69.56-1.25 1.25-1.25z"/></svg></button>
            </a>

            <!-- this should be the name of the attendee account -->
            <h4 class="pr-5">Ronnel L. Antaran</h4> 

            <h3>|</h3>

            <form class="flex justify-center align-center" method="POST" action="<?= url('logout') ?>"> 
            <button ><svg class="icon-secondary" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path  fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/></svg></button>
            </form>
        </div> 
    </nav>

<div class="relative h-5/6 mb-1 overflow-hidden">

    <!-- Blurred background -->
    <div class="absolute inset-0 bg-[url('/public/assets/images/logo.png')] bg-cover bg-center blur-3xl "></div>
    
    <!-- Content (NOT blurred) -->
<div class="relative z-10 p-5 flex flex-col items-center justify-center h-full text-center">
    
    <nav>
        <!-- the name depends on the user -->
        <h1 class="text-7xl">Welcome, Ronnel</h1>
    </nav>

    <h2 class="">
        Join events that you like
    </h2>


    <section class="bg-white h-2 w-full flex justify-center items-center mt-30">
    <button class="btn btn-primary rounded-full z-1 px-20"><a href="">Get Started</a></button>
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


    <div class=" w-full bg-surface">
        <section class="events-today"></section>
        <section class="Music"></section>
        <section class="Education"></section>
        <section class="arts_and_culture"></section>
        <section class="sports_and_fitness"></section>
        <section class="gaming_and_esports"></section>
    </div>

</body>

</html>