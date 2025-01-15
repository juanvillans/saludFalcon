<script>
    import { inertia } from "@inertiajs/svelte";
    import { page, router } from "@inertiajs/svelte";
    import { createEventDispatcher, onMount } from "svelte";
    import Pagination from "./Pagination.svelte";
    import Search from "./Search.svelte";
    const dispatch = createEventDispatcher();

    export let filtersOptions = false;
    export let selectedRow;
    export let selectedRowOptions = {};
    export let pagination = false;
    export let allowSearch = true;
    let firstTime = true;
    $: filterClientData = { ...$page.props.filters };
    // $: $form, handleFilters()
    const handleFilters = () => {
        firstTime = false;
        router.get(`${$page.url}`, filterClientData);
    };
</script>

<section class="w-full">
    <div class=" md:flex md:items-center md:justify-between lg:justify-end">
        {#if filtersOptions}
            <div class="flex">
                <div
                    class="inline-flex overflow-hidden border border-dark border-opacity-30 divide-x divide-gray-300 rounded-lg rtl:flex-row-reverse"
                >
                    <button
                        on:click={(e) => {
                            filterClientData["status"] = "";
                            handleFilters();
                        }}
                        class="filter_button px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm hover:bg-gray-200"
                        class:bg-gray-200={filterClientData["status"] == ""}
                    >
                        Todos
                    </button>
                    {#each Object.entries(filtersOptions) as [filterKey, filterOption] (filterKey)}
                        {#each filterOption as filter, i (filter.id)}
                            <button
                                class=" filter_button px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm hover:bg-gray-200"
                                class:bg-gray-200={filterClientData?.[
                                    filterKey
                                ] == filter.id}
                                on:click={(e) => {
                                    filterClientData[filterKey] = filter.id;
                                    handleFilters();
                                }}
                            >
                                {filter.name}
                            </button>
                        {/each}
                    {/each}
                </div>
                <slot name="filterBox"></slot>
            </div>
        {/if}

        {#if allowSearch}
            <Search />
        {/if}
        {#if selectedRow?.status}
            <div class="flex gap-5 relative items-end">
                {#if selectedRowOptions.editar}
                    <button
                        on:click={() => dispatch("fillFormToEdit")}
                        class="bg-color3 bg-opacity-10 hover:bg-opacity-20 cursor-pointer text-2xl rounded border border-color3 px-4 py-1"
                        title="Editar"
                    >
                        <iconify-icon
                            class="relative -bottom-1"
                            icon="line-md:edit"
                        ></iconify-icon>
                    </button>
                {/if}

                {#if selectedRowOptions.eliminar}
                    <button
                        on:click={() => dispatch("clickDeleteIcon")}
                        class="bg-red bg-opacity-10 hover:bg-opacity-20 cursor-pointer text-2xl rounded border border-red px-4 py-1"
                        title="Eliminar"
                    >
                        <iconify-icon
                            class="relative -bottom-1"
                            icon="material-symbols:delete-outline"
                        ></iconify-icon>
                    </button>
                {/if}

                {#if selectedRowOptions.aceptar}
                    <button
                        on:click={() => dispatch("acept")}
                        class="bg-color3 bg-opacity-10 hover:bg-opacity-20 cursor-pointer  rounded border border-color3 px-4 py-1"
                        title="Editar"
                    >
                        Aceptar
                    </button>
                {/if}

                {#if selectedRowOptions.rechazar}
                    <button
                        on:click={() => dispatch("reject")}
                        class="bg-red bg-opacity-10 hover:bg-opacity-20 cursor-pointer  rounded border border-red px-4 py-1"
                        title="Editar"
                    >
                        Rechazar
                    </button>
                {/if}
            </div>
        {/if}
    </div>

    <div class="flex flex-col mt-4">
        <div
            class="-mx-4 -my-2 overflow-x-auto overflow-y-visible sm:-mx-6 lg:-mx-8"
        >
            <div class="inline-block w-full py-2 align-middle md:px-6 lg:px-8">
                <div
                    class="overflow-x-auto border border-gray-200 md:rounded-lg"
                >
                    <table
                        class="relative table overflow-scroll overflow-y-auto w-full divide-y divide-gray-200"
                    >
                        <slot name="thead"></slot>

                        <slot
                            name="tbody"
                            class="bg-white divide-y divide-gray-200  "
                        ></slot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {#if pagination}
        <!-- Pagination ---------------------------------------------------------------------------------------------- -->
        <Pagination pagination={{ ...pagination }} />
    {/if}
</section>

<style>
    /* normal css */
    .scroll-table::-webkit-scrollbar {
        /* width: 17px;   */
        height: 13px;
    }

    .scroll-table::-webkit-scrollbar-track {
        background: rgb(14, 14, 14); /* color of the tracking area */
        /* padding: 2px; */
    }

    .scroll-table::-webkit-scrollbar-thumb {
        background-color: #35475c; /* color of the scroll thumb */
        border-radius: 4px; /* roundness of the scroll thumb */
        border: 3px solid rgb(14, 14, 14);
        border-bottom: 0.2px solid rgb(14, 14, 14);
    }
    .scroll-table::-webkit-scrollbar-thumb:hover {
        background-color: rgb(184, 206, 231);
    }
    .scroll-table::-webkit-scrollbar-corner {
        background: rgba(0, 0, 0, 0.5);
    }
    a.active {
        background-color: #c9ebf2;
    }
    table tr:first-child th {
        position: sticky !important;
        top: 120px !important;
        background: #333 !important;
    }
</style>
