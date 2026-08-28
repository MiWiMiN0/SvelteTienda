
<script lang="ts">
//se usa usar TypeScript "ts"

//"onMount" espera a que la página este completamente lista y cargada en el navegador
  import { onMount } from 'svelte';
  // "$state" interfaz reacciona cuando cambia
  let productos: any[] = $state([]);

  onMount(async () => {
    //"fetch" pide la información que esta guardada en la base de datos mysql
    const respuesta = await fetch('http://localhost:8000/api/productos');
    // "await" espera a que la respuesta llegue y luego la convierte en un objeto JSON para la variable productos ".data"
    productos = (await respuesta.json()).data;
  });
</script>

<h1>Productos Tienda</h1>
<ul class="productos">
<!--"each" recorre la variable productos y crea un elemento de lista para cada producto -->
  {#each productos as producto}
    <li class="producto">
      <img src={producto.imagen} alt={producto.nombre_producto} width="200" />
      
      <details>
      <!--"details" crea un elemento desplegable que muestra información adicional sobre el producto  (no prestar atencio prueba)-->
      
        <summary>{producto.nombre_producto}</summary>
        <!--"summary" crea un resumen del producto que se muestra cuando el elemento "details" está desplegado -->
        <!-- <p>{producto.descripcion}</p> -->
        <strong>${producto.precio_unitario}</strong>
        <h2>Stock: {producto.stock}</h2>
        <h2>IVA: {producto.iva_porcentaje}%</h2>
      </details>
    </li>
  {/each}
</ul>
