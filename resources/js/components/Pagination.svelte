<script>
    import { page, router } from "@inertiajs/svelte";

    export let pagination = false;
    let perPage = 0;
    if (pagination) {
        perPage = pagination.per_page;
    }

    // const screenZise = window.innerWidth;
    // let maxNroLinkPages = 7;
    // $: transformedPagination = () => {
    //     if (screenZise < 500) {
    //         return [pagination.links[pagination.current_page]];
    //     } else {
    //         // pagination.links.pop()
    //         if (pagination.current_page == 1) {
    //             let maxNroLinkPages = 7;
    //             return pagination.links.slice(
    //                 pagination.current_page,
    //                 maxNroLinkPages,
    //             );
    //         }
    //         return pagination.links.slice(1).splice(pagination.current_page-2, pagination.current_page + maxNroLinkPages );
    //         pagination.links;
    //     }
    // };
    function updateFilters() {
        router.get(`${$page.url}`, urlProps);
    }
    let urlProps = {};
    $: {
        updateFilters(urlProps);
    }
</script>

<div class="mt-2 sm:flex sm:items-center sm:justify-between">
    <div class="text-sm text-gray-500">
        Mostrando
        <select
            bind:value={perPage}
            name=""
            id=""
            on:change={(e) => (urlProps.per_page = perPage)}
        >
            <option value={10}>10</option>
            <option value={25}>25</option>
            <option value={50}>50</option>
            <option value={100}>100</option>
        </select>
        de <b> {pagination.total}</b> Registros y {pagination.last_page} páginas
    </div>
    <!-- pagination buttons -->
    <div class="flex items-center mt-4 gap-x-4 sm:mt-0 overflow-x-auto ">
        {#each pagination.links as link, i (link.label)}
            {#if i == 0}
                <a
                    on:click|preventDefault={() =>
                    urlProps.page = link.url[link.url.length-1]}
                    class={`cursor-pointer flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 ${pagination.prev == null ? "opacity-50 cursor-not-allowed" : ""}`}
                    disabled={pagination.prev == null}
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5 rtl:-scale-x-100"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18"
                        />
                    </svg>
                </a>
            {:else if i == pagination.links.length - 1}
                <a
                    on:click|preventDefault={() =>
                        urlProps.page =  link.url[link.url.length-1]}
                    class={`cursor-pointer flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 ${pagination.next == null ? "opacity-50 cursor-not-allowed" : ""}`}
                    disabled={pagination.next == null}
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5 rtl:-scale-x-100"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"
                        />
                    </svg>
                </a>
            {:else}
                <a
                    on:click|preventDefault={() =>
                        urlProps.page = link.label}
                    class="aspect-square border p-1 bg-gray-50 px-4 flex items-center rounded cursor-pointer"
                    class:active={link.active}>{link.label}</a
                >
            {/if}
        {/each}
    </div>
</div>

<style>
    a.active {
        background-color: #c9ebf2;
    }
</style>
