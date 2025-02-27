<script>
    import Input from "../components/Input.svelte";
    import { displayAlert } from "../stores/alertStore";
    import { useForm, page, inertia } from "@inertiajs/svelte";
    import Alert from "../components/Alert.svelte";

    export let data = [];

    let instituteSpecialities = [];
    $: if (data) {
        // UpdateData();
    }
    $: console.log(data.specialties);
    // Update data based on the current state of `data.specialties`

    const emptyDataForm = {
        ci: "",
        name: "",
        last_name: "",
        email: "",
        phone_number: "",
        role_name: "doctor",
        specialty_id: 1,
    };

    let formCreate = useForm({
        ...emptyDataForm,
    });

    let formEdit = useForm({
        ...emptyDataForm,
    });

    let selectedRow = { status: false, id: 0 };

    document.addEventListener("keydown", ({ key }) => {
        if (key === "Escape") {
            selectedRow = { status: false, id: 0 };
        }
    });

    function handleSubmit(event) {
        event.preventDefault();
        $formCreate.clearErrors();

        $formCreate.post("/registrarse", {
            onError: (errors) => {
                console.log(errors);

                if (errors.data) {
                    displayAlert({ type: "error", message: errors.data });
                }
            },
            onSuccess: (mensaje) => {
                console.log(mensaje);

                $formCreate.reset();
                displayAlert({
                    type: "success",
                    message: mensaje.props.flash.message,
                });
            },
        });
    }

    let submitStatus = "Enviar solicitud";
</script>

<svelte:head>
    <title>Usuarios</title>
</svelte:head>

<div class="w-11/12 mx-auto max-w-[800px]">
    <form
        id="a-form"
        on:submit={handleSubmit}
        action=""
        class="w-full px-5 mt-2 md:grid md:grid-cols-2 gap-x-5 p-6 pt-0 rounded-md"
    >
        <div class="mt-4 col-span-2"></div>
        <Input
            type="text"
            required={true}
            label={"Nombres"}
            bind:value={$formCreate.name}
            error={$formCreate.errors?.name}
        />
        <Input
            type="text"
            required={true}
            label={"Apellidos"}
            bind:value={$formCreate.last_name}
            error={$formCreate.errors?.last_name}
        />
        <Input
            type="email"
            label="correo"
            bind:value={$formCreate.email}
            error={$formCreate.errors?.email}
        />
        <Input
            type="number"
            required={true}
            label={"Cédula"}
            bind:value={$formCreate.ci}
            error={$formCreate.errors?.ci}
        />
        <Input
            type="tel"
            label={"Teléfono"}
            bind:value={$formCreate.phone_number}
            error={$formCreate.errors?.phone_number}
        />
        <Input
            label={"Matrícula médica"}
            required={true}
            bind:value={$formCreate.medical_license}
            error={$formCreate.errors?.medical_license}
        />
        <Input
            type="select"
            required={true}
            label={"Servicio tratante"}
            bind:value={$formCreate.specialty_id}
            error={$formCreate.errors?.specialty_id}
        >
            {#each data.specialties as speci (speci.id)}
                <option value={speci.id}>{speci.name}</option>
            {/each}
        </Input>
    </form>
    <input
        form="a-form"
        type="submit"
        value={$formCreate.processing ? "Cargando..." : submitStatus}
        class="hover:bg-color3 hover:text-white duration-200 mt-auto w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
    />

    <a
        href="/"
        class="mt-2 inline-block text-xl underline text-color1"
        use:inertia>Ya tengo cuenta, iniciar sesión</a
    >
</div>
