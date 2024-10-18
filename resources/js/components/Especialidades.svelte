<script>
    import { onMount } from "svelte";
    import { router } from "@inertiajs/svelte";

  
    export let instituteSpecialities = [];
    export let specialities = [];
    let searchTerm = "";

    // Function to handle form submission
    function handleSubmit(id) {
        const userConfirmed = window.confirm(
            "¿Está seguro de añadir esta especialidad?",
        );

        if (userConfirmed) {
            router.put(`/admin/especialidades/${id}`);
        }
    }

    // Reactive update function
   

    $: filteredItems = specialities.filter((item) =>
        item.name.toLowerCase().includes(searchTerm.toLowerCase()),
    );
    $: console.log(instituteSpecialities);
</script>

<main class="flex justify-between gap-8 md:gap-10">
    <div class="offered sticky top-2">
        <h1 class="mb-2">Especialidades y servicios de la institución</h1>
        <ul class="text-lg p-2">
            {#each instituteSpecialities as speci (speci.id)}
                <li>{speci.name}</li>
            {/each}
            {#if instituteSpecialities.length == 0}
                <li>No hay ningúna</li>
            {/if}
        </ul>
    </div>
    <div>
        <h2 class="font-bold text-lg">
            Selecciona otras Especialidades y/o servicios
        </h2>
        <div
            class="flex ml-7 bg-gray-200 md:min-w-72 rounded-full my-2 pl-3 items-center sticky top-1 z-50 shadow-md"
        >
            <iconify-icon icon="cil:search" class="mx-2" />
            <input
                type="search"
                placeholder="Buscar"
                name=""
                id=""
                class="bg-gray-200 px-3 py-2 rounded-full outline-none w-full"
                bind:value={searchTerm}
            />
        </div>
        <ul class="text-lg p-2 for_select">
            {#each filteredItems as item (item.id)}
                <!-- svelte-ignore a11y-click-events-have-key-events -->
                <li
                    on:click={() => handleSubmit(item.id)}
                    class="flex items-center cursor-pointer hover:text-color2"
                >
                    <span
                        class="text-3xl mr-3 h-8 aspect-square rounded-full inline-block icon"
                    >
                        <iconify-icon
                            class="relative"
                            icon="weui:sending-outlined"
                        ></iconify-icon>
                    </span>
                    {item.name}
                </li>
            {/each}
        </ul>
    </div>
</main>

<style>
    .offered ul li:before {
        content: "✓";
        margin-right: 6px;
    }
    ul.for_select li:hover .icon {
        background-color: #397373;
        color: white;
        transition: cubic-bezier(0.075, 0.82, 0.165, 1) 0.2s;
        font-size: 35px !important;
    }
</style>
