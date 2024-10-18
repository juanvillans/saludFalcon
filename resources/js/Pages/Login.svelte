<script>
    import { useForm } from "@inertiajs/svelte";

    // import secretariaLogo from '$lib/images/logo_secretaria-circle-main.png';
    import Input from "../components/Input.svelte";
    import Modal from "../components/Modal.svelte";

    import Alert from "../components/Alert.svelte";
    import { displayAlert } from "../stores/alertStore";
    let showModal = true;

    let form = useForm({
        ci: null,
        password: null,
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
<section class="bg-background min-h-screen flex justify-center items-center">

   <div class="w-11/12 max-w-sm">
        <legend class="text-center opacity-70"
            >INICIAR SESIÓN</legend
        >
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
                    label={"Contraseña"}
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
        </form>
    </div>

  
</section>

<style>
    * {
        box-sizing: border-box;
    }
</style>
