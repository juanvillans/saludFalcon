<script>
    import Table from "../../components/Table.svelte";
    import Modal from "../../components/Modal.svelte";
    import Input from "../../components/Input.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, page, router } from "@inertiajs/svelte";
    import { onMount } from "svelte";
    import fetchLocalData from "../../components/localData";

    export let data = [];

    let localData = {};

    onMount(async () => {
        try {
            localData = await fetchLocalData();
            // console.log(fetchLocalData())
        } catch (error) {
            console.error("Error loading data:", error);
        }
    });

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
        ci: "",
        name: "",
        last_name: "",
        email: "",
        phone_number: "",
        role_name: "",
        specialty_id: "",
        medical_license: "",
        photo: "",
    };

    let formCreate = useForm({
        ...emptyDataForm,
    });

    let showModal = false;
    let selectedRow = { status: false, id: 0 };

    document.addEventListener("keydown", ({ key }) => {
        if (key === "Escape") {
            selectedRow = { status: false, id: 0 };
        }
    });

    let requests = false;
    function CheckRequestUrl() {
        const params = new URLSearchParams(window.location.search);
        requests = params.get("requests") === "true"; // Check if requests is 'true'
    }

    onMount(() => {
        CheckRequestUrl();
    });
    let imagePreview = "";
    function handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            // Create a URL for the selected file
            imagePreview = URL.createObjectURL(file);
            // Update the form data
            $formCreate.photo = file;
        }
    }

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
                    imagePreview= ''
                    displayAlert({
                        type: "success",
                        message: "Ok todo salió bien",
                    });
                    showModal = false;
                },
            });
        }
    }

    function fillFormToEdit() {
        submitStatus = "Editar";
        showModal = true;
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

    function reject(id) {
        if ($page.props.auth.permissions.find((p) => p == "update-users")) {
            $formCreate.post(
                `/admin/usuarios/solicitudes/rechazar/${selectedRow.id}`,
                {
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

    function handleMouseDown() {
        selectingText = false; // Reset before mouse down
    }

    function handleMouseUp(event, id, row) {
        const selection = window.getSelection();

        // Check if there is any selected text
        if (selection.toString().length === 0) {
            if (requests) {
                if (
                    $page.props.auth.permissions.find(
                        (p) => p == "create-users",
                    )
                ) {
                    if (row.id != selectedRow.id) {
                        selectedRow = {
                            status: true,
                            id: row.id,
                            title: row.title,
                        };
                        $formCreate = {
                            ...$formCreate,
                            ...row,
                            specialty_id: row.specialty.id,
                        };
                        console.log($formCreate);

                        $formCreate.clearErrors();
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
                return;
            }
        }
        goToDetailPage(id);
    }

    function goToDetailPage(id) {
        if (!requests) {
            router.get("/admin/perfil/" + id,{});
        }
    }
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
                label="Correo"
                bind:value={$formCreate.email}
                error={$formCreate.errors?.email}
            />
            <label
                class="relative md:mt-4 row-span-3 mb-10 md:mb-7 max-w-[180px] h-[218px] block"
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
                        class="absolute max-w-[180px] w-[180px] h-[218px] object-cover border rounded border-gray-500"
                    />
                {:else}
                    <div
                        class="rouded absolute max-w-[180px] w-[180px] h-[218px] bg-gray-200 flex items-center justify-center cursor-pointer text-gray-300 hover:text-gray-500"
                    >
                        <iconify-icon
                            class=""
                            icon="tdesign:portrait"
                            width="180"
                            height="180"
                        ></iconify-icon>
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
                {#each localData.specialties || [] as speci (speci.id)}
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

                    $formCreate.defaults({
                        ...emptyDataForm,
                    });
                    setTimeout(() => {
                        $formCreate.reset();
                    }, 100);
                }
                e.preventDefault();

                showModal = true;
                submitStatus = "Crear";
            }}
        >
            <span class="sm:hidden  text-2xl relative top-1 font-bold"
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
                requests = false;
            }}
            class="filter_button px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm hover:bg-gray-200"
            class:bg-gray-300={!data.requests}
            class:text-gray-800={!data.requests}
        >
            Usuarios aceptados
        </button>
        <button
            on:click={(e) => {
                router.get(`${$page.url}`, { requests: true });
                requests = true;
            }}
            class="filter_button px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm hover:bg-gray-200"
            class:bg-gray-300={data.requests}
            class:text-gray-800={data.requests}
        >
            Solicitudes
        </button>
    </div>
</div>
<Table
    {selectedRow}
    on:fillFormToEdit={fillFormToEdit}
    on:acept={acept}
    on:reject={reject}
    selectedRowOptions={{
        editar: false,
        eliminar: false,
        aceptar: data.requests ? true : false,
        rechazar: data.requests ? true : false,
    }}
    pagination={false}
>
    <div slot="filterBox"></div>
    <thead slot="thead">
        <tr>
            <th>Foto</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>C.I</th>
            <th>Matrícula médica</th>
            <th>Correo</th>
            <th>Tel</th>
            <th>Servicio tratante</th>
        </tr>
    </thead>

    <tbody slot="tbody">
        {#each dataTable as row, i}
            <tr
                on:mousedown={handleMouseDown}
                on:mouseup={(e) => handleMouseUp(e, row.id, row)}
                class={`cursor-pointer  ${selectedRow.id == row.id ? "bg-color2 hover:bg-opacity-10 bg-opacity-10 brightness-110" : " hover:bg-gray-500 hover:bg-opacity-5"}`}
            >
                <td>
                    <img
                        class="w-12 aspect-square rounded-full object-cover"
                        src={`/storage/${requests ? "requests" : "users"}/${row.photo}`}
                        alt=""
                        srcset=""
                    />
                </td>

                <td>{row.name}</td>
                <td>{row.last_name}</td>
                <td>{row.ci}</td>
                <td>{row.medical_license}</td>
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
