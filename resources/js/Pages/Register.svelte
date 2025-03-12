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
        medical_license: "",
        photo: "",
    };

    let formCreate = useForm({
        ...emptyDataForm,
    });

    let formEdit = useForm({
        ...emptyDataForm,
    });

    // Handle file selection
  let imagePreview = '';
  function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
      // Create a URL for the selected file
      imagePreview = URL.createObjectURL(file);
      // Update the form data
      $formCreate.photo = file;
    }
  }
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
<div class="w-11/12 mx-auto md:grid grid-cols-12 gap-4">
    <div class="col-span-5 mt-10">

        <h1 class="mt-3">Registrarme</h1>
        <p>Una vez que envie la solicitud podrá inicar sesión si usuario admin lo acepta</p>
    </div>
    <form
        id="a-form"
        on:submit={handleSubmit}
        action=""
        class="col-span-7 px-5 mt-2 grid-flow-row grid  md:grid-cols-2 gap-x-5 p-6 pt-0 rounded-md"
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
        
        <label class="relative mt-4 row-span-3 mb-7">
            <p>Foto carnet</p>
            <input type="file" accept="image/*" on:change={handleFileChange} class="hidden" />
            <!-- Display the selected image -->
            {#if imagePreview}
            <img src={imagePreview} alt="Preview" class="absolute w-[180] h-full object-cover border rounded border-gray-500" />
            
            {:else}
            <div class="rouded absolute w-[180] h-full bg-gray-200 flex items-center justify-center cursor-pointer text-gray-300 hover:text-gray-500">
                <iconify-icon class="" icon="tdesign:portrait" width="180" height="180"></iconify-icon>

            </div>

            {/if}
        </label>
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


        <input
            form="a-form"
            type="submit"
            value={$formCreate.processing ? "Cargando..." : submitStatus}
            class="col-span-2 mt-3 hover:bg-color3 hover:text-white duration-200  w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
        />
    
        <a
            href="/"
            class="mt-2 inline-block text-xl underline text-color1 mb-10"
            use:inertia>Ya tengo cuenta, iniciar sesión</a
        >
    </form>
</div>
