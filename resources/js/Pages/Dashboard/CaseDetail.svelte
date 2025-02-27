<script>
    import Input from "../../components/Input.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, page } from "@inertiajs/svelte";
    import StatusColor from "../../components/StatusColor.svelte";
    import Alert from "../../components/Alert.svelte";
    import fetchLocalData from "../../components/localData";
    import { onMount } from "svelte";

    import Editor from "cl-editor/src/Editor.svelte";

    export let areas = [];
    export let patient = false;
    export let caseDetail = {};
    let countingCases;
    let form = useForm(structuredClone(caseDetail.data));
    let evolutionForm = useForm({
        diagnosis: "",
        treatment: "",
        reason: "",
        area_id: 1,
        status: null,
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
        countingCases = caseDetail.data.cases.length;
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
        $form.clearErrors();
        $form.post("/admin/historial-medico", {
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
        });
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
        class="order-2 lg:order-1 h-fit max-w-[400px] lg:max-w-[430px] xl:max-w-[460px] w-full lg:sticky top-0"
    >
        <fieldset
            class=" px-5 mt-4 gap-x-5 text-black p-6 pt-2 border-color2 rounded-md"
        >
            <legend
                class="relative text-center px-5 py-1 pt-1.5 rounded-xl bg-color2 text-gray-100"
                >DATOS DEL PACIENTE</legend
            >
            <div class="flex gap-1 items-end text-black">
                <Input
                    type="number"
                    required={true}
                    label={"C.I *"}
                    bind:value={$form.patient_ci}
                    on:input={() => ($form.cases = [])}
                    error={$form.errors.patient_ci}
                />
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

            <Input
                type="tel"
                label={"Teléfono"}
                bind:value={$form.patient_phone_number}
                error={$form.errors?.patient_phone_number}
            />
            <Input
                type="select"
                required={true}
                label={"Municipio"}
                bind:value={$form.municipality_id}
                error={$form.errors?.municipality_id}
            >
                {#each localData.municipalities || [] as micipality (micipality.id)}
                    <option value={micipality.id}>{micipality.name}</option>
                {/each}
            </Input>

            <Input
                type="select"
                required={true}
                label={"Parroquia"}
                bind:value={$form.parish_id}
                error={$form.errors?.parish_id}
            >
                {#each localData?.municipalities?.[$form.municipality_id - 1]?.parishes || [] as parish (parish.id)}
                    <option value={parish.id}>{parish.name}</option>
                {/each}
            </Input>
            <Input
                type="text"
                label={"Dirección"}
                bind:value={$form.patient_address}
                readOnly={$form.patient_id}
                error={$form.errors?.patient_address}
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
        <fieldset class=" sm:px-5 md:mt-4 gap-x-5 pt-2 bg-white rounded-xl">
            <legend
                class="relative text-center px-5 py-1 uppercase pt-1.5 rounded-xl bg-color1 text-gray-100"
                >HISTORIAL DE {caseDetail.data.patient_name}
                {caseDetail.data.patient_last_name}
            </legend>
            <div class="col-span-2 flex gap-5">
                <data value="">
                    <h3 class="font-bold">Duración total:</h3>
                    <p>3hrs</p>
                </data>
                <data value="">
                    <h3 class="font-bold">Nro de evoluciones</h3>
                    <p>7</p>
                </data>
            </div>
            <div class=" mt-4 gap-x-5 w-full pt-2 bg-gray-10">
                {#if isOpenCreateEvolution}
                    <div class="bg p-4 mb-3 rounded-lg">
                        <div class="">
                            <div class="grid-cols-2 md:grid gap-x-5">
                                <Input
                                    type="select"
                                    required={true}
                                    bind:value={$evolutionForm.status}
                                    error={$evolutionForm.errors?.status}
                                >
                                    {#each localData.statutes as status (status.id)}
                                        {#if status.id == 4}
                                            <option value={null} selected
                                                >Sigue en {$form.area_name}</option
                                            >

                                            >
                                        {:else}
                                            <option value={status.id}
                                                >{status.name}</option
                                            >
                                        {/if}
                                    {/each}
                                </Input>
                                {#if $evolutionForm.status == "3"}
                                    <div class="flex items-center">
                                        <span class="mr-2 relative top-2">
                                            a:
                                        </span>

                                        <Input
                                            type="select"
                                            required={true}
                                            bind:value={$evolutionForm.admitted_area_id}
                                            error={$evolutionForm.errors
                                                ?.admitted_area_id}
                                        >
                                            {#each localData.areas as area (area.id)}
                                                {#if area.division_id == 1}
                                                    <option value={area.id}
                                                        >{area.name}</option
                                                    >
                                                {/if}
                                            {/each}
                                        </Input>
                                    </div>
                                {/if}
                                {#if $evolutionForm.status == "3" && $evolutionForm.admitted_area_id == "7"}
                                    <Input
                                        type="text"
                                        required={true}
                                        label={"Hospital o ambulatorio *"}
                                        bind:value={$evolutionForm.destiny}
                                        error={$evolutionForm.errors?.destiny}
                                    />
                                {/if}
                                <div
                                    class="col-span-2 md:grid grid-cols-2 gap-x-4"
                                >
                                    {#if $evolutionForm.status != null}
                                        <Input
                                            type="date"
                                            label={"Fecha de salida "}
                                            required={true}
                                            bind:value={$evolutionForm.departure_date}
                                            error={$evolutionForm.errors
                                                ?.departure_date}
                                        />
                                        <Input
                                            type="time"
                                            required={true}
                                            label={"Hora de salida *"}
                                            bind:value={$evolutionForm.departure_hour}
                                            error={$evolutionForm.errors
                                                ?.departure_hour}
                                        />
                                    {/if}
                                </div>
                            </div>

                            <p class="mt-4">Diagnóstico *</p>
                            <div class="flex gap-4 mb-3">
                                {#each localData?.conditions || [] as condition (condition.id)}
                                    <label
                                        class={`py-1 pb-0 px-2 cursor-pointer rounded-full hover:bg-gray-100 flex items-center gap-1 ${$form.current_patient_condition_id == condition.id ? "bg-gray-200 font-bold" : " "}`}
                                    >
                                        <div
                                            class={`w-2 aspect-square rounded-full  condition${condition.id}`}
                                        ></div>
                                        <input
                                            class="mr-3 hidden"
                                            type="radio"
                                            bind:group={$form.current_patient_condition_id}
                                            value={condition.id}
                                            name="condition"
                                            id=""
                                        /><span>{condition.name}</span>
                                    </label>
                                {/each}
                            </div>
                            <Input
                                type="textarea"
                                required={true}
                                classes={"col-span-2"}
                                bind:value={$evolutionForm.diagnosis}
                                error={$evolutionForm?.errors?.diagnosis}
                            />
                        </div>

                        <Input
                            type="textarea"
                            required={true}
                            classes={"col-span-2"}
                            label={"Orden médica de ingreso *"}
                            bind:value={$evolutionForm.treatment}
                            error={$evolutionForm.errors?.treatment}
                        />
                        <div class="flex justify-between items-center">
                            <button
                                type="button"
                                class=""
                                on:click={() => (isOpenCreateEvolution = false)}
                                >Cancelar</button
                            >
                            <input
                                type="submit"
                                value={$evolutionForm.processing
                                    ? "Cargando..."
                                    : "Guardar"}
                                class="hover:bg-color3 hover:text-white duration-200 mt-3 px-4 md:px-20 bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
                            />
                        </div>
                    </div>
                {:else}
                    <button
                        on:click={(e) => (isOpenCreateEvolution = true)}
                        class="p-1 rounded shadow-md hover:bg-gray-100 hover: px-3 border-4 border-color3 font-bold text-color3 mb-3"
                        >Crear nueva evolución</button
                    >
                {/if}
            </div>
            <ul>
                {#each caseDetail.data.evolutions as evolution, i (evolution.id)}
                    <li
                        class="bg-gray-50 mb-3 border rounded-lg overflow-hidden"
                    >
                        <span
                            class="flex items-center gap-2 p-2 justify-between"
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
                                <span>{evolution.area_name}</span>

                                <span
                                    class={`w-2 inline-block aspect-square rounded-full condition${evolution.patient_condition_id}`}
                                ></span>
                                <span>{evolution.patient_condition_name}</span>
                            </div>
                            <div class="flex gap-2">
                                {#if evolution.is_interconsult}
                                    <span
                                        class="pl-6 pr-1 listType bg-color1 font-bold pt-1.5 pb-0.5 text-xs text-white mr-2 uppercase"
                                    >
                                        IC
                                    </span>
                                {:else if i != caseDetail.data.evolutions.length - 1}
                                    <span
                                        class="pl-6 pr-1 listType bg-color3 font-bold pt-1.5 pb-0.5 text-xs text-white mr-2 uppercase"
                                    >
                                        EVOL
                                    </span>
                                {/if}
                                <span class="flex items-center"
                                    ><iconify-icon
                                        icon="mdi:doctor"
                                        width="24"
                                        height="24"
                                    ></iconify-icon>{getFirstName(
                                        evolution.user_name,
                                    )}
                                    {getFirstName(
                                        evolution.user_last_name,
                                    )}</span
                                >
                                <span>{evolution.formatted_created_at}</span>
                            </div>
                        </span>

                        <div class="bg-white p-2.5 space-y-2">
                            {#if i == caseDetail.data.evolutions.length - 1}
                            <div>
                                <h3 class="font-bold">Motivo de consulta:</h3>
                                <p>{caseDetail.data.reason}</p>
                            </div>
                            {/if}
                            <div>
                                <h3 class="font-bold">Diagnostico:</h3>
                                <p>
                                    {evolution.diagnosis}
                                </p>
                            </div>

                            <div>
                                <h3 class="font-bold">Orden médica de ingreso:</h3>
                                <p>
                                    {evolution.treatment}
                                </p>
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
