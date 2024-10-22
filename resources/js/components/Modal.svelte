<script>
    import { onMount } from "svelte";
    import Alert from "./Alert.svelte";

    export let showModal = false; // Ensure default value is set
    export let onClose; // callback function
    export let modalClasses = "";
    let dialog; // HTMLDialogElement

    onMount(() => {
        window.addEventListener("popstate", () => {
            showModal = false;
        });
    });
    // Show modal when showModal is true
    $: if (dialog && showModal) dialog.showModal();
</script>

{#if showModal}
    <dialog
        bind:this={dialog}
        on:close={() => {
            showModal = false; // Close modal
            if (onClose) onClose(); // Call the onClose callback
        }}
        on:click|self={() => dialog.close()}
        class={`p-0 rounded-xl min-w-[300px] ${modalClasses}`}
    >
    <Alert/>
        <div class="p-6 pb-5" on:click|stopPropagation>
            <div class="sticky top-0 flex w-full items-center bg-white z-50">
                <slot name="header" />
                <button  style="font-size: 16px;" class="ml-auto p-1" on:click={() => dialog.close()}>

                    <iconify-icon icon="line-md:close"></iconify-icon>
                </button>
            </div>
            
            
            <hr class="mt-3" />
            <slot />
            <hr class="my-4" />
            <div class="flex justify-between gap-12">
                <button class="text-gray-400" on:click={() => dialog.close()}
                    >Cancelar</button
                >
                <slot name="btn_footer" />
            </div>
        </div>
    </dialog>
{/if}

<style>
    dialog {
        width: fit-content;
        z-index: 20 !important;
        border: none;
        padding: none !important;
    }
    dialog::backdrop {
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(0.5px);
    }
    dialog[open] {
        animation: zoom 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes zoom {
        from {
            transform: scale(0.95);
        }
        to {
            transform: scale(1);
        }
    }
    dialog[open]::backdrop {
        animation: fade 0.2s ease-out;
    }
    @keyframes fade {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    button {
        display: block;
    }
    hr {
        opacity: 0.2;
    }
</style>
