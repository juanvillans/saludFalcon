<script>
    import { useForm } from "@inertiajs/svelte";
    import { onMount, onDestroy } from "svelte";
    // import secretariaLogo from '$lib/images/logo_secretaria-circle-main.png';
    import Input from "../components/Input.svelte";
    import Modal from "../components/Modal.svelte";
    import DatePicker from "../components/DatePicker.svelte";
    import Alert from "../components/Alert.svelte";
    import { displayAlert } from "../stores/alertStore";
    let showModal = false;
    let sourceDiv;
    let width = 0;
    let numberOfDays = 7;
    function updateWidth() {
        const screenZise = window.innerWidth;
        console.log(screenZise);
        if (screenZise <= 1220) {
            numberOfDays = 5;
        }
        if (screenZise <= 1000) {
            numberOfDays = 4;
        }
        if (screenZise <= 900) {
            numberOfDays = 3;
        }
        if (screenZise <= 730) {
            numberOfDays = 2;
        }
        if (screenZise >= 1220) {
            numberOfDays = 7;
        }
        getNextNDays(focusedDate, numberOfDays);

        if (sourceDiv) {
            width = sourceDiv.getBoundingClientRect().width;
        }
    }
    const translateDays = {
        mon: "Lun",
        tue: "Mar",
        wed: "Mié",
        thu: "Jue",
        fri: "Vie",
        sat: "Sáb",
        sun: "Dom",
    };
    onMount(() => {
        updateWidth(); // Set the initial width
        window.addEventListener("resize", updateWidth);
    });

    onDestroy(() => {
        window.removeEventListener("resize", updateWidth);
    });

    let form = useForm({
        ci: null,
        password: null,
    });
    export let data = {
        availableDays: {
            7: true,
            8: true,
            9: true,
            10: true,
            14: true,
            15: true,
        },
        today: "2024-10-01T04:00:00.000Z",
    };
    let frontCalendar = [];
    function getNextNDays(startDate, n) {
        console.log({ startDate });
        const result = [];
        const start = new Date(startDate);

        for (let i = 0; i <= n - 1; i++) {
            const nextDate = new Date(start);
            nextDate.setDate(start.getDate() + i);
            result.push({
                date: nextDate.toISOString(), // Format as YYYY-MM-DD
                weekday: nextDate.toLocaleDateString("es-VE", {
                    weekday: "short",
                }),
                day: nextDate.toLocaleDateString("es-VE", {
                    day: "numeric",
                }),
            });
        }

        frontCalendar = result;
        focusedDate = startDate;
        return result;
    }
    let focusedDate = data.today;
    getNextNDays("2024-10-01T04:00:00.000Z", numberOfDays);
    $: console.log({ frontCalendar });
</script>

<Alert />
<section class=" min-h-screen">
    <header class=" border-b flex p-4">
        <div class="flex gap-3">
            <span
                class="rounded-full overflow-hidden bg-color4 w-12 h-12 justify-center items-center flex"
            >
                <iconify-icon icon="fa6-solid:user-doctor"></iconify-icon>
            </span>

            <div class="mt-1">
                <p><b class="text-xl"> Doctor Kilo</b></p>
                <p>
                    <span
                        class="bg-gray-200 rounded-full px-2 py-1 text-opacity-80"
                        >Ginecólogo</span
                    >
                </p>
            </div>
        </div>
        <div>
            <h1 class="text-2xl mx-auto">
                <span class="text-dark opacity-60">Ginecologia: </span>
                <span class="text-2xl">Titulo de la cita</span>
            </h1>
            <div class="flex gap-3">
                <iconify-icon icon="lets-icons:time-atack" class="mt-1 text-xl text-gray-500"
                ></iconify-icon>
                <p>Citas de 60 minutos</p>
            </div>
        </div>
    </header>

    <body class="md:flex justify-between">
        <div class="sticky top-1">
            <DatePicker
                on:datechange={(e) => getNextNDays(e.detail, numberOfDays)}
                selected={focusedDate}
                showDatePickerAlways={true}
                withInput={false}
                thereIsAvailable={(date) => {
                    if (data.availableDays[date]) {
                        return true;
                    } else {
                        false;
                    }
                }}
                isAllowed={(date) => {
                    console.log(date);
                    const millisecs = date.getTime();
                    if (millisecs + 25 * 3600 * 1000 < Date.now()) return false;
                    if (millisecs > Date.now() + 3600 * 24 * 45 * 10000)
                        return false;
                    return true;
                }}
            />
        </div>

        <div>
            <header class=" sticky top-0 pt-1 bg-gray-100 z-30 calendarHeader">
                <div class="flex gap-4 items-center">
                    <!-- <h2 class="text-2xl">{data.headerInfo.month_year}</h2> -->
                </div>

                <div class="py-5 w-max pt-10 flex">
                    <button
                        class="text-2xl text-gray-900 rounded-full aspect-square hover:bg-gray-200 flex items-center w-12 h-12"
                        on:click={() => {
                            const start = new Date(frontCalendar[0].date);
                            const nextDate = new Date(start);
                            nextDate.setDate(start.getDate() - numberOfDays);
                            getNextNDays(nextDate, numberOfDays);
                        }}
                    >
                        <iconify-icon
                            icon="iconamoon:arrow-left-2-bold"
                            class="relative left-2"
                        ></iconify-icon></button
                    >
                    <ul class="flex listCalendarHeader gap-2">
                        {#each frontCalendar as objDate (objDate.day)}
                            <li
                                class="flex flex-col justify-center text-center w-28"
                            >
                                <p
                                    class={` ${objDate.date == data.today ? "text-color1 " : ""}`}
                                >
                                    {objDate.weekday.toUpperCase()}
                                </p>
                                <p
                                    class={`text-2xl mx-auto w-12 aspect-square rounded-full flex items-center justify-center ${objDate.date == data.today ? "bg-color1 text-gray-50 " : ""}`}
                                >
                                    {objDate.day}
                                </p>
                                <div class="grid gap-2 mt-7">
                                    <button
                                        class="py-2 border-color1 border rounded hover:bg-color3 duration-75 hover:text-white bg-color4"
                                        >8:00AM</button
                                    >
                                    <button
                                        class="py-2 border-color1 border rounded hover:bg-color3 duration-75 hover:text-white bg-color4"
                                        >9:00AM</button
                                    >
                                </div>
                            </li>
                        {/each}
                    </ul>
                    <button
                        on:click={() => {
                            const start = new Date(
                                frontCalendar[frontCalendar.length - 1].date,
                            );
                            const nextDate = new Date(start);
                            nextDate.setDate(start.getDate() + 1);
                            getNextNDays(nextDate, numberOfDays);
                        }}
                        class="text-2xl text-gray-900 rounded-full aspect-square hover:bg-gray-200 flex items-center w-12 h-12"
                    >
                        <iconify-icon
                            icon="iconamoon:arrow-right-2-bold"
                            class="relative left-2"
                        ></iconify-icon></button
                    >
                </div>
            </header>
        </div>
    </body>
</section>
