<?php
require_once __DIR__ . '/classes/Cliente.php';

$cliente = new Cliente();

// ---- Página actual (para la paginación) ----
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1) {
    $pagina = 1;
}

// ---- Datos para pintar la tabla ----
$listaClientes = $cliente->listarClientes($pagina);
$totalPaginas  = $cliente->totalPaginas();

// ---- Si viene "editar", se precarga el formulario ----
$datosEditar = null;
if (isset($_GET['editar'])) {
    $datosEditar = $cliente->obtenerPorId((int) $_GET['editar']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ediciones Fares - Clientes</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <!-- ===== Navbar ===== -->
    <div class="navbar">
        <h1>Ediciones Fares</h1>
        <ul class="menu">
            <li><a href="frmcliente.php">Principal</a></li>
            <li>Libros &#9662;</li>
            <li><a href="datosProductos.php">Inventario</a></li>
            <li>Contacto</li>
        </ul>
    </div>

    <div class="contenedor">

        <!-- ===== Panel: Formulario ===== -->
        <div class="panel-formulario">
            <div class="titulo">Ingresar datos del cliente</div>
            <form action="guardar.php" method="POST">
                <input type="hidden" name="id_cliente"
                       value="<?= $datosEditar['id_cliente'] ?? '' ?>">

                <div class="campo">
                    <label>Nombre</label>
                    <input type="text" name="nombre" placeholder="Nombre del cliente"
                           value="<?= htmlspecialchars($datosEditar['nombre'] ?? '') ?>" required>
                </div>

                <div class="campo">
                    <label>Dirección</label>
                    <input type="text" name="direccion" placeholder="Dirección"
                           value="<?= htmlspecialchars($datosEditar['direccion'] ?? '') ?>" required>
                </div>

                <div class="fila-doble">
                    <div class="campo">
                        <label>Teléfono residencial</label>
                        <input type="text" name="telefono_residencial" placeholder="Teléfono residencial"
                               value="<?= htmlspecialchars($datosEditar['telefono_residencial'] ?? '') ?>" required>
                    </div>
                    <div class="campo">
                        <label>Celular</label>
                        <input type="text" name="celular" placeholder="Teléfono celular"
                               value="<?= htmlspecialchars($datosEditar['celular'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="campo">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Correo electrónico"
                           value="<?= htmlspecialchars($datosEditar['email'] ?? '') ?>" required>
                </div>

                <button type="submit" class="btn-guardar">Guardar</button>
            </form>
        </div>

        <!-- ===== Panel: Tabla + Paginación ===== -->
        <div class="panel-tabla">
            <div class="titulo">Lista de clientes</div>

            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Teléfono residencial</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($listaClientes) > 0): ?>
                    <?php foreach ($listaClientes as $fila): ?>
                        <tr>
                            <td><?= $fila['id_cliente'] ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['telefono_residencial']) ?></td>
                            <td>
                                <div class="acciones">
                                    <a class="btn-accion btn-editar"
                                       href="frmcliente.php?editar=<?= $fila['id_cliente'] ?>&pagina=<?= $pagina ?>"
                                       title="Editar">&#9998;</a>
                                    <a class="btn-accion btn-eliminar"
                                       href="eliminar.php?id=<?= $fila['id_cliente'] ?>&pagina=<?= $pagina ?>"
                                       title="Eliminar"
                                       onclick="return confirm('¿Desea eliminar este cliente?');">&#128465;</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="sin-registros">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <!-- ===== Paginación ===== -->
            <?php if ($totalPaginas > 1): ?>
                <div class="paginacion">
                    <?php if ($pagina > 1): ?>
                        <a href="frmcliente.php?pagina=<?= $pagina - 1 ?>">&laquo;</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <?php if ($i == $pagina): ?>
                            <span class="activa"><?= $i ?></span>
                        <?php else: ?>
                            <a href="frmcliente.php?pagina=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($pagina < $totalPaginas): ?>
                        <a href="frmcliente.php?pagina=<?= $pagina + 1 ?>">&raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
