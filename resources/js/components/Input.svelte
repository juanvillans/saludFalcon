<!-- FloatingInput.svelte -->
<script>
    export let value = "";
    export let label = "";
    export let required = "";
    export let placeholder = "";
    export let type = "text";
    export let classes = false
    export let theme = "ligtht"
    export let min = ""
    export let max = ""
    export let inputClasses= false
    export let labelClasses= false
    export let disabled=false
    export let readOnly=false
    export let error = false;
    export let name = ""

</script>

<style>
     input:read-only, select:disabled {
        background-color: transparent !important;
    }
    input,
    textarea,
    select {
        padding-block: 8px;
        border-radius: 5px;
    }
    
    
    input:focus,
    textarea:focus,
    select:focus {
        outline: none;
    }
    .parent_div:before {
        content: "";
        display: block;
        position: absolute;
        transition: 0.3s cubic-bezier(0.39, 0.575, 0.565, 1);
        bottom: 1px;
        left: 2.5px;
        border-radius: 4px;
        height: 2px;
        background: linear-gradient(80deg, #C9EBF2 9%, #2477BF 93%);
        background-color: aqua;
        width: 0%;
    }
    .parent_div:focus-within::before {
        width: 98%;
    }
    input:disabled, select:disabled {
        opacity: .8;
        background-color: white;
    }
    .bg-error {
        background-color: rgb(255, 147, 147);
    }
</style>

                
<div class={`text-left   ${theme == "dark" ? "bg-color1 text-gray-100" : ''}${classes ? classes : "mt-3 w-full"} ${type === "textarea" ? "mt-1" : ""}`} >
    <label for={label} class={` w-full text-gray-900  ${theme == "dark" ? "bg-color1 text-gray-100" : ''} ${labelClasses || "font-medium"}`} {placeholder}>{label}</label>
    <div class="relative w-full parent_div">
        {#if type === "textarea"}
            <textarea readonly={readOnly} class={`${inputClasses}  overflow-y-auto bg-gray-200 w-full p-3 py-4`} bind:value id={label} required={required} ></textarea>
        {:else if type === "select"}
                
                
                <select readonly={readOnly}  disabled={disabled} on:focus class={`w-full ${inputClasses ? inputClasses : " p-2 bg-gray-200 "} ${error ? ' border-b border-red bg-error' : ""}`} id={label} bind:value  required={required} on:change > 
                <slot></slot>
            </select>
        {:else}
            <input
                bind:value
                {...{ type }}
                placeholder={placeholder}
                id={label}
                readonly={readOnly}
                class={`w-full ${inputClasses ? inputClasses : "p-2 bg-gray-200 "} ${error ? ' border-b border-red bg-error' : ""}`}
                required={required}
                disabled={disabled}
                defaultValue={'xxx'}
                max={max}
                name={name}
                min={min}
                on:wheel
                on:input
                on:change
            />
        {/if}
        {#if error}
            <div class="text-red  pt-1 px-2">
                <span>{error}</span>
            </div>
        {/if}
    </div>
</div>

<!-- <input  class="bg-color1 block px-2.5 pb-2.5 pt-4 w-full text-sm   rounded-lg  appearance-none dark:text-white  focus:outline-none focus:ring-0  peer " placeholder=" "   />
    <label  class="absolute text-sm   duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] rounded-xl px-2 peer-focus:px-2 text-white peer-focus:text-color2 peer-focus:dark:text-color2  peer-placeholder-shown:text-color2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-4 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 ">{label}</label> -->

