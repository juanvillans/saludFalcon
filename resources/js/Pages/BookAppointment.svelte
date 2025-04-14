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
        if (screenZise <= 820) {
            numberOfDays = 2;
        }
        if (screenZise >= 1220) {
            numberOfDays = 7;
        }
        console.log(numberOfDays);

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
    export let data = {};
    console.log({ data });
    let form;
    const debouncedUpdate = debounce(updateWidth, 300);

    onMount(() => {
        updateWidth(); // Set the initial width
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
            },
            calendar_id: "",
            day_reserved: "",
            time_reserved: "",
        });
    });

    onDestroy(() => {
        window.removeEventListener("resize", debouncedUpdate);
    });

    let dataFront = {
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
    export let calendar = {
        // weekDays: {
        //     mon: {
        //         appointments: {
        //             "08:00": {
        //                 name: "juanito",
        //                 last_name: "Perez",
        //                 correo: "juanvillans16@gmail.com",
        //             },
        //         },
        //         current_date: "2025-04-07T04:00:00.000000Z",
        //     },
        //     tue: {
        //         appointments: {
        //             "09:00": {
        //                 name: "Juan",
        //                 last_name: "Donquis",
        //                 correo: "juanvillans16@gmail.com",
        //             },
        //         },
        //         current_date: "2025-04-08T04:00:00.000000Z",
        //     },
        //     wed: {
        //         appointments: {
        //             "08:00": {
        //                 name: "Douglas",
        //                 last_name: "Villasmil",
        //                 correo: "juanvillans16@gmail.com",
        //             },
        //             "09:00": {
        //                 name: "Deivis",
        //                 last_name: "Donquis",
        //                 correo: "juanvillans16@gmail.com",
        //             },
        //         },
        //         current_date: "2025-05-07T04:00:00.000Z",
        //     },
        //     thu: {
        //         appointments: {},
        //         current_date: "2025-04-10T04:00:00.000000Z",
        //     },
        //     fri: {
        //         appointments: {},
        //         current_date: "2025-04-11T04:00:00.000000Z",
        //     },
        //     sat: {
        //         appointments: {
        //             "08:00": {
        //                 name: "juanito",
        //                 last_name: "Perez",
        //                 correo: "juanvillans16@gmail.com",
        //             },
        //         },
        //         current_date: "2025-04-12T04:00:00.000000Z",
        //     },
        //     sun: {
        //         appointments: {},
        //         current_date: "2025-04-13T04:00:00.000000Z",
        //     },
        // },
    };
    let shiftsForCalendar = {};

    function updateShiftsForCalendar() {
        console.log(data.data.adjusted_availability);
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
        console.log({ shiftsForCalendar });
    }

    $: frontCalendar, updateShiftsForCalendar();

    $: console.log({ $form });

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
                EnglishWeekday: nextDate
                    .toLocaleDateString("en-US", {
                        weekday: "short",
                    })
                    .toLocaleLowerCase(),
                day: nextDate.toLocaleDateString("es-VE", {
                    day: "numeric",
                }),
            });
        }

        frontCalendar = result;
        focusedDate = startDate;
        updateCalendar();
        return result;
    }

    function updateCalendar(type) {
        router.get(
            window.location.pathname,
            {
                start_date: frontCalendar[0].date,
                end_date: frontCalendar[frontCalendar.length - 1].date,
            },
            {
                onSuccess: (page) => {
                    updateShiftsForCalendar();
                },
            },
        );
    }

    let focusedDate = dataFront.today;
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    getNextNDays(today, numberOfDays);
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
                    message: "Ok todo salió bien",
                });
                displayAlert({ type: "error", message: "error" });

                // $form.defaults({ ...page.props.data.data });
                $form.reset();
            },
        });
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
        <div class="flex md:block">
            <h1 class=" md:text-2xl mx-auto uppercase">
                <span class="text-dark opacity-60 block text-md relative top-2"
                    >{data.data.user_specialty_name}
                </span>
                <span class="text-xl md:text-2xl">{data.data.title}</span>
            </h1>
            <div class="flex gap-3">
                <iconify-icon
                    icon="lets-icons:time-atack"
                    class="mt-1 text-xl text-gray-500"
                ></iconify-icon>
                <p>Citas de {data.data.duration_per_appointment} minutos</p>
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
                    if (dataFront.availableDays[date]) {
                        return true;
                    } else {
                        false;
                    }
                }}
                isAllowed={(date) => {
                    // console.log(date);
                    const millisecs = date.getTime();
                    if (millisecs + 25 * 3600 * 1000 < Date.now()) return false;
                    if (millisecs > Date.now() + 3600 * 24 * 45 * 10000)
                        return false;
                    return true;
                }}
            />
        </div>

        <div>
            <header class="  pt-1 bg-gray-100 z-30 calendarHeader">
                <div class="flex gap-4 items-center">
                    <!-- <h2 class="text-2xl">{dataFront.headerInfo.month_year}</h2> -->
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
                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                            <li class="flex flex-col text-center w-28">
                                <p
                                    class={`text-sm lg:text-md ${objDate.date == dataFront.today ? "text-color1 " : ""}`}
                                >
                                    {objDate.weekday.toUpperCase()}
                                </p>
                                <p
                                    class={`text-lg lg:text-2xl mx-auto w-12 aspect-square rounded-full flex items-center justify-center ${objDate.date == dataFront.today ? "bg-color1 text-gray-50 " : ""}`}
                                >
                                    {objDate.day}
                                </p>
                                <div class="grid gap-2 mt-7">
                                    {#each shiftsForCalendar?.[objDate.EnglishWeekday] as shift, indx (objDate.day + "_" + indx)}
                                        {#each shift.appointments as appointment, i ("start_app" + "_" + i)}
                                            {#if !calendar.weekDays[objDate.EnglishWeekday + "_" + objDate.date.slice(0, 10)]?.appointments[appointment.start_appo]}
                                                <button
                                                    on:click={() => {
                                                        showModal = true;
                                                        $form.day_reserved =
                                                            objDate.date;
                                                        $form.time_reserved =
                                                            appointment.start_appo;
                                                        $form.calendar_id =
                                                            data.data.id;
                                                    }}
                                                    class="text-sm xl:text-md py-2 border-color1 border rounded hover:bg-color3 duration-75 hover:text-white bg-color4"
                                                    >{convertTo12HourFormat(
                                                        appointment.start_appo,
                                                    )}</button
                                                >
                                            {/if}
                                        {/each}
                                    {/each}
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

<Modal
    bind:showModal
    onClose={() => {
        // handleCloseCustomTime();
    }}
>
    <p slot="header" class="font-bold text-lg text-gray-500">
        Llena los campos para reservar tu cita
    </p>

    <form id="a-form" on:submit={handleSubmit}>
        <Input
            required={true}
            type={"number"}
            label={"C.I *"}
            name={"ci"}
            min={100000}
            placeholder={"Minimo 6 números"}
            on:wheel={(e) => document.activeElement.blur()}
            bind:value={$form.ci}
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
            readOnly={$form.appointment_data.email}
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
                bind:value={$form.appointment_data[field.name.toLocaleLowerCase()]}
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
