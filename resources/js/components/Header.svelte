<script>
    // import { authHandlers } from "../../stores/authStore";
    import { inertia, page } from "@inertiajs/svelte";

    let pageName = "";
    $: userNav = false;

    function toggleNavUser() {
        userNav = !userNav;
    }

    function clickOutside(element, callbackFunction) {
        function onClick(event) {
            if (!element.contains(event.target)) {
                callbackFunction();
            }
        }

        document.body.addEventListener("click", onClick);

        return {
            update(newCallbackFunction) {
                callbackFunction = newCallbackFunction;
            },
            destroy() {
                document.body.removeEventListener("click", onClick);
            },
        };
    }
</script>

<header class="  w-full text-color1">
    <nav
        class="flex justify-between items-center w-full max-h-32 py-2 gap-3 text-sm px-3 md:px-5"
    >
        <span class="flex gap-1 items-center">
            <a
                href="/dashboard"
                use:inertia
                class="text-sm hidden md:inline font-bold"
                >{$page.component.replace("Dashboard/", "").toUpperCase()}</a
            >
        </span>
        <!-- <div class="flex bg-color2  md:min-w-72 rounded-full items-center">
                <iconify-icon icon="cil:search" class="mx-2" />
                <input
                    type="search"
                    placeholder="Buscar"
                    name=""
                    id=""
                    class="bg-color2 px-3 py-2 rounded-full  w-full"
                />
            </div> -->

        <!-- svelte-ignore a11y-click-events-have-key-events -->
        <div
            class="cursor-pointer flex gap-2 text-right items-center relative"
            use:clickOutside={() => {
                userNav = false;
            }}
        >
            <div
                class="hidden md:block relative -top-1"
                on:click={toggleNavUser}
            >
                <b>
                    <iconify-icon
                        icon="solar:alt-arrow-down-broken"
                        class="text-xl relative top-1"
                    />
                    {$page.props.auth.name}
                    {$page.props.auth.last_name}
                </b>
                <!-- <p>juanvillans@gmail.com</p> -->
            </div>
            <div
                class="h-10 aspect-square rounded-full bg-green1 z-20 flex justify-center items-center text-green3"
                on:click={toggleNavUser}
                class:blueShadow={userNav}
            >
                <iconify-icon
                    icon="solar:user-broken"
                    class="text-2xl"
                    class:text-green4={userNav}
                />
            </div>
            {#if userNav}
                <div
                    class="absolute  w-fit rounded-lg flex items-center flex-col bg-color1 overflow-hidden  z-50 top-10 right-3 rounded-tr-none text-gray-100 shadow-xl"
                >
                    <a
                        href="/admin/cambiar-contraseña"
                        use:inertia
                        class="p-2 py-3 px-4  w-full cursor-pointer hover:underline hover:text-gray-50 block whitespace-nowrap"
                        >Cambiar contraseña</a
                    >
                    <a
                        href="/admin/logout"
                        class="p-3 pt-1 mt-2 bg-gray-300 text-dark  w-full cursor-pointer hover:underline hover:text-gray-500 block whitespace-nowrap"
                        > <iconify-icon class="text-2xl  relative top-1.5" icon="solar:logout-line-duotone"></iconify-icon> Cerrar sesión</a
                    >
                    <!-- <button on:click={authHandlers.logout} class="p-4 flex items-center text-rigth w-full justify-end hover:text-green4 gap-2 hover:font-bold hover:underline" >Cerrar sesión <iconify-icon icon="solar:logout-line-duotone" class="text-xl"></iconify-icon></button> -->
                </div>
            {/if}
        </div>
    </nav>
</header>

<style>
    header {
        /* z-index: 100 !important; */
        /* box-shadow: 1px 1px 10px black; */
    }
</style>