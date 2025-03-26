<script>
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, inertia } from "@inertiajs/svelte";
    import Input from "../../components/Input.svelte";

    const emptyDataForm = {
        current_password: "",
        new_password: "",
        confirm_password: "",
    };

    let form = useForm({
        ...emptyDataForm,
    });
    let showPassword = false
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
    $: console.log(showPassword)
    let submitStatus = "Cambiar";
</script>

<div class="w-fit mx-auto min-w-[310px] neumorphism p-5 md:p-10">
    <form id="a-form" on:submit={handleSubmit} action="">

        <div class="relative">
            <Input
                type={showPassword ? "text" : "password"}
                required={true}
                label={"Contraseña actual"}
                bind:value={$form.current_password}
                error={$form.errors?.current_password}
            />
            {#if showPassword}
            <iconify-icon title="Ocultar contraseña" on:click={() => showPassword = !showPassword} class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800" icon="charm:eye-slash" width="16" height="16"></iconify-icon>
            {:else}
            <iconify-icon title="Ver contraseña" on:click={() => showPassword = !showPassword} class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800" icon="charm:eye" width="16" height="16"></iconify-icon>
            {/if}
        </div>
        <div class="relative">
            <Input
                type={showPassword ? "text" : "password"}
                required={true}
                label={"Contraseña nueva"}
                bind:value={$form.new_password}
                error={$form.errors?.new_password}
            />
            {#if showPassword}
            <iconify-icon title="Ocultar contraseña" on:click={() => showPassword = !showPassword} class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800" icon="charm:eye-slash" width="16" height="16"></iconify-icon>
            {:else}
            <iconify-icon title="Ver contraseña" on:click={() => showPassword = !showPassword} class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800" icon="charm:eye" width="16" height="16"></iconify-icon>
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
            <iconify-icon title="Ocultar contraseña" on:click={() => showPassword = !showPassword} class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800" icon="charm:eye-slash" width="16" height="16"></iconify-icon>
            {:else}
            <iconify-icon title="Ver contraseña" on:click={() => showPassword = !showPassword} class="absolute top-9 text-gray-600 right-2 cursor-pointer hover:text-gray-800" icon="charm:eye" width="16" height="16"></iconify-icon>
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
