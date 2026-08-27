<script lang="ts">
    
    let { title, imageSrc, description }: {
        title: string;
        imageSrc: string;
        description: string;
    } = $props();
</script>

<article class="max-w-sm rounded overflow-hidden shadow-lg bg-white border border-gray-200">
    
    <img 
        class="w-full h-48 object-cover" 
        src={imageSrc} 
        alt={`Imagen representativa de ${title}`}
        loading="lazy"
        decoding="async"
    />
    <div class="px-6 py-4">
        <h2 class="font-bold text-xl mb-2 text-gray-800">{title}</h2>
        <p class="text-gray-700 text-base">{description}</p>
    </div>
</article>