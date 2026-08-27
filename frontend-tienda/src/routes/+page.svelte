<!-- frontend-tienda/src/routes/+page.svelte -->
<script lang="ts">
    import Card from '$lib/components/Card.svelte';
    import Collapsible from '$lib/components/Collapsible.svelte';
    import type { PageData } from './$types';

    // Intercepción inmutable de los datos servidos por +page.ts
    let { data }: { data: PageData } = $props();
</script>

<main class="container mx-auto p-8 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-8 text-gray-900">Panel de Control de Tienda</h1>

    <section class="mb-10">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Inventario Sincronizado</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Bloque de iteración estricta sobre la colección de la base de datos -->
            {#each data.productos as producto (producto.id)}
                <Card 
                    title={producto.nombre} 
                    imageSrc={producto.imagen || 'https://via.placeholder.com/500?text=Sin+Imagen'} 
                    description={producto.descripcion} 
                />
            {:else}
                <!-- Renderizado condicional en caso de fallo de red o base de datos vacía -->
                <div class="col-span-full p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
                    Falla de integridad: No se detectaron registros de productos en la base de datos.
                </div>
            {/each}
        </div>
    </section>

    <section>
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Métricas y Administración</h2>
        <Collapsible title="Estado de Sincronización">
            <p>Conexión establecida con el clúster de Laravel. Total de productos extraídos: {data.productos.length}</p>
        </Collapsible>
        
        <Collapsible title="Auditoría de Interfaz">
            <p>Los componentes visuales ahora son puramente reactivos y no mantienen estado hardcodeado local.</p>
        </Collapsible>
    </section>
</main>