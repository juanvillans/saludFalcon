<script>
    import Input from "../../components/Input.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, inertia, router, page } from "@inertiajs/svelte";
    import StatusColor from "../../components/StatusColor.svelte";

    export let patient = false;
    console.log({ patient });
    if (patient) {
        if (patient?.data?.cases instanceof Array == false) {
            patient.data.cases = JSON.parse(patient.data.cases);
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
    let form = useForm(patient.data);
    let countingCases = patient.data.cases.length;
    $: console.log(showAllCases);
    let showAllCases = window.screen.width > 700 ? true : false;
</script>

<form action="" class="flex flex-col lg:flex-row gap-2 md:gap-5">
    <fieldset
        class="order-2 lg:order-1 h-fit max-w-[400px] lg:sticky top-0 px-5 mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color2 rounded-md"
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
                error={$form.errors?.patient_ci}
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
    </fieldset>

    <fieldset
        class=" order-1 lg:order-2 px-5 md:mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color2 rounded-md"
    >
        <legend
            class="text-center px-5 py-1 uppercase pt-1.5 rounded-sm bg-color2 text-gray-100"
            >HISTORIAL DE {patient.data.patient_name}
            {patient.data.patient_last_name}
            {patient.data.patient_ci}</legend
        >
        <span
            class="text-center col-span-2 font-bold bg-color4 p-1 rounded-lg  inline-block w-10 px-2"
        >
            {countingCases}
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

        {#if showAllCases}
            {#each patient.data.cases as single_case, i (i)}
                {#if i !== 0}
                    <div
                        class="col-span-2 rounded border-dark border-t  gap-x-5 w-full md:border md:p-6 pb-2 md:pb-3 pt-2 mt-3"
                    >
                    <div class="sm:flex gap-3">
                        <span
                            class="text-center col-span-2 font-bold bg-color4 p-1 rounded-lg  inline-block w-10 px-2"
                        >
                            {--countingCases}
                        </span>
                            <p>
                                Del {formatDateSpanish(single_case.start_date)}
                                <span class="opacity-60">-</span>
                                {convertTo12HourFormat(single_case.start_time)}
                                <span class="mx-1">
                                    | 

                                </span>
                        <StatusColor status={single_case.status} />
                         el
                                {formatDateSpanish(single_case.start_date)}
                                <span class="opacity-60">-</span>
                                {convertTo12HourFormat(single_case.start_time)}
                            </p>
                        </div>
                    
                        <div>
                            
                        </div>
                       
                        <div class="mt-2">
                            <p>
                                <iconify-icon class="text-2xl relative top-1.5 text-red" icon="material-symbols:diagnosis"></iconify-icon>
                                {single_case.diagnosis}
                            </p>
                        </div>
                        <div class="mt-2">
                            <p>
                                <iconify-icon class="text-2xl relative top-1.5 text-color1" icon="mdi:medicine-bottle"></iconify-icon>
                                {single_case.treatment}
                            </p>
                        </div>

                        <div>
                            <p class=" gap-x-2 flex items-center justify-end">
                                <span class="bg-gray-50 rounded-full px-2 py-1 flex items-center justify-end">
                                    {single_case.doctor.name} {single_case.doctor.last_name}
                                    <iconify-icon icon="mdi:doctor" style="font-size: 20px;"
                                    ></iconify-icon>

                                </span>
                            </p>
                        </div>
                    </div>
                {/if}
            {/each}
        {:else if patient.data?.cases?.length > 1}
            <button type="button" class="flex gap-2" on:click={() => showAllCases = !showAllCases}>
                <p>Mostrar todos los casos</p>
                <iconify-icon icon="si:expand-more-fill"></iconify-icon>
            </button>
        {/if}
    </fieldset>
</form>
