<script>
    import { inertia } from "@inertiajs/svelte";
    import { page, router } from "@inertiajs/svelte";
    import { createEventDispatcher, onMount } from "svelte";
    import Pagination from "./Pagination.svelte";
    import Modal from "./Modal.svelte";
    import DateRange from "../components/DateRange.svelte";

    import Search from "./Search.svelte";
    import { object_without_properties } from "svelte/internal";
    const dispatch = createEventDispatcher();
    let showModal = false;

    export let filtersOptions = false;
    export let selectedRow;
    export let selectedRowOptions = {};
    export let pagination = false;
    export let allowSearch = true;
    export let visulizateType = "table";
    let isFilterAply = false;
    let firstTime = true;
    let filterClientData;
    $: {
        filterClientData = { ...$page.props.filters };
        isFilterAply = Object.keys(filterClientData).some(
            (value) => value != "search",
        );
    }
    const changeDateFilter = (args) => {
        filterClientData = {
            ...filterClientData,
            start_date: args.detail.startDate,
            end_date: args.detail.endDate,
        };
        handleFilters();
    };

    // $: $form, handleFilters()
    $: console.log(filterClientData);

    const handleFilters = () => {
        firstTime = false;
        router.get(`${$page.url.split("?")[0]}`, filterClientData);
    };
</script>

{#if filtersOptions}
<button
    class="relative flex gap-2 hover:bg-gray-300 rounded-full p-2 px-3"
    class:bg-gray-300={isFilterAply}
    title="Busqueda de filtros"
    on:click={(e) => {
        e.preventDefault();

        showModal = true;
    }}
>
    {#if isFilterAply}
        <div
            class="absolute bg-color1 h-2 w-2 rounded-full right-1 top-0"
        ></div>
    {/if}
    <span> Filtros </span>
    <iconify-icon icon="mage:filter" width="24" height="24"
    ></iconify-icon>
</button>
{/if}

<Modal bind:showModal modalClasses={"max-w-[960px] h-full"} showCancelButton={false}>
    <p slot="header" class="opacity-60 ">Filtros de busqueda</p>
    <div class="grid grid-cols-1 h-full md:grid-cols-3 gap-5 md:gap-10">
        {#each Object.entries(filtersOptions) as [filterKey, filterOption] (filterKey)}
            <article class="md:flex md:flex-col">
                <h4
                    class="uppercase w-fit md:w-full text-xs md:text-sm font-medium border-b px-2 flex items-center pb-2 lg:mb-1.5"
                >
                    {filterOption.label}
                </h4>
                {#if filterOption.type === "search"}
                    <input
                        value={filterClientData?.[filterKey] || ""}
                        class="h-auto border-gray-400 bg-gray-200 border p-2 py-1 rounded "
                        placeholder={"🔍 " +filterOption.label}
                        type="search"
                        name=""
                        id=""
                        on:input={(e) => {
                            const inputValue = e.target.value;
                            if (/^\d*$/.test(inputValue)) {
                                filterClientData[filterKey] = inputValue;
                                handleFilters();
                            } else {
                                e.target.value = filterClientData[filterKey] || "";
                            }
                        }}
                    />
                {:else if filterOption.type === "select"}
                    <select
                        on:change={(e) => {
                            if (e.target.value == "todos") {
                                delete filterClientData[filterKey];
                            } else {
                                filterClientData[filterKey] = e.target.value;
                            }
                            handleFilters();
                        }}
                        name={filterOption.label}
                        id=""
                        class="bg-gray-200 p-1 py-2 rounded-md"
                    >
                        <option value="todos">Todos</option>
                        {#each filterOption.options as filter, i (filter.id)}
                            <option
                                selected={filterClientData?.[filterKey] ==
                                    filter.id}
                                value={filter.id}>{filter.name}</option
                            >
                        {/each}
                    </select>

                {:else if filterOption.type === "date"}
                    <DateRange on:changeDateFilter={changeDateFilter} />
                {:else}
                    {#each filterOption.options as filter, i (filter.id)}
                        <button
                            class="text-left filter_button px-2 py-1  my-1 text-xs font-medium hover:text-dark rounded-full text-gray-700 block transition-colors duration-75 sm:text-sm hover:bg-gray-200"
                            class:bg-gray-200={filterClientData?.[filterKey] ==
                                filter.id}
                            on:click={(e) => {
                                if (filterClientData[filterKey] == filter.id) {
                                    delete filterClientData[filterKey];
                                } else {
                                    filterClientData[filterKey] = filter.id;
                                }

                                handleFilters();
                            }}
                        >
                            {filter.name}
                            {#if filterClientData?.[filterKey] == filter.id}
                                <iconify-icon
                                    icon="line-md:close"
                                    class="relative top-1"
                                ></iconify-icon>
                            {/if}
                        </button>
                    {/each}
                {/if}
            </article>
        {/each}

      
    </div>
</Modal>
