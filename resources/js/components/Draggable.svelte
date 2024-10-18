<script>
    import { onMount } from "svelte";
    export let left = 0;
    export let top = 0;
    let moving = false;
    let scrollPosition = 0;

    onMount(() => {
        const scrollableElement = document.querySelector(
            ".main_and_footer_container",
        );

        let previousScrollTop = scrollableElement.scrollTop;
        scrollableElement.addEventListener("scroll", (event) => {
            // Simulate a small delay to avoid rapid changes
            const currentScrollTop = scrollableElement.scrollTop;
            if (moving) {
               top = top - (previousScrollTop - currentScrollTop)
            }
            previousScrollTop = scrollableElement.scrollTop;
        });
    });
    function onMouseDown() {
        moving = true;
    }

    function onMouseMove(e) {
        if (moving) {
            left += e.movementX;
            top += e.movementY; // Adjust for scroll position
        }
        // console.log(main.scrollY, e.movementY)
    }

    function onMouseUp() {
        moving = false;
        left = 0;
        top = 0;
    }

    // 	$: console.log(moving);
</script>

<section
    class:duration-200={!moving}
    on:mousedown={onMouseDown}
    style="left: {left}px; top: {top}px;"
    class="draggable"
>
    <slot></slot>
</section>

<svelte:window on:mouseup={onMouseUp} on:mousemove={onMouseMove} />

<style>
    .draggable {
        user-select: none;
        cursor: move;
        border: solid 1px gray;
        position: absolute;
    }
</style>
