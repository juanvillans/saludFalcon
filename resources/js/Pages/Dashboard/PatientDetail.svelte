<script>
    import Input from "../../components/Input.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, page } from "@inertiajs/svelte";
    import StatusColor from "../../components/StatusColor.svelte";
    import Alert from "../../components/Alert.svelte";
    import Editor from "cl-editor/src/Editor.svelte";

    export let areas = [];
    export let patient = false;
    let countingCases;
    let form = useForm(structuredClone(patient.data));

    $: if (patient) {
        countingCases = patient.data.cases.length;
    }
    let editor;
    let descriptionLength = 0;
    let openAccordeon = -1;
    let editStatus = false;
    $: console.log(patient?.data);
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
            class=" px-5 mt-4 md:grid grid-cols-2 gap-x-5 border p-6 pt-2 border-color2 rounded-md"
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
            class=" sm:px-5 md:mt-4 md:grid grid-cols-2 gap-x-5 sm:border sm:p-6 pt-2 border-color2 rounded-md"
        >
            <legend
                class="text-center px-5 py-1 uppercase pt-1.5 rounded-sm bg-color2 text-gray-100"
                >HISTORIAL DE {patient.data.patient_name}
                {patient.data.patient_last_name}
                {patient.data.patient_ci}</legend
            >

            {#each patient?.data?.cases as single_case, i (i)}
                {#if i == 0}
                    <!-- svelte-ignore empty-block -->
                    {#if editStatus == false}
                        <!-- svelte-ignore a11y-click-events-have-key-events -->
                        <div
                            class="bg-white p-3 h-fit col-span-2 rounded gap-x-5 w-full md:border md:p-6 pb-2 md:pb-3 pt-2 mt-3"
                        >
                            <span
                                on:click={() =>
                                    openAccordeon == i
                                        ? (openAccordeon = -1)
                                        : (openAccordeon = i)}
                            >
                                <div class="flex gap-3">
                                    <span
                                        class="h-fit text-center col-span-2 font-bold bg-color4 p-1 rounded-lg inline-block w-10 px-2"
                                    >
                                        {countingCases--}
                                    </span>
                                    <p class="h-fit">
                                        Del {formatDateSpanish(
                                            single_case.start_date,
                                        )}
                                        <span class="opacity-60">-</span>
                                        {convertTo12HourFormat(
                                            single_case.start_time,
                                        )}
                                        <span class="mx-1"> | </span>
                                        <StatusColor
                                            status={single_case.status}
                                        />
                                        {#if single_case.status == "Remitido"}
                                            a {single_case.area?.name}
                                        {/if}

                                        {#if single_case.status != "Permanencia"}
                                            el
                                            {formatDateSpanish(
                                                single_case.end_date,
                                            )}
                                            <span class="opacity-60">-</span>
                                            {convertTo12HourFormat(
                                                single_case.end_time,
                                            )}
                                        {/if}
                                    </p>
                                    {#if $page.props.auth.user_id == single_case.doctor.id}
                                        <div
                                            class=" ml-auto h-fit flex gap-2 flex-col sm:flex-row rounded-md"
                                        >
                                            <button
                                                title="Editar"
                                                class="p-1 bg-gray-200 px-2 hover:bg-color3 text-gray-700 hover:text-white"
                                                on:click={() =>
                                                    (editStatus = !editStatus)}
                                            >
                                                <iconify-icon
                                                    icon="ic:baseline-edit"
                                                    class="relative top-0.5"
                                                ></iconify-icon>
                                            </button>

                                            <button
                                                title="Eliminar"
                                                class="p-1 bg-gray-200 px-2 hover:bg-red text-gray-700 hover:text-white"
                                                on:click={() => handleDelete()}
                                            >
                                                <iconify-icon
                                                    icon="mdi:trash-outline"
                                                    class="relative top-0.5"
                                                ></iconify-icon>
                                            </button>
                                        </div>
                                    {/if}
                                </div>

                                <div></div>

                                <div class="mt-2">
                                    <p>
                                        <iconify-icon
                                            class="text-2xl relative top-1.5 text-red"
                                            icon="material-symbols:diagnosis"
                                        ></iconify-icon>
                                        {single_case.diagnosis}
                                    </p>
                                </div>
                                <div class="mt-2">
                                    <p>
                                        <iconify-icon
                                            class="text-2xl relative top-1.5 text-color1"
                                            icon="mdi:medicine-bottle"
                                        ></iconify-icon>
                                        {single_case.treatment}
                                    </p>
                                </div>

                                <div>
                                    <p
                                        class=" gap-x-2 flex items-center justify-end"
                                    >
                                        <span
                                            class="bg-gray-50 rounded-full px-2 py-1 flex items-center justify-end"
                                        >
                                            {single_case.doctor.name}
                                            {single_case.doctor.last_name}
                                            <iconify-icon
                                                icon="mdi:doctor"
                                                style="font-size: 20px;"
                                            ></iconify-icon>
                                        </span>
                                    </p>
                                </div>

                                <h2>Evoluciones:</h2>
                            </span>
                            
                            <div>
                                {#if openAccordeon == i}
                                    <div>
                                        {#if openAccordeon}
                                            <div></div>
                                        {/if}
                                        {#if isOpenCreateEvolution}
                                            <div class="mb-5">
                                                <div class="flex gap-10 mt-3 items-center mb-3">
                                                    <label
                                                    class:bg-green={$form.condition == "Estable"}
                                                     class:text-white={$form.condition == "Estable"}    
                                                    class="text-green px-2 font-bold py-1 hover:shadow-xl cursor-pointer border-green border rounded-full"
                                                    >
                                                        <input
                                                            class="mr-3 inline-block hidden"
                                                            type="radio"
                                                            bind:group={$form.condition}
                                                            value="Estable"
                                                            name="condition"
                                                            id=""
                                                            
                                                        /><span
                                                            >Estable</span
                                                        >
                                                    </label>

                                                    <label
                                                    class:bg-orange={$form.condition == "Inestable"}
                                                     class:text-white={$form.condition == "Inestable"}    
                                                    class="text-orange px-2 font-bold py-1 hover:shadow-xl cursor-pointer  border-orange border rounded-full"
                                                    >
                                                        <input
                                                            class="mr-3 inline-block hidden"
                                                            type="radio"
                                                            bind:group={$form.condition}
                                                            value="Inestable"
                                                            name="condition"
                                                            id=""
                                                            
                                                        /><span
                                                          
                                                            >Inestable</span
                                                        >
                                                    </label>
                                                    <label
                                                    class:bg-red={$form.condition == "Crítica"}
                                                     class:text-white={$form.condition == "Crítica"}    
                                                    class="text-red px-2 font-bold py-1 hover:shadow-xl cursor-pointer border-red rounded-full border "
                                                    >
                                                        <input
                                                            class="mr-3 inline-block hidden"
                                                            type="radio"
                                                            bind:group={$form.condition}
                                                            value="Crítica"
                                                            name="condition"
                                                            id=""
                                                        /><span
                                                            >Crítica</span
                                                        >
                                                    </label>
                                                </div>
                                                <Editor
                                                    actions={[
                                                        "b",
                                                        "i",
                                                        "u",
                                                        "ol",
                                                        "ul",
                                                        "left",
                                                        "center",
                                                        "justify",
                                                    ]}
                                                    html={$form.description}
                                                    on:change={(evt) => {
                                                        descriptionLength =
                                                            evt.detail.length;
                                                        if (
                                                            descriptionLength >=
                                                            300
                                                        ) {
                                                            $form.setError(
                                                                "description",
                                                                "No puede tener más de 300 caracteres",
                                                            );
                                                        } else {
                                                            $form.clearErrors(
                                                                "description",
                                                            );
                                                        }
                                                        $form.description =
                                                            evt.detail;
                                                    }}
                                                    contentId="notes-content"
                                                    bind:this={editor}
                                                />
                                              
                                                <div
                                                    class="flex justify-between items-center"
                                                >
                                                    <button
                                                        type="button"
                                                        class=""
                                                        on:click={() =>
                                                            (isOpenCreateEvolution = false)}
                                                        >Cancelar</button
                                                    >
                                                    <input
                                                        type="submit"
                                                        
                                                        value={$form.processing
                                                            ? "Cargando..."
                                                            : "Guardar"}
                                                        class="hover:bg-color3 hover:text-white duration-200 mt-3 px-4 bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
                                                    />
                                                </div>
                                            </div>
                                        {:else}
                                            <button
                                                on:click={(e) =>
                                                    (isOpenCreateEvolution = true)}
                                                class="p-1 px-3 bg-color3 text-white mb-3"
                                                >Crear nueva evolución +</button
                                            >
                                        {/if}
                                        <ul>
                                            <li
                                                class="bg-gray-50 mb-3 rounded overflow-hidden shadow"
                                            >
                                                <span
                                                    class="flex items-center gap-2 p-1"
                                                >
                                                    #1
                                                    <iconify-icon
                                                        icon="fluent-mdl2:date-time-12"
                                                    ></iconify-icon>
                                                    <p>16 jun 2024, 3:00pm</p>

                                                    
                                                    <span  class="text-orange px-2 text-sm font-bold py-1 hover:shadow-xl cursor-pointer  border-orange border rounded-full">
                                                        Inestable
                                                    </span>
                                                </span>

                                                <div class="bg-white p-1">
                                                    <p>
                                                        Lorem ipsum dolor sit
                                                        amet consectetur
                                                        adipisicing elit. Eius
                                                        enim accusantium
                                                        explicabo repellendus id
                                                        commodi, nulla minima,
                                                        suscipit distinctio
                                                        quibusdam rerum nihil,
                                                        modi soluta cum nemo.
                                                        Adipisci itaque ipsa
                                                        enim!
                                                    </p>
                                                    Lorem ipsum dolor sit amet consectetur
                                                    adipisicing elit. voluptatum
                                                    odio culpa delectus quis, maiores,
                                                    accusamus iure eos
                                                </div>
                                            </li>

                                            <li
                                                class="bg-gray-50 mb-3 border rounded overflow-hidden"
                                            >
                                                <span
                                                    class="flex items-center gap-2 p-1"
                                                >
                                                    #1
                                                    <iconify-icon
                                                        icon="fluent-mdl2:date-time-12"
                                                    ></iconify-icon>
                                                    <p>16 jun 2024, 3:00pm</p>
                                                </span>

                                                <div class="bg-white p-1">
                                                    <p>
                                                        Lorem ipsum dolor sit
                                                        amet consectetur
                                                        adipisicing elit. Eius
                                                        enim accusantium
                                                        explicabo repellendus id
                                                        commodi, nulla minima,
                                                        suscipit distinctio
                                                        quibusdam rerum nihil,
                                                        modi soluta cum nemo.
                                                        Adipisci itaque ipsa
                                                        enim!
                                                    </p>
                                                    Lorem ipsum dolor sit amet consectetur
                                                    adipisicing elit. voluptatum
                                                    odio culpa delectus quis, maiores,
                                                    accusamus iure eos
                                                </div>
                                            </li>

                                            <li
                                                class="bg-gray-50 mb-3 border rounded overflow-hidden"
                                            >
                                                <span
                                                    class="flex items-center gap-2 p-1"
                                                >
                                                    #1
                                                    <iconify-icon
                                                        icon="fluent-mdl2:date-time-12"
                                                    ></iconify-icon>
                                                    <p>16 jun 2024, 3:00pm</p>
                                                </span>

                                                <div class="bg-white p-1">
                                                    <p>
                                                        Lorem ipsum dolor sit
                                                        amet consectetur
                                                        adipisicing elit. Eius
                                                        enim accusantium
                                                        explicabo repellendus id
                                                        commodi, nulla minima,
                                                        suscipit distinctio
                                                        quibusdam rerum nihil,
                                                        modi soluta cum nemo.
                                                        Adipisci itaque ipsa
                                                        enim!
                                                    </p>
                                                    Lorem ipsum dolor sit amet consectetur
                                                    adipisicing elit. voluptatum
                                                    odio culpa delectus quis, maiores,
                                                    accusamus iure eos
                                                </div>
                                            </li>

                                            <li
                                                class="bg-gray-50 mb-3 border rounded overflow-hidden"
                                            >
                                                <span
                                                    class="flex items-center gap-2 p-1"
                                                >
                                                    #1
                                                    <iconify-icon
                                                        icon="fluent-mdl2:date-time-12"
                                                    ></iconify-icon>
                                                    <p>16 jun 2024, 3:00pm</p>
                                                </span>

                                                <div class="bg-white p-1">
                                                    <p>
                                                        Lorem ipsum dolor sit
                                                        amet consectetur
                                                        adipisicing elit. Eius
                                                        enim accusantium
                                                        explicabo repellendus id
                                                        commodi, nulla minima,
                                                        suscipit distinctio
                                                        quibusdam rerum nihil,
                                                        modi soluta cum nemo.
                                                        Adipisci itaque ipsa
                                                        enim!
                                                    </p>
                                                    Lorem ipsum dolor sit amet consectetur
                                                    adipisicing elit. voluptatum
                                                    odio culpa delectus quis, maiores,
                                                    accusamus iure eos
                                                </div>
                                            </li>

                                            <li
                                                class="bg-gray-50 mb-3 border rounded overflow-hidden"
                                            >
                                                <span
                                                    class="flex items-center gap-2 p-1"
                                                >
                                                    #1
                                                    <iconify-icon
                                                        icon="fluent-mdl2:date-time-12"
                                                    ></iconify-icon>
                                                    <p>16 jun 2024, 3:00pm</p>
                                                </span>

                                                <div class="bg-white p-1">
                                                    <p>
                                                        Lorem ipsum dolor sit
                                                        amet consectetur
                                                        adipisicing elit. Eius
                                                        enim accusantium
                                                        explicabo repellendus id
                                                        commodi, nulla minima,
                                                        suscipit distinctio
                                                        quibusdam rerum nihil,
                                                        modi soluta cum nemo.
                                                        Adipisci itaque ipsa
                                                        enim!
                                                    </p>
                                                    Lorem ipsum dolor sit amet consectetur
                                                    adipisicing elit. voluptatum
                                                    odio culpa delectus quis, maiores,
                                                    accusamus iure eos
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                {/if}
                            </div>
                        </div>
                    {:else if $page.props.auth.user_id == single_case.doctor.id}
                        <span
                            class="text-center col-span-2 font-bold bg-color4 p-1 rounded-lg inline-block w-10 px-2"
                        >
                            {countingCases--}
                        </span>
                        <Input
                            type="date"
                            required={true}
                            label={"Fecha de entrada *"}
                            bind:value={$form.cases[0].start_date}
                            error={$form.errors?.cases?.[0]?.start_date}
                        />

                        <Input
                            type="time"
                            required={true}
                            label={"Hora de entrada *"}
                            bind:value={$form.cases[0].start_time}
                            error={$form.errors?.cases?.[0]?.start_time}
                        />

                        <Input
                            type="select"
                            required={true}
                            label={"Estado *"}
                            bind:value={$form.cases[0].status}
                            error={$form.errors?.cases?.[0]?.status}
                        >
                            <option value="Alta"> Alta </option>
                            <option value="Permanencia">Permanencia </option>
                            <option value="Remitido">Remitido </option>
                            <option value="Fallecido">Fallecido </option>
                        </Input>

                        {#if $form.cases[0].status == "Remitido"}
                            <Input
                                type="select"
                                required={true}
                                label={"Area remitida *"}
                                bind:value={$form.cases[0].area}
                                error={$form.errors?.cases?.[0]?.area}
                            >
                                {#each areas as area (area.id)}
                                    <option value={area}>{area.name} </option>
                                {/each}
                            </Input>
                        {/if}
                        {#if $form.cases[0].status !== "Permanencia"}
                            <Input
                                type="date"
                                label={"Fecha de salida "}
                                bind:value={$form.cases[0].end_date}
                                error={$form.errors?.cases?.[0]?.end_date}
                            />
                            <Input
                                type="time"
                                required={true}
                                label={"Hora de salida *"}
                                bind:value={$form.cases[0].end_time}
                                error={$form.errors?.cases?.[0]?.end_time}
                            />
                        {/if}
                        <div class="md:flex w-full gap-4 col-span-2">
                            <Input
                                type="textarea"
                                required={true}
                                label={"Diagnóstico *"}
                                bind:value={$form.cases[0].diagnosis}
                                error={$form?.errors?.cases?.[0]?.diagnosis}
                            />

                            <Input
                                type="textarea"
                                required={true}
                                label={"Tratamiento *"}
                                bind:value={$form.cases[0].treatment}
                                error={$form.errors?.cases?.[0]?.treatment}
                            />
                        </div>

                        <button
                            type="button"
                            class="max-w-fit px-2 hover:bg-gray-200"
                            on:click={() => (editStatus = !editStatus)}
                            >Cancelar</button
                        >
                        <input
                            type="submit"
                            value={$form.processing ? "Cargando..." : "Editar"}
                            class="hover:bg-color3 hover:text-white duration-200 mt-auto w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
                        />
                    {/if}
                {:else if showAllCases}
                    <div
                        class="bg-background p-3 h-fit col-span-2 rounded border-dark gap-x-5 w-full md:border md:p-6 pb-2 md:pb-3 pt-2 mt-3"
                    >
                        <div class="sm:flex gap-3">
                            <span
                                class="text-center col-span-2 font-bold bg-color4 p-1 rounded-lg inline-block w-10 px-2"
                            >
                                {--countingCases}
                            </span>
                            <p>
                                Del {formatDateSpanish(single_case.start_date)}
                                <span class="opacity-60">-</span>
                                {convertTo12HourFormat(single_case.start_time)}
                                <span class="mx-1"> | </span>
                                <StatusColor status={single_case.status} />
                                {#if single_case.status == "Remitido"}
                                    a {single_case.area?.name?.name}
                                {/if}
                                el
                                {formatDateSpanish(single_case.end_date)}
                                <span class="opacity-60">-</span>
                                {convertTo12HourFormat(single_case.end_time)}
                            </p>
                        </div>

                        <div></div>

                        <div class="mt-2">
                            <p>
                                <iconify-icon
                                    class="text-2xl relative top-1.5 text-red"
                                    icon="material-symbols:diagnosis"
                                ></iconify-icon>
                                {single_case.diagnosis}
                            </p>
                        </div>
                        <div class="mt-2">
                            <p>
                                <iconify-icon
                                    class="text-2xl relative top-1.5 text-color1"
                                    icon="mdi:medicine-bottle"
                                ></iconify-icon>
                                {single_case.treatment}
                            </p>
                        </div>

                        <div>
                            <p class=" gap-x-2 flex items-center justify-end">
                                <span
                                    class="bg-gray-50 rounded-full px-2 py-1 flex items-center justify-end"
                                >
                                    {single_case.doctor.name}
                                    {single_case.doctor.last_name}
                                    <iconify-icon
                                        icon="mdi:doctor"
                                        style="font-size: 20px;"
                                    ></iconify-icon>
                                </span>
                            </p>
                        </div>
                    </div>
                {/if}
            {/each}
            {#if patient.data?.cases?.length > 1 && !showAllCases}
                <button
                    type="button"
                    class="flex gap-2"
                    on:click={() => (showAllCases = !showAllCases)}
                >
                    <p>Mostrar todos los casos</p>
                    <iconify-icon icon="si:expand-more-fill"></iconify-icon>
                </button>
            {/if}
        </fieldset>
    </form>
</div>
<Alert />


<style>
</style>