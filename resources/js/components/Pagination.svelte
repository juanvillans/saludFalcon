<script>
    import { page, router } from "@inertiajs/svelte";

    export let pagination = false;
    let perPage = pagination?.per_page || 10;

    // Track if changes come from user interaction (not Inertia updates)
    let isUserInteraction = false;

    // Update filters only when user interacts (not on initial load or Inertia updates)
    function updateFilters() {
        if (isUserInteraction) {
            router.get($page.url, urlProps, { preserveState: true });
        }
    }

    // Store URL params separately to avoid reactivity issues
    let urlProps = {};

    // Debounce to prevent rapid requests
    let debounceTimer;
    function debouncedUpdateFilters() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(updateFilters, 150);
    }

    // React only to manual changes (not Inertia-driven updates)
    $: {
        if (Object.keys(urlProps).length > 0) {
            debouncedUpdateFilters();
        }
    }

    // Reset interaction flag after update
    $: page, isUserInteraction = false;
</script>

<div class="mt-2 sm:flex sm:items-center sm:justify-between">
    <div class="text-sm text-gray-500">
        Mostrando
        <select
            bind:value={perPage}
            on:change={() => {
                isUserInteraction = true;
                urlProps.per_page = perPage;
            }}
        >
            <option value={10}>10</option>
            <option value={25}>25</option>
            <option value={50}>50</option>
            <option value={100}>100</option>
        </select>
        de <b>{pagination.total}</b> Registros y {pagination.last_page} páginas
    </div>

    <!-- Pagination buttons -->
    <div class="flex items-center mt-4 gap-x-4 sm:mt-0 overflow-x-auto">
        {#each pagination.links as link, i (link.label)}
            {#if i == 0}
                <a
                    on:click|preventDefault={() => {
                        isUserInteraction = true;
                        urlProps.page = link.url?.split('=').pop();
                    }}
                    class={`cursor-pointer flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 ${!pagination.prev ? "opacity-50 cursor-not-allowed" : ""}`}
                    disabled={!pagination.prev}
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>
                </a>
            {:else if i == pagination.links.length - 1}
                <a
                    on:click|preventDefault={() => {
                        isUserInteraction = true;
                        urlProps.page = link.url?.split('=').pop();
                    }}
                    class={`cursor-pointer flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 ${!pagination.next ? "opacity-50 cursor-not-allowed" : ""}`}
                    disabled={!pagination.next}
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                    </svg>
                </a>
            {:else}
                <a
                    on:click|preventDefault={() => {
                        isUserInteraction = true;
                        urlProps.page = link.label;
                    }}
                    class="aspect-square border p-1 bg-gray-50 px-4 flex items-center rounded cursor-pointer"
                    class:active={link.active}
                >
                    {link.label}
                </a>
            {/if}
        {/each}
    </div>
</div>

<style>
    a.active {
        background-color: #c9ebf2;
    }
</style>