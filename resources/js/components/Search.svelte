<script>
    import { page, router } from "@inertiajs/svelte";
    import debounce from "lodash/debounce";
    import Modal from "./Modal.svelte";
    import DateRange from "../components/DateRange.svelte";

    let search;

    const handleSearch = debounce((event) => {
        router.get(`${$page.url}`, { search, page: "1" });
    }, 300);

    let showModal = false;
    export let filtersOptions = false;
    export let allowSearch = true;
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
            start_date: +args.detail.startDate,
            end_date: +args.detail.endDate,
        };
        handleFilters();
    };


    const handleFilters = () => {
        firstTime = false;
        router.get(`${$page.url.split("?")[0]}`, filterClientData);
    };
</script>

<div
    class="fixed top-3 lg right-20 md:right-64 flex items-center rounded-full bg-gray-50 border border-gray-200"
>
    <span class="absolute">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-5 h-5 mx-3 text-gray-400"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
            />
        </svg>
    </span>

    <input
        type="search"
        placeholder="Buscar"
        bind:value={search}
        on:input={() => {
            handleSearch();
        }}
        class=" block w-full py-1.5 pr-5 text-gray-700 rounded-full rounded-r-none md:w-56  placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"
    />
    {#if filtersOptions}
        <div class="md:right-64 top-3 z-50">
            <button
                class=" relative flex gap-2 hover:bg-gray-300 rounded-full rounded-l-none p-2 px-3"
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
        </div>
    {/if}
</div>

<Modal
    bind:showModal
    modalClasses={"max-w-[960px] h-full"}
    showCancelButton={false}
>
    <p slot="header" class="opacity-60">Filtros de busqueda</p>
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
                        class="h-auto border-gray-400 bg-gray-200 border p-2 py-1 rounded"
                        placeholder={"🔍 " + filterOption.label}
                        type="search"
                        name=""
                        id=""
                        on:input={(e) => {
                            const inputValue = e.target.value;
                            if (/^\d*$/.test(inputValue)) {
                                filterClientData[filterKey] = inputValue;
                                handleFilters();
                            } else {
                                e.target.value =
                                    filterClientData[filterKey] || "";
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
                    <DateRange  startDate={Number(filterClientData?.start_date)}
                    endDate={Number(filterClientData?.end_date)} on:changeDateFilter={changeDateFilter} />
                {:else}
                    {#each filterOption.options as filter, i (filter.id)}
                        <button
                            class="text-left filter_button px-2 py-1 my-1 text-xs font-medium hover:text-dark rounded-full text-gray-700 block transition-colors duration-75 sm:text-sm hover:bg-gray-200"
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
