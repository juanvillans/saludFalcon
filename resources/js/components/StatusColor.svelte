<script>
    function getStatusColor(status) {
        switch (status) {
            case 3: // admitido
                return "#F3BA2F";
                break;
            case 1: // Egreso: alta médica
                return "#397373";
                break;
            case 2:
                return "#818f8f";
                break;
            case 5: // fallecido
                return "#00000";
                break;
            case 4: // Ingreso
                return "#6a1ccd";
                break;
            default:
                break;
        }
    }
    export let status;
</script>
<div class=" gap-1 inline-block">

    <span  class="min-h-max inline-block">
        <span style={`color: ${getStatusColor(status.id)}; `}>
            {#if status.id == 1 || status.id == 2}

            <iconify-icon class="relative top-0.5"  icon="famicons:log-out" width="18" height="18"
            ></iconify-icon>
            {:else if status.id == 3}
            <iconify-icon
            icon="material-symbols-light:move-location-outline-rounded"
            width="15"
            height="15"
            ></iconify-icon>
            {:else if status.id == 4}
                <iconify-icon icon="icomoon-free:enter" width="15" height="15" class="relative top-1"
                ></iconify-icon>
                {:else if status.id == 5}
                <iconify-icon icon="mdi:cross" width="24" height="24"></iconify-icon>
                
           {/if}
        </span>
       {status.name}
    </span>
</div>
