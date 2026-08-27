<script lang="ts">
    import { slide } from 'svelte/transition';
    import type { Snippet } from 'svelte';
    
    let { title, children }: { title: string; children: Snippet } = $props();
    
    
    let isOpen = $state<boolean>(false);

    function toggle() {
        isOpen = !isOpen;
    }
</script>

<div class="border border-gray-300 rounded-md mb-4 bg-white">
    <button 
        class="w-full px-4 py-3 text-left font-semibold text-gray-800 hover:bg-gray-50 focus:outline-none focus:ring focus:ring-blue-200 transition-colors"
        onclick={toggle}
        aria-expanded={isOpen}
    >
        {title}
        
        <span class="float-right">{isOpen ? '▲' : '▼'}</span>
    </button>

    {#if isOpen}
        
        <div 
            class="px-4 py-3 border-t border-gray-300 text-gray-600"
            transition:slide={{ duration: 300 }}
        >
            {@render children()} 
        </div>
    {/if}
</div>