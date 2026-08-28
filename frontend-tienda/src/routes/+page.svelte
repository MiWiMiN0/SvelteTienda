
<script lang="ts">
//se usa usar TypeScript "ts"

//"onMount" espera a que la página este completamente lista y cargada en el navegador
  import { onMount } from 'svelte';
  // "$state" interfaz reacciona cuando cambia
  let productos: any[] = $state([]);

  //-----------------------------------------
  let clientes: any[] = $state([]);
  let facturas: any[] = $state([]);
  let rol = $state('');
  let clienteSeleccionado = $state('');
  let fechaDesde = $state('');
  let mensajeFacturas = $state('');

  //-----------------------------------------

  onMount(async () => {
    //"fetch" pide la información que esta guardada en la base de datos mysql
    const respuesta = await fetch('http://localhost:8000/api/productos');
    // "await" espera a que la respuesta llegue y luego la convierte en un objeto JSON para la variable productos ".data"
    productos = (await respuesta.json()).data;

    try {
      const respuestaClientes = await fetch('http://localhost:8000/api/cliente', {
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${localStorage.getItem('token') ?? ''}`
        }
      });

      if (!respuestaClientes.ok) {
        throw new Error('Inicia sesión para consultar las facturas.');
      }

      clientes = await respuestaClientes.json();
      facturas = clientes.flatMap((cliente) => cliente.facturas ?? []);
    } catch (error) {
      mensajeFacturas = error instanceof Error ? error.message : 'No se pudieron cargar las facturas.';
    }
  });

  const consultarFacturas = () => {
    const fecha = fechaDesde ? new Date(fechaDesde).getTime() : 0;

    facturas = clientes
      .filter((cliente) => rol === 'admin' || cliente.cliente_id === Number(clienteSeleccionado))
      .flatMap((cliente) =>
        (cliente.facturas ?? []).filter((factura: any) =>
          !fecha || new Date(factura.fecha_emision).getTime() >= fecha
        )
      );
  };
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

<!-- /////////////////////////////////////////////////////////////////////// -->

<!-- consulta de facturas con rol, cliente y fecha con hora -->
<section class="facturas-nuevo">
  <h1>Facturas</h1>

  <form onsubmit={(evento) => { evento.preventDefault(); consultarFacturas(); }} class="formulario-facturas">
    <label for="rol">Rol:</label>
    <select id="rol" bind:value={rol} required>
      <option value="" disabled>Selecciona un rol</option>
      <option value="admin">Administrador</option>
      <option value="cliente">Cliente</option>
    </select>

    {#if rol === 'cliente'}
      <label for="cliente">Cliente:</label>
      <select id="cliente" bind:value={clienteSeleccionado} required>
        <option value="" disabled>Selecciona un cliente</option>
        {#each clientes as cliente}
          <option value={cliente.cliente_id}>{cliente.nombre} {cliente.apellido}</option>
        {/each}
      </select>
    {/if}

    <label for="fechaDesde">Facturas desde fecha y hora:</label>
    <input id="fechaDesde" type="datetime-local" bind:value={fechaDesde} />

    <button type="submit" disabled={!rol || (rol === 'cliente' && !clienteSeleccionado)}>
      Consultar facturas
    </button>
  </form>

  {#if mensajeFacturas}
    <p class="mensaje-facturas">{mensajeFacturas}</p>
  {:else if rol}
    <h2>Facturas encontradas: {facturas.length}</h2>
    <ul class="lista-facturas">
      {#each facturas as factura}
        <li>
          <strong>{factura.numero_factura}</strong>
          <span>{new Date(factura.fecha_emision).toLocaleString()}</span>
          <span>Total: ${factura.total_pagar}</span>
        </li>
      {:else}
        <li>No hay facturas para los filtros seleccionados.</li>
      {/each}
    </ul>
  {/if}
</section>
