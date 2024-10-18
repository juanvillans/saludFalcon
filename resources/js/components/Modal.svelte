<script>
    export let showModal = false; // Ensure default value is set
    export let onClose; // callback function
    export let modalClasses = "";
    let dialog; // HTMLDialogElement

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
    class={`p-5 pb-2 rounded-xl min-w-[300px] ${modalClasses}`}
>
    <div on:click|stopPropagation>
        <slot name="header" />
        <hr class="mt-3" />
        <slot />
        <hr class="my-4" />
        <div class="flex justify-between gap-12">
            <button class="text-gray-400" on:click={() => dialog.close()}>Cancelar</button>
            <slot name="btn_footer" />
        </div>
    </div>
</dialog>
{/if}

<style>
    dialog {
        width: fit-content;
        border: none;
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
        opacity: .2;
    }
</style>