<script lang="ts">
    import Card from '$lib/components/Card.svelte';
    import Collapsible from '$lib/components/Collapsible.svelte';
</script>

<main class="container mx-auto p-8 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-8 text-gray-900">Panel de Control de Tienda</h1>

    <section class="mb-10">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Sección de Tarjetas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Card 
                title="Producto Analizado" 
                imageSrc="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&auto=format&fit=crop&q=60" 
                description="Validación de carga diferida completada." 
            />
            <Card 
                title="Optimización de UI" 
                imageSrc="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=60" 
                description="Las métricas de red permanecen estables." 
            />
        </div>
    </section>

    <section>
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Elementos Colapsables</h2>
        <Collapsible title="Detalles Técnicos">
            <p>El estado reactivo modifica el DOM de forma eficiente sin dependencias externas pesadas.</p>
        </Collapsible>
        
        <Collapsible title="Auditoría de Seguridad">
            <p>No se exponen variables del lado del servidor en el paquete del cliente.</p>
        </Collapsible>
    </section>
</main>
