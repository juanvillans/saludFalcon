<script>
    export let calendars;
    console.log(calendars);
    import { inertia } from "@inertiajs/svelte";
    
</script>

<div class="p-5 lg:p-10 lg:pt-5">
    <header
        class=" overflow-hidden h-fit relative w-full max-w-[1400px] mx-auto bg-color1 rounded-3xl"
    >
        <div class="absolute top-0 left-0 z-10 flex flex-col justify-between h-full pl-5 pt-5">
            <a href="#" class="text-white flex gap-3 items-center">
                <img src="/img/logoBlue.svg" class="w-12" alt="" />
                <span>SaludFalcón.org</span>
            </a>
            <div>

                <h2 class="text-gray-200 pl-3 text-xl relative top-3 ">Hospital Universitario Alfredo Van Grieken</h2>
                <h1 class="text-gray-50 font-bold ">
                    <span class="medium-text text-gray-100">
                        RESERVA TU
                    </span>
                    
                    <div class="biggest-text relative top-2">Cita Médica</div>
                </h1>
            </div>
        </div>

        <img
            src="/img/hero.png"
            class=" h-[400px] w-full max-w-[1400px] rounded-2xl object-cover object-right-bottom"
            alt=""
        />
        <img
            src="/img/onlyDoctor.png"
            class=" pointer-events-none z-50 absolute top-0 left-0 h-[400px] w-full max-w-[1400px] rounded-2xl object-cover object-right-bottom"
            alt=""
        />
        <div class="circle absolute right-28 -bottom-52 w-96 z-20 block aspect-square rounded-full"></div>
    </header>

    <body class="mt-7 flex gap-7 md:gap-10 overflow-hidden">
        {#each calendars.data as calendar (calendar.id)}
            
        <a href='/citas/{calendar.id}' use:inertia   class="bg-color1 block rounded-md p-4 relative max-w-[350px]">
            <h3 class="text-white font-bold uppercase">{calendar.specialty_name}</h3>

            <p class="text-opacity-50 text-white flex justify-end items-center gap-3 text-right w-full"> <iconify-icon
                icon="lets-icons:time-atack"
            ></iconify-icon>Citas de {calendar.duration_per_appointment} min</p>
            <img src="/img/blueBlup.png" alt="" srcset="" class="absolute left-0 bottom-10">
            <img src="/img/redBlup.png" alt="" srcset="" class="absolute right-4 top-10">
        </a>
        {/each}
    </body>
</div>

<style>
    .biggest-text {
        font-size: 12vw;
        line-height: 12vw;
    }
    .medium-text {
        font-size: min(6vw, 70px);
        line-height: 5vw;
        padding-left: 10px;
        position: relative;
        top: 18px;
        /* letter-spacing: .9vw;     */
    }
    .circle {
        background: radial-gradient(circle,rgba(3, 3, 3, 0.8) 0%, rgba(1, 17, 64, 0.01) 70%);
    }
</style>
