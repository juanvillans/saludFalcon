<script>
    import Table from "../../components/Table.svelte";
    import Modal from "../../components/Modal.svelte";
    import Input from "../../components/Input.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, page, router } from "@inertiajs/svelte";
    export let data = [];

    let instituteSpecialities = [];
    let specialities = [];
    let dataTable = [];
    $: if (data) {
        if (data.requests) {
            dataTable = data.requests;
        } else {
            dataTable = data.users.data;
        }

        // UpdateData();
    }
    // Update data based on the current state of `data.specialties`
    const emptyDataForm = {
        id: 1,
        ci: "",
        name: "",
        last_name: "",
        email: "",
        phone_number: "",
        created_at: "",
        specialty: { id: 1, name:"", status: ""},
        role_id: "",
        medical_license: "",
    };

    let formCreate = useForm({
        ...emptyDataForm,
    });

    let formEdit = useForm({
        ...emptyDataForm,
    });

    let showModal = false;
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
            submitStatus == "Crear" &&
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
                        message: mensaje.props.flash.message,
                    });
                    selectedRow = { status: false, id: 0, row: {} };
                },
            });
        }
    }

    function fillFormToEdit() {
        submitStatus = "Editar";
        console.log(selectedRow);

        showModal = true;
        console.log($formCreate);
    }

    function acept(id) {
        if ($page.props.auth.permissions.find((p) => p == "update-users")) {
            $formCreate.post(
                `/admin/usuarios/solicitudes/aceptar/${selectedRow.id}`,
                {
                    onBefore: () =>
                        confirm(`¿Está seguro de aceptar a este usuario?`),
                    onError: (errors) => {
                        if (errors.data) {
                            displayAlert({
                                type: "error",
                                message: errors.data,
                            });
                        }
                    },
                    onSuccess: (mensaje) => {
                        displayAlert({
                            type: "success",
                            message: mensaje.props.flash.message,
                        });
                        selectedRow = { status: false, id: 0, row: {} };
                    },
                },
            );
        }
    }

    let submitStatus = "Crear";
    let selectSpecialityModal = false;
    let filteredSpecialities = [];
</script>

<svelte:head>
    <title>Usuarios</title>
</svelte:head>

{#if $page.props.auth.permissions.find((p) => p == "create-users")}
    <Modal bind:showModal modalClasses={"max-w-[560px]"}>
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
            slot="btn_footer"
            type="submit"
            value={$formCreate.processing ? "Cargando..." : submitStatus}
            class="hover:bg-color3 hover:text-white duration-200 mt-auto w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
        />
    </Modal>
{/if}

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

                    $formCreate = {
                        ...emptyDataForm,
                    };
                 
                }
                e.preventDefault();

                showModal = true;
                submitStatus = "Crear";
            }}
        >
            <span class="md:hidden text-4xl relative top-1 font-bold"
                ><iconify-icon icon="ic:round-add"></iconify-icon></span
            >
            <span class="hidden md:block"> Nuevo Usuario </span>
        </button>
        <!-- svelte-ignore missing-declaration -->
        <!-- svelte-ignore a11y-click-events-have-key-events -->
    </div>
{/if}

<div class="flex">
    <div
        class="mt-2 inline-flex overflow-hidden border border-dark border-opacity-30 divide-x divide-gray-300 rounded-lg rtl:flex-row-reverse"
    >
        <button
            on:click={(e) => {
                router.get(`/admin/usuarios`, {});
            }}
            class="filter_button px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm hover:bg-gray-200"
            class:bg-gray-200={!data.requests}
        >
            Usuarios aceptados
        </button>
        <button
            on:click={(e) => {
                router.get(`${$page.url}`, { requests: true });
            }}
            class="filter_button px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm hover:bg-gray-200"
            class:bg-gray-200={data.requests}
        >
            Solicitudes
        </button>
    </div>
</div>
<Table
    {selectedRow}
    on:fillFormToEdit={fillFormToEdit}
    on:acept={acept}
    on:clickDeleteIcon={() => {
        handleDelete(selectedRow.id);
    }}
    selectedRowOptions={{
        editar: !data.requests ? true : false,
        eliminar: !data.requests ? true : false,
        aceptar: data.requests ? true : false,
        rechazar: data.requests ? true : false,
    }}
    pagination={false}
>
    <div slot="filterBox"></div>
    <thead slot="thead">
        <tr>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>C.I</th>
            <th>Correo</th>
            <th>Tel</th>
            <th>Especialidad</th>
        </tr>
    </thead>

    <tbody slot="tbody">
        {#each dataTable as row, i}
            <tr
                on:click={(e) => {
                    if (
                        $page.props.auth.permissions.find(
                            (p) => p == "create-users",
                        )
                    ) {
                        if (row.id != selectedRow.id) {
                            console.log(row);

                            selectedRow = {
                                status: true,
                                id: row.id,
                            };
                            $formCreate = { ...row };
                        } else {
                            selectedRow = {
                                status: false,
                                id: 0,
                                title: "",
                            };
                            $formCreate.defaults({
                                ...emptyDataForm,
                            });
                        }
                    }
                }}
                class={`cursor-pointer  ${selectedRow.id == row.id ? "bg-color2 hover:bg-opacity-10 bg-opacity-10 brightness-110" : " hover:bg-gray-500 hover:bg-opacity-5"}`}
            >
                <td>{row.name}</td>
                <td>{row.last_name}</td>
                <td>{row.ci}</td>
                <td>{row.email}</td>
                <!-- <td>{row.sex}</td> -->
                <td>{row.phone_number}</td>
                <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                <td class="flex gap-3"
                    >{#if row.specialty}
                        <span class="px-3 py-1 rounded-full bg-gray-100">
                            {row.specialty.name + " "}
                        </span>
                    {:else}
                        <span class="opacity-60">No tiene</span>
                    {/if}
                </td>
            </tr>
        {/each}
    </tbody>
</Table>
