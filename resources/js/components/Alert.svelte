<script>
	import { alertInfo } from '../stores/alertStore';
	const clasesByTypeAlert = {
		success: 'bg-green',
		info: 'bg-white',
		error: 'bg-red'
	};
</script>

<div style="z-index: 100;"
	class={` fixed top-16  duration-300 bg-opacity-90 z-50 text-white max-w-[400px] ${
		clasesByTypeAlert[$alertInfo.type]
	} ${$alertInfo.isOpen ? 'opacity-1 px-4 py-2 right-10 rounded-md ' : 'opacity-0 -right-20 '}`}
>
	<b>{$alertInfo.message}</b>
</div>
