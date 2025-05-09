<script> 
    export let isSuccess = true 

</script>
<header class="flex items-center justify-center mt-5 gap-3">
    <img
        src="/img/logoBlue.svg"
        alt=""
        srcset=""
        class="1/2 w-10"
    />

    <h4>SALUDFALCÓN.COM</h4>
</header>
{#if isSuccess}
    <p class="flex items-center justify-center gap-2 mt-2">
        <iconify-icon icon="icon-park-outline:success" width="48" height="48"></iconify-icon> Su cita fue cancelada éxitosamente
    </p>
{:else }

<p class="flex items-center justify-center gap-2 mt-2 text-red">
    Hubo un error al intentar eliminar la cita, vuelvalo a intentar
</p>
{/if}
