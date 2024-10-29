<script>
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, inertia } from "@inertiajs/svelte";
    const emptyDataForm = {
        current_psw: "",
        new_psw1: "",
        new_psw2: "",
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
        bind:value={$form.current_psw}
        error={$form.errors?.current_psw}
    />
    <Input
        type={"password"}
        required={true}
        label={"Contraseña nueva"}
        bind:value={$form.new_psw1}
        error={$form.errors?.new_psw1}
    />
    <Input
        type={"password"}
        required={true}
        label={"Repetir contraseña nueva"}
        bind:value={$form.new_psw2}
        error={$form.errors?.new_psw2}
    />
</form>
<input
    form="a-form"
    type="submit"
    value={$form.processing ? "Cargando..." : submitStatus}
    class="hover:bg-color3 hover:text-white duration-200 mt-auto w-full bg-color4 text-black font-bold py-3 rounded-md cursor-pointer"
/>
