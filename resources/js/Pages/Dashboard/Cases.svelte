<script>
    import Table from "../../components/Table.svelte";
    import Modal from "../../components/Modal.svelte";
    import Input from "../../components/Input.svelte";
    import StatusColor from "../../components/StatusColor.svelte";
    import debounce from "lodash/debounce";
    import fetchLocalData from "../../components/localData";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, router, page } from "@inertiajs/svelte";
    import { construct_svelte_component } from "svelte/internal";
    import Pagination from "../../components/Pagination.svelte";
    import axios from "axios";
    import { onMount } from "svelte";
    import Search from "../../components/Search.svelte";
    import { getDuration } from "../../components/getDuration.js";

    export let data = {};
    export let statutes = {};
    let showModalDoctor = false;

    let localData;

    onMount(async () => {
        try {
            localData = await fetchLocalData();
        } catch (error) {
            console.error("Error loading data:", error);
        }
    });
    function getFirstName(firstName) {
        const parts = firstName.split(" ");
        return parts[0];
    }
    // $: console.log($page.props.auth);
    // Update data based on the current state of `data.specialties`
    const today = new Date();
    // Format the date to YYYY-MM-DD
    const formattedDate = today.toISOString().split("T")[0];

    function getCurrentHour() {
        const hours = String(today.getHours()).padStart(2, "0");
        const minutes = String(today.getMinutes()).padStart(2, "0");
        return `${hours}:${minutes}`;
    }
    let searchedDoctors = [];

    const emptyDataForm = {
        patient_id: null,
        patient_name: "",
        patient_last_name: "",
        patient_ci: "",
        patient_sex: "Masculino",
        patient_date_birth: "",
        patient_phone_number: "",
        patient_address: "",
        municipality_id: 14,
        parish_id: 0,
        user_specialty_name: $page.props.auth.specialty_name,
        user_id: $page.props.auth.user_id,
        user_name: $page.props.auth.name,
        user_last_name: $page.props.auth.last_name,
        user_pohto: $page.props.auth.photo,
        reason: "",
        diagnosis: "",
        treatment: "",
        current_status: "",
        current_patient_condition_id: 1,
        condition: "",
        cases: [],
        entry_date: formattedDate,
        departure_date: "",
        entry_hour: getCurrentHour(),
        departure_hour: "",
        area_id: "",
        area_name: "",
        destiny: "",
        admitted_area_id: null,
    };
    let form = useForm(structuredClone(emptyDataForm));

    let visulizateType = "table";
    // Check if 'visualizateTypeCases' exists in localStorage
    if (typeof localStorage !== "undefined") {
        const storedValue = localStorage.getItem("visualizateTypeCases");
        if (storedValue) {
            visulizateType = storedValue; // Use the stored value if it exists
        } else {
            localStorage.setItem("visualizateTypeCases", visulizateType); // Save the default value
        }
    }

    $: if (visulizateType) {
        if (typeof localStorage !== "undefined") {
            localStorage.setItem("visualizateTypeCases", visulizateType);
        }
    }
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
            $form.post("/admin/casos", {
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
    // $: console.log(municipalities[$form?.municipality_id - 1]?.parishes);
    const searchPatient = debounce(async (ci) => {
        prosecingSearchPatient = true; // Cambiar a verdadero antes de la búsqueda

        try {
            const res = await axios.get(`/admin/historial-medico`, {
                params: { ci },
            });

            if (res.data.patient == null) {
                $form.patient_id = null;
                return;
            } else {
                $form = {
                    ...$form,
                    ...res.data.patient,
                };
            }
        } catch (err) {
            console.log(err);
        } finally {
            prosecingSearchPatient = false; // Cambiar a falso después de la búsqueda
        }
    }, 280);

    const searchDoctor = debounce(async (search) => {

        try {
            const res = await axios.get(`/admin/historial-medico/doctor`, {
                headers: {},
                params: { search },
            });
            searchedDoctors = res.data.doctors;
        } catch (err) {
            console.log(err);
        } finally {
            prosecingSearchPatient = false; // Cambiar a falso después de la búsqueda
        }
    }, 280);

    function goToDetailsPatientPage(id) {
        router.get("/admin/casos/detalle-caso/" + id);
    }

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

    $: if (showModal) {
        setTimeout(() => {
            if (showModal == true) {
                document.querySelector("input[name='ci']")?.focus();
            }
        }, 100);
    }

    function handlesubmit() {
        
    }
</script>

<svelte:head>
    <title>Casos</title>
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
            {#if $form?.patient_ci.toString().length >= 6}
                <div class="w-full col-span-2 h-6 overflow-hidden text-center">
                    {#if prosecingSearchPatient}
                        <iconify-icon
                            class="text-3xl"
                            icon="eos-icons:three-dots-loading"
                        ></iconify-icon>
                    {:else if $form?.patient_id !== null}
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
                    bind:value={$form.patient_ci}
                    on:input={(e) => {
                        prosecingSearchPatient = true;
                        $form.patient_id = null;
                        $form.cases = [];
                        if ($form.patient_ci.toString().length >= 6) {
                            searchPatient($form.patient_ci);
                        }
                    }}
                    error={$form.errors?.ci}
                />
                <!-- <button
                    type="button"
                    title="Buscar si el paciente existe"
                    class="bg-color4 h-fit px-2 rounded hover:bg-color1 hover:text-white"
                    on:click={() => {
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
                </button> -->
            </div>
            <Input
                type="text"
                required={true}
                label={"Nombres *"}
                bind:value={$form.patient_name}
                readOnly={$form.patient_id}
                error={$form.errors?.patient_name}
            />
            <Input
                type="text"
                required={true}
                label={"Apellidos *"}
                bind:value={$form.patient_last_name}
                readOnly={$form.patient_id}
                error={$form.errors?.patient_last_name}
            />

            <Input
                type="date"
                label={"Fecha de Nacimiento*"}
                bind:value={$form.patient_date_birth}
                readOnly={$form.patient_id}
                required={true}
                error={$form.errors?.patient_date_birth}
            />

            <Input
                type="select"
                required={true}
                label={"Municipio"}
                bind:value={$form.municipality_id}
                error={$form.errors?.municipality_id}
                disabled={$form.patient_id}
            >
                {#each localData.municipalities as micipality (micipality.id)}
                    <option value={micipality.id}>{micipality.name}</option>
                {/each}
            </Input>

            <Input
                type="select"
                required={true}
                label={"Parroquia"}
                bind:value={$form.parish_id}
                error={$form.errors?.parish_id}
                disabled={$form.patient_id}
            >
                {#if localData.municipalities[$form.municipality_id - 1]}
                    {#each localData.municipalities[$form.municipality_id - 1].parishes as parish (parish.id)}
                        <option value={parish.id}>{parish.name}</option>
                    {/each}
                {/if}
            </Input>
            <Input
                type="text"
                label={"Dirección"}
                bind:value={$form.patient_address}
                readOnly={$form.patient_id}
                error={$form.errors?.patient_address}
            />

            <Input
                type="tel"
                label={"Teléfono"}
                readOnly={$form.patient_id}
                bind:value={$form.patient_phone_number}
                error={$form.errors?.patient_phone_number}
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
                        required
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
                        required
                    /><span class:font-bold={$form.patient_sex == "Femenino"}
                        >Femenino</span
                    >
                </label>
            </div>
        </fieldset>

        <fieldset
            class=" px-5 mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color3 rounded-md"
        >
            <legend
                class="relative text-center px-5 py-1 pt-1.5 rounded-xl bg-color1 text-gray-100"
                >DATOS DE LA EMERGENCIA</legend
            >
            <div class="">
                <button
                    type="button"
                    class="w-full"
                    on:click={() => {
                        if ($page.props.auth.rol[0] == "admin") {
                            showModalDoctor = true;
                            setTimeout(() => {
                                document.querySelector("#searchDoctor").focus();
                            }, 150);
                        }
                    }}
                >
                    <div class="mt-3 text-left">
                        <span class="text-left">Médico y servico</span>
                    </div>

                    <div
                        class=" border rounded cursor-pointer hover:bg-gray-100 hover:border-dark p-2"
                    >
                        <div class="flex gap-1.5">
                            <img
                                class="bg-gray-300 w-7 aspect-square rounded-full object-cover"
                                src={`/storage/users/${$form.user_photo}`}
                                alt=""
                            />
                            <p class="inline-block w-fit">
                                <b>
                                    {$form.user_name}
                                    {$form.user_last_name}</b
                                >
                            </p>
                        </div>
                        <p class="bg-gray-200 rounded-full w-fit ml-7 relative -top-1 px-2 py-1 text-xs">
                            {$form.user_specialty_name}
                        </p>
                    </div>
                </button>
            </div>

            <Input
                type="date"
                required={true}
                label={"Fecha de ingreso *"}
                on:change={(e) => {
                    const selectedDate = new Date(e.target.value);
                    if (selectedDate > today) {
                        $form.errors = {
                            ...$form.errors,
                            entry_date:
                                "La fecha de ingreso no puede ser posterior a hoy.",
                        };
                    } else {
                        $form.errors = { ...$form.errors, entry_date: null }; // Clear the error
                    }
                    $form.entry_date = e.target.value;
                }}
                value={$form.entry_date}
                error={$form.errors?.entry_date}
            />

            <Input
                type="time"
                required={true}
                label={"Hora de ingreso *"}
                bind:value={$form.entry_hour}
                error={$form.errors?.entry_hour}
            />

            <Input
                type="select"
                required={true}
                label={"Estado *"}
                bind:value={$form.current_status}
                error={$form.errors?.current_status}
                on:change={() => {
                    if ($form.current_status == 4) {
                        $form.departure_date = "";
                        $form.departure_hour = "";
                    } else {
                        $form.departure_date = formattedDate;
                        $form.departure_hour = getCurrentHour();
                    }
                }}
            >
                {#each localData.statutes as status (status.id)}
                    {#if status.id !== 6 && status.id !== 3}
                        <option value={status.id}>{status.name}</option>
                    {/if}
                {/each}
            </Input>

            <Input
                type="select"
                required={true}
                label={"Area de ingreso *"}
                bind:value={$form.area_id}
                error={$form.errors?.area_id}
                on:change={(e) => {
                    $form.area = e.target.value;
                }}
            >
                {#each localData.areas as area (area.id)}
                    {#if area.division_id == 2}
                        <option value={area.id}>{area.name}</option>
                    {/if}
                {/each}
            </Input>

            <!-- {#if $form.current_status == "3"}
                <Input
                    type="select"
                    required={true}
                    label={"Area de observación *"}
                    bind:value={$form.observationArea}
                    error={$form.errors?.observationArea}
                >
                    {#each areas as area (area.id)}
                        {#if area.division_id == 2}
                            <option value={area.id}>{area.name} </option>
                        {/if}
                    {/each}
                </Input>
            {/if} -->

            {#if $form.current_status != "4" && $form.current_status != ""}
                <Input
                    type="date"
                    label={"Fecha de salida "}
                    required={true}
                    on:change={(e) => {
                        const selectedDate = new Date(e.target.value);

                        if (selectedDate < new Date($form.entry_date)) {
                            $form.errors = {
                                ...$form.errors,
                                departure_date:
                                    "La fecha de salida no puede ser menor a la de ingreso",
                            };
                        } else {
                            $form.errors = {
                                ...$form.errors,
                                departure_date: null,
                            }; // Clear the error
                        }
                        $form.departure_date = e.target.value;
                    }}
                    value={$form.departure_date}
                    error={$form.errors?.departure_date}
                />
                <Input
                    type="time"
                    required={true}
                    label={"Hora de salida *"}
                    on:input={(e) => {
                        if ($form.entry_date == $form.departure_date) {
                            if (e.target.value < $form.entry_hour) {
                                $form.errors = {
                                    ...$form.errors,
                                    departure_hour:
                                        "la hora de salida no puede ser menor a la de ingreso",
                                };
                            } else {
                                $form.errors = {
                                    ...$form.errors,
                                    departure_hour: null,
                                }; // Clear the error
                            }
                        }
                        $form.departure_hour = e.target.value;
                    }}
                    value={$form?.departure_hour}
                    error={$form.errors?.departure_hour}
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
                bind:value={$form.reason}
                error={$form?.errors?.reason}
            />
            <div class="col-span-2">
                <span>Diagnóstico *</span>
                <div class="flex gap-4 mb-3">
                    {#each localData.conditions as condition (condition.id)}
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
                    bind:value={$form.diagnosis}
                    error={$form?.errors?.diagnosis}
                />
            </div>

            <Input
                type="textarea"
                required={true}
                label={"Orden médica de ingreso *"}
                error={$form.errors?.treatment}
                classes={"col-span-2"}
                bind:value={$form.treatment}
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

<Modal bind:showModal={showModalDoctor}>
    <div class=" top-3 flex items-center">
        <span class="absolute">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-5 h-5 mx-3 text-gray-400"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                />
            </svg>
        </span>

        <input
            type="search"
            placeholder="Buscar por nombre o CI"
            on:input={(e) => {
                searchDoctor(e.target.value);
            }}
            id="searchDoctor"
            class=" block w-full py-1.5 pr-5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg md:w-80 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
        />
    </div>
    <ul class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mt-3">
        {#each searchedDoctors as doctor (doctor.user_id)}
            <!-- svelte-ignore a11y-click-events-have-key-events -->
            <li
                class="flex gap-3 border rounded cursor-pointer hover:bg-gray-100 hover:border-dark p-4"
                on:click={() => {
                    if ($page.props.auth.rol[0] == "admin") {
                        $form = { ...$form, ...doctor };
                        showModalDoctor = false;
                    }
                }}
            >
                <span
                    class="rounded-full overflow-hidden bg-color4 w-9 h-9 justify-center items-center flex"
                >
                    <iconify-icon icon="fa6-solid:user-doctor"></iconify-icon>
                </span>

                <div class="mt-1">
                    <p>
                        <b> {doctor.user_name} {doctor.user_last_name}</b> - {doctor.user_ci}
                    </p>
                    <span class="bg-gray-200 rounded-full px-2 py-1 text-sm"
                        >{doctor.user_specialty_name}</span
                    >
                </div>
            </li>
        {/each}
    </ul>
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
        <span class="sm:hidden text-2xl relative top-1 font-bold"
            ><iconify-icon icon="ic:round-add"></iconify-icon></span
        >
        <span class="hidden sm:block"> Nuevo caso</span>
    </button>

    <div class="text-gray-600 text-xl md:text-2xl ml-auto">
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

<Search filtersOptions={{
    date: {
        type: "date",
        label: "Fecha de ingreso",
    },
    status:
        {
            type: "select",
            label: "Estado",
            options: localData?.statutes || [],
        } || {},
    case_id:
        {
            type: "search",
            label: "ID del caso",
            options: [],
        } || {},
    specialty_id:
        {
            type: "select",
            label: "Servicio tra.",
            options: localData?.specialties || [],
        } || {},
    area_id:
        {
            type: "select",
            label: "Última area",
            options: localData?.areas || [],
        } || {},

    condition:
        {
            type: "select",
            label: "Condición",
            options: localData?.conditions || [],
        } || {},
}} />
<Table
    
    {visulizateType}
>
    <div slot="filterBox"></div>
    <thead slot="thead" class="sticky top-0">
        <tr>
            <th style="font-size: 12px;">ID</th>
            <th>F. ingreso</th>
            <th>Estado y area</th>
            <!-- <th>Condición</th> -->
            <th>Paciente</th>
            <th>Motivo de consulta</th>
            <th>Diagnóstico</th>
            <th>Orden médica de ingreso</th>
        </tr>
    </thead>

    <tbody slot="tbody">
        {#if data?.data?.length > 0 && visulizateType == "table"}
            {#each data?.data as row, i (row.id)}
                <tr
                    on:mousedown={handleMouseDown}
                    on:mouseup={(e) => handleMouseUp(e, row.id)}
                    class={`md:max-h-[200px] overflow-hidden cursor-pointer  hover:bg-gray-500 hover:bg-opacity-5`}
                >
                    <td style="font-size: 12px;">{row.id}</td>
                    <td class="">{row.formatted_entry_date}</td>

                    <td style="white-space: normal;" class="min-w-[150px]">
                        <StatusColor
                            status={{
                                name: row?.current_status_name,
                                id: row?.current_status,
                            }}
                        />

                        <!-- {#if row?.current_status == "3"}
                                    a {row?.admitted_area_name}
                                {/if} -->
                        <span class="inline-block flex">
                            {#if row.current_status == 1 || row.current_status == 2}
                                de
                            {:else if row.current_status == 4 || row.current_status == 5}
                                en
                            {:else if row.current_status == 3}
                                a
                            {/if}
                            {row.area_name}
                        </span>
                    </td>

                    <td class="min-w-[180px]">
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
                            <span class="whitespace-normal"
                                >{getFirstName(row?.patient_name)}
                                {getFirstName(row?.patient_last_name)}
                                <small class="text-gray-400">C.I:</small
                                >{row?.patient_ci}
                            </span>
                        </div>
                    </td>
                    <td
                        class="max-w-[340px] min-w-[290px] md:min-w-[320px] max-h-[100px] overflow-hidden"
                        style="white-space: normal;"
                    >
                        {#if row?.reason.length > 200}
                            {row?.reason.slice(0, 200)}
                            <span
                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                >...</span
                            >
                        {:else}
                            {row?.reason}
                        {/if}
                    </td>
                    <td
                        title={row?.current_patient_condition_name}
                        class="max-w-[340px] min-w-[290px] md:min-w-[320px] max-h-[100px] overflow-hidden"
                        style="white-space: normal;"
                    >
                        <div
                            class={`inline-block w-2 h-2 mr-2 aspect-square rounded-full  condition${row.current_patient_condition_id}`}
                        ></div>
                        {#if row?.diagnosis.length > 200}
                            {row?.diagnosis.slice(0, 200)}
                            <span
                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                >...</span
                            >
                        {:else}
                            {row?.diagnosis}
                        {/if}
                    </td>
                    <!-- <td>{row.sex}</td> -->
                    <td
                        class="max-w-[340px] min-w-[290px] md:min-w-[320px] max-h-[100px] overflow-hidden"
                        style="white-space: normal;"
                    >
                        {#if row?.treatment.length > 200}
                            {row?.treatment.slice(0, 200)}
                            <span
                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                >...</span
                            >
                        {:else}
                            {row?.treatment}
                        {/if}
                    </td>
                    <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                </tr>
            {/each}
        {/if}
    </tbody>
</Table>

{#if visulizateType == "card"}
    <div class="grid lg:grid-cols-2 gap-5 mt-3">
        {#each data?.data as row, i (row.id)}
            <!-- svelte-ignore a11y-click-events-have-key-events -->
            <article
                on:mousedown={handleMouseDown}
                on:mouseup={(e) => handleMouseUp(e, row.id)}
                class={`relative w-full cursor-pointer bg-gray-100 p-2 md:p-5 rounded-md  hover:bg-color4 hover:bg-opacity-60 neumorphism2`}
            >
                <span
                    class="h-fit absolute right-0 top-0 text-center col-span-2 p-1 text-xs inline-block w-10 md:px-2"
                    >{row.id}</span
                >
                <div class="flex gap-1 items-center">
                    <StatusColor
                        status={{
                            name: row?.current_status_name,
                            id: row?.current_status,
                        }}
                    />

                    <!-- {#if row?.current_status == "3"}
                        a {row?.admitted_area_name}
                    {/if} -->
                    <span class="inline-flex">
                        {#if row.current_status == 1 || row.current_status == 2}
                            de
                        {:else if row.current_status == 4 || row.current_status == 5}
                            en
                        {:else if row.current_status == 3}
                            a
                        {/if}
                        {row.area_name}.
                    </span>
                    <div class="flex items-cenenter gap-1">
                        <iconify-icon
                            icon="game-icons:duration"
                            class="text-gray-600 text-xs md:text-sm"
                        ></iconify-icon>
                    </div>
                </div>
                <p>
                    F. de ingreso: {row.formatted_entry_date}
                </p>

                <div class="flex items-center gap-3 mt-1">
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
                        >{getFirstName(row?.patient_name)}
                        {getFirstName(row.patient_last_name)}
                        <small class="text-gray-500">C.I:</small>{row.user_ci}
                    </span>
                </div>
                <div class="mt-1 flex gap-1.5">
                    <iconify-icon
                        icon="emojione-monotone:speaking-head"
                        width="20"
                        height="20"
                        class="text-gray-900"
                    ></iconify-icon>
                    <p>
                        {#if row?.reason.length > 200}
                            {row?.reason.slice(0, 200)}
                            <span
                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                >...</span
                            >
                        {:else}
                            {row?.reason}
                        {/if}
                    </p>
                </div>
                <div class="mt-2 flex gap-2">
                    <div
                        class={`inline-block w-2 h-2 mr-2 relative top-2 aspect-square rounded-full  condition${row.current_patient_condition_id}`}
                    ></div>
                    <p>
                        {#if row.diagnosis.length > 200}
                            {row.diagnosis.slice(0, 200)}
                            <span
                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                >...</span
                            >
                        {:else}
                            {row.diagnosis}
                        {/if}
                    </p>
                </div>
                <div class="mt-2 flex gap-2">
                    <iconify-icon
                        class="relative top-1 -left-1 text-color2"
                        icon="ant-design:medicine-box-filled"
                        width="20"
                        height="20"
                    ></iconify-icon>
                    <p>
                        {#if row.treatment.length > 200}
                            {row.treatment.slice(0, 200)}
                            <span
                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                >...</span
                            >
                        {:else}
                            {row.treatment}
                        {/if}
                    </p>
                </div>

                <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                <!-- <p
                    class="text-right justify-end w-full flex items-center gap-2"
                >
                    {row.user_name}
                    {row.user_last_name}
                    <iconify-icon icon="mdi:doctor" style="font-size: 20px;"
                    ></iconify-icon>
                </p> -->
            </article>
        {/each}
    </div>
{/if}

<div class="col-span-2">
    <Pagination pagination={{ ...data?.meta, ...data?.links }} />
</div>

<style>
    @media (max-width: 750px) {
        legend::after,
        legend::before {
            bottom: 5px !important;
            height: 13px;
        }
    }
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
