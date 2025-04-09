<script>
    import { displayAlert } from "../stores/alertStore";
    import { useForm, inertia } from "@inertiajs/svelte";
    import Input from "../components/Input.svelte";

    const emptyDataForm = {
        new_password: "",
        confirm_password: "",
    };

    let form = useForm({
        ...emptyDataForm,
    });
    let showPassword = false;
    function handleSubmit(event) {
        event.preventDefault();
        $form.clearErrors();
        $form.post("/admin/cambiar-contraseña", {
            onError: (errors) => {
                if (errors.data) {
                    displayAlert({ type: "error", message: errors.data });
                }
            },
            onSuccess: (mensaje) => {
                $form.reset();
                displayAlert({
                    type: "success",
                    message: "Ok todo salió bien",
                });
            },
        });
    }
    $: console.log(showPassword);
    let submitStatus = "Cambiar";
</script>

<section
    class="sm:overflow-clip dark:bg-gray-800 bg-white sm:min-h-screen justify-center items-center px-2 relative"
>
    <div
        class="animate-slide-in bg-red w-[99%] h-[calc(100vh-14px)] right-2 top-1.5 rounded-3xl absolute sm:translate-x-[110%]"
    ></div>
    <div
        class="delay-1 animate-slide-in bg-color1 w-[96.6%] h-[calc(100vh-14px)] right-2 top-1.5 rounded-3xl absolute sm:translate-x-[110%]"
    ></div>
    <div
        class="delay-2 animate-slide-in bg-gray-100 w-[92%] h-[calc(100vh-14px)] right-2 top-1.5 rounded-3xl absolute sm:translate-x-[110%]  p-3 md:p-5 lg:p-8 md:gap-5"
    >
        <a href="/" use:inertia
            class="flex items-center gap-3 text-center justify-center mt-5 "
        >
            <img src="/img/logoBlue.svg" alt="" srcset="" class="1/2 w-10" />

             <b>SALUDFALCÓN.COM</b>
        </a>
        <p class="text-center mt-5 my-10">
            ¡Hola <b> Nacho Vidal</b>! Aquí puedes recuperar tu contraseña
        </p>
        <div class="w-fit mx-auto min-w-[310px] neumorphism p-5 md:p-10">
            <form id="a-form" on:submit={handleSubmit} action="">
                <div class="relative">
                    <Input
                        type={showPassword ? "text" : "password"}
                        required={true}
                        label={"Contraseña nueva"}
                        bind:value={$form.new_password}
                        error={$form.errors?.new_password}
                    />
                    {#if showPassword}
                        <iconify-icon
                            title="Ocultar contraseña"
                            on:click={() => (showPassword = !showPassword)}
                            class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800"
                            icon="charm:eye-slash"
                            width="16"
                            height="16"
                        ></iconify-icon>
                    {:else}
                        <iconify-icon
                            title="Ver contraseña"
                            on:click={() => (showPassword = !showPassword)}
                            class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800"
                            icon="charm:eye"
                            width="16"
                            height="16"
                        ></iconify-icon>
                    {/if}
                </div>
                <div class="relative">
                    <Input
                        type={showPassword ? "text" : "password"}
                        required={true}
                        label={"Repetir contraseña nueva"}
                        bind:value={$form.confirm_password}
                        error={$form.errors?.confirm_password}
                    />
                    {#if showPassword}
                        <iconify-icon
                            title="Ocultar contraseña"
                            on:click={() => (showPassword = !showPassword)}
                            class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800"
                            icon="charm:eye-slash"
                            width="16"
                            height="16"
                        ></iconify-icon>
                    {:else}
                        <iconify-icon
                            title="Ver contraseña"
                            on:click={() => (showPassword = !showPassword)}
                            class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800"
                            icon="charm:eye"
                            width="16"
                            height="16"
                        ></iconify-icon>
                    {/if}
                </div>
            </form>
            <input
                form="a-form"
                type="submit"
                value={$form.processing ? "Cargando..." : submitStatus}
                class="mt-8 hover:bg-color3 hover:text-white duration-200 w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
            />
        </div>
    </div>
</section>

<style>
    * {
        box-sizing: border-box;
    }
    @keyframes slide-in {
        0% {
            transform: translateX(110%);
            /* opacity: 0; */
        }
        100% {
            transform: translateX(0%);
            opacity: 1;
        }
    }

    @media (min-width: 640px) {
        .animate-slide-in {
            animation: slide-in 0.4s ease-out forwards;
        }
    }

    .delay-1 {
        animation-delay: 0.15s; /* First div delay */
    }

    .delay-2 {
        animation-delay: 0.3s; /* Second div delay */
    }
</style>
