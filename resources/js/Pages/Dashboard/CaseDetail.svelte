<script>
    import Input from "../../components/Input.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, page } from "@inertiajs/svelte";
    import StatusColor from "../../components/StatusColor.svelte";
    import Alert from "../../components/Alert.svelte";
    import fetchLocalData  from "../../components/localData"
    import { onMount } from 'svelte';

    import Editor from "cl-editor/src/Editor.svelte";

    export let areas = [];
    export let patient = false;
    export let caseDetail = {};
    let countingCases;
    let form = useForm(structuredClone(caseDetail.data));
    let localData = {};

    onMount(async () => {
        console.log('ayy')
        try {
            localData = await fetchLocalData();
            // console.log(fetchLocalData())
        } catch (error) {
            console.error("Error loading data:", error);
        }
    });
    console.log(localData);
    $: if (patient) {
        countingCases = caseDetail.data.cases.length;
    }
    let editor;
    let descriptionLength = 0;
    let openAccordeon = -1;
    let editStatus = false;
    $: console.log(localData);
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
        <fieldset
            class=" sm:px-5 md:mt-4  gap-x-5 sm:border sm:p-6 pt-2 border-color2 rounded-md"
        >
            <legend
                class="text-center px-5 py-1 uppercase pt-1.5 rounded-sm bg-color2 text-gray-100"
                >HISTORIAL DE {caseDetail.data.patient_name}
                {caseDetail.data.patient_last_name}
                </legend
            >
            <div class="col-span-2">
                <h3 class="font-bold">Motivo de consulta:</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel aspernatur alias asperiores consequuntur recusandae voluptates quam soluta voluptas nihil inventore non</p>
                <h3 class="font-bold">Duración total:</h3>
                <p>3hrs</p>
            </div>
            <fieldset
            class=" px-5 mt-4 md:grid grid-cols-2 gap-x-5 w-full border p-6 pt-2 border-color3 rounded-md"
        >
            <legend
                class="relative text-center px-5 py-1 pt-1.5 rounded-xl bg-color1 text-gray-100"
                >DIAGNÓSTICO Y TRATAMIENTO</legend
            >
           
        ñ
            <div class="col-span-2">
                <span>Diagnóstico *</span>
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
                    bind:value={$form.diagnosis}
                    error={$form?.errors?.diagnosis}
                />
            </div>
           
            <Input
                type="textarea"
                required={true}
                classes={"col-span-2"}
                label={"Orden médica de ingreso *"}
                bind:value={$form.treatment}
                error={$form.errors?.treatment}
            />
        </fieldset>
        </fieldset>
    </form>
</div>
<Alert />
