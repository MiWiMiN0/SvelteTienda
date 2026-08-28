
<script lang="ts">
//se usa usar TypeScript "ts"

//"onMount" espera a que la página este completamente lista y cargada en el navegador
  import { onMount , tick } from 'svelte';


  // "$state" interfaz reacciona cuando cambia
  let productos: any[] = $state([]);

  //----------------------  ALAN  -------------------
  let clientes: any[] = $state([]);
  let facturas: any[] = $state([]);
  let rol = $state('');
  let clienteSeleccionado = $state('');
  let fechaDesde = $state('');
  let mensajeFacturas = $state('');

  //-----------------------  LUIS --------------------------
  let usuarios: any[] = $state([]);
  let cargando = $state(true);
  let error = $state('');
  let usuarioActual: any = $state({});
  let modalEl: HTMLDivElement;
  let bsModal: any;
  //-----------------------  JESUS --------------------------

  onMount(async () => {
    //"fetch" pide la información que esta guardada en la base de datos mysql
    const respuesta = await fetch('http://localhost:8000/api/productos');
    // "await" espera a que la respuesta llegue y luego la convierte en un objeto JSON para la variable productos ".data"
    productos = (await respuesta.json()).data;
//----------------------  ALAN  -------------------
    try {
      const respuestaClientes = await fetch('http://localhost:8000/api/cliente', {
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${localStorage.getItem('token') ?? ''}`
        }
      });

      clientes = await respuestaClientes.json();
      facturas = clientes.flatMap((cliente) => cliente.facturas ?? []);
    } catch (error) {
      mensajeFacturas = error instanceof Error ? error.message : 'No se pudieron cargar las facturas.';
    }

    //-----------------------  LUIS --------------------------
    await cargarUsuarios();
    bsModal = new (window as any).bootstrap.Modal(modalEl);
  });

  async function cargarUsuarios() {
    cargando = true;
    try {
      const respuesta = await fetch('http://localhost:8000/api/usuarios');
      if (!respuesta.ok) throw new Error('Error al listar usuarios');
      usuarios = await respuesta.json();
    } catch (e) {
      error = e instanceof Error ? e.message : 'Error al cargar usuarios';
    } finally {
      cargando = false;
    }
  }

  function abrirModalVer(usuario: any) {
    usuarioActual = { ...usuario };
    bsModal.show();
  }
  //-----------------------  ALAN --------------------------

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
  //-------------------------------------------------
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

<!-- //////////////////////////////   ALAN   //////////////////////////////////////// -->

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

<!----------------------------- LUIS ----------------------------->
<div class="container mt-4 mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Listado de Usuarios</h2>
  </div>

  {#if error}
    <div class="alert alert-danger">{error}</div>
  {/if}

  {#if cargando}
    <p>Cargando usuarios...</p>
  {:else}
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Rol</th>
          <th class="text-end">Acción</th>
        </tr>
      </thead>
      <tbody>
        {#each usuarios as usuario (usuario.id)}
          <tr>
            <td>{usuario.id}</td>
            <td>{usuario.name}</td>
            <td>{usuario.email}</td>
            <td>{usuario.role?.nombre ?? 'Sin rol'}</td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-primary" onclick={() => abrirModalVer(usuario)}>
                Ver usuario
              </button>
            </td>
          </tr>
        {:else}
          <tr>
            <td colspan="5" class="text-center">No hay usuarios registrados</td>
          </tr>
        {/each}
      </tbody>
    </table>
  {/if}
</div>

<!-- Modal Bootstrap de solo lectura -->
<div class="modal fade" tabindex="-1" bind:this={modalEl} aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Información del usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nombre:</strong> {usuarioActual.name}</p>
        <p><strong>Correo:</strong> {usuarioActual.email}</p>
        <p><strong>Rol:</strong> {usuarioActual.role?.nombre ?? 'Sin rol'}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

