<script>
    import { useForm } from "@inertiajs/svelte";
    import { onMount, onDestroy } from "svelte";
    import { inertia, router } from "@inertiajs/svelte";
    import Input from "../../components/Input.svelte";
    import Modal from "../../components/Modal.svelte";
    import Calender from "../../components/Calender.svelte";

    import Alert from "../../components/Alert.svelte";
    import { displayAlert } from "../../stores/alertStore";

    import DatePicker from "../../components/DatePicker.svelte";
    import { text } from "svelte/internal";

    let currentDate = new Date().toISOString();
    console.log(currentDate);
    const onDateChange = (d, indx) => {
        $form.adjusted_availability[indx].date = d.detail.toISOString();
    };
    let defaulTtime_between_appointment = 30;
    export let data = {};
    let acordion = { franja: false, ajustesCitasReservadas: false };
    let showModal = false;
    let showModalFranja = false;
    let showModalappointments = false;
    let form = useForm({
        title: "",
        allow_max_appointment_per_day: false,
        allow_time_between_appointment: false,
        duration_per_appointment: "60",
        prev_value_duration_per_appointment: "",
        duration_per_appointment_type: "",
        allow_max_reservation_time_before_appointment: true,
        allow_min_reservation_time_before_appointment: true,
        max_reservation_time_before_appointment: 60,
        min_reservation_time_before_appointment: 40,
        time_between_appointment: null,
        max_appointment_per_day: 4,
        availability: {
            mon: [
                {
                    start: "08:00",
                    end: "16:00",
                    appointments: [
                        {
                            start_appo: "08:00",
                        },
                        {
                            start_appo: "09:00",
                        },
                        {
                            start_appo: "10:00",
                        },
                        {
                            start_appo: "11:00",
                        },
                        {
                            start_appo: "12:00",
                        },
                        {
                            start_appo: "13:00",
                        },
                        {
                            start_appo: "14:00",
                        },
                        {
                            start_appo: "15:00",
                        },
                    ],
                },
            ],
            tue: [
                {
                    start: "08:00",
                    end: "16:00",
                    appointments: [
                        {
                            start_appo: "08:00",
                        },
                        {
                            start_appo: "09:00",
                        },
                        {
                            start_appo: "10:00",
                        },
                        {
                            start_appo: "11:00",
                        },
                        {
                            start_appo: "12:00",
                        },
                        {
                            start_appo: "13:00",
                        },
                        {
                            start_appo: "14:00",
                        },
                        {
                            start_appo: "15:00",
                        },
                    ],
                },
            ],
            wed: [
                {
                    start: "08:00",
                    end: "16:00",
                    appointments: [
                        {
                            start_appo: "08:00",
                        },
                        {
                            start_appo: "09:00",
                        },
                        {
                            start_appo: "10:00",
                        },
                        {
                            start_appo: "11:00",
                        },
                        {
                            start_appo: "12:00",
                        },
                        {
                            start_appo: "13:00",
                        },
                        {
                            start_appo: "14:00",
                        },
                        {
                            start_appo: "15:00",
                        },
                    ],
                },
            ],
            thu: [
                {
                    start: "08:00",
                    end: "16:00",
                    appointments: [
                        {
                            start_appo: "08:00",
                        },
                        {
                            start_appo: "09:00",
                        },
                        {
                            start_appo: "10:00",
                        },
                        {
                            start_appo: "11:00",
                        },
                        {
                            start_appo: "12:00",
                        },
                        {
                            start_appo: "13:00",
                        },
                        {
                            start_appo: "14:00",
                        },
                        {
                            start_appo: "15:00",
                        },
                    ],
                },
            ],
            fri: [
                {
                    start: "08:00",
                    end: "16:00",
                    appointments: [
                        {
                            start_appo: "08:00",
                        },
                        {
                            start_appo: "09:00",
                        },
                        {
                            start_appo: "10:00",
                        },
                        {
                            start_appo: "11:00",
                        },
                        {
                            start_appo: "12:00",
                        },
                        {
                            start_appo: "13:00",
                        },
                        {
                            start_appo: "14:00",
                        },
                        {
                            start_appo: "15:00",
                        },
                    ],
                },
            ],
            sat: [],
            sun: [],
        },

        adjusted_availability: [
            {
                date: "2024-09-09T04:00:00.000Z",
                shifts: [
                    {
                        start: "09:00",
                        end: "12:00",
                        appointments: [
                            {
                                start_appo: "09:00",
                            },
                            {
                                start_appo: "10:30",
                            },
                        ],
                    },
                    {
                        start: "14:00",
                        end: "18:00",
                        appointments: [
                            {
                                start_appo: "14:00",
                            },
                            {
                                start_appo: "15:30",
                            },
                            {
                                start_appo: "17:00",
                            },
                        ],
                    },
                ],
            },
            {
                date: "2024-09-20T04:00:00.000Z",
                shifts: [
                    {
                        start: "08:00",
                        end: "16:00",
                        appointments: [
                            {
                                start_appo: "08:00",
                            },
                            {
                                start_appo: "09:30",
                            },
                            {
                                start_appo: "11:00",
                            },
                            {
                                start_appo: "12:30",
                            },
                            {
                                start_appo: "14:00",
                            },
                        ],
                    },
                ],
            },
        ],
        time_available_type: 1,
    });
    let optionValue = "";

    const optionsShift = [
        { label: "5:00am", value: "05:00" },
        { label: "5:15am", value: "05:15" },
        { label: "5:30am", value: "05:30" },
        { label: "5:45am", value: "05:45" },
        { label: "6:00am", value: "06:00" },
        { label: "6:15am", value: "06:15" },
        { label: "6:30am", value: "06:30" },
        { label: "6:45am", value: "06:45" },
        { label: "7:00am", value: "07:00" },
        { label: "7:15am", value: "07:15" },
        { label: "7:30am", value: "07:30" },
        { label: "7:45am", value: "07:45" },
        { label: "8:00am", value: "08:00" },
        { label: "8:15am", value: "08:15" },
        { label: "8:30am", value: "08:30" },
        { label: "8:45am", value: "08:45" },
        { label: "9:00am", value: "09:00" },
        { label: "9:15am", value: "09:15" },
        { label: "9:30am", value: "09:30" },
        { label: "9:45am", value: "09:45" },
        { label: "10:00am", value: "10:00" },
        { label: "10:15am", value: "10:15" },
        { label: "10:30am", value: "10:30" },
        { label: "10:45am", value: "10:45" },
        { label: "11:00am", value: "11:00" },
        { label: "11:15am", value: "11:15" },
        { label: "11:30am", value: "11:30" },
        { label: "11:45am", value: "11:45" },
        { label: "12:00pm", value: "12:00" },
        { label: "12:15pm", value: "12:15" },
        { label: "12:30pm", value: "12:30" },
        { label: "12:45pm", value: "12:45" },
        { label: "1:00pm", value: "13:00" },
        { label: "1:15pm", value: "13:15" },
        { label: "1:30pm", value: "13:30" },
        { label: "1:45pm", value: "13:45" },
        { label: "2:00pm", value: "14:00" },
        { label: "2:15pm", value: "14:15" },
        { label: "2:30pm", value: "14:30" },
        { label: "2:45pm", value: "14:45" },
        { label: "3:00pm", value: "15:00" },
        { label: "3:15pm", value: "15:15" },
        { label: "3:30pm", value: "15:30" },
        { label: "3:45pm", value: "15:45" },
        { label: "4:00pm", value: "16:00" },
        { label: "4:15pm", value: "16:15" },
        { label: "4:30pm", value: "16:30" },
        { label: "4:45pm", value: "16:45" },
        { label: "5:00pm", value: "17:00" },
        { label: "5:15pm", value: "17:15" },
        { label: "5:30pm", value: "17:30" },
        { label: "5:45pm", value: "17:45" },
        { label: "6:00pm", value: "18:00" },
        { label: "6:15pm", value: "18:15" },
        { label: "6:30pm", value: "18:30" },
        { label: "6:45pm", value: "18:45" },
        { label: "7:00pm", value: "19:00" },
        { label: "7:15pm", value: "19:15" },
        { label: "7:30pm", value: "19:30" },
        { label: "7:45pm", value: "19:45" },
        { label: "8:00pm", value: "20:00" },
        { label: "8:15pm", value: "20:15" },
        { label: "8:30pm", value: "20:30" },
        { label: "8:45pm", value: "20:45" },
        { label: "9:00pm", value: "21:00" },
        { label: "9:15pm", value: "21:15" },
        { label: "9:30pm", value: "21:30" },
        { label: "9:45pm", value: "21:45" },
        { label: "10:00pm", value: "22:00" },
        { label: "10:15pm", value: "22:15" },
        { label: "10:30pm", value: "22:30" },
        { label: "10:45pm", value: "22:45" },
        { label: "11:00pm", value: "23:00" },
        { label: "11:15pm", value: "23:15" },
        { label: "11:30pm", value: "23:30" },
        { label: "11:45pm", value: "23:45" },
    ];
    const calendarHours = () => {
        let arrResult = [];
        for (let i = 0; i < optionsShift.length; i += 4) {
            arrResult.push(
                optionsShift[i].label.replace(":00", " ").toUpperCase(),
            );
        }
        // console.log(arrResult);
        return arrResult;
    };
    const customTimePerAppointment = {
        time: "3",
        type: "horas",
    };
    const translateDays = {
        mon: "Lun",
        tue: "Mar",
        wed: "Mié",
        thu: "Jue",
        fri: "Vie",
        sat: "Sáb",
        sun: "Dom",
    };
    let showPaymentOptions = false;
    function handleCloseCustomTime() {
        $form.duration_per_appointment =
            $form.prev_value_duration_per_appointment;
    }
    let durationOptions = [
        { value: "15", label: "15 minutos" },
        { value: "30", label: "30 minutos" },
        { value: "45", label: "45 minutos" },
        { value: "60", label: "1 hora" },
        { value: "90", label: "1,5 horas" },
        { value: "120", label: "2 horas" },
        { value: "999999", label: "personalizar..." },
    ];

    function timeDifference(startTime, endTime) {
        // console.log(endTime);
        const [startHours, startMinutes] = startTime.split(":").map(Number);
        const [endHours, endMinutes] = endTime.split(":").map(Number);
        // Convert both times to total minutes
        const startTotalMinutes = startHours * 60 + startMinutes;
        const endTotalMinutes = endHours * 60 + endMinutes;

        // Calculate the difference
        const difference = endTotalMinutes - startTotalMinutes;
        return difference;
    }

    let sourceDiv;
    let targetDiv;
    let width = 0;

    function updateWidth() {
        if (sourceDiv) {
            width = sourceDiv.getBoundingClientRect().width;
        }
    }
    onMount(() => {
        updateWidth(); // Set the initial width
        window.addEventListener("resize", updateWidth);
    });

    onDestroy(() => {
        window.removeEventListener("resize", updateWidth);
    });
    const GetTop = (start) => {
        let [hours, minutes] = start.split(":");
        hours = parseInt(+hours, 10) + +minutes / 60;
        // console.log(start, (parseInt((+hours), 10)-4)*48)
        return (+hours - 4) * 48;
    };

    const GetHeight = (start, end) => {
        let [startHours, startMinutes] = start.split(":");
        startHours = parseInt(+startHours, 10) + +startMinutes / 60 - 4;

        let [endHours, endMinutes] = end.split(":");
        endHours = parseInt(+endHours, 10) + +endMinutes / 60 - 4;

        return +endHours - +startHours;
    };
    let database = {
        headerInfo: {
            month_year: "Septiembre de 2024",
            today: "2024-09-10T04:00:00.000Z",
        },
        calendar: {
            mon: {
                current_date: "2024-09-09T04:00:00.000Z",
                appointments: [
                    {
                        name: "Clarck Kent",
                        last_name: "Lopez",
                        ci: "27253194",
                        phone: "04124393123",
                        email: "clarito@gmail.com",
                        start: "09:00",
                        end: "10:00",
                    },
                ],
            },
            tue: { current_date: "2024-09-10T04:00:00.000Z", appointments: [] },
            wed: { current_date: "2024-09-11T04:00:00.000Z", appointments: [] },
            thu: {
                current_date: "2024-09-12T04:00:00.000Z",
                appointments: [
                    {
                        name: "Bruce javier",
                        last_name: "Wayne Henriquez",
                        ci: "27253194",
                        phone: "04124393123",
                        email: "brunito14@gmail.com",
                        start: "14:00",
                        end: "15:00",
                    },
                ],
            },
            fri: { current_date: "2024-09-13T04:00:00.000Z", appointments: [] },
            sat: { current_date: "2024-09-14T04:00:00.000Z", appointments: [] },
            sun: { current_date: "2024-09-15T04:00:00.000Z", appointments: [] },
        },
    };
    let databaseCurrentDate = new Date(database.headerInfo.today);
    const formatter = new Intl.DateTimeFormat("en-US", { weekday: "short" });

    // Get the abbreviated weekday name
    const currentDatabaseDay = formatter
        .format(databaseCurrentDate)
        .toLowerCase();
    console.log({ currentDatabaseDay });
    let shiftsForCalendar = {};

    function updateShiftsForCalendar() {
        Object.entries(database.calendar).forEach(([key, value], indx) => {
            let isItAjustedShift = $form.adjusted_availability.findIndex(
                (arrDates) => arrDates.date == value.current_date,
            );
            shiftsForCalendar = {
                ...shiftsForCalendar,
                [key]:
                    isItAjustedShift >= 0
                        ? $form.adjusted_availability[isItAjustedShift].shifts
                        : $form.availability[key],
            };
        });
    }

    $: {
        // updateShiftsForCalendar();
        console.log($form.adjusted_availability);
        console.log($form.availability);
    }
    $: $form, updateShiftsForCalendar();

    function calculateAppointments(availableHours, duration, rest) {
        // Calculate the number of appointments
        const totalTimeForAppointments = availableHours + rest; // Add rest time for one additional appointment
        const appointments = Math.floor(
            totalTimeForAppointments / (duration + rest),
        );

        // If there is enough time for the last appointment without needing an additional rest afterwards
        if (appointments * (duration + rest) - rest > availableHours) {
            return appointments - 1; // Subtract the last rest time if it exceeds available hours
        }

        return appointments;
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
    const GetStartAppointmets = (shift) => {
        console.log($form.time_between_appointment);
        let forAppointments = [];
        let amountOfAppointments = calculateAppointments(
            GetHeight(shift.start, shift.end),
            $form.duration_per_appointment / 60,
            $form.time_between_appointment / 60,
        );
        let lastTime = shift.start;
        for (let i = 0; i < amountOfAppointments; i++) {
            forAppointments.push({
                start_appo: lastTime,
            });
            lastTime = addMinutes(
                lastTime,
                +$form.duration_per_appointment +
                    +$form.time_between_appointment,
            );
        }
        return forAppointments;
        // Array.from({ length: calculateAppointments(GetHeight(shift.start, shift.end), $form.duration_per_appointment / 60, $form.time_between_appointment / 60) }, (_, index) => index)
    };

    function updateAllStartAppointmets() {
        let newAvaibility = {};
        Object.entries($form.availability).forEach(([key, shifts]) => {
            newAvaibility[key] = shifts.map((obj) => ({
                ...obj,
                appointments: GetStartAppointmets(obj),
            }));
        });

        let newAdjustedAvaibility = [];
        $form.adjusted_availability.forEach((obj) => {
            newAdjustedAvaibility.push({
                ...obj,
                shifts: obj.shifts.map((shift) => ({
                    ...shift,
                    appointments: GetStartAppointmets(shift),
                })),
            });
        });
        $form.availability = newAvaibility;
        $form.adjusted_availability = newAdjustedAvaibility;
    }
    let selectedAppointmentDetails = {};

    function convertTo12HourFormat(time24) {
        // Split the input into hours and minutes
        const [hours, minutes] = time24.split(":").map(Number);

        // Determine AM or PM suffix
        const suffix = hours >= 12 ? "PM" : "AM";

        // Convert hours to 12-hour format
        const hours12 = hours % 12 || 12; // If hours is 0, set it to 12

        // Format minutes to be two digits
        const formattedMinutes = String(minutes).padStart(2, "0");

        // Return the formatted time
        return `${hours12}:${formattedMinutes} ${suffix}`;
    }
</script>

<Modal
    bind:showModal
    onClose={() => {
        handleCloseCustomTime();

        // $form.duration_per_appointment=  $form.prev_value_duration_per_appointmen
    }}
>
    <p slot="header" class="font-bold text-lg text-gray-500">
        Duración personalizada
    </p>
    <div class="flex gap-3">
        <Input
            type="number"
            classes={"mt-0 border-none w-24"}
            inputClasses={"bg-gray-200 p-3 border-none  appearance-none"}
            bind:value={customTimePerAppointment.time}
        />

        <Input
            type="select"
            classes={"mt-0 w-36  border-none"}
            inputClasses={"p-3  bg-gray-200 "}
            bind:value={customTimePerAppointment.type}
        >
            <option value="minutos">minutos</option>
            <option value="horas">horas</option>
        </Input>
    </div>
    <button
        on:click={() => {
            let valueFixed =
                customTimePerAppointment.type == "horas"
                    ? customTimePerAppointment.time * 60
                    : customTimePerAppointment.time;
            if (!durationOptions.some((obj) => obj.value == valueFixed)) {
                let copyDurations = [...durationOptions];
                (copyDurations[7] = {
                    value: valueFixed,

                    label: `${customTimePerAppointment.time} ${customTimePerAppointment.type} `,
                }),
                    (durationOptions = copyDurations);
            }
            // if (customTimePerAppointment.)

            ($form.prev_value_duration_per_appointment = valueFixed),
                ($form.duration_per_appointment = valueFixed),
                (showModal = false);
        }}
        slot="btn_footer"
        type="button"
        class="text-color2 font-bold hover:bg-color2 p-2 hover:font-extrabold px-4 rounded-xl hover:bg-opacity-10"
        >Hecho</button
    >
</Modal>

<Modal bind:showModal={showModalFranja}>
    <p slot="header" class="font-bold text-lg text-gray-500">
        Fechas de inicio y finalización de la ventana de la agenda
    </p>

    <p class="opacity-80 mb-3">Empieza</p>
    <label class="flex gap-3 items-center mb-3">
        <input
            bind:group={$form.time_available_type}
            type="radio"
            class="w-5 h-5"
            name="time_available_type"
            value={1}
        />
        <span class="opacity-80">Ahora</span>
    </label>

    <label class="flex gap-3 items-center">
        <input
            bind:group={$form.time_available_type}
            type="radio"
            class="w-5 h-5"
            name="time_available_type"
            value={1}
        />
        <span>
            <DatePicker
                on:datechange={onDateChange}
                selected={currentDate}
                isAllowed={(date) => {
                    const millisecs = date.getTime();
                    if (millisecs + 25 * 3600 * 1000 < Date.now()) return false;
                    if (millisecs > Date.now() + 3600 * 24 * 45 * 1000)
                        return false;
                    return true;
                }}
            />
        </span>
    </label>

    <p class="opacity-80 mt-5 mb-4">Termina</p>
    <label class="flex gap-3 items-center mb-3">
        <input
            bind:group={$form.time_available_type}
            type="radio"
            class="w-5 h-5"
            name="time_available_type"
            value={1}
        />
        <span class="opacity-80">Nunca</span>
    </label>

    <label class="flex gap-3 items-center h-max">
        <input
            bind:group={$form.time_available_type}
            type="radio"
            class="w-5 h-5"
            name="time_available_type"
            value={1}
        />
        <span>
            <DatePicker
                on:datechange={onDateChange}
                selected={currentDate}
                isAllowed={(date) => {
                    const millisecs = date.getTime();
                    if (millisecs + 25 * 3600 * 1000 < Date.now()) return false;
                    if (millisecs > Date.now() + 3600 * 24 * 45 * 1000)
                        return false;
                    return true;
                }}
            />
        </span>
    </label>

    <button
        on:click={() => {
            let valueFixed =
                customTimePerAppointment.type == "horas"
                    ? customTimePerAppointment.time * 60
                    : customTimePerAppointment.time;
            if (!durationOptions.some((obj) => obj.value == valueFixed)) {
                let copyDurations = [...durationOptions];
                (copyDurations[7] = {
                    value: valueFixed,

                    label: `${customTimePerAppointment.time} ${customTimePerAppointment.type} `,
                }),
                    (durationOptions = copyDurations);
            }
            // if (customTimePerAppointment.)

            ($form.prev_value_duration_per_appointment = valueFixed),
                ($form.duration_per_appointment = valueFixed),
                (showModal = false);
        }}
        slot="btn_footer"
        type="button"
        class="text-color2 font-bold hover:bg-color2 p-2 hover:font-extrabold px-4 rounded-xl hover:bg-opacity-10"
        >Hecho</button
    >
</Modal>

<Modal bind:showModal={showModalappointments}>
    <div slot="header">
        <h2 class="text-lg font-bold text-center">
            {selectedAppointmentDetails.name}
            {selectedAppointmentDetails.last_name}
        </h2>
        <p class="font-bold text-color2 text-center">
            El {new Date(selectedAppointmentDetails.date).toLocaleDateString(
                "es-VE",
                {
                    weekday: "long",
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                },
            )}
        </p>
    </div>
    <div class="flex gap-5 h-28 relative">
        <div class="h-full w-full text-color2">
            <div
                class="bg-color2 absolute -left-5 shadow-inner bottom-0 rounded-r-full text-white w-max px-2 font-bold h-16 flex items-center"
            >
                <p class="">
                    {convertTo12HourFormat(selectedAppointmentDetails.start)}
                </p>
            </div>
        </div>
        <ul class="ml-24">
            <li class="flex items-center text-right justify-end gap-2 mb-2">
                {selectedAppointmentDetails.ci}
                <span
                    class="bg-color2 w-8 aspect-square rounded-full flex items-center justify-center text-white p-1"
                    ><iconify-icon icon="teenyicons:id-solid"
                    ></iconify-icon></span
                >
            </li>
            <li class="flex items-center text-right justify-end gap-2 mb-2">
                {selectedAppointmentDetails.email}
                <span
                    class="bg-color2 w-8 aspect-square rounded-full flex items-center justify-center text-white p-1"
                    ><iconify-icon icon="iconamoon:email-duotone"
                    ></iconify-icon></span
                >
            </li>
            <li class="flex items-center text-right justify-end gap-2 mb-2">
                {selectedAppointmentDetails.phone}
                <span
                    class="bg-color2 w-8 aspect-square rounded-full flex items-center justify-center text-white p-1"
                    ><iconify-icon icon="ri:phone-fill"></iconify-icon></span
                >
            </li>
        </ul>
    </div>
</Modal>

<section class="flex gap-4 justify-between">
    <div class="min-w-[450px] w-[470px]">
        <!-- <div class="sticky top-1">
            <DatePicker
                on:datechange={(e) => console.log(e)}
                selected={database.headerInfo.today}
                showDatePickerAlways={true}
                whitInput={false}
                isAllowed={(date) => {
                    const millisecs = date.getTime();
                    if (millisecs + 25 * 3600 * 1000 < Date.now()) return false;
                    if (millisecs > Date.now() + 3600 * 24 * 45 * 10000)
                        return false;
                    return true;
                }}
            />
          
        </div> -->
        <form
            class=" bg-gray-100 p-3 pl-0 rounded pt-5 sticky top-1 h-screen overflow-y-scroll overflow-x-hidden pr-2"
            action=""
            on:submit={(e) => {
                e.preventDefault();
            }}
        >
            <fieldset class="border-b border-gray-300 items-center pb-4">
                <h2>CONFIGURAR CITAS DISPONIBLES</h2>

                <Input
                    type="text"
                    required={true}
                    label={"Titulo"}
                    labelClasses={"font-bold w-11/12"}
                    inputClasses={"text-2xl  p-1 px-3 bg-gray-200 w-11/12 "}
                    bind:value={$form.title}
                    error={$form.errors?.title}
                />
            </fieldset>

            <fieldset class="border-b border-gray-300 flex gap-4 pb-4">
                <iconify-icon icon="lets-icons:time-atack" class="mt-4"
                ></iconify-icon>
                <Input
                    type="select"
                    required={true}
                    labelClasses={"font-bold"}
                    label={"Duración de cada cita"}
                    classes={"mt-3 w-auto"}
                    bind:value={$form.duration_per_appointment}
                    on:change={(e) => {
                        if (e.target.value == "999999") {
                            $form.prev_value_duration_per_appointment =
                                $form.duration_per_appointment;
                            showModal = true;
                            $form.duration_per_appointment = "";
                        }
                        // console.log('se ejecutó esto?')
                        updateAllStartAppointmets();
                    }}
                    error={$form.errors?.duration_per_appointment}
                    inputClasses={"bg-gray-200 px-2"}
                >
                    {#each durationOptions.slice().sort((a, b) => {
                        return parseInt(a.value) - parseInt(b.value);
                    }) as { value, label }}
                        <option {value}>{label}</option>
                    {/each}
                </Input>
            </fieldset>

            <fieldset class="mt-6 border-b border-gray-300 pb-5 flex gap-4">
                <span>
                    <iconify-icon icon="icon-park-outline:time"></iconify-icon>
                </span>
                <section>
                    <legend class="font-bold">Disponibilidad general</legend>
                    <small
                        >Indica qué disponibilidad sueles tener para citas</small
                    >
                    <ul class=" flex flex-col space-y-2 mt-4 gap-y-1">
                        <!-- svelte-ignore empty-block -->
                        {#each Object.entries($form.availability) as [day, shifts] (day)}
                            <li class="flex gap-4 justify-">
                                <span class="w-9 mt-2"
                                    >{translateDays[day]}</span
                                >
                                {#if shifts.length >= 1}
                                    <div class="gap-2 flex flex-col">
                                        {#each shifts as shift, indx (day + "_" + indx)}
                                            <div>
                                                <div
                                                    class="flex gap-3 max-w-[340px]"
                                                >
                                                    <span
                                                        class="flex w-48 items-center justify-between bg-gray-200"
                                                    >
                                                        <Input
                                                            type="select"
                                                            classes={"mt-0 border-none flex-1"}
                                                            required={true}
                                                            inputClasses={"bg-gray-200  p-3 border-none appearance-none"}
                                                            bind:value={$form
                                                                .availability[
                                                                day
                                                            ][indx].start}
                                                            error={$form.errors
                                                                ?.availability?.[
                                                                day
                                                            ][indx].start}
                                                            on:change={(e) => {
                                                                console.log(
                                                                    e.target
                                                                        .value,
                                                                );

                                                                $form.availability[
                                                                    day
                                                                ][
                                                                    indx
                                                                ].appointments =
                                                                    GetStartAppointmets(
                                                                        {
                                                                            ...shift,
                                                                            start: e
                                                                                .target
                                                                                .value,
                                                                        },
                                                                    );
                                                                // Array.from({ length: calculateAppointments(GetHeight(shift.start, shift.end), $form.duration_per_appointment / 60, $form.time_between_appointment / 60) }, (_, index) => index)
                                                            }}
                                                        >
                                                            {#each optionsShift as shiftOption (shiftOption.value)}
                                                                {#if indx == 0 || shiftOption.value >= $form.availability[day][indx - 1]?.end}
                                                                    <option
                                                                        value={shiftOption.value}
                                                                    >
                                                                        {shiftOption.label}
                                                                    </option>
                                                                {/if}
                                                            {/each}
                                                        </Input>
                                                        <span
                                                            class="p-1 px-1 font-bold"
                                                        >
                                                            -
                                                        </span>
                                                        <Input
                                                            type="select"
                                                            classes={"mt-0 flex-1 text-right"}
                                                            inputClasses={"bg-gray-200 text-right p-3 border-none appearance-none"}
                                                            bind:value={$form
                                                                .availability[
                                                                day
                                                            ][indx].end}
                                                            on:change={(e) => {
                                                                $form.availability[
                                                                    day
                                                                ][
                                                                    indx
                                                                ].appointments =
                                                                    GetStartAppointmets(
                                                                        {
                                                                            ...shift,
                                                                            end: e
                                                                                .target
                                                                                .value,
                                                                        },
                                                                    );
                                                                // Array.from({ length: calculateAppointments(GetHeight(shift.start, shift.end), $form.duration_per_appointment / 60, $form.time_between_appointment / 60) }, (_, index) => index)
                                                            }}
                                                            required={true}
                                                        >
                                                            <option value=""
                                                            ></option>
                                                            {#each optionsShift as shiftOption (shiftOption.value)}
                                                                {#if shiftOption.value > $form.availability[day][indx].start}
                                                                    <option
                                                                        value={shiftOption.value}
                                                                    >
                                                                        {shiftOption.label}
                                                                    </option>
                                                                {/if}
                                                            {/each}
                                                        </Input>
                                                        <!-- {} -->
                                                    </span>
                                                    <span
                                                        class="grid grid-cols-3 items-center gap-3 text-xl"
                                                    >
                                                        <button
                                                            on:click={(e) => {
                                                                $form.availability[
                                                                    day
                                                                ] = [
                                                                    ...$form.availability[
                                                                        day
                                                                    ].filter(
                                                                        (
                                                                            v,
                                                                            i,
                                                                        ) =>
                                                                            i !=
                                                                            indx,
                                                                    ),
                                                                ];
                                                            }}
                                                            type="button"
                                                            class="cursor-pointer hover:font-bold hover:bg-gray-200 rounded-full"
                                                            title="No disponible"
                                                        >
                                                            <iconify-icon
                                                                icon="ic:baseline-block"
                                                            ></iconify-icon>
                                                        </button>
                                                        {#if indx == 0}
                                                            <button
                                                                on:click={(
                                                                    e,
                                                                ) => {
                                                                    $form.availability[
                                                                        day
                                                                    ] = [
                                                                        ...$form
                                                                            .availability[
                                                                            day
                                                                        ],
                                                                        {
                                                                            start:
                                                                                (
                                                                                    +shifts[
                                                                                        shifts.length -
                                                                                            1
                                                                                    ].end.split(
                                                                                        ":",
                                                                                    )[0] +
                                                                                    1
                                                                                )
                                                                                    .toString()
                                                                                    .padStart(
                                                                                        2,
                                                                                        "0",
                                                                                    ) +
                                                                                ":00",
                                                                            end:
                                                                                (
                                                                                    +shifts[
                                                                                        shifts.length -
                                                                                            1
                                                                                    ].end.split(
                                                                                        ":",
                                                                                    )[0] +
                                                                                    2
                                                                                )
                                                                                    .toString()
                                                                                    .padStart(
                                                                                        2,
                                                                                        "0",
                                                                                    ) +
                                                                                ":00",
                                                                            appointments:
                                                                                GetStartAppointmets(
                                                                                    {
                                                                                        start:
                                                                                            (
                                                                                                +shifts[
                                                                                                    shifts.length -
                                                                                                        1
                                                                                                ].end.split(
                                                                                                    ":",
                                                                                                )[0] +
                                                                                                1
                                                                                            )
                                                                                                .toString()
                                                                                                .padStart(
                                                                                                    2,
                                                                                                    "0",
                                                                                                ) +
                                                                                            ":00",
                                                                                        end:
                                                                                            (
                                                                                                +shifts[
                                                                                                    shifts.length -
                                                                                                        1
                                                                                                ].end.split(
                                                                                                    ":",
                                                                                                )[0] +
                                                                                                2
                                                                                            )
                                                                                                .toString()
                                                                                                .padStart(
                                                                                                    2,
                                                                                                    "0",
                                                                                                ) +
                                                                                            ":00",
                                                                                    },
                                                                                ),
                                                                        },
                                                                    ];
                                                                }}
                                                                type="button"
                                                                class="cursor-pointer hover:font-bold hover:bg-gray-200 rounded-full"
                                                                title="Añadir otro turno a este día"
                                                            >
                                                                <iconify-icon
                                                                    icon="gala:add"
                                                                ></iconify-icon>
                                                            </button>
                                                            <button
                                                                on:click={(
                                                                    e,
                                                                ) => {
                                                                    let copyDay =
                                                                        [
                                                                            ...$form
                                                                                .availability[
                                                                                day
                                                                            ],
                                                                        ]; // Shallow copy for array of objects
                                                                    Object.keys(
                                                                        $form.availability,
                                                                    ).forEach(
                                                                        (
                                                                            days,
                                                                        ) => {
                                                                            $form.availability[
                                                                                days
                                                                            ] =
                                                                                copyDay.map(
                                                                                    (
                                                                                        shift,
                                                                                    ) => ({
                                                                                        ...shift,
                                                                                    }),
                                                                                ); // Create a new object for each shift
                                                                        },
                                                                    );
                                                                }}
                                                                type="button"
                                                                class="cursor-pointer hover:font-bold hover:bg-gray-200 rounded-full"
                                                                title="Copiar este horario en todos"
                                                            >
                                                                <iconify-icon
                                                                    class="text-2xl"
                                                                    icon="material-symbols-light:content-copy-outline"
                                                                ></iconify-icon>
                                                            </button>
                                                        {/if}
                                                    </span>
                                                </div>
                                                <div class="relative pr-3">
                                                    <p
                                                        class="text-red text-sm leading-4"
                                                    >
                                                        {#if timeDifference($form.availability[day][indx].start, $form.availability[day][indx].end) < $form.duration_per_appointment}
                                                            La duración de la
                                                            cita es mayor que el
                                                            intervalo de tiempo
                                                        {/if}
                                                    </p>
                                                </div>
                                            </div>
                                        {/each}
                                    </div>
                                {:else}
                                    <p class="opacity-60 w-48 py-2">
                                        No disponible
                                    </p>
                                    <span
                                        class="grid grid-cols-3 items-center gap-3 text-xl"
                                    >
                                        <span> </span>

                                        <button
                                            on:click={(e) => {
                                                $form.availability[day] = [
                                                    ...$form.availability[day],
                                                    {
                                                        start: "08:00",
                                                        end: "16:00",
                                                    },
                                                ];
                                            }}
                                            type="button"
                                            class="relative cursor-pointer hover:font-bold hover:bg-gray-200 rounded-full"
                                            title="Añadir otro turno a este día"
                                        >
                                            <iconify-icon icon="gala:add"
                                            ></iconify-icon>
                                        </button>
                                        <span> </span>
                                    </span>
                                {/if}
                            </li>
                        {/each}
                    </ul>
                </section>
            </fieldset>

            <fieldset class="mt-2 border-b border-gray-300 pb-4 flex gap-4">
                <span>
                    <iconify-icon icon="fa-solid:exchange-alt" class="pt-2"
                    ></iconify-icon>
                </span>
                <section class="w-full">
                    <!-- svelte-ignore a11y-click-events-have-key-events -->
                    <div
                        class="p-2 flex justify-between hover:bg-gray-200 cursor-pointer w-full"
                        on:click={(e) => {
                            acordion.franja = !acordion.franja;
                        }}
                    >
                        <div>
                            <legend class="font-bold"
                                >Franja de programación</legend
                            >
                            <small class="inline-block"
                                >Desde 60 días de antelación hasta 4 horas antes</small
                            >
                        </div>
                        <iconify-icon icon="iconamoon:arrow-down-2-duotone"
                        ></iconify-icon>
                    </div>
                    {#if acordion.franja}
                        <div class="p-2 mt-1">
                            <label class="flex gap-3 items-center mb-3">
                                <input
                                    bind:group={$form.time_available_type}
                                    type="radio"
                                    class="w-5 h-5"
                                    name="time_available_type"
                                    value={1}
                                />
                                <span>Ya disponible</span>
                            </label>
                            <label class="flex gap-3 items-center mb-3">
                                <input
                                    bind:group={$form.time_available_type}
                                    type="radio"
                                    class="w-5 h-5"
                                    name="time_available_type"
                                    value={2}
                                    on:change={() => {
                                        if ($form.time_available_type == 2) {
                                            showModalFranja = true;
                                        }
                                    }}
                                />
                                <div class="leading-4">
                                    <p>Fechas de inicio y finalización</p>
                                    <small
                                        >Limita el intervalo de fechas en todas
                                        las citas</small
                                    >
                                </div>
                            </label>

                            <small class="mt-4 mb-2 inline-block"
                                >Tiempo máximo antes de la cita con el que se
                                puede reservar
                            </small>
                            <span class={`flex items-center gap-3`}>
                                <input
                                    type="checkbox"
                                    class="w-6 h-6"
                                    bind:checked={$form.allow_max_reservation_time_before_appointment}
                                />
                                <Input
                                    type="number"
                                    disabled={!$form.allow_max_reservation_time_before_appointment}
                                    classes={"w-16 mt-0"}
                                    bind:value={$form.max_reservation_time_before_appointment}
                                    error={$form.errors
                                        ?.max_reservation_time_before_appointment}
                                />
                                <span
                                    class={`${!$form.allow_max_reservation_time_before_appointment ? "opacity-80" : ""}`}
                                >
                                    días
                                </span>
                            </span>
                            <small class="mt-4 mb-2 inline-block"
                                >Tiempo máximo antes de la cita con el que se
                                puede reservar
                            </small>
                            <span class={` flex items-center gap-3`}>
                                <input
                                    type="checkbox"
                                    class="w-6 h-6"
                                    bind:checked={$form.allow_min_reservation_time_before_appointment}
                                />
                                <Input
                                    type="number"
                                    disabled={!$form.allow_min_reservation_time_before_appointment}
                                    classes={"w-16 mt-0"}
                                    bind:value={$form.min_reservation_time_before_appointment}
                                    error={$form.errors
                                        ?.min_reservation_time_before_appointment}
                                />
                                <span
                                    class={`${!$form.allow_min_reservation_time_before_appointment ? "opacity-80" : ""}`}
                                >
                                    horas
                                </span>
                            </span>
                        </div>
                    {/if}
                </section>
            </fieldset>

            <fieldset class="mt-2 border-b border-gray-300 pb-4 flex gap-1">
                <span class="pt-2">
                    <iconify-icon icon="ant-design:reload-time-outline"
                    ></iconify-icon>
                </span>
                <section class="p-2">
                    <legend class="font-bold">Disponibilidad ajustada</legend>
                    <small class="mb-5 inline-block"
                        >Indica a qué horas estás disponible en fechas concretas</small
                    >
                    <ul
                        class="flex flex-col space-y-2 mt-2 gap-y-1 relative -left-3"
                    >
                        {#each $form.adjusted_availability as adjust_date, indxDate}
                            <li class="flex gap-2 justify-between">
                                <span class="h-max">
                                    <DatePicker
                                        on:datechange={(e) =>
                                            onDateChange(e, indxDate)}
                                        selected={adjust_date.date}
                                        isAllowed={(date) => {
                                            const millisecs = date.getTime();
                                            if (
                                                millisecs + 25 * 3600 * 1000 <
                                                Date.now()
                                            )
                                                return false;
                                            if (
                                                millisecs >
                                                Date.now() +
                                                    3600 * 24 * 45 * 10000
                                            )
                                                return false;
                                            return true;
                                        }}
                                    />
                                    {#if $form.adjusted_availability.some((obj, i) => obj.date == adjust_date.date && i != indxDate)}
                                        <p class="text-red text-sm leading-4">
                                            Fechas iguales han sido añadidas más
                                            de una vez
                                        </p>
                                    {/if}
                                </span>
                                <!-- <input
                                    type="date"
                                    class="px-2 p-1 flex-grow-0 h-10"
                                    bind:value={$form.adjusted_availability[
                                        indxDate
                                    ].date}
                                /> -->
                                {#if adjust_date.shifts.length >= 1}
                                    <div class="gap-2 flex flex-col">
                                        {#each adjust_date.shifts as shift, indxShift}
                                            <div class="flex gap-3">
                                                <span
                                                    class="flex w-48 items-center justify-between bg-gray-200"
                                                >
                                                    <Input
                                                        type="select"
                                                        classes={"mt-0 border-none flex-auto text-center"}
                                                        bind:value={$form
                                                            .adjusted_availability[
                                                            indxDate
                                                        ].shifts[indxShift]
                                                            .start}
                                                        on:change={(e) => {
                                                            $form.adjusted_availability[
                                                                indxDate
                                                            ].shifts[
                                                                indxShift
                                                            ].appointments =
                                                                GetStartAppointmets(
                                                                    {
                                                                        ...shift,
                                                                        start: e
                                                                            .target
                                                                            .value,
                                                                    },
                                                                );
                                                            // Array.from({ length: calculateAppointments(GetHeight(shift.start, shift.end), $form.duration_per_appointment / 60, $form.time_between_appointment / 60) }, (_, index) => index)
                                                        }}
                                                        inputClasses={"bg-gray-200 p-3 px-2 border-none appearance-none"}
                                                    >
                                                        {#each optionsShift as shiftOption (shiftOption.value)}
                                                            {#if indxShift == 0 || shiftOption.value >= adjust_date.shifts[indxShift - 1]?.end}
                                                                <option
                                                                    value={shiftOption.value}
                                                                >
                                                                    {shiftOption.label}
                                                                </option>
                                                            {/if}
                                                        {/each}
                                                    </Input>
                                                    <span
                                                        class="p-1 px-1 font-bold"
                                                    >
                                                        -
                                                    </span>
                                                    <Input
                                                        type="select"
                                                        classes={"mt-0 flex-auto text-center"}
                                                        inputClasses={"bg-gray-200 p-3  px-2 border-none appearance-none"}
                                                        bind:value={$form
                                                            .adjusted_availability[
                                                            indxDate
                                                        ].shifts[indxShift].end}
                                                        on:change={(e) => {
                                                            $form.adjusted_availability[
                                                                indxDate
                                                            ].shifts[
                                                                indxShift
                                                            ].appointments =
                                                                GetStartAppointmets(
                                                                    {
                                                                        ...shift,
                                                                        end: e
                                                                            .target
                                                                            .value,
                                                                    },
                                                                );
                                                            // Array.from({ length: calculateAppointments(GetHeight(shift.start, shift.end), $form.duration_per_appointment / 60, $form.time_between_appointment / 60) }, (_, index) => index)
                                                        }}
                                                    >
                                                        <option value=""
                                                        ></option>
                                                        {#each optionsShift as shiftOption (shiftOption.value)}
                                                            {#if shiftOption.value > $form.adjusted_availability[indxDate].shifts[indxShift].start}
                                                                <option
                                                                    value={shiftOption.value}
                                                                >
                                                                    {shiftOption.label}
                                                                </option>
                                                            {/if}
                                                        {/each}
                                                    </Input>
                                                </span>
                                                <span
                                                    class="grid grid-cols-3 items-center gap-4 text-xl flex-auto"
                                                >
                                                    <button
                                                        on:click={(e) => {
                                                            $form.adjusted_availability[
                                                                indxDate
                                                            ] = {
                                                                ...adjust_date,
                                                                shifts: adjust_date.shifts.filter(
                                                                    (v, i) =>
                                                                        i !=
                                                                        indxShift,
                                                                ),
                                                            };
                                                        }}
                                                        type="button"
                                                        class="cursor-pointer hover:font-bold hover:bg-gray-200 rounded-full"
                                                        title="No disponible"
                                                    >
                                                        <iconify-icon
                                                            icon="ic:baseline-block"
                                                        ></iconify-icon>
                                                    </button>
                                                    {#if indxShift == 0}
                                                        <button
                                                            on:click={(e) => {
                                                                $form.adjusted_availability[
                                                                    indxDate
                                                                ] = {
                                                                    ...adjust_date,
                                                                    shifts: [
                                                                        ...adjust_date.shifts,
                                                                        {
                                                                            start:
                                                                                (
                                                                                    +adjust_date.shifts[
                                                                                        adjust_date
                                                                                            .shifts
                                                                                            .length -
                                                                                            1
                                                                                    ].end.split(
                                                                                        ":",
                                                                                    )[0] +
                                                                                    1
                                                                                )
                                                                                    .toString()
                                                                                    .padStart(
                                                                                        2,
                                                                                        "0",
                                                                                    ) +
                                                                                ":00",
                                                                            end:
                                                                                (
                                                                                    +adjust_date.shifts[
                                                                                        adjust_date
                                                                                            .shifts
                                                                                            .length -
                                                                                            1
                                                                                    ].end.split(
                                                                                        ":",
                                                                                    )[0] +
                                                                                    2
                                                                                )
                                                                                    .toString()
                                                                                    .padStart(
                                                                                        2,
                                                                                        "0",
                                                                                    ) +
                                                                                ":00",
                                                                        },
                                                                    ],
                                                                };
                                                            }}
                                                            type="button"
                                                            class="cursor-pointer hover:font-bold hover:bg-gray-200 rounded-full"
                                                            title="Añadir otro turno a este día"
                                                        >
                                                            <iconify-icon
                                                                icon="gala:add"
                                                            ></iconify-icon>
                                                        </button>
                                                        <button
                                                            on:click={(e) => {
                                                                $form.adjusted_availability =
                                                                    $form.adjusted_availability.filter(
                                                                        (
                                                                            v,
                                                                            i,
                                                                        ) =>
                                                                            i !=
                                                                            indxDate,
                                                                    );
                                                            }}
                                                            type="button"
                                                            class="cursor-pointer hover:font-bold hover:bg-gray-200 rounded-full"
                                                            title="Eliminar esta fecha"
                                                        >
                                                            <iconify-icon
                                                                icon="ic:outline-close"
                                                            ></iconify-icon>
                                                            <!-- <iconify-icon
                                                        class="text-2xl"
                                                        icon="material-symbols-light:content-copy-outline"
                                                    ></iconify-icon> -->
                                                        </button>
                                                    {/if}
                                                </span>
                                            </div>
                                        {/each}
                                    </div>
                                {:else}
                                    <p class="opacity-60 w-48 py-2">
                                        No disponible
                                    </p>
                                    <span
                                        class="grid grid-cols-3 items-center gap-3 text-xl pt-1.5"
                                    >
                                        <span> </span>

                                        <button
                                            on:click={(e) => {
                                                $form.adjusted_availability[
                                                    indxDate
                                                ] = {
                                                    ...$form
                                                        .adjusted_availability[
                                                        indxDate
                                                    ],
                                                    shifts: [
                                                        ...adjust_date.shifts,
                                                        {
                                                            start: "08:00",
                                                            end: "16:00",
                                                        },
                                                    ],
                                                };
                                            }}
                                            type="button"
                                            class="relative cursor-pointer hover:font-bold hover:bg-gray-200 rounded-full"
                                            title="Añadir otro turno a este día"
                                        >
                                            <iconify-icon icon="gala:add"
                                            ></iconify-icon>
                                        </button>
                                        <button
                                            on:click={(e) => {
                                                $form.adjusted_availability =
                                                    $form.adjusted_availability.filter(
                                                        (v, i) => i != indxDate,
                                                    );
                                            }}
                                            type="button"
                                            class="cursor-pointer hover:font-bold hover:bg-gray-200 rounded-full"
                                            title="Eliminar esta fecha"
                                        >
                                            <iconify-icon
                                                icon="ic:outline-close"
                                            ></iconify-icon>
                                            <!-- <iconify-icon
                                    class="text-2xl"
                                    icon="material-symbols-light:content-copy-outline"
                                ></iconify-icon> -->
                                        </button>
                                        <span> </span>
                                    </span>
                                {/if}
                            </li>
                        {/each}
                    </ul>
                    <!-- svelte-ignore a11y-click-events-have-key-events -->
                    <label
                        on:click={() => {
                            $form.adjusted_availability = [
                                ...$form.adjusted_availability,
                                {
                                    date: currentDate,
                                    shifts: [{ start: "08:00", end: "16:00" }],
                                },
                            ];
                        }}
                        for="date1"
                        class="cursor-pointer text-color2 font-bold p-1 px-3 rounded hover:bg-color2 hover:bg-opacity-10 mt-3 inline-block"
                        >Cambiar la diponibilidad en una fecha</label
                    >
                </section>
            </fieldset>

            <fieldset class="mt-2 pb-4 flex gap-4">
                <span>
                    <iconify-icon class="pt-2" icon="mdi:calendar-check"
                    ></iconify-icon>
                </span>
                <section>
                    <!-- svelte-ignore a11y-click-events-have-key-events -->
                    <div
                        class="p-2 flex justify-between hover:bg-gray-200 cursor-pointer w-full"
                        on:click={(e) => {
                            acordion.ajustesCitasReservadas =
                                !acordion.ajustesCitasReservadas;
                        }}
                    >
                        <div>
                            <legend class="font-bold"
                                >Ajustes de citas reservadas</legend
                            >
                            <small class="leading-4 inline-block">
                                Gestionar las citas reservadas que aparecerán en
                                tu calendario</small
                            >
                        </div>
                        {#if acordion.ajustesCitasReservadas}
                            <iconify-icon icon="iconamoon:arrow-up-2-duotone"
                            ></iconify-icon>
                        {:else}
                            <iconify-icon icon="iconamoon:arrow-down-2-duotone"
                            ></iconify-icon>
                        {/if}
                    </div>
                    {#if acordion.ajustesCitasReservadas}
                        <div class="p-2 mt-1">
                            <div>
                                <div class="leading-5 mb-1">
                                    <p class="mt-2 inline-block font-bold">
                                        Duración del periodo entre citas
                                    </p>
                                    <small class="inline-block">
                                        Añade tiempo entre una cita y otra
                                    </small>
                                </div>

                                <span
                                    class={`${!$form.time_between_appointment ? "opacity-80" : ""} flex items-center gap-3`}
                                >
                                    <input
                                        type="checkbox"
                                        class="w-6 h-6"
                                        name=""
                                        id=""
                                        bind:checked={$form.time_between_appointment}
                                        on:change={(e) => {
                                            if (e.target.checked) {
                                                $form.time_between_appointment =
                                                    defaulTtime_between_appointment;
                                            } else {
                                                $form.time_between_appointment = 0;
                                            }
                                            updateAllStartAppointmets();
                                        }}
                                    />
                                    <Input
                                        type="number"
                                        classes={"w-16 mt-0"}
                                        disabled={!$form.time_between_appointment}
                                        inputClasses={"p-3 ray-50 w-16"}
                                        min={0}
                                        value={$form.time_between_appointment ||
                                            defaulTtime_between_appointment}
                                        on:change={(e) => {
                                            $form.time_between_appointment =
                                                e.target.value;
                                            defaulTtime_between_appointment =
                                                $form.time_between_appointment;
                                            updateAllStartAppointmets();
                                        }}
                                        error={$form.errors
                                            ?.time_between_appointment}
                                    />
                                    <p>minutos</p>
                                </span>
                                {#if $form.time_between_appointment < 0}
                                    <p class="text-red text-sm">
                                        No puede ser menor a cero
                                    </p>
                                {/if}
                            </div>

                            <div>
                                <div class="leading-5 mb-1 mt-1">
                                    <p class="mt-4 inline-block font-bold">
                                        Máximo de reservas por día
                                    </p>
                                    <small class="inline-block">
                                        Limitar cuántas citas reservadas se
                                        pueden aceptar en un día
                                    </small>
                                </div>

                                <span
                                    class={`${!$form.allow_max_appointment_per_day ? "opacity-80" : ""} flex items-center gap-3`}
                                >
                                    <input
                                        type="checkbox"
                                        class="w-6 h-6"
                                        bind:checked={$form.allow_max_appointment_per_day}
                                    />
                                    <Input
                                        type="number"
                                        disabled={!$form.allow_max_appointment_per_day}
                                        classes={"w-16 mt-0"}
                                        inputClasses={"p-3 ray-50 w-16"}
                                        bind:value={$form.max_appointment_per_day}
                                        error={$form.errors
                                            ?.max_appointment_per_day}
                                    />
                                </span>
                            </div>
                        </div>
                    {/if}
                </section>
            </fieldset>
        </form>
    </div>

    <!-- // calendar -->
    <section>
        <header class=" sticky top-0 pt-1 bg-gray-100 z-30 calendarHeader">
            <div class="flex gap-4 items-center">
                <button
                    class="text-md font-bold border border-gray-300 rounded-md p-2 px-6 hover:bg-gray-200"
                    >Hoy</button
                >
                <div class="mx-5 flex gap-2">
                    <button
                        class="text-2xl text-gray-900 rounded-full aspect-square hover:bg-gray-200 flex items-center w-10"
                    >
                        <iconify-icon
                            icon="iconamoon:arrow-left-2-bold"
                            class="relative left-2"
                        ></iconify-icon></button
                    >
                    <button
                        class="text-2xl text-gray-900 rounded-full aspect-square hover:bg-gray-200 flex items-center w-10"
                    >
                        <iconify-icon
                            icon="iconamoon:arrow-right-2-bold"
                            class="relative left-2"
                        ></iconify-icon></button
                    >
                </div>
                <h2 class="text-2xl">{database.headerInfo.month_year}</h2>
            </div>

            <div
                class="py-5 w-max pt-10 flex"
                bind:this={sourceDiv}
                on:resize={updateWidth}
            >
                <div class="w-10 max-w-[40px]"></div>
                <ul class="flex listCalendarHeader">
                    {#each Object.entries(database.calendar) as [day, values], indxDay (day)}
                        <li
                            class="flex flex-col justify-center text-center w-28"
                        >
                            <p
                                class={` ${values.current_date == database.headerInfo.today ? "text-color1 " : ""}`}
                            >
                                {translateDays[day].toUpperCase()}
                            </p>
                            <p
                                class={`text-2xl mx-auto w-12 aspect-square rounded-full flex items-center justify-center ${values.current_date == database.headerInfo.today ? "bg-color1 text-gray-50 " : ""}`}
                            >
                                {new Date(values.current_date).getUTCDate()}
                            </p>
                        </li>
                    {/each}
                </ul>
            </div>
        </header>

        <body
            bind:this={targetDiv}
            class="flex z-10 relative"
            style={`width: ${width}px`}
        >
            <ul class="text-xs">
                <li
                    class="h-12 border-t border-gray-300 block w-full"
                    style={`width: ${width}px`}
                >
                    <div class="w-10 max-w-[40px] text-right"></div>
                </li>
                {#each calendarHours() as hour (hour)}
                    <li
                        class="h-12 border-t border-gray-300 block text-gray-600 w-full"
                        style={`width: ${width}px`}
                    >
                        <div
                            class="w-10 max-w-[40px] text-right relative -top-2.5 -left-2 pr-1.5 bg-gray-100"
                        >
                            {hour}
                        </div>
                    </li>
                {/each}
            </ul>
            <!-- <div class="w-10 max-w-[40px]">
            </div> -->
            {#each Object.entries(shiftsForCalendar) as [day, shifts], indxDay (day)}
                {#if shifts.length >= 1 && shifts[0].start != ""}
                    <div
                        class={`gap-2 flex flex-col z-30 ${database.calendar[day].current_date < database.headerInfo.today ? "opacity-40" : ""} `}
                    >
                        {#each shifts as shift, indx (day + "_" + indx)}
                            <div
                                class="flex gap-3 w-28 h-12 absolute duration-300 z-30 px-0.5"
                                style={`top: ${GetTop(shift.start)}px; left: ${40 + (112 * indxDay + 1)}px; 
                                    height: ${GetHeight(shift.start, shift.end) * 48}px
                                   `}
                            >
                                <div
                                    class="bg-color4 bg-opacity-60 border z-30 border-color1 border-opacity-25 px-1 rounded-md text-color1 w-full"
                                >
                                    {#each shift.appointments as xxx}
                                        <div
                                            class={`bg-color1 bg-opacity-30 w-[98%] mx-auto h-full rounded-lg ${$form.time_between_appointment < 5 ? "border-b-4 border-color4" : ""}`}
                                            style={`margin-bottom: ${(+$form.time_between_appointment / 60) * 48}px ;height: ${(GetHeight(shift.start, shift.end) * 48) / ((GetHeight(shift.start, shift.end) * 60) / $form.duration_per_appointment)}px`}
                                        ></div>
                                    {/each}
                                </div>
                            </div>
                        {/each}
                    </div>
                {/if}
            {/each}

            {#each Object.entries(database.calendar) as [day, values], indxDay (day)}
                {#each values.appointments as appointment, indx (day + "_" + indx)}
                    <div
                        class="flex gap-3 w-28 h-12 absolute duration-300 z-50 px-0.5"
                        style={`top: ${GetTop(appointment.start)}px; left: ${40 + (112 * indxDay + 1)}px; 
                            height: ${GetHeight(appointment.start, appointment.end) * 48}px
                           `}
                    >
                        <div class=" z-50 px-1 w-full">
                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                            <div
                                class={`cursor-pointer hover:bg-color1 text-center bg-color3 ${database.calendar[day].current_date < database.headerInfo.today ? "opacity-40" : ""}  w-[98%] h-full mx-auto p-1 rounded-lg ${$form.time_between_appointment < 5 ? "border-b-4 border-color4" : ""}`}
                                on:click={() => {
                                    showModalappointments = true;
                                    selectedAppointmentDetails = {
                                        ...appointment,
                                        date: values.current_date,
                                    };
                                }}
                            >
                                <h4 class="text-white text-sm">
                                    {appointment.name.split(" ")[0]}
                                    {appointment.last_name.split(" ")[0]}
                                </h4>
                                <span class="invisible"></span>
                            </div>
                        </div>
                    </div>
                {/each}
            {/each}
        </body>
    </section>
</section>

<style>
    .calendarHeader:before {
        content: "";
        position: absolute;
        height: 160px;
        width: 10px;
        background-color: rgb(243 244 246);
        top: 0px;
        left: -10px;
    }
    .listCalendarHeader li {
        position: relative;
    }
    .listCalendarHeader li::before {
        content: "";
        position: absolute;
        height: calc(100vh - 100px);
        width: 1px;
        background-color: rgb(221, 221, 221);
        top: 45px;
        z-index: -1 !important;
    }
    option:selected {
        background-color: red;
    }
    .octagon {
        clip-path: polygon(
            30% 0%,
            70% 0%,
            100% 30%,
            100% 70%,
            70% 100%,
            30% 100%,
            0% 70%,
            0% 30%
        );
    }
</style>
