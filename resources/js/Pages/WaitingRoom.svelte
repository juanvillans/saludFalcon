<script>
    import Table from "../components/Table.svelte";
    import StatusColor from "../components/StatusColor.svelte";
    import fetchLocalData from "../components/localData";
    import Search from "../components/Search.svelte";
    import { onMount } from "svelte";

    let localData;

    onMount(async () => {
        try {
            localData = await fetchLocalData();
        } catch (error) {
            console.error("Error loading data:", error);
        }
    });

    function getFirstName(firstName) {
        const parts = firstName.split(" ");
        return parts[0];
    }
    let data = {
        data: [
            {
                id: 100,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 100,
                patient_name: "Carlos",
                patient_last_name: "Villasmil",
                patient_ci: "3084799",
                patient_phone_number: "+5841299",
                patient_sex: "Masculino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 99,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 99,
                patient_name: "Juan",
                patient_last_name: "Diaz",
                patient_ci: "3084798",
                patient_phone_number: "+5841298",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 98,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 98,
                patient_name: "Juan",
                patient_last_name: "Donquis",
                patient_ci: "3084797",
                patient_phone_number: "+5841297",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 97,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 97,
                patient_name: "Jose",
                patient_last_name: "Donquis",
                patient_ci: "3084796",
                patient_phone_number: "+5841296",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 96,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 96,
                patient_name: "Jose",
                patient_last_name: "Tovar",
                patient_ci: "3084795",
                patient_phone_number: "+5841295",
                patient_sex: "Masculino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 95,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 95,
                patient_name: "Carlos",
                patient_last_name: "Diaz",
                patient_ci: "3084794",
                patient_phone_number: "+5841294",
                patient_sex: "Masculino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 94,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 94,
                patient_name: "Jose",
                patient_last_name: "Diaz",
                patient_ci: "3084793",
                patient_phone_number: "+5841293",
                patient_sex: "Masculino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 93,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 93,
                patient_name: "Marcos",
                patient_last_name: "Rodriguez",
                patient_ci: "3084792",
                patient_phone_number: "+5841292",
                patient_sex: "Masculino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 92,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 92,
                patient_name: "Carlos",
                patient_last_name: "Diaz",
                patient_ci: "3084791",
                patient_phone_number: "+5841291",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 91,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 91,
                patient_name: "Jose",
                patient_last_name: "Diaz",
                patient_ci: "3084790",
                patient_phone_number: "+5841290",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 90,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 90,
                patient_name: "Juan",
                patient_last_name: "Donquis",
                patient_ci: "3084789",
                patient_phone_number: "+5841289",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 89,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 89,
                patient_name: "Jose",
                patient_last_name: "Diaz",
                patient_ci: "3084788",
                patient_phone_number: "+5841288",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 88,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 88,
                patient_name: "Daniel",
                patient_last_name: "Rodriguez",
                patient_ci: "3084787",
                patient_phone_number: "+5841287",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 87,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 87,
                patient_name: "Jose",
                patient_last_name: "Diaz",
                patient_ci: "3084786",
                patient_phone_number: "+5841286",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 86,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 86,
                patient_name: "Carlos",
                patient_last_name: "Rodriguez",
                patient_ci: "3084785",
                patient_phone_number: "+5841285",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 85,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 85,
                patient_name: "Daniel",
                patient_last_name: "Donquis",
                patient_ci: "3084784",
                patient_phone_number: "+5841284",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 84,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 84,
                patient_name: "Juan",
                patient_last_name: "Rodriguez",
                patient_ci: "3084783",
                patient_phone_number: "+5841283",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 83,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 83,
                patient_name: "Juan",
                patient_last_name: "Diaz",
                patient_ci: "3084782",
                patient_phone_number: "+5841282",
                patient_sex: "Masculino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 82,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 82,
                patient_name: "Marcos",
                patient_last_name: "Villasmil",
                patient_ci: "3084781",
                patient_phone_number: "+5841281",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 81,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 81,
                patient_name: "Jose",
                patient_last_name: "Tovar",
                patient_ci: "3084780",
                patient_phone_number: "+5841280",
                patient_sex: "Masculino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 80,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 80,
                patient_name: "Jose",
                patient_last_name: "Rodriguez",
                patient_ci: "3084779",
                patient_phone_number: "+5841279",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 79,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 79,
                patient_name: "Daniel",
                patient_last_name: "Donquis",
                patient_ci: "3084778",
                patient_phone_number: "+5841278",
                patient_sex: "Masculino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 78,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 78,
                patient_name: "Jose",
                patient_last_name: "Tovar",
                patient_ci: "3084777",
                patient_phone_number: "+5841277",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 77,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 77,
                patient_name: "Carlos",
                patient_last_name: "Rodriguez",
                patient_ci: "3084776",
                patient_phone_number: "+5841276",
                patient_sex: "Masculino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
            {
                id: 76,
                entry_date: "2025-04-29",
                formatted_entry_date: "29 Apr 2025",
                entry_hour: "00:00:00",
                departure_date: null,
                departure_hour: null,
                reason: "Diarrea",
                diagnosis: "Efectivamente tiene diarrea",
                treatment: "Caf\u00e9 con bastante cambul",
                current_status: 4,
                current_status_name: "Ingresado",
                municipality_id: 14,
                municipality_name: "Miranda",
                parish_id: 37,
                parish_name: "San Antonio",
                user_id: 3,
                user_name: "Jose",
                user_last_name: "Rodriguez",
                user_ci: "14027371",
                user_specialty_id: 2,
                user_specialty_name: "Anestesiolog\u00eda",
                patient_id: 76,
                patient_name: "Juan",
                patient_last_name: "Donquis",
                patient_ci: "3084775",
                patient_phone_number: "+5841275",
                patient_sex: "Femenino",
                patient_date_birth: "2000-12-25",
                patient_age: 25,
                patient_address: "San Jos\u00e9",
                area_id: 1,
                area_name: "Quir\u00f3fano",
                current_patient_condition_id: 2,
                current_patient_condition_name: "Cr\u00edtico",
            },
        ],
    };

    let visulizateType = "table";
    // Check if 'visualizateTypeCases' exists in localStorage
    if (typeof localStorage !== "undefined") {
        const storedValue = localStorage.getItem("visualizateTypeCases");
        if (storedValue) {
            visulizateType = storedValue; // Use the stored value if it exists
        } else {
            localStorage.setItem("visualizateTypeCases", visulizateType); // Save the default value
        }
    }

    $: if (visulizateType) {
        if (typeof localStorage !== "undefined") {
            localStorage.setItem("visualizateTypeCases", visulizateType);
        }
    }
</script>

<div class="p-4">
    <div class="">
        <div class="neumorphism2 p-3 rounded-xl">
            <h2>Condión de los pacientes</h2>
<Search filtersOptions={{
    date: {
        type: "date",
        label: "Fecha de ingreso",
    },
    status:
        {
            type: "select",
            label: "Estado",
            options: localData?.statutes || [],
        } || {},
    case_id:
        {
            type: "search",
            label: "ID del caso",
            options: [],
        } || {},
    specialty_id:
        {
            type: "select",
            label: "Servicio tra.",
            options: localData?.specialties || [],
        } || {},
    area_id:
        {
            type: "select",
            label: "Última area",
            options: localData?.areas || [],
        } || {},

    condition:
        {
            type: "select",
            label: "Condición",
            options: localData?.conditions || [],
        } || {},
}} />
            <div>
                <Table {visulizateType}>
                    <div slot="filterBox"></div>
                    <thead slot="thead" class="sticky top-0">
                        <tr>
                            <th >Cama</th>
                            <th>Paciente</th>
                            <th>Ubicación</th>
                            <th>Condición</th>
                            <th>Observación</th>
                            <th style="font-size: 12px  ">Última actualización.</th>
                        </tr>
                    </thead>

                    <tbody slot="tbody">
                        {#if data?.data?.length > 0 && visulizateType == "table"}
                            {#each data?.data as row, i (row.id)}
                                <tr
                                    on:mousedown={handleMouseDown}
                                    on:mouseup={(e) => handleMouseUp(e, row.id)}
                                    class={`md:max-h-[200px] overflow-hidden cursor-pointer  hover:bg-gray-500 hover:bg-opacity-5`}
                                >
                                    <td style="font-size: 12px;">{row.id}</td>
                                    <td class="min-w-[180px]">
                                        <div class="flex items-center gap-2">
                                            <span class="whitespace-normal"
                                                >{getFirstName(
                                                    row?.patient_name,
                                                )}
                                                {getFirstName(
                                                    row?.patient_last_name,
                                                )}
                                                <small class="text-gray-400"
                                                    >C.I:</small
                                                >{row?.patient_ci}
                                            </span>
                                        </div>
                                    </td>
                                    <td
                                        style="white-space: normal;"
                                        class="min-w-[150px]"
                                    >
                                        <span class="inline-block flex">
                                            {row.area_name}
                                        </span>
                                    </td>

                                    <td
                                        title={row?.current_patient_condition_name}
                                        class="max-w-[340px] md:min-w-[120px] max-h-[100px] overflow-hidden"
                                        style="white-space: normal;"
                                    >
                                        <div
                                            class={`inline-block w-2 h-2 mr-2 aspect-square rounded-full  condition${row.current_patient_condition_id}`}
                                        ></div>
                                        <span>
                                            {row?.current_patient_condition_name}
                                        </span>
                                    </td>
                                    <!-- <td>{row.sex}</td> -->
                                    <td
                                        class="max-w-[340px] min-w-[290px] md:min-w-[320px] max-h-[100px] overflow-hidden"
                                        style="white-space: normal;"
                                    >
                                        {#if row?.treatment.length > 200}
                                            {row?.treatment.slice(0, 200)}
                                            <span
                                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                                >...</span
                                            >
                                        {:else}
                                            {row?.treatment}
                                        {/if}
                                    </td>
                                    <td class="">{row.formatted_entry_date}</td>

                                    <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                                </tr>
                            {/each}
                        {/if}
                    </tbody>
                </Table>

                {#if visulizateType == "card"}
                    <div class="grid lg:grid-cols-2 gap-5 mt-3">
                        {#each data?.data as row, i (row.id)}
                            <!-- svelte-ignore a11y-click-events-have-key-events -->
                            <article
                                on:mousedown={handleMouseDown}
                                on:mouseup={(e) => handleMouseUp(e, row.id)}
                                class={`relative w-full cursor-pointer bg-gray-100 p-2 md:p-5 rounded-md  hover:bg-color4 hover:bg-opacity-60 neumorphism2`}
                            >
                                <span
                                    class="h-fit absolute right-0 top-0 text-center col-span-2 p-1 text-xs inline-block w-10 md:px-2"
                                    >{row.id}</span
                                >
                                <div class="flex gap-1 items-center">
                                    <StatusColor
                                        status={{
                                            name: row?.current_status_name,
                                            id: row?.current_status,
                                        }}
                                    />

                                    <!-- {#if row?.current_status == "3"}
                        a {row?.admitted_area_name}
                    {/if} -->
                                    <span class="inline-flex">
                                        {#if row.current_status == 1 || row.current_status == 2}
                                            de
                                        {:else if row.current_status == 4 || row.current_status == 5}
                                            en
                                        {:else if row.current_status == 3}
                                            a
                                        {/if}
                                        {row.area_name}.
                                    </span>
                                    <div class="flex items-cenenter gap-1">
                                        <iconify-icon
                                            icon="game-icons:duration"
                                            class="text-gray-600 text-xs md:text-sm"
                                        ></iconify-icon>
                                    </div>
                                </div>
                                <p>
                                    F. de ingreso: {row.formatted_entry_date}
                                </p>

                                <div class="flex items-center gap-3 mt-1">
                                    {#if row.sex == "Femenino"}
                                        <span
                                            class="text-pink text-lg sm:text-2xl"
                                        >
                                            <iconify-icon icon="fa-solid:female"
                                            ></iconify-icon>
                                        </span>
                                    {:else}
                                        <span
                                            class="text-color3 text-lg sm:text-2xl"
                                        >
                                            <iconify-icon icon="fa-solid:male"
                                            ></iconify-icon>
                                        </span>
                                    {/if}
                                    <span
                                        >{getFirstName(row?.patient_name)}
                                        {getFirstName(row.patient_last_name)}
                                        <small class="text-gray-500">C.I:</small
                                        >{row.user_ci}
                                    </span>
                                </div>
                                <div class="mt-1 flex gap-1.5">
                                    <iconify-icon
                                        icon="emojione-monotone:speaking-head"
                                        width="20"
                                        height="20"
                                        class="text-gray-900"
                                    ></iconify-icon>
                                    <p>
                                        {#if row?.reason.length > 200}
                                            {row?.reason.slice(0, 200)}
                                            <span
                                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                                >...</span
                                            >
                                        {:else}
                                            {row?.reason}
                                        {/if}
                                    </p>
                                </div>
                                <div class="mt-2 flex gap-2">
                                    <div
                                        class={`inline-block w-2 h-2 mr-2 relative top-2 aspect-square rounded-full  condition${row.current_patient_condition_id}`}
                                    ></div>
                                    <p>
                                        {#if row.diagnosis.length > 200}
                                            {row.diagnosis.slice(0, 200)}
                                            <span
                                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                                >...</span
                                            >
                                        {:else}
                                            {row.diagnosis}
                                        {/if}
                                    </p>
                                </div>
                                <div class="mt-2 flex gap-2">
                                    <iconify-icon
                                        class="relative top-1 -left-1 text-color2"
                                        icon="ant-design:medicine-box-filled"
                                        width="20"
                                        height="20"
                                    ></iconify-icon>
                                    <p>
                                        {#if row.treatment.length > 200}
                                            {row.treatment.slice(0, 200)}
                                            <span
                                                class="leading-3 text-2xl inline-block font-bold text-color1 relative"
                                                >...</span
                                            >
                                        {:else}
                                            {row.treatment}
                                        {/if}
                                    </p>
                                </div>

                                <!-- <td>{row.rep_name} {row.rep_last_name}</td> -->
                                <!-- <p
                    class="text-right justify-end w-full flex items-center gap-2"
                >
                    {row.user_name}
                    {row.user_last_name}
                    <iconify-icon icon="mdi:doctor" style="font-size: 20px;"
                    ></iconify-icon>
                </p> -->
                            </article>
                        {/each}
                    </div>
                {/if}
            </div>
        </div>
    </div>
</div>
