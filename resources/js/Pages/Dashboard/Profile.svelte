<script>
    import Input from "../../components/Input.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, page, router, inertia } from "@inertiajs/svelte";
    import StatusColor from "../../components/StatusColor.svelte";
    import Alert from "../../components/Alert.svelte";
    import fetchLocalData from "../../components/localData";
    import { onMount } from "svelte";
    import axios from "axios";
    import debounce from "lodash/debounce";


    export let data = [];
    export let areas = [];

    export let patient = false;
    // export let = {};
    let nroEvol = data.nroEvol;
    let nroInter = data.nroInter;
    let countingCases;
    let isTheSameUser = data.user.data.id == $page.props.auth.user_id;
    let isAdmin = $page.props.auth.rol[0] == "admin";
    let allowToEdit = !(isTheSameUser || isAdmin);
    let pageNumber = 1; // Current page number
    let loading = false; // Tracks if data is being loaded
    let hasMore = true; // Tracks if there are more items to load

    async function loadMore() {
        if (loading || !hasMore) return; // Prevent multiple requests

        loading = true; // Set loading state

        try {
            const res = await axios.get(
                "/admin/perfil/json/" + data.user.data.id,
                { params: { page: pageNumber + 1 } },
            );
            if (res.data.data.length > 0) {
                console.log(res.data.data, data.evolutions.data);
                data.evolutions.data = [
                    ...data.evolutions.data,
                    ...res.data.data,
                ];
                pageNumber += 1; // Increment page number
            } else {
                hasMore = false; // No more items to load
            }
            loading = false;
        } catch (error) {}
    }

    // Detect when the user scrolls to the bottom
    function handleScroll(e, i) {
        // console.log(e,i)
        const scrollableElement = document.querySelector(
            ".main_and_footer_container",
        );

        // Check if the user has scrolled to the bottom of the scrollable element
        const bottom =
            scrollableElement.scrollTop + scrollableElement.clientHeight >=
            scrollableElement.scrollHeight - 200; // 200px buffer

        if (bottom && hasMore) {
            loadMore();
        }
    }

    // Attach scroll event listener
    onMount(() => {
        const scrollableElement = document.querySelector(
            ".main_and_footer_container",
        );
        scrollableElement.addEventListener("scroll", handleScroll);
        return () =>
            scrollableElement.removeEventListener("scroll", handleScroll);
    });

    let form = useForm(structuredClone(data.user.data));

    let localData = {};

    onMount(async () => {
        try {
            localData = await fetchLocalData();
            // console.log(fetchLocalData())
        } catch (error) {
            console.error("Error loading data:", error);
        }
    });

    let editor;
    let descriptionLength = 0;
    let openAccordeon = -1;

    let editStatus = false;

    function getFirstName(firstName) {
        const parts = firstName.split(" ");
        return parts[0];
    }

    let imagePreview = "";
    
    let photoChanged = false;

    function handleFileChange(event) {
        const file = event.target.files[0];
        photoChanged = true
        if (file) {
            // Create a URL for the selected file
            imagePreview = URL.createObjectURL(file);
            // Update the form data
            $form.photo = file;
            $form.post(
            "/admin/perfil/picture/" + $form.id,
            {
                onError: (errors) => {
                    if (errors.data) {
                        displayAlert({ type: "error", message: errors.data });
                    }
                },
                onSuccess: (mensaje) => {
                    displayAlert({
                        type: "success",
                        message: "Foto cambiada exitosamente!",
                    });
                },
            },
            photoChanged = false
        );
        }
    }

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

    let showAllCases = window.screen.width > 700 ? true : false;
    let isOpenCreateEvolution = false;

    const updateClient = debounce((event) => {
        $form.clearErrors();
        $form.post(
            "/admin/perfil/" + $form.id,
            {
                onError: (errors) => {
                    if (errors.data) {
                        displayAlert({ type: "error", message: errors.data });
                    }
                },
                onSuccess: (mensaje) => {
                    displayAlert({
                        type: "success",
                        message: "Usuario editado exitosamente!",
                    });
                },
            },
        );
    }, 400);


    
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
        on:input={(e) => {
        
          if (e.target.type !== "file") {
            updateClient()
        }  
        }}
        style="height: calc(100vh - 100px);"
        class=" overflow-y-auto md:max-w-[330px] w-full lg:sticky top-0"
    >
        <fieldset
            class=" px-5 mt-4 gap-x-5 text-black p-6 pt-2 border-color2 rounded-md"
        >
            <legend
                class="relative text-center px-5 py-1 pt-1.5 rounded-xl bg-gray-50 text-dark"
                >Datos del usuario</legend
            >
            <label
                class="relative md:mt-4 row-span-3 mb-10 md:mb-10 max-w-[180px] cursor-pointer h-[218px] block"
            >
                <p class="mt-4 md:mt-0">Foto carnet</p>
                <input
                    type="file"
                    accept="image/*"
                    on:change={handleFileChange}
                    class="hidden"
                />
                <!-- Display the selected image -->
                {#if imagePreview}
                    <img
                        src={imagePreview}
                        alt="Preview"
                        class="absolute  max-w-[180px] w-[180px] h-[218px] object-cover border rounded border-gray-500"
                    />
                {:else}
                <img class="absolute  max-w-[180px] w-[180px] h-[218px] object-cover border rounded " src={`/storage/users/${$form.photo}`} alt="" srcset="">

                {/if}
            </label>
            <Input
                type="text"
                required={true}
                label={"Nombres"}
                bind:value={$form.name}
                error={$form.errors?.name}
                disabled={allowToEdit}
            />
            <Input
                type="text"
                required={true}
                label={"Apellidos"}
                bind:value={$form.last_name}
                error={$form.errors?.last_name}
                disabled={allowToEdit}
            />
            {#if isAdmin} 
            
                <Input
                    type="email"
                    label="correo"
                    bind:value={$form.email}
                    error={$form.errors?.email}
                    disabled={allowToEdit}
                />
                <Input
                    type="number"
                    required={true}
                    label={"Cédula"}
                    bind:value={$form.ci}
                    error={$form.errors?.ci}
                    disabled={allowToEdit}
                />
                <Input
                    type="tel"
                    label={"Teléfono"}
                    bind:value={$form.phone_number}
                    error={$form.errors?.phone_number}
                    disabled={allowToEdit}
                />
            {/if}
            <Input
                type="select"
                required={true}
                label={"Tipo de Usuario"}
                bind:value={$form.role_name}
                error={$form.errors?.role_name}
                disabled={!isAdmin}
            >
                <option value="doctor">Doctor</option>
                <option value="admin">Admin</option>
            </Input>
            <Input
                label={"Matrícula médica"}
                required={true}
                bind:value={$form.medical_license}
                error={$form.errors?.medical_license}
                disabled={allowToEdit}
            />
            <Input
                type="select"
                required={true}
                label={"Servicio tratante"}
                bind:value={$form.specialty_id}
                error={$form.errors?.specialty_id}
                disabled={!isAdmin}
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

    <div class="w-full">
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
                            class="md:flex items-center gap-2 py-3 px-2 sm:p-2 md:pr-4 lg:pr-6 justify-between"
                        >
                            <div
                                class="md:flex flex-col sm:flex-row items-center gap-2 px-2 md:p-2 justify-between"
                            >
                                <a
                                    href={`/admin/casos/detalle-caso/${evolution.emergency_case_id}`}
                                    use:inertia
                                    class="ml-1 text-sm caseLink"
                                >
                                    CASO: {evolution.emergency_case_id}
                                </a>
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
                                        class="pl-4 pr-1 listType bg-color1 font-bold pt-1.5 pb-0.5 text-xs text-white mr-2 uppercase"
                                    >
                                        IC
                                    </span>
                                {:else}
                                    <span
                                        class="pl-4 pr-1 listType bg-color3 font-bold pt-1.5 pb-0.5 text-xs text-white mr-2 uppercase"
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
                {#if loading}
                    <div class="loading">Cargando...</div>
                {/if}

                <!-- Show message if no more items -->
                {#if !hasMore}
                    <div class="loading text-gray-800">
                        No hay más datos que cargar.
                    </div>
                {/if}
            </ul>
        </fieldset>
    </div>
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
    a.caseLink {
        text-decoration: none;
        position: relative;
        /* display: block; */
        z-index: 1;
    }
    a.caseLink::before {
        content: "";
        background-color: #c9ebf2 !important;
        position: absolute;
        left: 0;
        bottom: 5px;
        width: 100%;
        height: 5px;
        z-index: -1;
        transition: all 0.2s ease-in-out;
    }

    a.caseLink:hover::before {
        bottom: 0 !important;
        left: 0 !important;
        height: 100% !important;
    }
</style>
