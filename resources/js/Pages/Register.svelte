<script>
    import Table from "../components/Table.svelte";
    import Modal from "../components/Modal.svelte";
    import Input from "../components/Input.svelte";
    import Especialidades from "../components/Especialidades.svelte";
    import { displayAlert } from "../stores/alertStore";
    import { useForm, page } from "@inertiajs/svelte";
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
        role_name: "",
        specialty: {},
        specialties_ids: [],
    };

    let formCreate = useForm({
        ...emptyDataForm,
    });

    let formEdit = useForm({
        ...emptyDataForm,
    });

    let showModal = false;
    $: showModalCreateSpecialties = false;
    let selectedRow = { status: false, id: 0 };

    document.addEventListener("keydown", ({ key }) => {
        if (key === "Escape") {
            selectedRow = { status: false, id: 0 };
        }
    });

    function handleSubmit(event) {
        event.preventDefault();
        $formCreate.clearErrors();
        if (
            submitStatus == "Envir solicitud" &&
            $page.props.auth.permissions.find((p) => p == "create-users")
        ) {
            $formCreate.post("/admin/usuarios", {
                onError: (errors) => {
                    if (errors.data) {
                        displayAlert({ type: "error", message: errors.data });
                    }
                },
                onSuccess: (mensaje) => {
                    $formCreate.reset();
                    displayAlert({
                        type: "success",
                        message: "Ok todo salió bien",
                    });
                    showModal = false;
                },
            });
        } else if (
            submitStatus == "Editar" &&
            $page.props.auth.permissions.find((p) => p == "update-users")
        ) {
            $formCreate.put(`/admin/usuarios/${$formCreate.id}`, {
                onError: (errors) => {
                    if (errors.data) {
                        displayAlert({ type: "error", message: errors.data });
                    }
                },
                onSuccess: (mensaje) => {
                    $formCreate.reset();
                    displayAlert({
                        type: "success",
                        message: "Ok todo salió bien",
                    });
                    showModal = false;
                    selectedRow = { status: false, id: 0, row: {} };
                },
            });
        }
    }

    function handleDelete(id) {
        if ($page.props.auth.permissions.find((p) => p == "update-users")) {
            $formCreate.delete(`/admin/usuarios/${id}`, {
                onBefore: () =>
                    confirm(`¿Está seguro de eliminar a este usuario?`),
                onError: (errors) => {
                    if (errors.data) {
                        displayAlert({ type: "error", message: errors.data });
                    }
                },
                onSuccess: (mensaje) => {
                    displayAlert({
                        type: "success",
                        message: "Usuario Eliminado",
                    });
                    selectedRow = { status: false, id: 0, row: {} };
                },
            });
        }
    }

    function fillFormToEdit() {
        submitStatus = "Editar";
        $formCreate.reset();
        showModal = true;
    }

    let submitStatus = "Envir solicitud";
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
        <div class="mt-4 col-span-2">
 
        </div>
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
            type="select"
            required={true}
            label={"Tipo de Usuario"}
            bind:value={$formCreate.role_name}
            error={$formCreate.errors?.role_name}
        >
            <option value="doctor">Doctor</option>
            <option value="admin">Admin</option>
        </Input>
        <Input
            type="select"
            required={true}
            label={"Servicio tratante"}
            bind:value={$formCreate.speciality}
            error={$formCreate.errors?.speciality}
        >
            {#each data.specialties as speci (speci.id)}
                <option value={speci}>{speci.name}</option>
            {/each}
        </Input>
    </form>
    <input
        form="a-form"
        type="submit"
        value={$formCreate.processing ? "Cargando..." : submitStatus}
        class="hover:bg-color3 hover:text-white duration-200 mt-auto w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
    />

</div>

{#if $page.props.auth.permissions.find((p) => p == "create-users")}
    <div class="flex justify-between items-center">
        <button
            class="btn_create inline-block p-2 px-3"
            on:click={(e) => {
                if (submitStatus == "Editar") {
                    selectedRow = {
                        status: false,
                        id: 0,
                        title: "",
                    };

                    $formCreate.defaults({
                        ...emptyDataForm,
                    });
                    setTimeout(() => {
                        $formCreate.reset();
                    }, 100);
                }
                e.preventDefault();

                showModal = true;
                submitStatus = "Envir solicitud";
            }}
        >
            <span class="md:hidden text-4xl relative top-1 font-bold"
                ><iconify-icon icon="ic:round-add"></iconify-icon></span
            >
            <span class="hidden md:block"> Nuevo Usuario </span>
        </button>
        <!-- svelte-ignore missing-declaration -->
        <!-- svelte-ignore a11y-click-events-have-key-events -->
        <a
            class="cursor-pointer border-b py-2 px-3 hover:bg-color4"
            on:click={() => (showModalCreateSpecialties = true)}
            >Especialidades de la institución</a
        >
    </div>
{/if}
