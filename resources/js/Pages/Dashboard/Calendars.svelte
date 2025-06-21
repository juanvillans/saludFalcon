<script>
    import { inertia, router } from "@inertiajs/svelte";
    import Modal from "../../components/Modal.svelte";
    import Alert from "../../components/Alert.svelte";
    import { displayAlert } from "../../stores/alertStore";
    export let data = {};
    let contentForModal;
    $: console.log(data);
    let showModal = false;
    // $: console.log(showModal);
    function deleteCalendar(id) {
        if (!window.confirm("eliminar este calendario")) {
            return
        }
        router.delete(`/admin/agenda/ver-citas/${id}`, {
            // preserveState: true,
            onError: (errors) => {
                displayAlert({
                    type: "error",
                    message: errors.data || "algo salió mal",
                });
            },
            onSuccess: (page) => {
                displayAlert({
                    type: "success",
                    message: "Eliminado",
                });
            },
        });
    }
</script>

<a
    class="btn_create inline-block p-2 px-3"
    href="/admin/agenda/crear-calendario"
    use:inertia
>
    <span class="sm:hidden text-2xl relative top-1 font-bold"
        ><iconify-icon icon="ic:round-add"></iconify-icon></span
    >
    <span class="hidden md:block"> Crear calendario</span>
</a>

<div class="mt-4 card_container w-full">
    {#each data.calendars.data as calendar}
        <a
            use:inertia={{ prefetch: true }}
            href={`/admin/agenda/ver-citas/${calendar.id}`}
            class="neumorphism2 block border overflow-hidden rounded-lg mb-3  cursor-pointer bg-gray-50 hover:border-dark"
        >
            <header class="py-2 px-3 bg-gray-100 w-full flex justify-between">
                <h3 class="font-bold uppercase">{calendar.specialty_name}</h3>
                <button
                    title="Eliminar"
                    on:click|preventDefault|stopPropagation={()=> deleteCalendar(calendar.id)}
                    class="delete_button"
                >
                    <iconify-icon icon="ph:trash"></iconify-icon>
                </button>
            </header>
            <div class="p-3">
              
                

                <div class="description mt-3 text-gray-600">
                    {@html calendar.description}
                </div>
            </div>
        </a>
    {/each}

   
      
        
    </div>

<style>
    .card_container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
        gap: 14px
    }
    .delete_button {
        display: none;
    }
    a:hover button {
        display: block;
    }
</style>
