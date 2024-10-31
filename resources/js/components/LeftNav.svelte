<script>
    // import { page } from "$app/stores";

    // $: url = $page.route.id
    // console.log(url)
    import { navStatus, toggleMenu } from "../stores/navStatus.js";
    import { inertia, page } from "@inertiajs/svelte";

    const navPages = [
        {
            icon: "healthicons:doctor-male",
            href: "/admin/usuarios",
            name: "Usuarios",
        },
        {
            icon: "icon-park-outline:history-query",
            href: "/admin/historial-medico",
            name: "Historial Médico",
        },
        
    ];

</script>

<nav
    on:mouseenter={toggleMenu}
    on:mouseleave={toggleMenu}
    class="left_nav flex md:block items-center bg-color1 text-white h-14 md:h-full relative top-2 md:top-0 rounded-3xl overflow-hidden"
>
    <!-- <button on:click={() => toggleMenu()} class="hidden md:block burger_icon  items-center pt-1  text-center text-2xl text-gray-300 hover:text-color4 bg-color2 bg-opacity-20 ">
    
        <iconify-icon class="mb-0 pb-0" icon="majesticons:menu-expand-left-line"></iconify-icon>
    </button> -->
    <div
        class="hidden md:block burger_icon items-center pt-1 text-center text-2xl text-gray-300 hover:text-color4 bg-color2 bg-opacity-20"
    ></div>

    <ul
        class="flex justify-around items-center w-full md:flex-col
    md:items-start md:justify-normal md:p-2 md:gap-1 md:[&>*]:w-full"
    >
        {#each navPages as navPage}
            <li>
                <a
                    href={navPage.href}
                    use:inertia
                    name={navPage.name}
                    class="hover:text-color4 whitespace-nowrap z-10 rounded-md flex gap-1 md:gap-2 items-center p-2 max-h-9 h-9"
                    class:active={$page.url.startsWith(navPage.href)}
                    ><iconify-icon
                        class="text-xl md:text-lg"
                        icon={navPage.icon}
                    /><span class:hidden={!$page.url.startsWith(navPage.href)} class="label_link text-xs md:text-base md:block"
                        >{navPage.name}
                    </span>
                   
                </a>
            </li>
        {/each}
    </ul>
</nav>

<style>
    .active {
        background-color: #6595bf;
        border-radius: 6px;
        font-weight: bold;
        color: white;
    }
</style>
