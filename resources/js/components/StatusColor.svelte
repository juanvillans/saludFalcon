<script>
    function getStatusColor(status) {
        switch (status) {
            case 3: // transferido
                return "#f3ba2f";
                break;
            case 1: // Egreso: alta médica
                return "#397373";
                break;
            case 2:
                return "#738884";
                break;
            case 5: // fallecido
                return "#00000";
                break;
            case 4: // Ingreso
                return "#6a1ccd";
                break;
                case 6: // permanece
                return "#bbbbbb";
                break;
            default:
                break;
        }
    }
    export let status;
</script>
<div class=" gap-1 inline-block">

    <span  class="min-h-max inline-block">
        <span style={`color: ${getStatusColor(status.id)}; `} class="relative pl-5">
            {#if status.id == 1 || status.id == 2}

            <iconify-icon class="absolute -left-0 top-0.5"  icon="famicons:log-out" width="18" height="18"
            ></iconify-icon>
            {:else if status.id == 3}
            
            <iconify-icon  class="font-bold absolute -left-1.5 " icon="material-symbols:move" width="24" height="24"></iconify-icon>
            {:else if status.id == 4}
                <iconify-icon icon="icomoon-free:enter" width="15" height="15" class=" absolute -left-1 top-1"
                ></iconify-icon>
                {:else if status.id == 5}
                <iconify-icon  class=" absolute -left-1"  icon="mdi:cross" width="24" height="24"></iconify-icon>
                
                {:else if status.id == 6}
                <iconify-icon class=" absolute -left-2" icon="mdi:keep-outline" width="24" height="24"></iconify-icon>
           {/if}
        </span>
       {status.name}
    </span>
</div>
