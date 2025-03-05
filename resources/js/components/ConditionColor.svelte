<script>
    function getConditionColor(status) {
        switch (status) {
            case "1":
                return "#F3BA2F";
                break;
            case "2":
                return "#397373";
                break;
            case "3":
                return "#BF0404";
                break;
            case "4":
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
   
>
    <div class={`w-2 aspect-square rounded-full`}  style={`background-color: ${getConditionColor(status)}; `}></div>
    {status}
</span>
