<script>
    export let calendars;
    console.log(calendars);
    import { inertia } from "@inertiajs/svelte";
    
</script>

<div class="p-5 lg:p-10 lg:pt-5 bg-gray-50">
    <header
        class=" overflow-hidden h-fit relative w-full max-w-[1400px] mx-auto bg- rounded-3xl"
    >
        <div class="absolute top-0 left-0 z-10 flex flex-col justify-between h-full pl-5 pt-5">
            <a href="#" class="text-white flex gap-3 items-center">
                <img src="/img/hospital.png" class="w-12" alt="" />
                <span>Hospital Alfredo Van Grieken</span>
            </a>
            <div>

                <h1 class="text-gray-50 font-bold flex flex-col ">
                    <span class="smaller-text text-gray-200 pl-3 text-xl relative top-3 ">En esta página ahora</span>
                    <span class="medium-text text-gray-100">
                        Puedes Reservar Tu
                    </span>
                    
                    <div class="biggest-text relative top-2 uppercase">Cita Médica</div>
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
        <div class="circle absolute right-20 -bottom-52 w-96 z-20 block aspect-square rounded-full"></div>
    </header>

    <main class="mt-7 grid xl:grid-cols-4 justify-center gap-7 md:gap-7 overflow-hidden max-w-[1000px] mx-auto">
        {#each calendars.data as calendar (calendar.id)}
            
        <a href='/citas/{calendar.id}' use:inertia   class="h-32 hover:shadow-2xl hover:brightness-110 bg-color1 block rounded-md p-4 relative w-full overflow-hidden">
            <h3 class="relative z-10 text-white font-bold uppercase">{calendar.specialty_name}</h3>

            <p class=" absolute z-10 bottom-4 right-4 text-gray-100 flex justify-end items-center gap-3 text-right w-full"> <iconify-icon
                icon="lets-icons:time-atack"
            ></iconify-icon>Citas de {calendar.duration_per_appointment} min</p>
            <img src="/img/blueBlup.png" alt="" srcset="" class="absolute left-0 bottom-10">
            <img src="/img/redBlup.png" alt="" srcset="" class="absolute right-0 top-10">
        </a>
        {/each}
       
    </main>
</div>

<style>
    /* .calendar {
        min-width: 300px;
    } */
     body {
        background: #c50202;
     }
    .biggest-text {
        font-size: 10.4vw;
        line-height: 10.7vw;
    }
    .medium-text {
        font-size: min(6.427vw, 70px);
        line-height: 6.427vw;
        padding-left: 10px;
        position: relative;
        top: 18px;
        /* letter-spacing: .9vw;     */
    }
    .smaller-text {
        font-size: 3.972vw;
    }
    .circle {
        background: radial-gradient(circle,rgba(3, 3, 3, 0.8) 0%, rgba(1, 17, 64, 0.01) 70%);
    }
</style>
