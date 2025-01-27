<script>
    import Table from "../../components/Table.svelte";
    import Modal from "../../components/Modal.svelte";
    import Input from "../../components/Input.svelte";
    import StatusColor from "../../components/StatusColor.svelte";
    import Pagination from "../../components/Pagination.svelte";
    import debounce from "lodash/debounce";
    import Search from "../../components/Search.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, router, page } from "@inertiajs/svelte";
    import { construct_svelte_component } from "svelte/internal";
    export let data = {};
    export let areas = [];

    $: console.log(areas);
    $: console.log(data);
    // Update data based on the current state of `data.specialties`
    const today = new Date();
    // Format the date to YYYY-MM-DD
    const formattedDate = today.toISOString().split("T")[0];

    const hours = String(today.getHours()).padStart(2, "0");
    const minutes = String(today.getMinutes()).padStart(2, "0");
    const formattedTime = `${hours}:${minutes}`;

    const emptyDataForm = {
        id: null,
        name: "",
        last_name: "",
        ci: "",
        sex: "",
        date_birth: "",
        phone_number: "",
        history_number: "",
        marital_status: "",
        current_address: "",
        economic_classification: "",
        nationality: "",
        place_of_birth: "",
        notify_in_case_of_emergency: "",
        relationship: "",
        profession: "",
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
            status: "Altamedica",
            area: "",
        },
    };
    let form = useForm(structuredClone(emptyDataForm));

    let visulizateType = "table";
    let showModal = false;

    function handleSubmit(event) {
        event.preventDefault();
        if ($form.cases.length == 0) {
            $form.cases = [$form.newCase];
        } else {
            $form.cases = [$form.newCase, ...$form.cases];
        }
        $form.clearErrors();
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
        }
    }
    $: console.log($form);

    const searchPatient = debounce((ci) => {
        showModal = true;

        router.get(
            "/admin/historial-medico",
            { ci },
            {
                preserveState: true,
                only: ["patient"],
                onSuccess: (page) => {
                    // showModal = true;
                    if (page.props.patient == null) {
                        return;
                    }
                    $form = {
                        ...$form,
                        ...page.props.patient.data,
                        cases: page.props.patient.data.cases,
                    };
                },
                onFinish: (visit) => {
                    prosecingSearchPatient = false;
                },
            },
        );
    }, 210);

    function goToDetailsPatientPage(id) {
        router.get("/admin/historial-medico/detalle-paciente/" + id);
    }
    let selectingText = false;

    function handleMouseDown() {
        selectingText = false; // Reset before mouse down
    }

    function handleMouseUp(event, patientId) {
        const selection = window.getSelection();

        // Check if there is any selected text
        if (selection.toString().length === 0) {
            goToDetailsPatientPage(patientId);
        }
    }
    let submitStatus = "Crear";
    let prosecingSearchPatient = false;

    function timeBetweenDateAndTime(
        startDate,
        startTime,
        endDate,
        endTime,
        status,
    ) {
        // Combine date and time strings into a single Date object
        if (status == "Observación") {
            const now = new Date(); // Get the current date and time
            endDate = now.toISOString().split("T")[0]; // Format to YYYY-MM-DD
            endTime = now.toTimeString().split(" ")[0].substring(0, 5); // Format to HH:mm
        }
        const startDateTime = new Date(`${startDate}T${startTime}`);
        const endDateTime = new Date(`${endDate}T${endTime}`);

        const diffInMs = endDateTime - startDateTime; // Difference in milliseconds
        const diffInMinutes = Math.floor(diffInMs / (1000 * 60));
        const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
        const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
        if (diffInDays > 0) {
            if (diffInHours > 0) {
                return `${diffInDays} Dia${diffInDays > 1 ? "s" : ""}, ${diffInHours - 24} Hr${diffInHours > 1 ? "s" : ""}`;
            } else {
                return `${diffInDays} Dia${diffInDays > 1 ? "s" : ""}`;
            }
        } else if (diffInHours > 0) {
            return `${diffInHours} Hr${diffInHours > 1 ? "s" : ""}`;
        } else {
            return `${diffInMinutes} Min${diffInMinutes > 1 ? "s" : ""}`;
        }
    }

    $: if (showModal) {
        setTimeout(() => {
            if (showModal == true) {
                document.querySelector("input[name='ci']")?.focus();
            }
        }, 100);
    }
</script>

<svelte:head>
    <title>Historial médico</title>
</svelte:head>

<Modal bind:showModal modalClasses={"max-w-[560px]"}>
    <p slot="header" class="opacity-60">Registrar un nuevo caso</p>
    <form id="a-form" on:submit={handleSubmit} action="">
        <fieldset
            class=" px-5 mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color3 rounded-md"
        >
            <legend
                class="relative text-center px-5 py-1 pt-1.5 rounded-xl bg-color1 text-gray-100"
                >DATOS DEL PACIENTE</legend
            >
            {#if $form?.ci.toString().length >= 6}
                <div class="w-full col-span-2 h-6 overflow-hidden text-center">
                    {#if prosecingSearchPatient}
                        <iconify-icon
                            class="text-3xl"
                            icon="eos-icons:three-dots-loading"
                        ></iconify-icon>
                    {:else if $form?.id !== null}
                        <span
                            class="flex items-center gap-2 text-center mx-auto justify-center"
                        >
                            <iconify-icon
                                class="text-2xl text-color3"
                                icon="iconoir:settings-profiles"
                            ></iconify-icon>
                            <small>Paciente Registrado con historia</small>
                        </span>
                    {:else}
                        <span
                            class="flex items-center gap-2 text-center mx-auto justify-center"
                        >
                            <iconify-icon
                                class="text-3xl"
                                icon="clarity:new-line"
                            ></iconify-icon>
                            <small>Nuevo paciente sin historia</small>
                        </span>
                    {/if}
                </div>
            {/if}
            <div>
                <Input
                    required={true}
                    type={"number"}
                    label={"C.I *"}
                    name={"ci"}
                    min={100000}
                    placeholder={"Minimo 6 números"}
                    on:wheel={(e) => document.activeElement.blur()}
                    bind:value={$form.ci}
                    on:input={(e) => {
                        prosecingSearchPatient = true;
                        $form.id = null;
                        $form.cases = [];
                        if ($form.ci.toString().length >= 6) {
                            searchPatient($form.ci);
                        }
                    }}
                    error={$form.errors?.ci}
                />
                <!-- <button
                    type="button"
                    title="Buscar si el paciente existe"
                    class="bg-color4 h-fit px-2 rounded hover:bg-color1 hover:text-white"
                    on:click={() => {
                        searchPatient($form.ci);
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
                </button> -->
            </div>
            <Input
                type="text"
                required={true}
                label={"Nombres *"}
                bind:value={$form.name}
                readOnly={$form.id}
                error={$form.errors?.name}
            />
            <Input
                type="text"
                required={true}
                label={"Apellidos *"}
                bind:value={$form.last_name}
                readOnly={$form.id}
                error={$form.errors?.last_name}
            />

            <Input
                type="date"
                required={true}
                label={"Fecha de Nacimiento*"}
                bind:value={$form.date_birth}
                readOnly={$form.id}
                error={$form.errors?.date_birth}
            />
            <div class="flex flex-col mt-6">
                <label class="py-1 cursor-pointer hover:bg-gray-100">
                    <input
                        class="mr-3 inline-block"
                        type="radio"
                        bind:group={$form.sex}
                        value="Masculino"
                        name="sex"
                        id=""
                    /><span class:font-bold={$form.sex == "Masculino"}
                        >Masculino</span
                    >
                </label>

                <label class="py-1 cursor-pointer hover:bg-gray-100">
                    <input
                        class="mr-3 inline-block"
                        type="radio"
                        bind:group={$form.sex}
                        value="Femenino"
                        name="sex"
                        id=""
                    /><span class:font-bold={$form.sex == "Femenino"}
                        >Femenino</span
                    >
                </label>
            </div>

            <Input
                type="tel"
                label={"Teléfono"}
                readOnly={$form.id}
                bind:value={$form.phone_number}
                error={$form.errors?.phone_number}
            />
        </fieldset>

        <fieldset
            class=" px-5 mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color3 rounded-md"
        >
            <legend
                class="relative text-center px-5 py-1 pt-1.5 rounded-xl bg-color1 text-gray-100"
                >DATOS DE LA EMERGENCIA</legend
            >

            <Input
                type="select"
                required={true}
                label={"Servicio Tratante *"}
                bind:value={$form.newCase.treatingService}
                error={$form.errors?.newCase?.treatingService}
            >
                {#each areas as area (area.id)}
                    {#if area.type_zone_id == 1}
                        <option value={area}>{area.name}</option>
                    {/if}
                {/each}
            </Input>
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
                <option value="Altamedica"> Egreso: Alta médica </option>
                <option value="Altacontraindicacion"> Egreso: Alta contraindicación </option>
                <option value="Observación">Ingreso </option>
                <option value="Admitido">Admitido </option>
                <option value="Fallecido">Defución </option>
            </Input>
            {#if $form.newCase.status == "Admitido"}
                <Input
                    type="select"
                    required={true}
                    label={"Area admitida *"}
                    bind:value={$form.newCase.area}
                    error={$form.errors?.newCase?.area}
                >
                    {#each areas as area (area.id)}
                        {#if area.type_zone_id == 2}
                            <option value={area}>{area.name}</option>
                        {/if}
                    {/each}
                </Input>
            {/if}
            {#if $form.newCase.status == "Admitido" && $form.newCase.area.id == "10" }
            <Input
                type="text"
                required={true}
                label={"Hospital o ambulatorio *"}
                bind:value={$form.newCase.placeOfAmbulatory}
                error={$form.errors?.newCase?.placeOfAmbulatory}
            />
        
        {/if}
            {#if $form.newCase.status == "Observación"}
                <Input
                    type="select"
                    required={true}
                    label={"Area de observación *"}
                    bind:value={$form.newCase.observationArea}
                    error={$form.errors?.newCase?.observationArea}
                >
                    {#each areas as area (area.id)}
                        {#if area.type_zone_id == 1}
                            <option value={area}>{area.name} </option>
                        {/if}
                    {/each}
                </Input>
            {/if}
          
            {#if $form.newCase.status !== "Observación"}
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

        <fieldset
            class=" px-5 mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color3 rounded-md"
        >
            <legend
                class="relative text-center px-5 py-1 pt-1.5 rounded-xl bg-color1 text-gray-100"
                >DIAGNÓSTICO Y TRATAMIENTO</legend
            >
            <Input
                type="textarea"
                required={true}
                classes={"col-span-2"}
                label={"Motivo de consulta *"}
                bind:value={$form.newCase.reasonForConsultation}
                error={$form?.errors?.newCase?.reasonForConsultation}
            />
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
                label={"Orden médica de ingreso *"}
                bind:value={$form.newCase.treatment}
                error={$form.errors?.newCase?.treatment}
            />
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
        class="btn_create inline-block p-2 px-3"
        on:click={(e) => {
            e.preventDefault();

            showModal = true;
            submitStatus = "Crear";
        }}
        title="Crear un nuevo caso"
    >
        <span class="md:hidden text-4xl relative top-1 font-bold"
            ><iconify-icon icon="ic:round-add"></iconify-icon></span
        >
        <span class="hidden md:block"> Nuevo caso</span>
    </button>

    <div class="text-gray-600 text-xl md:text-2xl">
        <iconify-icon
            class="cursor-pointer mr-2"
            title="Vizualizar tipo Tabla"
            on:click={() => (visulizateType = "table")}
            icon="material-symbols:table-sharp"
            class:text-color1={visulizateType == "table"}
            class:bg-color4={visulizateType == "table"}
        ></iconify-icon>
        <iconify-icon
            class="cursor-pointer"
            title="Vizualizar tipo lista"
            on:click={() => (visulizateType = "card")}
            icon="carbon:show-data-cards"
            class:text-color1={visulizateType == "card"}
            class:bg-color4={visulizateType == "card"}
        ></iconify-icon>
    </div>
</div>

{#if visulizateType == "table"}
    <Table
        filtersOptions={{
            status: [
                { name: "Alta", id: "Alta" },
                { name: "Admitido", id: "Admitido" },
                { name: "Observación", id: "Observación" },
                { name: "Fallecido", id: "Fallecido" },
            ],
        }}
        allowSearch={false}
    >
        <div slot="filterBox"></div>
        <thead slot="thead" class="sticky top-0">
            <tr>
                <th style="font-size: 12px;">N°</th>
                <th>Duración</th>
                <th>Estado</th>
                <th>Paciente</th>
                <th>Diagnóstico</th>
                <th>Tratamiento</th>
                <th>Doctor</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#if data?.data?.length > 0 && data?.data?.[0].cases.length > 0}
                {#each data?.data as row, i (row.id)}
                    <tr
                        on:mousedown={handleMouseDown}
                        on:mouseup={(e) => handleMouseUp(e, row.id)}
                        class={`md:max-h-[200px] overflow-hidden cursor-pointer  hover:bg-gray-500 hover:bg-opacity-5`}
                    >
                        <td style="font-size: 12px;"
                            >{data.meta.total -
                                (data.meta.current_page - 1) *
                                    data.meta.per_page -
                                i}</td
                        >
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

                        <td style="white-space: normal;">
                            <StatusColor status={row.cases?.[0]?.status} />
                            <p>
                                {#if row.cases?.[0]?.status == "Admitido"}
                                    {row.cases?.[0]?.area?.name}
                                {/if}
                            </p>
                        </td>
                        <td class="min-w-[120px]">
                            <div class="flex items-center gap-2">
                                {#if row.sex == "Femenino"}
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
                                <span class="whitespace-normal"
                                    >{row.name}
                                    {row.last_name}
                                    - {row.ci}
                                </span>
                            </div>
                        </td>
                        <td
                            class="max-w-[340px] min-w-[290px] md:min-w-[320px] max-h-[100px] overflow-hidden"
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
                            class="max-w-[340px] min-w-[290px] md:min-w-[320px] max-h-[100px] overflow-hidden"
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
{/if}

{#if visulizateType == "card"}
    <div class="lg:grid lg:grid-cols-2 gap-3 mt-3">
        {#each data?.data as row, i (row.id)}
            <!-- svelte-ignore a11y-click-events-have-key-events -->
            <article
                on:mousedown={handleMouseDown}
                on:mouseup={(e) => handleMouseUp(e, row.id)}
                class={`border mb-3 relative w-full cursor-pointer bg-gray-50 p-2 md:p-5 rounded-md shadow-sm hover:bg-gray-500 hover:bg-opacity-5`}
            >
                <span
                    class="h-fit absolute right-0 top-0 text-center col-span-2 bg-gray-100 p-1 rounded-lg inline-block w-10 px-2"
                    >{i + 1}°</span
                >
                <div class="flex gap-2 items-center">
                    <p>
                        {timeBetweenDateAndTime(
                            row.cases?.[0]?.start_date,
                            row.cases?.[0]?.start_time,
                            row.cases?.[0]?.end_date,
                            row.cases?.[0]?.end_time,
                            row.cases?.[0]?.status,
                        )}
                    </p>
                    |
                    <StatusColor status={row.cases?.[0]?.status} />
                    {#if row.cases?.[0]?.status == "Admitido"}
                        a {row.cases?.[0]?.area?.name}
                    {/if}
                </div>

                <div class="flex items-center gap-2 mt-1">
                    {#if row.sex == "Femenino"}
                        <span class="text-pink text-lg sm:text-2xl">
                            <iconify-icon icon="fa-solid:female"></iconify-icon>
                        </span>
                    {:else}
                        <span class="text-color3 text-lg sm:text-2xl">
                            <iconify-icon icon="fa-solid:male"></iconify-icon>
                        </span>
                    {/if}
                    <span
                        >{row.name}
                        {row.last_name}
                        - {row.ci}
                    </span>
                </div>

                <div class="mt-2">
                    <p>
                        <iconify-icon
                            class="text-lg sm:text-2xl relative top-1.5 text-red"
                            icon="material-symbols:diagnosis"
                        ></iconify-icon>
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
                </div>
                <div class="mt-2">
                    <p>
                        <iconify-icon
                            class="text-lg sm:text-2xl relative top-1.5 text-color1"
                            icon="mdi:medicine-bottle"
                        ></iconify-icon>
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
                </div>

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

<Search />
<div class="col-span-2">
    <Pagination pagination={{ ...data?.meta, ...data?.links }} />
</div>

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
</style>
