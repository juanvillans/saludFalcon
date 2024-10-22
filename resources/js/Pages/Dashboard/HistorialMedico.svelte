<script>
    import Table from "../../components/Table.svelte";
    import Modal from "../../components/Modal.svelte";
    import Input from "../../components/Input.svelte";
    import axios from "axios";
    import debounce from "lodash/debounce";

    // import Alert from "../../components/Alert.svelte";

    import { displayAlert } from "../../stores/alertStore";
    import { useForm, inertia, router, page } from "@inertiajs/svelte";
    export let data = {};
    // console.log(data.data[0].cases)

    $: if (data) {
        if (data?.data?.[0]?.cases instanceof Array == false) {
            data?.data?.forEach((patient) => {
                patient.cases = JSON.parse(patient.cases);
            });
        }
    }
    console.log({ data });
    let instituteSpecialities = [];
    let specialities = [];
    // Update data based on the current state of `data.specialties`
    const today = new Date();

    // Format the date to YYYY-MM-DD
    const formattedDate = today.toISOString().split("T")[0];

    const hours = String(today.getHours()).padStart(2, "0");
    const minutes = String(today.getMinutes()).padStart(2, "0");
    const formattedTime = `${hours}:${minutes}`;

    const emptyDataForm = {
        patient_id: null,
        patient_name: "",
        patient_last_name: "",
        patient_ci: "",
        patient_sex: "",
        patient_date_birth: "",
        patient_phone_number: "",

        cases: [],
        newCase: {
            doctor: {
                id: $page.props.auth.user_id,
                name: $page.props.auth.name,
                last_name: $page.props.auth.last_name,
            },
            start_date: formattedDate,
            end_date: formattedDate,
            start_time: formattedTime,
            end_time: formattedTime,
            treatment: "",
            diagnosis: "",
            status: "Alta",
        },
    };
    let form = useForm(structuredClone(emptyDataForm));
    // $: console.log({ $form });

    // console.log($form.newCase.diagnosis);
    let visulizateType = "table";
    let showModal = false;
    // $: console.log($form);
    let selectedRow = { status: false, id: 0 };

    document.addEventListener("keydown", ({ key }) => {
        if (key === "Escape") {
            selectedRow = { status: false, id: 0 };
        }
    });

    function handleSubmit(event) {
        event.preventDefault();
        if ($form.cases.length == 0) {
            $form.cases = [$form.newCase];
        } else {
            console.log($form.cases);
            $form.cases = [$form.newCase, ...$form.cases];
        }
        $form.clearErrors();
        console.log(emptyDataForm);
        if (submitStatus == "Crear") {
            $form.post("/admin/historial-medico", {
                onError: (errors) => {
                    if (errors.data) {
                        displayAlert({ type: "error", message: errors.data });
                    }

                    $form.cases.shift();
                },
                onSuccess: (mensaje) => {
                    // $form.defaults()
                    $form.reset();
                    $form.newCase = { ...emptyDataForm.newCase };
                    displayAlert({
                        type: "success",
                        message: "Nuevo Caso creado con exito",
                    });
                    showModal = false;
                },
            });
        } else if (submitStatus == "Editar") {
            $form.put(`/admin/historial-medico/${$form.id}`, {
                onError: (errors) => {
                    if (errors.data) {
                        displayAlert({ type: "error", message: errors.data });
                    }
                },
                onSuccess: (mensaje) => {
                    $form.reset();
                    displayAlert({
                        type: "success",
                        message: "Nuevo Caso creado con exito",
                    });
                    showModal = false;
                    selectedRow = { status: false, id: 0, row: {} };
                },
            });
        }
    }

    function handleDelete(id) {
        $form.delete(`/admin/historial-medico/${id}`, {
            onBefore: () => confirm(`¿Está seguro de eliminar a este usuario?`),
            onError: (errors) => {
                if (errors.data) {
                    displayAlert({ type: "error", message: errors.data });
                }
            },
            onSuccess: (mensaje) => {
                displayAlert({
                    type: "success",
                    message: "Usuario Eliminado",
                });
                selectedRow = { status: false, id: 0, row: {} };
            },
        });
    }

    function fillFormToEdit() {
        submitStatus = "Editar";
        $form.reset();
        showModal = true;
    }

    function searchPatient(ci) {
        router.get(
            "/admin/patient",
            { ci },
            {
                replace: true,
                preserveState: true,
                onSuccess: (page) => {
                    showModal = true;
                    if (page.props.patient == null) {
                        displayAlert({
                            type: "error",
                            message: "No se encontró,",
                        });

                        return;
                    }
                    $form = { ...$form, ...page.props.patient.data };
                },
                onFinish: (visit) => {
                    console.log(visit);
                    prosecingSearchPatient = false;
                },
            },
        );
    }
    let submitStatus = "Crear";
    let prosecingSearchPatient = false;

    function convertTo12HourFormat(time24) {
        // Split the input into hours and minutes
        let [hours, minutes] = time24.split(":").map(Number);

        // Determine AM or PM suffix
        const suffix = hours >= 12 ? "PM" : "AM";

        // Convert hours to 12-hour format
        hours = hours % 12 || 12; // Converts 0 to 12 for midnight

        // Format minutes to always show two digits
        minutes = minutes <= 10 ? "0" + minutes : minutes;

        // Return the formatted time
        return `${hours}:${minutes} ${suffix}`;
    }

    function formatDateSpanish(dateString) {
        const options = {
            year: "numeric",
            month: "short",
            day: "numeric",
        };

        const date = new Date(dateString);
        const formattedDate = date.toLocaleDateString("es-ES", options);

        return formattedDate;
    }

    function timeBetweenDateAndTime(
        startDate,
        startTime,
        endDate,
        endTime,
        status,
    ) {
        // Combine date and time strings into a single Date object
        if (status == "Permanencia") {
            const now = new Date(); // Get the current date and time
            endDate = now.toISOString().split("T")[0]; // Format to YYYY-MM-DD
            endTime = now.toTimeString().split(" ")[0].substring(0, 5); // Format to HH:mm
        }
        console.log(startDate, startTime, endDate, endTime, status);
        const startDateTime = new Date(`${startDate}T${startTime}`);
        const endDateTime = new Date(`${endDate}T${endTime}`);

        const diffInMs = endDateTime - startDateTime; // Difference in milliseconds
        const diffInMinutes = Math.floor(diffInMs / (1000 * 60));
        const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
        const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

        if (diffInDays > 0) {
            return `${diffInDays} Dia${diffInDays > 1 ? "s" : ""}`;
        } else if (diffInHours > 0) {
            return `${diffInHours} Hora${diffInHours > 1 ? "s" : ""}`;
        } else {
            return `${diffInMinutes} Minuto${diffInMinutes > 1 ? "s" : ""}`;
        }
    }

    function getStatusColor(status) {
        switch (status) {
            case "Permanencia":
                return "#F3BA2F";
                break;
            case "Alta":
                return "#397373";
                break;
            case "Fallecido":
                return "#BF0404";
                break;
            case "Remitido":
                return "#6a1ccd";
                break;
            default:
                break;
        }
    }
</script>

<svelte:head>
    <title>Historial médico</title>
</svelte:head>

<Modal bind:showModal modalClasses={"max-w-[560px]"}>
    <p slot="header">Registrar un nuevo caso</p>
    <form id="a-form" on:submit={handleSubmit} action="">
        <fieldset
            class="px-5 mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color2 rounded-md"
        >
            <legend
                class="text-center px-5 py-1 pt-1.5 rounded-sm bg-color2 text-gray-100"
                >DATOS DEL PACIENTE</legend
            >
            <div class="flex gap-1 items-end">
                <Input
                    type="number"
                    required={true}
                    label={"C.I *"}
                    bind:value={$form.patient_ci}
                    error={$form.errors?.patient_ci}
                />
                <button
                    type="button"
                    title="Buscar si el paciente existe"
                    class="bg-color4 h-fit px-2 rounded hover:bg-color1 hover:text-white"
                    on:click={() => {
                        prosecingSearchPatient = true;
                        searchPatient($form.patient_ci);
                    }}
                >
                    {#if prosecingSearchPatient == true}
                        <iconify-icon
                            class="mt-2 text-2xl"
                            icon="eos-icons:bubble-loading"
                        ></iconify-icon>
                    {:else}
                        <iconify-icon
                            class="mt-2 text-2xl"
                            icon="material-symbols:search"
                        ></iconify-icon>
                    {/if}
                </button>
            </div>
            <Input
                type="text"
                required={true}
                label={"Nombres del paciente *"}
                bind:value={$form.patient_name}
                error={$form.errors?.patient_name}
            />
            <Input
                type="text"
                required={true}
                label={"Apellidos del paciente *"}
                bind:value={$form.patient_last_name}
                error={$form.errors?.patient_last_name}
            />

            <Input
                type="date"
                required={true}
                label={"Fecha de Nacimiento*"}
                bind:value={$form.patient_date_birth}
                error={$form.errors?.patient_date_birth}
            />
            <div class="flex flex-col mt-6">
                <label class="py-1 cursor-pointer hover:bg-gray-100">
                    <input
                        class="mr-3 inline-block"
                        type="radio"
                        bind:group={$form.patient_sex}
                        value="Masculino"
                        name="sex"
                        id=""
                    /><span class:font-bold={$form.patient_sex == "Masculino"}
                        >Masculino</span
                    >
                </label>

                <label class="py-1 cursor-pointer hover:bg-gray-100">
                    <input
                        class="mr-3 inline-block"
                        type="radio"
                        bind:group={$form.patient_sex}
                        value="Femenino"
                        name="sex"
                        id=""
                    /><span class:font-bold={$form.patient_sex == "Femenino"}
                        >Femenino</span
                    >
                </label>
            </div>

            <Input
                type="tel"
                label={"Teléfono"}
                bind:value={$form.patient_phone_number}
                error={$form.errors?.patient_phone_number}
            />
        </fieldset>

        <fieldset
            class="px-5 mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color2 rounded-md"
        >
            <legend
                class="text-center px-5 py-1 pt-1.5 rounded-sm bg-color2 text-gray-100"
                >DIAGNÓSTICO Y TRATAMIENTO</legend
            >
            <Input
                type="textarea"
                required={true}
                classes={"col-span-2"}
                label={"Diagnóstico *"}
                bind:value={$form.newCase.diagnosis}
                error={$form?.errors?.newCase?.diagnosis}
            />

            <Input
                type="textarea"
                required={true}
                classes={"col-span-2"}
                label={"Tratamiento *"}
                bind:value={$form.newCase.treatment}
                error={$form.errors?.newCase?.treatment}
            />
        </fieldset>

        <fieldset
            class="px-5 mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color2 rounded-md"
        >
            <legend
                class="text-center px-5 py-1 pt-1.5 rounded-sm bg-color2 text-gray-100"
                >DURACIÓN DE LA EMERGENCIA</legend
            >
            <Input
                type="date"
                required={true}
                label={"Fecha de entrada *"}
                bind:value={$form.newCase.start_date}
                error={$form.errors?.newCase?.start_date}
            />

            <Input
                type="time"
                required={true}
                label={"Hora de entrada *"}
                bind:value={$form.newCase.start_time}
                error={$form.errors?.newCase?.start_time}
            />

            <Input
                type="select"
                required={true}
                label={"Estado *"}
                bind:value={$form.newCase.status}
                error={$form.errors?.newCase?.status}
            >
                <option value="Alta"> <span class="block p-1 text-white" style={`background-color: ${getStatusColor("Alta")};`}>  </span> Alta </option>
                <option value="Permanencia"> <span class="block p-1 text-white" style={`background-color: ${getStatusColor("Permanencia")};`}> </span> Permanencia </option>
                <option value="Remitido"> <span class="block p-1 text-white" style={`background-color: ${getStatusColor("Remitido")};`}> </span> Remitido </option>
                <option value="Fallecido"> <span class="block p-1 text-white" style={`background-color: ${getStatusColor("Fallecido")};`}> </span> Fallecido </option>
            </Input>
            {#if $form.newCase.status !== "Permanencia"}
                <Input
                    type="date"
                    label={"Fecha de salida "}
                    bind:value={$form.newCase.end_date}
                    error={$form.errors?.newCase?.end_date}
                />
                <Input
                    type="time"
                    required={true}
                    label={"Hora de salida *"}
                    bind:value={$form.newCase.end_time}
                    error={$form.errors?.newCase?.end_time}
                />
            {/if}
        </fieldset>
    </form>
    <input
        form="a-form"
        slot="btn_footer"
        type="submit"
        value={$form.processing ? "Cargando..." : submitStatus}
        class="hover:bg-color3 hover:text-white duration-200 mt-auto w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
    />
</Modal>

<div class="flex justify-between items-center">
    <button
        class="btn_create inline-block p-1 px-2 md:p-2 md:px-3"
        on:click={(e) => {
            if (submitStatus == "Editar") {
                selectedRow = {
                    status: false,
                    id: 0,
                    title: "",
                };
                // console.log(emptyDataForm)

                $form.defaults({
                    ...emptyDataForm,
                });
                setTimeout(() => {
                    $form.reset();
                }, 100);
            }
            e.preventDefault();

            showModal = true;
            submitStatus = "Crear";
        }}
    >
        <span class="md:hidden relative top-1 font-bold"
            ><iconify-icon icon="ic:round-add" style="font-size: 20px;"
            ></iconify-icon></span
        >
        <span class="hidden md:block"> Nuevo caso </span>
    </button>
</div>

<Table
    {selectedRow}
    on:fillFormToEdit={fillFormToEdit}
    on:clickDeleteIcon={() => {
        handleDelete(selectedRow.id);
    }}
    pagination={{...data?.meta, ...data.links}}
>
    <div slot="filterBox"></div>
    <thead slot="thead" class="sticky top-0 z-50">
        <tr>
            <th>N°</th>
            <th>Duración</th>
            <th>Estado</th>
            <th>Paciente</th>
            <th>Diagnóstico</th>
            <th>Tratamiento</th>
            <th>Doctor</th>
        </tr>
    </thead>

    <tbody slot="tbody">
        {#if data?.data?.length > 0}
            {#each data?.data as row, i (row.id)}
                <tr
                    on:click={(e) => {
                        // let newSelectedRowStatus = !selectedRow.status;
                        if (row.id != selectedRow.id) {
                            selectedRow = {
                                status: true,
                                id: row.id,
                                title: row.title,
                            };
                            $form.defaults({
                                ...row,
                                specialties_ids: row.specialties.map(
                                    (obj) => obj.id,
                                ),
                            });
                            $form.clearErrors();
                        } else {
                            selectedRow = {
                                status: false,
                                id: 0,
                                title: "",
                            };
                            $form.defaults({
                                ...emptyDataForm,
                            });
                        }
                    }}
                    class={`md:max-h-[200px] overflow-hidden cursor-pointer  ${selectedRow.id == row.id ? "bg-color2 hover:bg-opacity-10 bg-opacity-10 brightness-110" : " hover:bg-gray-500 hover:bg-opacity-5"}`}
                >
                    <td>{i + 1}</td>
                    <td>
                        {timeBetweenDateAndTime(
                            row.cases?.[0]?.start_date,
                            row.cases?.[0]?.start_time,
                            row.cases?.[0]?.end_date,
                            row.cases?.[0]?.end_time,
                            row.cases?.[0]?.status,
                        )}

                        <!-- {formatDateSpanish(row.cases[0].start_date)} -->
                    </td>
                    <td>
                        <span
                            class={`block px-1 py-0.5 rounded-md`}
                            style={`background-color: ${getStatusColor(row.cases[0].status)};`}
                        >
                            {row.cases?.[0]?.status}
                        </span>
                    </td>
                    <td class="">
                        <div class="flex items-center gap-2">
                            {#if row.patient_sex == "Femenino"}
                                <span class="text-pink text-2xl">
                                    <iconify-icon icon="fa-solid:female"
                                    ></iconify-icon>
                                </span>
                            {:else}
                                <span class="text-color3 text-2xl">
                                    <iconify-icon icon="fa-solid:male"
                                    ></iconify-icon>
                                </span>
                            {/if}
                            <span
                                >{row.patient_name}
                                {row.patient_last_name}
                                - {row.patient_ci}
                            </span>
                        </div>
                    </td>
                    <td
                        class="max-w-[340px] min-w-[320px] max-h-[100px] overflow-hidden"
                        style="white-space: normal;"
                    >
                        {#if row.cases?.[0]?.diagnosis.length > 240}
                            {row.cases?.[0]?.diagnosis.slice(0, 240)}
                            <span
                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                >...</span
                            >
                        {:else}
                            {row.cases?.[0]?.diagnosis}
                        {/if}
                    </td>
                    <!-- <td>{row.sex}</td> -->
                    <td
                        class="max-w-[340px] min-w-[320px] max-h-[100px] overflow-hidden"
                        style="white-space: normal;"
                    >
                        {#if row.cases?.[0]?.treatment.length > 240}
                            {row.cases?.[0]?.treatment.slice(0, 240)}
                            <span
                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                >...</span
                            >
                        {:else}
                            {row.cases?.[0]?.treatment}
                        {/if}
                    </td>
                    <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                    <td>
                        {row.cases?.[0]?.doctor.name}
                        {row.cases?.[0]?.doctor.last_name}
                    </td>
                </tr>
            {/each}
        {/if}
    </tbody>
</Table>

{#if visulizateType == "card"}
    <div class="grid gap-3">
        {#each data?.data as row, i (row.id)}
            <article
                class={`w-full cursor-pointer bg-gray-50 p-2 md:p-5 rounded-md shadow-sm ${selectedRow.id == row.id ? "bg-color2 hover:bg-opacity-10 bg-opacity-10 brightness-110" : " hover:bg-gray-500 hover:bg-opacity-5"}`}
            >
                <div class="flex justify-between bg-g">
                    <span>{i + 1}</span>
                    <p>
                        {formatDateSpanish(row.cases[0].start_date)}
                        <span class="opacity-60">-</span>
                        {convertTo12HourFormat(row.cases[0].start_time)}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    {#if row.patient_sex == "Femenino"}
                        <span class="text-pink text-2xl">
                            <iconify-icon icon="fa-solid:female"></iconify-icon>
                        </span>
                    {:else}
                        <span class="text-color3 text-2xl">
                            <iconify-icon icon="fa-solid:male"></iconify-icon>
                        </span>
                    {/if}
                    <span
                        >{row.patient_name}
                        {row.patient_last_name}
                        - {row.patient_ci}
                    </span>
                </div>

                <p>
                    <b>Diag.:</b>
                    {#if row.cases[0].diagnosis.length > 240}
                        {row.cases[0].diagnosis.slice(0, 240)}
                        <span
                            class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                            >...</span
                        >
                    {:else}
                        {row.cases[0].diagnosis}
                    {/if}
                </p>
                <!-- <td>{row.sex}</td> -->
                <p class="my-2">
                    <b>Trat.:</b>
                    {#if row.cases[0].treatment.length > 240}
                        {row.cases[0].treatment.slice(0, 240)}
                        <span
                            class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                            >...</span
                        >
                    {:else}
                        {row.cases[0].treatment}
                    {/if}
                </p>
                <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                <p
                    class="text-right justify-end w-full flex items-center gap-2"
                >
                    {row.cases[0].doctor.name}
                    {row.cases[0].doctor.last_name}
                    <iconify-icon icon="mdi:doctor" style="font-size: 20px;"
                    ></iconify-icon>
                </p>
            </article>
        {/each}
    </div>
{/if}

<style>
</style>
