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
<div class="bg-gray-50 min-h-screen relative">

    <img src="/img/hospitalVector.svg" class="w-3/5 mx-auto absolute bottom-0" alt="" srcset="">
    <div class="space-y-4 mr-10 p-5 absolute my-auto">
        <img class="w-20" src="/img/ministerio.png" alt="" srcset="" />
        <img class="w-20" src="/img/gobernacion.png" alt="" srcset="" />
        <img class="w-20" src="/img/secretaria.png" alt="" srcset="" />
        <img class="w-20" src="/img/hospital.png" alt="" srcset="" />
    </div>
    <div class="h-screen w-10 bg-red absolute rigth-0" ></div>
        <div class="w-11/12 max-w-md absolute right-0 h-screen  bg-white p-5 md:p-10 xl:p-12 rounded-xl">
            <h1 class="text-center text-2xl">
                Hospital Doctor Alfredo Van Grieken
            </h1>
            <legend class="text-center opacity-70">INICIAR SESIÓN</legend>
            <form on:submit={handleSubmit} class="min-w-[250px]">
                <div>
                    <Input
                        type="text"
                        name="ci"
                        required={true}
                        label={"Cédula"}
                        bind:value={$form.ci}
                        error={$form.errors?.ci}
                    />
                    <!-- {#if $form.errors.ci}
                <div class="text-white bg-opacity-30 bg-red pt-1">
                    
                    <span >{$form.errors.ci}</span>
                </div>
                {/if} -->
    
                    <Input
                        type="password"
                        required={true}
                        name="password"
                        label={"Contraseña *"}
                        bind:value={$form.password}
                    />
                </div>
                <!-- <button type="submit">Iniciar sesión</button> -->
    
                <input
                    type="submit"
                    disabled={$form.processing}
                    value={$form.processing ? "Cargando..." : "ENTRAR"}
                    class="bg-color3 text-white duration-200 mt-5 w-full hover:bg-color4 hover:text-black font-bold py-3 rounded-md cursor-pointer"
                />
                <a
                    href="/registrarse"
                    class="mt-2 inline-block text-xl underline text-color1"
                    use:inertia>Registrarme</a
                >
            </form>
        </div>
      
</div>

<style>
    * {
        box-sizing: border-box;
    }
</style>
