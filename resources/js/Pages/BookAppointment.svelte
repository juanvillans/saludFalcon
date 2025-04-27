<script>
    import { useForm, router } from "@inertiajs/svelte";
    import { onMount, onDestroy } from "svelte";
    // import secretariaLogo from '$lib/images/logo_secretaria-circle-main.png';
    import Input from "../components/Input.svelte";
    import Modal from "../components/Modal.svelte";
    import DatePicker from "../components/DatePicker.svelte";
    import Alert from "../components/Alert.svelte";
    import { displayAlert } from "../stores/alertStore";
    import debounce from "lodash/debounce";

    let showModal = false;
    let sourceDiv;
    let width = 0;
    let numberOfDays = 7;
    let isThereSomeAppointment = "loading";
    function updateWidth() {
        const screenZise = document.documentElement.clientWidth;
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
        if (screenZise <= 814) {
            numberOfDays = 2;
        }
        if (screenZise <= 770) {
            numberOfDays = 4;
        }
        if (screenZise <= 576) {
            numberOfDays = 3;
        }
        if (screenZise <= 450) {
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
        Lun: "mon",
        Mar: "tue",
        Mié: "wed",
        Jue: "thu",
        Vie: "fri",
        Sáb: "sat",
        Dom: "sun",
    };
    export let data = {};
    export let calendar = {};
    export let calendar_month = {};
    let form;
    const debouncedUpdate = debounce(updateWidth, 300);

    function getTodayInVenezuelaISO() {
        const now = new Date();

        // Obtener la fecha y hora actual en Caracas en formato ISO YYYY-MM-DD
        const formatter = new Intl.DateTimeFormat("en-CA", {
            timeZone: "America/Caracas",
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
        });

        const parts = formatter.formatToParts(now);
        const venezuelaDateString = `${parts.find((p) => p.type === "year").value}-${parts.find((p) => p.type === "month").value}-${parts.find((p) => p.type === "day").value}`;

        // Convertirla a Date en zona horaria local (UTC, pero representando Caracas)
        const venezuelaDate = new Date(`${venezuelaDateString}T00:00:00-04:00`);
        return venezuelaDate.toISOString(); // Esta es la fecha en formato ISO UTC representando Caracas
    }

    const today = getTodayInVenezuelaISO();
    $: focusedDate = today;

    export let serverTime = "13:40"; // Default fallback
    let currentTime = serverTime;
    let interval;

    function syncTime() {
        // Option 1: Client-side update only
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, "0");
        const minutes = String(now.getMinutes()).padStart(2, "0");
        currentTime = `${hours}:${minutes}`;

        // Option 2: Fetch fresh time from backend (uncomment if needed)
        // Inertia.reload({ only: ['serverTime'] });
    }

    onMount(() => {
        syncTime(); // Initial sync
        interval = setInterval(syncTime, 60000);

        window.addEventListener("resize", debouncedUpdate);

        const formFields = {};
        data.data.fields.forEach((field) => {
            formFields[field.name.toLocaleLowerCase()] = ""; // Initialize once
        });

        form = useForm({
            patient_id: null,
            appointment_data: {
                ...formFields,
                name: "",
                last_name: "",
                ci: "",
                sex: "Masculino",
                date_birth: "",
                phone_number: "",
                email: "",
                end_date: "",
            },
            calendar_id: "",
            day_reserved: "",
            time_reserved: "",
        });

        const now = new Date();

        // Adjust for Venezuela's UTC-4 offset (Caracas time)
        const venezuelaTime = new Date(now.getTime() - 4 * 60 * 60 * 1000); // Subtract 4 hours

        // Format to ISO string (UTC representation)
        // getNextNDays(today, numberOfDays);
        focusedDate = today;
        updateWidth(); // Set the initial width
        // checkForAvailableDays(focusedDate)
    });

    onDestroy(() => {
        if (interval) clearInterval(interval);

        window.removeEventListener("resize", debouncedUpdate);
    });

    let availableDays = {};
    let dataFront = {
        today: "2024-10-01T04:00:00.000Z",
    };

    let shiftsForCalendar = {};

    function updateShiftsForCalendar() {
        // console.log(data.data.adjusted_availability);
        // console.log($form.adjusted_availability);
        frontCalendar.forEach((obj, indx) => {
            let isItAjustedShift =
                data.data?.adjusted_availability.length > 0
                    ? data.data?.adjusted_availability?.findIndex(
                          (arrDates) =>
                              arrDates.date.slice(0, 10) ==
                              obj.date?.slice(0, 10),
                      )
                    : -1;
            shiftsForCalendar = {
                ...shiftsForCalendar,
                [obj.EnglishWeekday]:
                    isItAjustedShift >= 0
                        ? data.data.adjusted_availability[isItAjustedShift]
                              .shifts
                        : data.data.availability[obj.EnglishWeekday],
            };
        });
        // console.log({ shiftsForCalendar });
    }

    $: frontCalendar, updateShiftsForCalendar();

    $: console.log({ $form });

    let frontCalendar = [];
    function getNextNDays(startDate, n) {
        // console.log({ startDate });
        const result = [];
        const start = new Date(startDate);

        for (let i = 0; i <= n - 1; i++) {
            const nextDate = new Date(start);
            nextDate.setDate(start.getDate() + i);
            // console.log({ start, nextDate });

            result.push({
                date: nextDate.toISOString(), // Format as YYYY-MM-DD
                weekday: nextDate.toLocaleDateString("es-VE", {
                    weekday: "short",
                }),
                EnglishWeekday: nextDate
                    .toLocaleDateString("en", {
                        weekday: "short",
                    })
                    .toLocaleLowerCase(),
                day: nextDate.toLocaleDateString("es-VE", {
                    day: "numeric",
                }),
            });
        }
        let prevDate = new Date(focusedDate);
        let nextDate = new Date(startDate);
        frontCalendar = result;
        focusedDate = startDate;
        if (
            prevDate.getFullYear() == nextDate.getFullYear() &&
            prevDate.getMonth() !== nextDate.getMonth()
        ) {
            updateCalendar(true);

        } else {
            console.log(calendar_month);
            
            updateCalendar(calendar_month == null);
        }
        return result;
    }

    function getMonthBoundaries(dateString) {
        const date = new Date(dateString);

        if (isNaN(date.getTime())) {
            throw new Error("Invalid date string");
        }

        // Get first moment of month in local time (Venezuela UTC-4)
        const firstDay = new Date(
            date.getFullYear(),
            date.getMonth(),
            1,
            0,
            0,
            0,
            0,
        );

        // Get last moment of month in local time (23:59:59.999)
        const lastDay = new Date(
            date.getFullYear(),
            date.getMonth() + 1,
            0,
            23,
            59,
            59,
            999,
        );

        // Convert to ISO format (UTC) without timezone offset
        return [
            new Date(
                firstDay.getTime() - firstDay.getTimezoneOffset() * 60000,
            ).toISOString(),
            new Date(
                lastDay.getTime() - lastDay.getTimezoneOffset() * 60000,
            ).toISOString(),
        ];
    }

    // $: console.log(availableDays);

     function updateCalendar(type) {
      console.log({type});
      
        if (type) {
            calendar_month = {}
        }
        isThereSomeAppointment = "loading";
        router.get(
            window.location.pathname,
            {   
                calendar_month: type,
                start_date: frontCalendar[0].date,
                end_date: frontCalendar[frontCalendar.length - 1].date,
            },
            {
                onSuccess: (page) => {
                    updateShiftsForCalendar();
                    if (document.querySelector(".bookButton")) {
                        isThereSomeAppointment = true;
                    } else {
                        isThereSomeAppointment = false;
                    }
                },
            },
        );
    }

    $: console.log({ calendar, data, frontCalendar });

    function convertTo12HourFormat(time24) {
        const [hours, minutes] = time24.split(":").map(Number);
        const suffix = hours >= 12 ? "PM" : "AM";
        const hours12 = hours % 12 || 12; // If hours is 0, set it to 12
        const formattedMinutes = String(minutes).padStart(2, "0");
        return `${hours12}:${formattedMinutes} ${suffix}`;
    }

    function handleSubmit(event) {
        event.preventDefault();
        $form.clearErrors();

        $form.post(`${window.location.pathname}`, {
            // preserveState: true,
            onError: (errors) => {
                if (errors.data) {
                    displayAlert({ type: "error", message: errors.data });
                }
            },
            onSuccess: (page) => {
                displayAlert({
                    type: "success",

                    message:
                        "Su cita es el " +
                        $form.day_reserved.slice(0, 10) +
                        " a las " +
                        convertTo12HourFormat($form.time_reserved),
                });

                // $form.defaults({ ...page.props.data.data });
                $form.reset();
            },
        });
    }

    function addMinutes(time, minutesToAdd) {
        // Split the time into hours and minutes
        const [hours, minutes] = time.split(":").map(Number);

        // Create a new Date object, setting the hours and minutes
        const date = new Date();
        date.setHours(hours);
        date.setMinutes(minutes + minutesToAdd);

        // Format the new time back to "HH:MM"
        const newHours = String(date.getHours()).padStart(2, "0");
        const newMinutes = String(date.getMinutes()).padStart(2, "0");
        return `${newHours}:${newMinutes}`;
    }

    function isTimeDifferenceSufficient(
        currentTime24,
        currentDate,
        futureTime24,
        futureDate,
    ) {
        // Parse current datetime
        const [currentHours, currentMinutes] = currentTime24
            .split(":")
            .map(Number);
        const currentDateTime = new Date(currentDate);
        currentDateTime.setHours(currentHours, currentMinutes, 0, 0);

        // Parse future datetime
        const [futureHours, futureMinutes] = futureTime24
            .split(":")
            .map(Number);
        const futureDateTime = new Date(futureDate);
        futureDateTime.setHours(futureHours, futureMinutes, 0, 0);

        // Calculate difference in milliseconds
        const diffMs = futureDateTime - currentDateTime;
        const diffDays = diffMs / (1000 * 60 * 60 * 24);

        // Handle past dates (difference is negative)
        if (diffMs < 0) {
            return false; // Future time is in the past
        }
        // Check day threshold (if specified)
        if (
            data.data.programming_slot
                .allow_max_reservation_time_before_appointment == true &&
            diffDays >
                data.data.programming_slot
                    .max_reservation_time_before_appointment
        ) {
            return false;
        }
        if (
            data.data.programming_slot
                .allow_min_reservation_time_before_appointment == false
        )
            return true;

        // Convert threshold hours to milliseconds
        const thresholdMs =
            data.data.programming_slot.min_reservation_time_before_appointment *
            60 *
            60 *
            1000;

        // Compare difference with threshold
        // console.log(
        //     { currentTime24, currentDate, futureTime24, futureDate },
        //     diffMs >= thresholdMs,
        // );

        return diffMs >= thresholdMs;
    }

    function validateDay(calendarDate, weekDay) {
        
        if (calendarDate < today.slice(0, 10)) {
            return false;
        }

        if (data.data.booked_appointment_settings.allow_max_appointment_per_day && calendar.weekDays[weekDay + "_" + calendarDate]?.nro_appointments >= data.data.booked_appointment_settings.max_appointment_per_day){
            return false
        }

        if (
            data.data.programming_slot.available_now_check == 0 &&
            !data.data.programming_slot.interval_date.start_now_check &&
            data.data.programming_slot.interval_date.custom_start_date.slice(
                0,
                10,
            ) > calendarDate
        ) {
            // console.log(data.data.programming_slot.interval_date);
            return false;
        }

        if (
            data.data.programming_slot.available_now_check == 0 &&
            !data.data.programming_slot.interval_date.end_never_check &&
            data.data.programming_slot.interval_date.custom_end_date.slice(
                0,
                10,
            ) < calendarDate
        ) {
            return false;
        }

        return true;
    }
</script>

<Alert />
<section class=" min-h-screen sm:w-11/12 mx-auto max-w-[1480px]">
    <header class=" border-b md:flex gap-5 xl:gap-10 p-4">
        <div class="flex gap-3">
            <img
                class="bg-gray-300 w-7 h-7 md:w-10 md:h-10 block aspect-square rounded-full object-cover"
                src={`/storage/users/${data.data.user_photo}`}
                alt=""
            />

            <div class="mt-1">
                <p>
                    <b class="md:text-xl"
                        >{data.data.user_name} {data.data.user_last_name}</b
                    >
                </p>
            </div>
        </div>
        <div class="">
            <h1 class="text-lg md:text-2xl mx-auto uppercase">
                <span class="text-dark opacity-60 block text-md relative top-2"
                    >{data.data.user_specialty_name}
                </span>
                <span class="text-xl md:text-2xl">{data.data.title}</span>
            </h1>
            <div class="flex gap-1">
                <iconify-icon
                    icon="lets-icons:time-atack"
                    class="text-xl text-gray-500"
                ></iconify-icon>
                <p>Citas de {data.data.duration_per_appointment} minutos</p>
            </div>
        </div>
    </header>

    <body
        class="md:flex justify-between pt-1 pl-1.5 pb-10 min-h-[500px] bg-gray-100"
    >
        <div class="md:sticky top-1">
            <div class="w-[300px] mx-auto">
                <DatePicker
                    on:datechange={(e) => getNextNDays(e.detail, numberOfDays)}
                    selected={focusedDate}
                    showDatePickerAlways={true}
                    withInput={false}
                    bind:availableDays={calendar_month}
                    isAllowed={(date) => {
                        // console.log(date);
                        const millisecs = date.getTime();
                        if (millisecs + 25 * 3600 * 1000 < Date.now())
                            return false;
                        if (millisecs > Date.now() + 3600 * 24 * 45 * 10000)
                            return false;
                        return true;
                    }}
                />
            </div>
        </div>

        <div>
            <header class="pt-60 md:pt-1 bg-gray-100 z-30 calendarHeader">
                <div class="flex gap-4 items-center">
                    <!-- <h2 class="text-2xl">{dataFront.headerInfo.month_year}</h2> -->
                </div>

                <div class="py-5 w-max pt-10 flex mx-auto">
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
                    <ul class="flex listCalendarHeader gap-2 relative h-full">
                        {#each frontCalendar as objDate (objDate.day)}
                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                            <li class="flex flex-col text-center w-28">
                                <p
                                    class={`text-sm lg:text-md ${objDate.date.slice(0, 10) == today.slice(0, 10) ? "text-color3 font-semibold" : ""}`}
                                >
                                    {objDate.weekday.toUpperCase()}
                                </p>
                                <p
                                    class={`text-lg lg:text-2xl mx-auto w-12 aspect-square rounded-full flex items-center justify-center ${objDate.date.slice(0, 10) == today.slice(0, 10) ? "bg-color1 text-gray-50 " : ""}`}
                                >
                                    {objDate.day}
                                </p>
                                <div class="grid gap-2 mt-7">
                                    {#if validateDay(objDate.date.slice(0, 10), objDate.EnglishWeekday)}
                                        {#each shiftsForCalendar?.[objDate.EnglishWeekday] as shift, indx (objDate.day + "_" + indx)}
                                            {#each shift.appointments as appointment, i ("start_app" + "_" + i)}
                                                {#if isTimeDifferenceSufficient(currentTime, today.slice(0, 10), appointment.start_appo, objDate.date.slice(0, 10)) && !calendar.weekDays[objDate.EnglishWeekday + "_" + objDate.date.slice(0, 10)]?.appointments[appointment.start_appo]}
                                                    <button
                                                        on:click={() => {
                                                            showModal = true;
                                                            $form.day_reserved =
                                                                objDate.date;
                                                            $form.time_reserved =
                                                                appointment.start_appo;
                                                            $form.calendar_id =
                                                                data.data.id;
                                                            $form.appointment_data.end_time =
                                                                addMinutes(
                                                                    appointment.start_appo,
                                                                    data.data
                                                                        .duration_per_appointment,
                                                                );
                                                        }}
                                                        class="bookButton font-semibold text-sm xl:text-md py-2 border-color1 border rounded hover:bg-color3 duration-75 hover:text-white bg-color4"
                                                        >{convertTo12HourFormat(
                                                            appointment.start_appo,
                                                        )}</button
                                                    >
                                                {/if}
                                            {/each}
                                        {/each}
                                    {/if}
                                </div>
                            </li>
                        {/each}
                        {#if isThereSomeAppointment === false}
                            <li
                                class="absolute left-1/2 top-24 md:top-36 -translate-x-1/2 text-center w-full"
                            >
                                <p>
                                    No hay citas disponibles durante estos dias
                                </p>

                                <button
                                    type="button"
                                    class="text-color3 font-semibold p-2"
                                    >Ir a la siguiente fecha disponible</button
                                >
                            </li>
                        {/if}
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

<Modal
    bind:showModal
    onClose={() => {
        // handleCloseCustomTime();
    }}
>
    <div slot="header" class="font-bold text-sm ">
        <p class="text-gray-500">
            Llena los campos para reservar tu cita 

        </p>
        <p>
            El {new Date($form.day_reserved).toLocaleDateString("es-VE", {
                weekday: "short",
                year: "numeric",
                month: "short",
                day: "numeric",
            })} a las {convertTo12HourFormat($form.time_reserved)}
        </p>
    </div>

    <form id="a-form" on:submit={handleSubmit} class="grid grid-cols-2 gap-x-4">
        <Input
            required={true}
            type={"number"}
            label={"C.I *"}
            name={"ci"}
            min={100000}
            placeholder={"Minimo 6 números"}
            on:wheel={(e) => document.activeElement.blur()}
            bind:value={$form.appointment_data.ci}
            error={$form.errors?.ci}
        />
        <Input
            type="text"
            required={true}
            label={"Nombres *"}
            bind:value={$form.appointment_data.name}
            readOnly={$form.appointment_data.patient_id}
            error={$form.errors?.name}
        />
        <Input
            type="text"
            required={true}
            label={"Apellidos *"}
            bind:value={$form.appointment_data.last_name}
            readOnly={$form.appointment_data.patient_id}
            error={$form.errors?.last_name}
        />

        <Input
            type="date"
            label={"Fecha de Nacimiento*"}
            bind:value={$form.appointment_data.date_birth}
            readOnly={$form.appointment_data.patient_id}
            required={true}
            error={$form.errors?.date_birth}
        />
        <Input
            type="tel"
            label={"Teléfono *"}
            readOnly={$form.appointment_data.patient_id}
            required={true}
            bind:value={$form.appointment_data.phone_number}
            error={$form.errors?.phone_number}
        />

        <Input
            type="email"
            label={"Correo*"}
            bind:value={$form.appointment_data.email}
            readOnly={$form.appointment_data.patient_id}
            required={true}
            error={$form.errors?.email}
        />
        <div class="flex flex-col mt-6">
            <label class="py-1 cursor-pointer hover:bg-gray-100">
                <input
                    class="mr-3 inline-block"
                    type="radio"
                    bind:group={$form.appointment_data.sex}
                    value="Masculino"
                    name="sex"
                    id=""
                    required
                /><span class:font-bold={$form.sex == "Masculino"}
                    >Masculino</span
                >
            </label>

            <label class="py-1 cursor-pointer hover:bg-gray-100">
                <input
                    class="mr-3 inline-block"
                    type="radio"
                    bind:group={$form.appointment_data.sex}
                    value="Femenino"
                    name="sex"
                    id=""
                    required
                /><span
                    class:font-bold={$form.appointment_data.sex == "Femenino"}
                    >Femenino</span
                >
            </label>
        </div>
        {#each data.data.fields as field}
            <Input
                required={field.required}
                label={`${field.name} ${field.required ? "*" : ""}`}
                error={$form.errors?.[field.name.toLocaleLowerCase()]}
                bind:value={
                    $form.appointment_data[field.name.toLocaleLowerCase()]
                }
            />
        {/each}
    </form>
    <button
        on:click={() => {
            // $form.fields = [...$form.fields, newItem];
            // showModalForm = false;
            // newItem = { name: "", required: false, by_default: false };
        }}
        slot="btn_footer"
        form="a-form"
        type="submit"
        class="hover:bg-color3 hover:text-white duration-200 mt-auto w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
        value={$form.processing ? "Cargando..." : "Reservar"}>Hecho</button
    >
</Modal>
