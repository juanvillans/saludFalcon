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

    function handleSubmit(event) {
        event.preventDefault();
        $form.clearErrors();
        $form.post(window.location.pathname, {
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

    let submitStatus = "Cambiar";

</script>

<form
    id="a-form"
    on:submit={handleSubmit}
    action=""
    class=""
>
    <Input
        type={"password"}
        required={true}
        label={"Contraseña actual"}
        bind:value={$form.current_password}
        error={$form.errors?.current_password}
    />
    <Input
        type={"password"}
        required={true}
        label={"Contraseña nueva"}
        bind:value={$form.new_password}
        error={$form.errors?.new_password}
    />
    <Input
        type={"password"}
        required={true}
        label={"Repetir contraseña nueva"}
        bind:value={$form.confirm_password}
        error={$form.errors?.confirm_password}
    />
</form>
<input
    form="a-form"
    type="submit"
    value={$form.processing ? "Cargando..." : submitStatus}
    class="hover:bg-color3 hover:text-white duration-200 mt-auto w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
/>
