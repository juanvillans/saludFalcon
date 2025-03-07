<script>
    import Input from "../../components/Input.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm } from "@inertiajs/svelte";
    import StatusColor from "../../components/StatusColor.svelte";
    import Alert from "../../components/Alert.svelte";
    import fetchLocalData from "../../components/localData";
    import { onMount } from "svelte";

    import Editor from "cl-editor/src/Editor.svelte";
    export let data = [];
    export let areas = [];

    export let patient = false;
    // export let = {};
    let nroEvol = data.nroEvol;
    let nroInter = data.nroInter;
    let countingCases;
    let form = useForm(structuredClone(data.user));
    let evolutionForm = useForm({
        diagnosis: "",
        treatment: "",
        departure_date: "",
        departure_hour: "",
        area_id: "",
        patient_condition_id: "",
        status_id: 6,
        destiny: "",
    });
    let localData = {};

    onMount(async () => {
        try {
            localData = await fetchLocalData();
            // console.log(fetchLocalData())
        } catch (error) {
            console.error("Error loading data:", error);
        }
    });
    $: if (patient) {
        countingCases = data.cases.length;
    }
    let editor;
    let descriptionLength = 0;
    let openAccordeon = -1;

    let editStatus = false;

    function getFirstName(firstName) {
        console.log(firstName);
        const parts = firstName.split(" ");
        return parts[0];
    }

    function convertTo12HourFormat(time24) {
        console.log(time24);
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
    let showAllCases = window.screen.width > 700 ? true : false;
    let isOpenCreateEvolution = false;

    function updateClient(event) {
        event.preventDefault();
        $form.clearErrors();
        $form.put(
            "/admin/historial-medico/detalle-paciente/" + $form.patient_id,
            {
                onError: (errors) => {
                    if (errors.data) {
                        displayAlert({ type: "error", message: errors.data });
                    }
                },
                onSuccess: (mensaje) => {
                    displayAlert({
                        type: "success",
                        message: "Paciente editado exitosamente!",
                    });
                },
            },
        );
    }

    function submitCases(event) {
        event.preventDefault();
        $evolutionForm.clearErrors();
        $evolutionForm.post(
            "/admin/casos/detalle-caso/" + $form.id + "/evolution",
            {
                onError: (errors) => {
                    if (errors.data) {
                        displayAlert({ type: "error", message: errors.data });
                    }
                },
                onSuccess: (mensaje) => {
                    displayAlert({
                        type: "success",
                        message: "Caso editado exitosamente!",
                    });
                    editStatus = false;
                },
            },
        );
    }

    function handleDelete() {
        $form.post("/admin/historial-medico", {
            onBefore: () => {
                if (confirm(`¿Está seguro de eliminar este caso?`)) {
                    $form.cases = $form.cases.slice(1);
                }
            },
            onError: (errors) => {
                if (errors.data) {
                    displayAlert({ type: "error", message: errors.data });
                }
            },
            onSuccess: (mensaje) => {
                displayAlert({
                    type: "success",
                    message: "Caso Eliminado exitosamente",
                });
            },
        });
    }
</script>

<div class="flex flex-col lg:flex-row gap-2 md:gap-5">
    <form
        on:submit={updateClient}
        class="order-2 lg:order-1 h-fit max-w-[330px] w-full lg:sticky top-0"
    >
        <fieldset
            class=" px-5 mt-4 gap-x-5 text-black p-6 pt-2 border-color2 rounded-md"
        >
            <legend
                class="relative text-center px-5 py-1 pt-1.5 rounded-xl bg-gray-50 text-dark"
                >Datos del usuario</legend
            >
            <Input
                type="text"
                required={true}
                label={"Nombres"}
                bind:value={$form.name}
                error={$form.errors?.name}
            />
            <Input
                type="text"
                required={true}
                label={"Apellidos"}
                bind:value={$form.last_name}
                error={$form.errors?.last_name}
            />
            <Input
                type="email"
                label="correo"
                bind:value={$form.email}
                error={$form.errors?.email}
            />
            <Input
                type="number"
                required={true}
                label={"Cédula"}
                bind:value={$form.ci}
                error={$form.errors?.ci}
            />
            <Input
                type="tel"
                label={"Teléfono"}
                bind:value={$form.phone_number}
                error={$form.errors?.phone_number}
            />
            <!-- <Input
                type="select"
                required={true}
                label={"Tipo de Usuario"}
                bind:value={$form.role_name}
                error={$form.errors?.role_name}
                disabled={true}
            >
                <option value="doctor">Doctor</option>
                <option value="admin">Admin</option>
            </Input> -->
            <Input
                label={"Matrícula médica"}
                required={true}
                bind:value={$form.medical_license}
                error={$form.errors?.medical_license}
            />
            <Input
                type="select"
                required={true}
                label={"Servicio tratante"}
                bind:value={$form.specialty_id}
                error={$form.errors?.specialty_id}
                disabled={true}
            >
                {#each localData.specialties || [] as speci (speci.id)}
                    <option value={speci.id}>{speci.name}</option>
                {/each}
            </Input>
            {#if $form.isDirty}
                <input
                    type="submit"
                    value={$form.processing ? "Cargando..." : "Actualizar"}
                    class="hover:bg-color3 hover:text-white duration-200 mt-3 w-full col-span-2 bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
                />
            {/if}
        </fieldset>
    </form>

    <form on:submit={submitCases} class="order-1 lg:order-2 w-full">
        <fieldset
            class=" sm:px-5 md:mt-4 gap-x-5 pt-5 bg-gray-200 rounded-2xl pb-4"
        >
            <legend
                class="relative text-center px-5 py-1 pt-1.5 rounded-xl bg-color3 text-gray-100"
                >Movimientos del usuario</legend
            >

            <div class="col-span-2 flex gap-5 md:mt-5 mb-5">
                <div value="" class="neumorphism2 p-3 rounded-2xl">
                    <h3 class="font-bold text-gray-800 text-sm">
                        Nro de evoluciones
                    </h3>
                    <p>{nroEvol}</p>
                </div>
                <div value="" class="neumorphism2 p-3 rounded-2xl">
                    <h3 class="font-bold text-gray-800 text-sm">
                        Nro de Interconsultas
                    </h3>
                    <p>{nroInter}</p>
                </div>
            </div>
            <ul>
                {#each data.evolutions.data as evolution, i (evolution.id)}
                    <li
                        class="bg-gray-50 mb-3 border rounded-lg overflow-hidden neumorphism"
                    >
                        <span
                            class="flex items-center gap-2 p-2 md:pr-4 lg:pr-6 justify-between"
                        >
                            <div
                                class="flex flex-col sm:flex-row items-center gap-2 p-2 justify-between"
                            >
                                <StatusColor
                                    status={{
                                        name: evolution.status_name,
                                        id: evolution.status_id,
                                    }}
                                />
                                {#if evolution.status_id == 1 || evolution.status_id == 2}
                                    de
                                {:else if evolution.status_id == 4 || evolution.status_id == 5}
                                    en
                                    <!-- {:else if evolution.status_id == 3} -->
                                    a
                                {/if}
                                {evolution.area_name}
                            </div>
                            <div class="flex gap-2">
                                {#if evolution.is_interconsult}
                                    <span
                                        class="pl-6 pr-1 listType bg-color1 font-bold pt-1.5 pb-0.5 text-xs text-white mr-2 uppercase"
                                    >
                                        IC
                                    </span>
                                {:else}
                                    <span
                                        class="pl-6 pr-1 listType bg-color3 font-bold pt-1.5 pb-0.5 text-xs text-white mr-2 uppercase"
                                    >
                                        EVOL
                                    </span>
                                {/if}
                                <span class="flex items-center">
                                    <span class="text-xs">
                                        <span class="text-gray-500">el</span>
                                        {evolution.formatted_created_at}
                                    </span>
                                </span>
                            </div>
                        </span>

                        <div class="bg-white p-3 md:p-4 lg:p-5 space-y-2">
                            {#if evolution.status_id == 4}
                                <div>
                                    <h3 class="font-semibold">Fecha y hora:</h3>
                                    <p class="text-dark">
                                        El {evolution.formatted_entry_date} a las
                                        {evolution.entry_hour}
                                    </p>
                                </div>
                                <div>
                                    <h3 class="font-semibold">
                                        Motivo de consulta:
                                    </h3>
                                    <p class="text-dark">
                                        {evolution.reason}
                                    </p>
                                </div>
                            {/if}
                            {#if evolution.status_id !== 6 && evolution.status_id !== 4}
                                <div>
                                    <h3 class="font-semibold">Fecha y hora:</h3>
                                    <p class="text-dark">
                                        {evolution.formatted_departure_date} a las{evolution.departure_hour}
                                    </p>
                                </div>
                            {/if}
                            <div>
                                <div class="flex">
                                    <h3 class="font-semibold">Diagnostico:</h3>

                                    <div>
                                        <span
                                            class={`ml-2 w-2 inline-block aspect-square rounded-full condition${evolution.patient_condition_id}`}
                                        ></span>
                                        <span
                                            class="opacity-90 uppercase text-xs"
                                            >{evolution.patient_condition_name}</span
                                        >
                                    </div>
                                </div>
                                {#if evolution.diagnosis}
                                    <p class="text-dark">
                                        {evolution.diagnosis}
                                    </p>
                                {/if}
                            </div>

                            <div>
                                {#if evolution.treatment}
                                    <h3 class="font-semibold">
                                        Orden médica de ingreso:
                                    </h3>
                                    <p class="text-dark">
                                        {evolution.treatment}
                                    </p>
                                {/if}
                            </div>
                        </div>
                    </li>
                {/each}
            </ul>
        </fieldset>
    </form>
</div>
<Alert />

<style>
    legend::after {
        content: " ";
        position: absolute;
        background-color: hsl(208, 41%, 57%, 0.4);
        left: -10px;
        bottom: 8.5px;
        height: 18px;
        width: 10px;
        border-radius: 10px 0 0 10px;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }
    legend::before {
        content: " ";
        position: absolute;
        background-color: hsl(208, 41%, 57%, 0.4);
        right: -10px;
        bottom: 8.5px;
        height: 18px;
        width: 10px;
        border-radius: 0 10px 10px 0;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }
    .listType {
        clip-path: polygon(100% 10%, 100% 51%, 100% 90%, 0 90%, 13% 53%, 0 15%);
    }
</style>
