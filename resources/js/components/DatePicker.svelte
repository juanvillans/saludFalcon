<script>
    import { onMount, onDestroy } from "svelte";
    import { createEventDispatcher } from "svelte";
    import Calender from "./Calender.svelte";
    import { getMonthName } from "./date-time.js";

    const dispatch = createEventDispatcher();
    let datePickerElement;

    function handleClickOutside(event) {
        // Check if the click is outside the date picker and input element
        if (
            datePickerElement &&
            !datePickerElement.contains(event.target) &&
            !inputElement.contains(event.target)
        ) {
            showDatePicker = false;
        }
    }

    onMount(() => {
        document.addEventListener("mousedown", handleClickOutside);
    });

    onDestroy(() => {
        document.removeEventListener("mousedown", handleClickOutside);
    });
    // props
    export let thereIsAvailable = ()  => false;
    export let isAllowed = () => true;
    export let selected = new Date();
    export let withInput = true
    export let showDatePickerAlways = false
    let options = {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
    };
    // state
    let date, month, year, showDatePicker,formattedDate;
    // so that these change with props
    $: {
        date = new Date(selected).getDate();
        month = new Date(selected).getMonth();
        year = new Date(selected).getFullYear();
        formattedDate = new Date(selected).toLocaleDateString("es-VE", options);
        console.log(formattedDate)
    }
    let datePickerPosition = "below"; // 'above' or 'below'
    let inputElement; // Reference for the input element

    // handlers
    const onFocus = () => {
        showDatePicker = true;
        calculateDatePickerPosition();
    };

    const next = () => {

        if (month === 11) {
            month = 0;
            year = year + 1;
        } else {

            month = month + 1;
        }
        if (withInput == false) {
            selected = new Date(selected).setMonth(month); // month is 1-indexed, so subtract 1
            selected = new Date(selected).setDate(1); // month is 1-indexed, so subtract 1
            selected = new Date(selected).setYear(year);
            dispatch("datechange", new Date(selected));
        }
    };

    const prev = () => {
        
        if (month === 0) {
            month = 11;
            year -= 1;
           
        } else {
            month -= 1;

        }

        if (withInput == false) {
            selected = new Date(selected).setMonth(month); // month is 1-indexed, so subtract 1
            selected = new Date(selected).setDate(1); // month is 1-indexed, so subtract 1
            selected = new Date(selected).setYear(year);
            dispatch("datechange", new Date(selected));
        }
    };

    const onDateChange = (d) => {
        if (!showDatePickerAlways) {
            showDatePicker = false;
        }
        dispatch("datechange", d.detail);
    };
    function calculateDatePickerPosition() {
        if (inputElement) {
            const rect = inputElement.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;
            const datePickerHeight = 400; // Adjust as necessary

            datePickerPosition =
                spaceBelow < datePickerHeight && spaceAbove >= datePickerHeight
                    ? "above"
                    : "below";
        }
    }

    onMount(() => {
        window.addEventListener("resize", calculateDatePickerPosition);
        return () =>
            window.removeEventListener("resize", calculateDatePickerPosition);
    });
    $: console.log({selected})
</script>

<div class="relative">
    {#if withInput }
        <input
            bind:this={inputElement}
            type="text"
            on:click={onFocus}
            value={formattedDate}
            class="px-2 py-2 w-[148px] bg-gray-200 rounded"
        />
        
    {/if}
    {#if showDatePicker || showDatePickerAlways}
        <div
            bind:this={datePickerElement}
            class="box absolute shadow-xl bg-white z-50 rounded-md pt-3"
            style="top: {datePickerPosition === 'above'
                ? `${-244}px`
                : '100%'}; left: 0;"
        >
            <div class="month-name">
                <div class="center">
                    <button class="text-xl" type="button" on:click={prev}>
                        <iconify-icon icon="iconamoon:arrow-left-2-bold"
                        ></iconify-icon>
                    </button>
                </div>
                <div class="center">{getMonthName(month)} {year}</div>
                <div class="center">
                    <button class="text-xl" type="button" on:click={next}>
                        <iconify-icon icon="iconamoon:arrow-right-2-bold"
                        ></iconify-icon>
                    </button>
                </div>
            </div>
            <Calender
                {month}
                {year}
                date={new Date(selected)}
                {withInput}
                {isAllowed}
                {thereIsAvailable}
                on:datechange={onDateChange}
            />
        </div>
    {/if}
</div>

<style>
    .relative {
        position: relative;
    }
    .box {
        position: absolute;
        top: 40px;
        left: 0px;
        display: inline-block;
    }

    .month-name {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin: 6px 0;
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
