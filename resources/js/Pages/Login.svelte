<script>
    import { useForm, inertia } from "@inertiajs/svelte";
    // import loginImages from 'img/loginImages.webp'
    // import secretariaLogo from '$lib/images/logo_secretaria-circle-main.png';
    import Input from "../components/Input.svelte";
    import Modal from "../components/Modal.svelte";
    import { onMount } from "svelte";
    import Alert from "../components/Alert.svelte";
    import { displayAlert } from "../stores/alertStore";
    let showModal = true;

    // const { lastUpdatedAt, isPrefetching, isPrefetched } = usePrefetch(
    //     "/admin/historial-medico",
    //     { method: "get"},
    //     { cacheFor: "5s" },
    // );
    let form = useForm({
        ci: null,
        password: null,
    });
    function focusInput() {
        document.querySelector("input[name='ci']").focus();
    }
    onMount(() => {
        focusInput();
    });

    function handleSubmit(event) {
        event.preventDefault();
        $form.clearErrors();
        $form.post("/admin/login", {
            onError: (errors) => {
                if (errors.data) {
                    displayAlert({ type: "error", message: errors.data });
                }
            },
        });
    }
</script>

<Alert />

    <section
        class="overflow-clip dark:bg-gray-800 bg-white min-h-screen justify-center items-center px-2 relative"
    >
        <div
            class="animate-slide-in bg-red w-[99%] h-[calc(100vh-14px)] right-2 top-1.5 rounded-3xl absolute translate-x-[110%]"
        ></div>
        <div
            class="delay-1 animate-slide-in bg-color1 w-[96.6%] h-[calc(100vh-14px)] right-2 top-1.5 rounded-3xl absolute translate-x-[110%]"
        ></div>
        <div
            class="delay-2 animate-slide-in bg-gray-100 w-[92%] h-[calc(100vh-14px)] right-2 top-1.5 rounded-3xl absolute translate-x-[110%] flex  flex-col md:flex-row md:justify-between p-3 md:p-5 lg:p-8 md:gap-5"
        >
            <div class="relative">
                <header class="flex  items-center gap-3">
                    <img src="/img/logoBlue.svg" alt="" srcset=""  class="1/2 w-10"/>

                    <h4>SALUDFALCÓN.COM</h4>
                </header>
                <h1 class="text-color1  xl:text-3xl mt-2 lg:mt-6 xl:mt-12">
                    REGISTROS DE CASOS DE EMERGENCIA DEL HOSPITAL DR ALFREDO VAN
                    GRIECKEN
                </h1>
                <img src="/img/doctor.png" alt="" srcset=""  class=""/>

            <img src="/img/4logos.png" alt="" srcset=""  class="target fixed  md:absolute bottom-2 md:bottom-0 left-1/2 -translate-x-1/2 mx-auto mt-5 md:mt-12 lg:mt-16 max-w-[200px] md:max-w-[300px]"/>

            </div>
            
            <form
                on:submit={handleSubmit}
                class="md:min-w-[290px] px-4 md:px-6 lg:px-8 bg-gray-100 rounded-3xl md:h-full md:neumorphism"
            >
                <legend class="text-center opacity-70 mt-3 md:mt-7">INICIAR SESIÓN</legend>
                <div>
                    <Input
                        type="text"
                        name="ci"
                        required={true}
                        label={"Cédula"}
                        bind:value={$form.ci}
                        error={$form.errors?.ci}
                    />
    
                    <Input
                        type="password"
                        required={true}
                        name="password"
                        label={"Contraseña *"}
                        bind:value={$form.password}
                    />
                </div>
    
                <input
                    type="submit"
                    disabled={$form.processing}
                    value={$form.processing ? "Cargando..." : "ENTRAR"}
                    class="btn_create py-3 w-40 mx-auto  md:w-full md:mt-2 lg:mt-5 rounded-md cursor-pointer hover:text-color2"
                />
                <a
                    href="/registrarse"
                    class="mt-2 inline-block text-xl underline text-color1"
                    use:inertia>Registrarme</a
                >
            </form>

            
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


        /* Hide scrollbar for Firefox */
    .animate-slide-in {
        animation: slide-in 0.4s ease-out forwards; /* Adjust duration and timing as needed */
    }
    .delay-1 {
        animation-delay: 0.15s; /* First div delay */
    }

    .delay-2 {
        animation-delay: 0.3s; /* Second div delay */
    }
</style>
