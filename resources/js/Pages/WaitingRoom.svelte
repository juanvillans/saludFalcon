<script>
    import Table from "../components/Table.svelte";
    import StatusColor from "../components/StatusColor.svelte";
    import fetchLocalData from "../components/localData";
    import Search from "../components/Search.svelte";
    import { onMount } from "svelte";
    import axios from "axios";
    import { page } from "@inertiajs/svelte";
    import { displayAlert } from "../stores/alertStore";
    import { usePoll } from "@inertiajs/svelte";
    import { echo } from '../lib/echo';
    
    usePoll(10000, {
        onStart() {
            console.log("Polling request started");
        },
        onFinish() {
            console.log("Polling request finished");
            scrollDownChat();
        },
    });

    let localData;
    let showChat = false;

    onMount(async () => {
        try {
            localData = await fetchLocalData();
        } catch (error) {
            console.error("Error loading data:", error);
        }
    });

    function getFirstName(firstName) {
        const parts = firstName.split(" ");
        return parts[0];
    }
    export let data = {};
    $: console.log(data);
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

    let selectedPatient;
    let newMessage = "";
    async function sendMessage(e) {
        e.preventDefault();
        if (!newMessage.trim()) return;

        const message = {
            body: newMessage,
            emergency_case_id: selectedPatient.id,
        };

        try {
            const res = await axios.post("/admin/mensajes", message);
            console.log(res);
            selectedPatient.messages = [
                ...selectedPatient.messages,
                res.data.message,
            ];
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

    $: console.log(selectedPatient);
</script>

<div class="p-4 overflow-hidden">
    <div class=" p-3 rounded-xl">
        <h2>Condión de los pacientes</h2>
        <Search
            filtersOptions={{
                date: {
                    type: "date",
                    label: "Fecha de ingreso",
                },
                status:
                    {
                        type: "select",
                        label: "Estado",
                        options: localData?.statutes || [],
                    } || {},
                case_id:
                    {
                        type: "search",
                        label: "ID del caso",
                        options: [],
                    } || {},
                specialty_id:
                    {
                        type: "select",
                        label: "Servicio tra.",
                        options: localData?.specialties || [],
                    } || {},
                area_id:
                    {
                        type: "select",
                        label: "Última area",
                        options: localData?.areas || [],
                    } || {},

                condition:
                    {
                        type: "select",
                        label: "Condición",
                        options: localData?.conditions || [],
                    } || {},
            }}
        />
        <div class="w-full z-0">
            <Table {visulizateType}>
                <div slot="filterBox"></div>
                <thead slot="thead" class="sticky top-0">
                    <tr>
                        <th>Cama</th>
                        <th>Paciente</th>
                        <th>Ubicación</th>
                        <th>Condición</th>
                        <th>Mensaje</th>
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
                                <td style="font-size: 12px;">{row.id}</td>
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
                                    {:else}{/if}
                                </td>
                                <td class="">{row.formatted_entry_date}</td>

                                <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                            </tr>
                        {/each}
                    {/if}
                </tbody>
            </Table>

            {#if visulizateType == "card"}
                <div class="grid lg:grid-cols-2 gap-5 mt-3">
                    {#each data?.data as row, i (row.id)}
                        <!-- svelte-ignore a11y-click-events-have-key-events -->
                        <!-- svelte-ignore missing-declaration -->
                        <!-- svelte-ignore a11y-no-noninteractive-element-interactions -->
                        <article
                            on:mousedown={handleMouseDown}
                            on:mouseup={(e) => handleMouseUp(e, row.id)}
                            class={`relative w-full cursor-pointer bg-gray-100 p-2 md:p-5 rounded-md  hover:bg-color4 hover:bg-opacity-60 neumorphism2`}
                        >
                            <span
                                class="h-fit absolute right-0 top-0 text-center col-span-2 p-1 text-xs inline-block w-10 md:px-2"
                                >{row.id}</span
                            >
                            <div class="flex gap-1 items-center">
                                <StatusColor
                                    status={{
                                        name: row?.current_status_name,
                                        id: row?.current_status,
                                    }}
                                />

                                <!-- {#if row?.current_status == "3"}
                        a {row?.admitted_area_name}
                    {/if} -->
                                <span class="inline-flex">
                                    {#if row.current_status == 1 || row.current_status == 2}
                                        de
                                    {:else if row.current_status == 4 || row.current_status == 5}
                                        en
                                    {:else if row.current_status == 3}
                                        a
                                    {/if}
                                    {row.area_name}.
                                </span>
                                <div class="flex items-cenenter gap-1">
                                    <iconify-icon
                                        icon="game-icons:duration"
                                        class="text-gray-600 text-xs md:text-sm"
                                    ></iconify-icon>
                                </div>
                            </div>
                            <p>
                                F. de ingreso: {row.formatted_entry_date}
                            </p>

                            <div class="flex items-center gap-3 mt-1">
                                {#if row.sex == "Femenino"}
                                    <span class="text-pink text-lg sm:text-2xl">
                                        <iconify-icon icon="fa-solid:female"
                                        ></iconify-icon>
                                    </span>
                                {:else}
                                    <span
                                        class="text-color3 text-lg sm:text-2xl"
                                    >
                                        <iconify-icon icon="fa-solid:male"
                                        ></iconify-icon>
                                    </span>
                                {/if}
                                <span
                                    >{getFirstName(row?.patient_name)}
                                    {getFirstName(row.patient_last_name)}
                                    <small class="text-gray-500">C.I:</small
                                    >{row.user_ci}
                                </span>
                            </div>
                            <div class="mt-1 flex gap-1.5">
                                <iconify-icon
                                    icon="emojione-monotone:speaking-head"
                                    width="20"
                                    height="20"
                                    class="text-gray-900"
                                ></iconify-icon>
                                <p>
                                    {#if row?.reason.length > 200}
                                        {row?.reason.slice(0, 200)}
                                        <span
                                            class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                            >...</span
                                        >
                                    {:else}
                                        {row?.reason}
                                    {/if}
                                </p>
                            </div>
                            <div class="mt-2 flex gap-2">
                                <div
                                    class={`inline-block w-2 h-2 mr-2 relative top-2 aspect-square rounded-full  condition${row.current_patient_condition_id}`}
                                ></div>
                                <p>
                                    {#if row.diagnosis.length > 200}
                                        {row.diagnosis.slice(0, 200)}
                                        <span
                                            class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                            >...</span
                                        >
                                    {:else}
                                        {row.diagnosis}
                                    {/if}
                                </p>
                            </div>
                            <div class="mt-2 flex gap-2">
                                <iconify-icon
                                    class="relative top-1 -left-1 text-color2"
                                    icon="ant-design:medicine-box-filled"
                                    width="20"
                                    height="20"
                                ></iconify-icon>
                                <p>
                                    {#if row.last_message.length > 200}
                                        {row.last_message.slice(0, 200)}
                                        <span
                                            class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                            >...</span
                                        >
                                    {:else}
                                        {row.last_message}
                                    {/if}
                                </p>
                            </div>

                            <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                            <!-- <p
                    class="text-right justify-end w-full flex items-center gap-2"
                >
                    {row.user_name}
                    {row.user_last_name}
                    <iconify-icon icon="mdi:doctor" style="font-size: 20px;"
                    ></iconify-icon>
                </p> -->
                        </article>
                    {/each}
                </div>
            {/if}
        </div>
    </div>

    <button
        title="open chat"
        class="fixed bottom-7 right-7 w-16 shadow-2xl border-color3 border aspect-square flex justify-center items-center bg-color4 rounded-full"
        on:click={() => (showChat = true)}
    >
        <iconify-icon icon="line-md:chat-filled" class="text-3xl text-color1"
        ></iconify-icon>
    </button>

    <form
        class="neumorphism2 rounded-2xl fixed flex overflow-hidden flex-col justify-between bg-white bottom-4 right-4 h-[500px] md:w-[340px]"
        class:hidden={!showChat}
        on:submit={sendMessage}
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
                ></textarea>
                <button
                    class="btn btn-primary h-full flex items-center"
                    on:click={sendMessage}
                    ><iconify-icon icon="iconoir:send" width="24" height="24"
                    ></iconify-icon></button
                >
            </div>
        </footer>
    </form>
</div>
