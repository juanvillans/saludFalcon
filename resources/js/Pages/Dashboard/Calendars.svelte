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

<div class="mt-4 lg:grid lg:grid-cols-2 xl:grid-cols-3 lg:gap-4 w-full gap-2">
    {#each data.calendars.data as calendar}
        <a
            use:inertia
            href={`/admin/agenda/ver-citas/${calendar.id}`}
            class="neumorphism2 block border overflow-hidden rounded-lg mb-3 min-w-[290px] md:w-[420px] cursor-pointer bg-gray-50 hover:border-dark"
        >
            <header class="py-2 px-3 bg-gray-100 w-full flex justify-between">
                <h3 class="font-bold uppercase">{calendar.title}</h3>
                <button
                    title="Eliminar"
                    on:click|preventDefault|stopPropagation={()=> deleteCalendar(calendar.id)}
                    class="delete_button"
                >
                    <iconify-icon icon="ph:trash"></iconify-icon>
                </button>
            </header>
            <div class="p-3">
                <!-- <button
                    on:click|preventDefault={() => {
                        console.log("eliminal");
                    }}
                    class="ml-3 px-3 hover:bg-gray-200 rounded-full"
                >
                    <iconify-icon class="text-xl relative top-1" icon="ph:trash"
                    ></iconify-icon>
                </button> -->
                <div class="flex gap-1.5">
                    <img
                        class="bg-gray-300 w-7 aspect-square rounded-full object-cover"
                        src={`/storage/users/${calendar.user_photo}`}
                        alt=""
                    />
                    <p class="inline-block w-fit">
                        <span>
                            {calendar.user_name}
                            {calendar.user_last_name}</span
                        >
                    </p>
                </div>
                <p
                    class="bg-gray-200 rounded-full w-fit ml-7 relative -top-1 px-2 py-1 text-xs"
                >
                    {calendar.user_specialty_name}
                </p>

                <div class="description mt-3 text-gray-600">
                    {@html calendar.description}
                </div>
            </div>
        </a>
    {/each}
    </div>

<style>
    main .specialties {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(14rem, 170px));
    }
    .cta {
        position: relative;
        /* margin: auto; */
        padding: 19px 22px;
        transition: all 0.2s ease;
    }

    .cta:before {
        content: "";
        position: relative;
        top: 0;
        left: 0;
        display: block;
        border-radius: 28px;
        background: pink; /* Replace $primary with CSS variable */
        width: 56px;
        height: 56px;
        transition: all 0.3s ease;
    }

    .cta span {
        position: absolute;
        font-size: 16px;
        line-height: 18px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: 900;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        vertical-align: middle;
    }

    .cta svg {
        position: absolute;
        right: 20px;
        top: 40px;
        margin-left: 10px;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke: black; /* Replace $color with CSS variable */
        stroke-width: 2;
        transform: translateX(-5px);
        transition: all 0.3s ease;
    }

    .cta:hover:before {
        width: 100%;
        background: pink; /* Replace $primary with CSS variable */
    }

    .cta:hover svg {
        transform: translateX(0);
    }

    .cta:active {
        transform: scale(0.96);
    }
    .delete_button {
        display: none;
    }
    a:hover button {
        display: block;
    }
</style>
