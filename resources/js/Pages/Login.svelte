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

    let forgotPassword = useForm({
        ci: "",
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
    let showPassword = false

    function handleForgetPsw() {
        console.log('h');
        
        if ($forgotPassword.ci.length < 6) {
            displayAlert({ type: "error", message: "Escriba su Cédula" });
            return
        }
        $forgotPassword.clearErrors();
        $forgotPassword.post("olvidar-contraseña", {
            onError: (errors) => {
                if (errors.data) {
                    displayAlert({ type: "error", message: errors.data });
                }
            },
            onSuccess: (mensaje) => {
                $forgotPassword.reset();
                displayAlert({
                    type: "success",
                    message: "Se le envió un correo para cambiar su contraseña",
                    time: 10000
                });
            },
        });
    }

</script>

<Alert />

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
        class="delay-2 animate-slide-in bg-gray-100 w-[92%] h-[calc(100vh-14px)] right-2 top-1.5 rounded-3xl absolute sm:translate-x-[110%] flex flex-col sm:flex-row sm:justify-between p-3 md:p-5 lg:p-8 md:gap-5"
    >
        <div class="relative">
            <header class="flex items-center gap-3">
                <img
                    src="/img/logoBlue.svg"
                    alt=""
                    srcset=""
                    class="1/2 w-10"
                />

                <h4>SALUDFALCÓN.COM</h4>
            </header>
            <h1
                class="text-color1 sm:text-xl xl:text-3xl mt-2 lg:mt-6 xl:mt-12"
            >
                REGISTROS DE CASOS DE EMERGENCIA DEL HOSPITAL DR ALFREDO VAN
                GRIEKEN
            </h1>
            <img
                class="w-28 sm:w-40 lg:w-80 fixed -left-2 md:absolute bottom-0"
                src="/img/doctor.png"
                alt=""
                srcset=""
            />

            <img
                src="/img/4logos.png"
                alt=""
                srcset=""
                class="target fixed md:absolute bottom-2 md:bottom-0 left-1/2 -translate-x-1/2 mx-auto mt-5 md:mt-12 lg:mt-16 max-w-[200px] md:max-w-[300px]"
            />
        </div>

        <form
            on:submit={handleSubmit}
            class="sm:min-w-[290px] lg:sm:min-w-[350px] mx-2 py-7 px-4 md:px-6 lg:px-8 bg-gray-200 bg-opacity-30 rounded-3xl md:h-full neumorphism"
        >
            <legend class="text-center opacity-70 mt-3 md:mt-7"
                >INICIAR SESIÓN</legend
            >
            <div>
                <Input
                    type="text"
                    name="ci"
                    required={true}
                    label={"Cédula *"}
                    on:input={(e) => {
                        $form.ci = e.target.value
                        $forgotPassword.ci = e.target.value
                    }}
                    error={$form.errors?.ci}
                />
                <div class="relative">
                    <Input
                    type={showPassword ? "text" : "password"}

                        required={true}
                        name="password"
                        label={"Contraseña *"}
                        bind:value={$form.password}
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
            </div>
            {#if $forgotPassword.processing }
                <p>Enviando link a su correo...</p>
            {:else}
            
            <button on:click={handleForgetPsw} type="button" class="text-right w-full mt-2 p-1 hover:underline text-gray-800"> Olvidé mi contraseña</button>
            {/if}
            <input
                type="submit"
                disabled={$form.processing}
                value={$form.processing ? "Cargando..." : "ENTRAR"}
                class=" py-3 mx-auto w-full mt-4 lg:mt-6 rounded-md cursor-pointer hover:text-color2 bg-color4 text-color1 border-color1 border"
            />
            <a
                href="/registrarse"
                class="mt-4 inline-block text-lg w-full  text-center underline text-color1"
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
