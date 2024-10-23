<script>
       function getStatusColor(status) {
        switch (status) {
            case "Permanencia":
                return "#F3BA2F";
                break;
            case "Alta":
                return "#397373";
                break;
            case "Fallecido":
                return "#BF0404";
                break;
            case "Remitido":
                return "#6a1ccd";
                break;
            default:
                break;
        }
    }
 export let status;
</script>
<span
    class={`inline-block px-1 py-0.5 rounded-md text-white`}
    style={`background-color: ${getStatusColor(status)}; `}
>
    {status}
</span>
