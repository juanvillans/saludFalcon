<script>
    import { onMount, onDestroy } from "svelte";
    import LeftNav from "./LeftNav.svelte";
    import Header from "./Header.svelte";
    import Footer from "./Footer.svelte";
	import 'iconify-icon';
	import { navStatus } from '../stores/navStatus';
    import {  page } from "@inertiajs/svelte";
    import Alert from "./Alert.svelte";
    let mainWidth;
    let screenWidth;

    function updateWidth() {
        // mainWidth = `width:${screenWidth}%`
        screenWidth =  window.innerWidth 
        // $: mainWidth = main.offsetWidth;
        // if (screenWidth > 768) {
            mainWidth =`width:${screenWidth - $navStatus.navWidth-20}px`
        // }
    }

    onMount(() => {
        updateWidth(); // Set the initial width
        window.addEventListener("resize", updateWidth);
    });

    onDestroy(() => {
        window.removeEventListener("resize", updateWidth);
    });
    // if ($page.props.flash.message) {
    //   <div class="alert">{$page.props.flash.message}</div>
    // }
</script>
<Alert />

<svelte:head>

</svelte:head>
<div class="bg-gray-700 h-screen">

    <section class="bg-gray-200 h-full  dashboard_container p-2 rounded-2xl" class:menuStatus-false={$navStatus.isContracted}>
        
        <Header />
        <div class=" main_and_footer_container  " bing:this="main_and_footer_container" scroll-region>
            <main  style={mainWidth}  class={`mx-auto  main_dashboard   relative md:px-10 duration-100 pb-10 pt-3`} >
                <slot />
            </main>
            <Footer />
    
        </div>
        <LeftNav />
    </section>
</div>
