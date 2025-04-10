<script>
    import { inertia } from "@inertiajs/svelte";
    import Modal from "../../components/Modal.svelte";
    export let data = {};
    let contentForModal;
    $: console.log(contentForModal);
    let showModal = false;
    // $: console.log(showModal);
</script>

<Modal bind:showModal>
    <h2 slot="header" class="text-center">
        Citas de {contentForModal.specialty_name}
        <span class="text-gray-300 block">|</span>
    </h2> 
    <div class="lg:grid lg:grid-cols-2 lg:gap-4">
        {#each contentForModal.calendar as calendar}
            <a use:inertia href={`/admin/agenda/cita/${calendar.id}`} class="border p-3 rounded-md mb-3 min-w-[290px] md:w-[420px] cursor-pointer hover:bg-gray-50 hover:border-dark">
                <div class="flex justify-between">
                    <h3 class="font-bold">{calendar.title}</h3>
                    <button
                        on:click|preventDefault={() => {
                            console.log('eliminal')
                        }}
                        class="ml-3  px-3 hover:bg-gray-200 rounded-full"
                    >
                        <iconify-icon
                            class="text-xl relative top-1"
                            icon="ph:trash"
                        ></iconify-icon>
                    </button>
                </div>
                <div
                    class="ml-2 mt-1.5 flex gap-2 items-center bg-gray-100 rounded-full max-w-fit pr-3"
                >
                    <span
                        class="rounded-full overflow-hidden bg-color4 w-7 h-7 justify-center items-center flex"
                    >
                        <iconify-icon icon="fa6-solid:user-doctor"
                        ></iconify-icon>
                    </span>
                    <p>{calendar.doctor_name} {calendar.doctor_last_name}</p>
                </div>

                <div class="description mt-3 text-gray-600">
                    {@html calendar.description} 
                </div>
            </a>
        {/each}
    </div>
</Modal>
<a href="/admin/agenda/crear-cita" use:inertia class="btn_create inline-block"
    >Crear Cita</a
>
<main>
    <div class="specialties gap-5 md:gap-8 cursor-pointer">
        {#each data.specialties.data as speciality}
            <button
                class="cta"
                on:click={() => {
                    showModal = true;
                    contentForModal = speciality;
                }}
            >
                <span>{speciality.specialty_name}</span>
                <svg width="13px" height="10px" viewBox="0 0 13 10">
                    <path d="M1,5 L11,5"></path>
                    <polyline points="8 1 12 5 8 9"></polyline>
                </svg>
            </button>
        {/each}
    </div>
</main>

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
</style>
