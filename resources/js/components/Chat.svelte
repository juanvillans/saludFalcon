<script>
    import { onMount } from "svelte";
    import axios from "axios";
    import { page, router } from "@inertiajs/svelte";
    import { displayAlert } from "../stores/alertStore";

    export let selectedPatient;

    export let showChat;
    export let showLink = true;
    $: console.log({ selectedPatient });

    let newMessage = "";
    let singleChatChannel = null;
    let messageSound;

    function handleKeydown(event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault(); // Evita el salto de línea
            console.log(event.key);
            sendMessage();
        }
    }

    async function sendMessage(e) {
        if (!newMessage.trim()) return;

        const message = {
            body: newMessage,
            emergency_case_id: selectedPatient.id,
        };

        try {
            const res = await axios.post("/admin/mensajes", message);
            newMessage = "";
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

    onMount(async () => {
        messageSound = new Audio("/mixkit-long-pop-2358.wav");
    });

    const playNotificationSound = () => {
        if (messageSound) {
            messageSound.currentTime = 0; // Rewind to start if already playing
            messageSound
                .play()
                .catch((e) => console.log("Audio play failed:", e));
        }
    };

    $: {
        // Limpiar canal anterior si existe
        if (singleChatChannel) {
            Echo.leave(`chat-${singleChatChannel.name.split("-")[1]}`);
        }

        // Solo crear nuevo canal si selectedPatient tiene ID válido
        if (selectedPatient?.id) {
            singleChatChannel = Echo.channel("chat-" + selectedPatient.id);

            singleChatChannel.listen(".newMessage", function (data) {
                scrollDownChat();
                playNotificationSound();
                if (selectedPatient.id) {
                    selectedPatient.messages = data.messages;
                }
                // Aquí tu lógica para manejar mensajes específicos
            });
        } else {
            singleChatChannel = null; // Asegurarse que queda limpio
        }
    }

    function getFirstName(firstName) {
        const parts = firstName.split(" ");
        return parts[0];
    }
</script>

{#if $page.props.auth.user_id}
    <button
        title="open chat"
        class="fixed bottom-16 md:bottom-7 right-7 w-10 md:w-16 shadow-2xl border-color3 border aspect-square flex justify-center items-center bg-color4 rounded-full"
        on:click={() => (showChat = true)}
    >
        <iconify-icon icon="line-md:chat-filled" class="text-xl md:text-3xl text-color1"
        ></iconify-icon>
    </button>
{/if}

<div
    class="z-50 shadow-md rounded-2xl fixed flex overflow-hidden flex-col justify-between bg-white bottom-1 right-1 md:bottom-4 md:right-4 h-[500px] w-[250px] md:w-[340px]"
    class:hidden={!showChat}
>
    <header class="p-2 px-3 bg-gray-200">
        <div class="flex justify-between items-center">
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
        </div>
        {#if $page.props.auth.user_id && selectedPatient && showLink}
            <a
                class="text-xs underline text-color3"
                href="http://localhost:8000/admin/casos/detalle-caso/{selectedPatient?.id}"
                target="_blank"
                rel="noopener noreferrer">Actualizar caso</a
            >
        {/if}
    </header>

    <main class="flex-1 overflow-y-scroll chat-container">
        {#if selectedPatient}
            {#each selectedPatient?.messages as message, i (message.id)}
                <div class="p-3">
                    <p class="text-xs md:text-sm text-gray-500 mb-1">
                        {message.date}
                    </p>
                    <div class="flex gap-2">
                        <img
                            class="bg-gray-400 w-5 h-5 md:w-8 md:h-8 aspect-square rounded-full object-cover"
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
                    ><iconify-icon icon="iconoir:send" width="24" height="24"
                    ></iconify-icon></button
                >
            </div>
        </footer>
    {/if}
</div>
