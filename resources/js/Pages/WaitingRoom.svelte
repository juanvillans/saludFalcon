<script>
    import Table from "../components/Table.svelte";
    import StatusColor from "../components/StatusColor.svelte";
    import fetchLocalData from "../components/localData";
    import Search from "../components/Search.svelte";
    import { onMount } from "svelte";
    import axios from "axios";
    import { page, router } from "@inertiajs/svelte";
    import { displayAlert } from "../stores/alertStore";
    import QRCode from "qrcode";

    let qrDataUrl = "";
    const targetUrl = "https://saludfalcon.org/sala-de-espera";
    // usePoll(10000, {
    //     onStart() {
    //         console.log("Polling request started");
    //     },
    //     onFinish() {
    //         console.log("Polling request finished");
    //         scrollDownChat();
    //     },
    // })

    let localData;
    let showChat = false;
    // Audio setup
    let messageSound;
    onMount(async () => {
        try {
            localData = await fetchLocalData();
        } catch (error) {
            console.error("Error loading data:", error);
        }
        messageSound = new Audio("/mixkit-long-pop-2358.wav");
        try {
            qrDataUrl = await QRCode.toDataURL(targetUrl, {
                width: 300,
                margin: 2,
            });
        } catch (err) {
            console.error("Error generando QR:", err);
        }
    });

    const playNotificationSound = () => {
        if (messageSound) {
            messageSound.currentTime = 0; // Rewind to start if already playing
            messageSound
                .play()
                .catch((e) => console.log("Audio play failed:", e));
        }
    };
    let filterClientData;
    let selectedPatient;
    let singleChatChannel = null;

    $: {
        // Limpiar canal anterior si existe
        if (singleChatChannel) {
            Echo.leave(`chat-${singleChatChannel.name.split("-")[1]}`);
        }

        // Solo crear nuevo canal si selectedPatient tiene ID válido
        if (selectedPatient?.id) {
            singleChatChannel = Echo.channel("chat-" + selectedPatient.id);

            singleChatChannel.listen(".newMessage", function (data) {

                playNotificationSound();
                if (selectedPatient.id) {
                    selectedPatient.messages = data.messages;
                }
                // Aquí tu lógica para manejar mensajes específicos
            });
        } else {
            singleChatChannel = null; // Asegurarse que queda limpio
        }

        filterClientData = { ...$page.props.filters };
        console.log({ $page });
    }
    let generalChannel = Echo.channel("generalChat");
    generalChannel.listen(".newMessage", function (data) {

        router.reload(`${$page.url.split("?")[0]}`, filterClientData, {
            preserveState: true,
        });

        playNotificationSound();
    });

    // Reactividad para el canal específico del paciente

    // Limpieza cuando sea necesario (ejemplo: al cambiar de componente)
    const cleanupChannels = () => {
        if (generalChannel) {
            Echo.leave("generalChat");
        }
        if (singleChatChannel) {
            Echo.leave(`chat-${singleChatChannel.name.split("-")[1]}`);
        }
    };
    function getFirstName(firstName) {
        const parts = firstName.split(" ");
        return parts[0];
    }
    export let data = {};
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

    function handleKeydown(event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault(); // Evita el salto de línea
            console.log(event.key);
            sendMessage();
        }
    }

    let newMessage = "";
    async function sendMessage(e) {
        if (!newMessage.trim()) return;

        const message = {
            body: newMessage,
            emergency_case_id: selectedPatient.id,
        };

        try {
            const res = await axios.post("/admin/mensajes", message);

            newMessage = "";
            scrollDownChat();
        } catch (errors) {
            displayAlert({
                type: "error",
                message: errors.message || "algo salió mal",
            });
        }
    }
    function scrollDownChat() {
        setTimeout(() => {
            // add a transition to the scrollDownChat function to make it smoother
            const chatContainer = document.querySelector(".chat-container");
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }, 100);
    }
    function selectPatient(row) {
        console.log(row);
        showChat = true;
        selectedPatient = row;
        scrollDownChat();
    }

    const handleFilters = () => {
        // router.get(`${$page.url.split("?")[0]}`, filterClientData, {
        //     preserveState: true,
        // });
        console.log("sin filtros")
    };
    $: console.log($page);
</script>

<div class="pt-4 md:p-4 overflow-hidden mt-12">
    <div class=" p-3 rounded-xl">
        <div
            class="w-full flex gap-2 items-center fixed top-0 z-50 py-4 bg-white"
        >
            <h2>Condión de los pacientes</h2>
            <p>ubicados en</p>
            <select
                on:change={(e) => {
                    if (e.target.value == "todas") {
                        delete filterClientData["area_id"];
                    } else {
                        filterClientData["area_id"] = e.target.value;
                    }
                    handleFilters();
                }}
                name={"Ubicación actual"}
                id=""
                class="bg-gray-200 p-1 py-2 rounded-md"
            >
                <option value="todas">Todas las areas</option>
                {#if localData?.areas}
                    {#each localData?.areas as filter, i (filter.id)}
                        <option
                            selected={filterClientData?.["area_id"] ==
                                filter.id}
                            value={filter.id}>{filter.name}</option
                        >
                    {/each}
                {/if}
            </select>

            <div class="text-gray-600 text-xl md:text-2xl">
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
        <Search
            placeholder="Buscar por nombre o CI"
            style="min-width: 300px; z-index: 100 !important; border: 1px solid gray;"
        />
        <div class="w-full z-0 max-w-[1600px]">
            <Table {visulizateType}>
                <div slot="filterBox"></div>
                <thead slot="thead" class="sticky top-0">
                    <tr>
                        <th>Cama</th>
                        <th>Paciente</th>
                        <th>Ubicación</th>
                        <th>Condición</th>
                        <th>Último mensaje</th>
                        <th style="font-size: 12px  ">Última actualización.</th>
                    </tr>
                </thead>

                <tbody slot="tbody">
                    {#if data?.data?.length > 0 && visulizateType == "table"}
                        {#each data?.data as row, i (row.id)}
                            <tr
                                on:click={() => selectPatient(row)}
                                class={`md:max-h-[200px] overflow-hidden cursor-pointer  hover:bg-gray-500 hover:bg-opacity-5 ${selectedPatient?.id == row.id ? "bg-color3 hover:bg-opacity-10 bg-opacity-20 brightness-110" : ""}`}
                            >
                                <td >{row.bed_number}</td>
                                <td class="min-w-[180px]">
                                    <div class="flex items-center gap-2">
                                        <span class="whitespace-normal"
                                            >{getFirstName(row?.patient_name)}
                                            {getFirstName(
                                                row?.patient_last_name,
                                            )}
                                            <small class="text-gray-400"
                                                >C.I:</small
                                            >{row?.patient_ci}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    style="white-space: normal;"
                                    class="min-w-[150px]"
                                >
                                    <span class="inline-block flex">
                                        {row.area_name}
                                    </span>
                                </td>

                                <td
                                    title={row?.current_patient_condition_name}
                                    class="max-w-[340px] md:min-w-[120px] max-h-[100px] overflow-hidden"
                                    style="white-space: normal;"
                                >
                                    <div
                                        class={`inline-block w-2 h-2 mr-2 aspect-square rounded-full  condition${row.current_patient_condition_id}`}
                                    ></div>
                                    <span>
                                        {row?.current_patient_condition_name}
                                    </span>
                                </td>
                                <!-- <td>{row.sex}</td> -->
                                <td
                                    class="max-w-[340px] min-w-[290px] md:min-w-[320px] max-h-[100px] overflow-hidden"
                                    style="white-space: normal;"
                                >
                                    {#if row?.last_message?.length > 200}
                                        {row?.last_message.slice(0, 200)}
                                        <span
                                            class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                            >...</span
                                        >
                                    {:else if row.last_message}
                                        {row?.last_message}
                                    {/if}
                                </td>
                                <td class="">{row.updated_at}</td>

                                <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                            </tr>
                        {/each}
                    {/if}
                </tbody>
            </Table>

            {#if visulizateType == "card"}
                <div class="grid lg:grid-cols-2 2xl:grid-cols-3 gap-5 mt-3">
                    {#each data?.data as row, i (row.id)}
                        <!-- svelte-ignore a11y-click-events-have-key-events -->
                        <!-- svelte-ignore missing-declaration -->
                        <!-- svelte-ignore a11y-no-noninteractive-element-interactions -->
                        <article
                            on:click={() => selectPatient(row)}
                            class={`relative w-full cursor-pointer  p-2 md:p-5 rounded-md hover:bg-color4 hover:bg-opacity-60 neumorphism2 ${selectedPatient?.id == row.id ? "bg-color4 bg-opacity-70" : " bg-gray-100"}`}
                        >
                            <span
                                class="h-fit absolute right-0 top-0 text-center col-span-2 p-1 text-xs inline-block w-10 md:px-2"
                            >
                            <p class="font-bold text-color3">
                                {row.bed_number}
                            </p>
                                <iconify-icon
                                    icon="streamline-ultimate-color:medical-instrument-ambulance-bed"
                                    width="24"
                                    height="24"
                                    class="xl:text-xl"
                                ></iconify-icon>
                            </span>

                            <div
                                class="grid grid-cols-[auto_1fr] gap-x-2 gap-y-1 items-start"
                            >

                             <div class="flex items-center justify-center">
                                    <iconify-icon
                                        icon="material-symbols:person-rounded"
                                        width="20"
                                        height="20"
                                        class="text-gray-500"
                                    ></iconify-icon>
                                </div>
                                <div>
                                    <span>
                                        {getFirstName(row?.patient_name)}
                                        {getFirstName(row.patient_last_name)}
                                        <small class="text-gray-500">C.I:</small
                                        >
                                        {row.user_ci}
                                    </span>
                                </div>
                                <div class="flex items-center justify-center">
                                    <iconify-icon
                                        icon="ic:baseline-place"
                                        width="20"
                                        height="20"
                                        class="text-gray-500"
                                    ></iconify-icon>
                                </div>
                                <div>
                                    <span>
                                        {row.area_name}
                                    </span>
                                </div>

                                <!-- <div class="flex items-center justify-center">
                                    <iconify-icon
                                        icon="mingcute:time-line"
                                        width="20"
                                        height="20"
                                        class="text-gray-500"
                                    ></iconify-icon>
                                </div>
                                <div>
                                    <span>
                                        {row.updated_at}
                                    </span>
                                </div> -->

                               

                                <div class="flex items-center justify-center">
                                    <div
                                        class={`inline-block w-6 h-6 aspect-square rounded-full condition${row.current_patient_condition_id} flex items-center justify-center`}
                                    ></div>
                                </div>
                                <div>
                                    <span>
                                        {row.current_patient_condition_name}  <small class="opacity-70">
                                                | hoy a las 4:00 pm
                                        </small>
                                    </span>
                                </div>

                                <div class="flex items-center justify-center">
                                    <iconify-icon
                                        icon="tabler:message-filled"
                                        width="20"
                                        height="20"
                                        class="text-color1"
                                    ></iconify-icon>
                                </div>
                                <div>
                                    <p>
                                        {#if row.last_message?.length > 200}
                                            {row.last_message.slice(0, 200)}
                                            <span
                                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                                >...</span
                                            >
                                        {:else}
                                            {row.last_message}
                                        {/if}
                                        <small class="opacity-70">
                                                | hoy a las 4:00 pm
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </article>
                    {/each}
                </div>
            {/if}
        </div>
    </div>

    {#if !$page.props.auth.user_id}
        <div class=" bottom-7 right-2 hidden 2xl:block fixed">
            {#if qrDataUrl}
                <div class="shadow-xl rounded-md bg-white">
                    <div class="py-2 px-2">Buscas a tu familiar?</div>
                    <img class="w-[300px]" src={qrDataUrl} alt="Código QR" />
                </div>
            {:else}
                <p>Generando código QR...</p>
            {/if}
        </div>
    {:else}
        <button
            title="open chat"
            class="fixed bottom-7 right-7 w-16 shadow-2xl border-color3 border aspect-square flex justify-center items-center bg-color4 rounded-full"
            on:click={() => (showChat = true)}
        >
            <iconify-icon
                icon="line-md:chat-filled"
                class="text-3xl text-color1"
            ></iconify-icon>
        </button>
    {/if}

    <div
        class="neumorphism2 rounded-2xl fixed flex overflow-hidden flex-col justify-between bg-white bottom-4 right-4 h-[500px] md:w-[340px]"
        class:hidden={!showChat}
    >
        <header class="p-2 px-3 flex justify-between items-center bg-gray-200">
            {#if selectedPatient}
                <p>
                    {getFirstName(selectedPatient?.patient_name)}
                    {getFirstName(selectedPatient?.patient_last_name)}
                    <span class="text-xs text-opacity-75">
                        C.I:{selectedPatient?.patient_ci}</span
                    >
                </p>
            {:else}
                <p>Selecciona un paciente</p>
            {/if}
            <button on:click={() => (showChat = false)}>
                <iconify-icon icon="line-md:close"></iconify-icon>
            </button>
        </header>

        <main class="flex-1 overflow-y-scroll chat-container">
            {#if selectedPatient}
                {#each selectedPatient?.messages as message, i (message.id)}
                    <div class="p-3">
                        <p class="text-sm text-gray-500 mb-1">{message.date}</p>
                        <div class="flex gap-2">
                            <img
                                class="bg-gray-400 w-8 h-8 aspect-square rounded-full object-cover"
                                src={`/storage/users/${message.user_photo}`}
                                alt=""
                            />
                            <div
                                class=" bg-gray-100 py-1 pl-3 pr-5 rounded-r-xl rounded-bl-xl"
                            >
                                <p class="text-color1 text-xs">
                                    {message.user_fullname}
                                </p>
                                <p class="text-sm">{message.body}</p>
                            </div>
                        </div>
                    </div>
                {/each}
            {/if}
        </main>
        {#if $page.props.auth.user_id}
            <footer class="bg-color4 pt-2">
                <div class="flex">
                    <button> </button>
                </div>
                <div
                    class="overflow-hidden text-sm px-3 py-1 rounded-full border border-gray-600 mb-2 flex justify-between w-11/12 mx-auto items-center bg-gray-100"
                >
                    <textarea
                        name="message"
                        id=""
                        class="w-full h-10 bg-transparent p-2 outline-none"
                        placeholder="Escribe un mensaje"
                        bind:value={newMessage}
                        on:keydown={handleKeydown}
                    ></textarea>
                    <button
                        class="btn btn-primary h-full flex items-center"
                        on:click={sendMessage}
                        ><iconify-icon
                            icon="iconoir:send"
                            width="24"
                            height="24"
                        ></iconify-icon></button
                    >
                </div>
            </footer>
        {/if}
    </div>
</div>
