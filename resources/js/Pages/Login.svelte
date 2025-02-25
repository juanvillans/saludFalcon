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
            class="delay-1 animate-slide-in bg-color1 w-[96%] h-[calc(100vh-14px)] right-2 top-1.5 rounded-3xl absolute translate-x-[110%]"
        ></div>
        <div
            class="delay-2 animate-slide-in bg-gray-200 w-[92%] h-[calc(100vh-14px)] right-2 top-1.5 rounded-3xl absolute translate-x-[110%] flex justify-between p3 md:p-5 lg:p-8 md:gap-5"
        >
            <div>
                <header class="flex items-center gap-3">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="32"
                        height="32"
                        viewBox="0 0 48 48"
                        fill="none"
                    >
                        <rect
                            x="20.25"
                            width="7.5"
                            height="48"
                            rx="3.75"
                            fill="#00247D"
                        />
                        <rect
                            x="48"
                            y="20.25"
                            width="7.5"
                            height="48"
                            rx="3.75"
                            transform="rotate(90 48 20.25)"
                            fill="#00247D"
                        />
                        <path
                            d="M20 44.8043V27.7514C25.4857 27.3379 27.8095 22.4115 28 20V27.7514V44.8043C24.3429 50.592 21.1429 47.2159 20 44.8043Z"
                            fill="#BF0404"
                        />
                        <path
                            d="M28 20H44C46.2091 20 48 21.7909 48 24V24C48 26.2091 46.2091 28 44 28H28V20Z"
                            fill="#FFCC00"
                        />
                    </svg>
                    <h4>SALUDFALCÓN.COM</h4>
                </header>
                <img src="/images/4logos.png" alt="" srcset="" />
                <h1 class="text-color1 xl:text-3xl">
                    REGISTROS DE CASOS DE EMERGENCIA DEL HOSPITAL DR ALFREDO VAN
                    GRIECKEN
                </h1>
            </div>
    
            <form
                on:submit={handleSubmit}
                class="md:min-w-[270px] px-4 md:px-6 bg-white rounded-3xl h-full"
            >
                <legend class="text-center opacity-70 mt-7">INICIAR SESIÓN</legend>
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
                    class="bg-color1 text-white duration-200 mt-5 w-full hover:bg-color4 hover:text-black font-bold py-3 rounded-md cursor-pointer"
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
