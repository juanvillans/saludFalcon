<script>
    import { onMount } from 'svelte';
    import Alert from './Alert.svelte';
  
    export let showModal = false;
    export let onClose;
    export let modalClasses = '';
    export let showCancelButton = true;
  
    let dialog;
  
    onMount(() => {
      window.addEventListener('popstate', () => {
        showModal = false;
      });
    });
    $: if (dialog && showModal) {
      dialog.showModal();
    }
  
  
    // Show modal when showModal is true and dialog is ready
  </script>
  
  {#if showModal}
    <!-- svelte-ignore a11y-click-events-have-key-events -->
    <!-- svelte-ignore a11y-no-noninteractive-element-interactions -->
    <dialog
      bind:this={dialog}
      on:close={() => {
        showModal = false;
        if (onClose) onClose();
      }}
      on:click|self={() => dialog.close()}
      class={`p-0 rounded-xl min-w-[300px] ${modalClasses}`}
    >
      <Alert />
      <!-- svelte-ignore a11y-click-events-have-key-events -->
      <!-- svelte-ignore a11y-no-static-element-interactions -->
     <div class="p-2 sm:p-6 pb-5 flex flex-col min-h-[300px] max-h-[90vh]">
  <div class="sticky top-0 flex w-full items-center bg-white z-50">
    <slot name="header" />
    <button style="font-size: 16px;" class="ml-auto p-1" on:click={() => dialog.close()}>
      <iconify-icon icon="line-md:close"></iconify-icon>
    </button>
  </div>

  <hr class="mt-3" />
  <div class="flex-1 overflow-y-auto">
    <slot />
  </div>
  <div class="flex pt-3  justify-between gap-12 sticky bottom-0 bg-white ">
    {#if showCancelButton}
      <button class="text-gray-500 hover:bg-gray-300 rounded-full p-2 px-3" on:click={() => dialog.close()}>
        Cancelar
      </button>
    {/if}
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
