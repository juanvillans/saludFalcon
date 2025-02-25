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
                return "#818f8f"
                break;
            case 6: // fallecido
                return "#BF0404";
                break;
            case 4:
                return "#6a1ccd";
                break;
            default:
                break;
        }
    }
 export let status;
 
</script>
<span
    class={`inline-block px-1 py-0.5  border-l-4`}
    style={`border-color: ${getStatusColor(status.id)}; `}
>
    <div ></div>
    {status.name}
</span>
