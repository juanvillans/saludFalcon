<script>
    import Table from "../../components/Table.svelte";
    import Modal from "../../components/Modal.svelte";
    import Input from "../../components/Input.svelte";
    import axios from "axios";
    import debounce from "lodash/debounce";

    import Alert from "../../components/Alert.svelte";
    import { displayAlert } from "../../stores/alertStore";
    import { useForm, router, page } from "@inertiajs/svelte";
    import { claim_svg_element } from "svelte/internal";
    let data = {
        appointments: [
            {
                day: 18,
                day_name: "VIE",
                shifts: [
                    "9:00am",
                    "10:00am",
                    "11:00am",
                    "12:00am",
                    "1:00pm",
                    "2:00pm",
                    "3:00pm",
                ],
            },
            {
                day: 19,
                day_name: "VIE",
                shifts: [
                    "9:00am",
                    "10:00am",
                    "11:00am",
                    "12:00am",
                    "2:00pm",
                    "3:00pm",
                ],
            },
            {
                day: 20,
                day_name: "VIE",
                shifts: [
                    "9:00am",
                    "10:00am",
                    "11:00am",
                    "12:00am",
                    "2:00pm",
                    "3:00pm",
                ],
            },
            {
                day: 21,
                day_name: "VIE",
                shifts: [
                    "10:00am",
                    "11:00am",
                    "12:00am",
                    "1:00pm",
                    "2:00pm",
                    "3:00pm",
                    "4:00pm",
                ],
            },
        ],
    };

    let form = useForm({});
    function handleSubmit(event) {
        event.preventDefault();
        $form.clearErrors();
        $form.post("/dashboard/matricula", {
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
                showModal = false;
            },
        });
    }
</script>

<svelte:head>
    <title>Matricula</title>
</svelte:head>

<Alert />
