<?php
require_once __DIR__ . '/config/Conexion.php';

class datosProductos
{
    const TABLA = 'inventario';

    private $codproducto;
    private $nom_producto;
    private $costoproducto;
    private $porc_ventapro;
    private $precio_ventapro;
    private $imagenpro;
    private $stockpro;
    private $fechapro;

    public function __construct(
        $codproducto = null,
        $nom_producto = "",
        $costoproducto = 0.00,
        $porc_ventapro = 0,
        $precio_ventapro = 0.00,
        $imagenpro = "",
        $stockpro = 0,
        $fechapro = null
    ) {
        $this->codproducto = $codproducto;
        $this->nom_producto = $nom_producto;
        $this->costoproducto = $costoproducto;
        $this->porc_ventapro = $porc_ventapro;
        $this->precio_ventapro = $precio_ventapro;
        $this->imagenpro = $imagenpro;
        $this->stockpro = $stockpro;
        $this->fechapro = $fechapro;
    }

    public function get_codproducto() { return $this->codproducto; }
    public function get_nom_producto() { return $this->nom_producto; }
    public function get_costoproducto() { return $this->costoproducto; }
    public function get_porc_ventapro() { return $this->porc_ventapro; }
    public function get_precio_ventapro() { return $this->precio_ventapro; }
    public function get_imagenpro() { return $this->imagenpro; }
    public function get_stockpro() { return $this->stockpro; }
    public function get_fechapro() { return $this->fechapro; }

    public function set_codproducto($codproducto) { $this->codproducto = $codproducto; }
    public function set_nom_producto($nom_producto) { $this->nom_producto = $nom_producto; }
    public function set_costoproducto($costoproducto) { $this->costoproducto = $costoproducto; }
    public function set_porc_ventapro($porc_ventapro) { $this->porc_ventapro = $porc_ventapro; }
    public function set_precio_ventapro($precio_ventapro) { $this->precio_ventapro = $precio_ventapro; }
    public function set_imagenpro($imagenpro) { $this->imagenpro = $imagenpro; }
    public function set_stockpro($stockpro) { $this->stockpro = $stockpro; }
    public function set_fechapro($fechapro) { $this->fechapro = $fechapro; }

    public function guardarProducto()
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' 
            (nom_producto, costo, porc_venta, precio_venta, Imagen, stock, Fecha)
            VALUES(:producto, :pcosto, :pporc_venta, :pprecio_venta, :pImagen, :pStock, :pFecha)');

        $consulta->bindParam(':producto', $this->nom_producto);
        $consulta->bindParam(':pcosto', $this->costoproducto);
        $consulta->bindParam(':pporc_venta', $this->porc_ventapro);
        $consulta->bindParam(':pprecio_venta', $this->precio_ventapro);
        $consulta->bindParam(':pImagen', $this->imagenpro);
        $consulta->bindParam(':pStock', $this->stockpro);
        $consulta->bindParam(':pFecha', $this->fechapro);
        return $consulta->execute();
    }

    public function actualizarProducto()
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET nom_producto = :producto,
            costo = :pcosto, porc_venta = :pporc_venta, precio_venta = :pprecio_venta,
            Imagen = :pImagen, stock = :pStock, Fecha = :pFecha where codigo = :codpro');

        $consulta->bindParam(':producto', $this->nom_producto);
        $consulta->bindParam(':pcosto', $this->costoproducto);
        $consulta->bindParam(':pporc_venta', $this->porc_ventapro);
        $consulta->bindParam(':pprecio_venta', $this->precio_ventapro);
        $consulta->bindParam(':pImagen', $this->imagenpro);
        $consulta->bindParam(':pStock', $this->stockpro);
        $consulta->bindParam(':pFecha', $this->fechapro);
        $consulta->bindParam(':codpro', $this->codproducto);
        return $consulta->execute();
    }

    public static function actualizarStock($v_idpro, $canstock, $nuevacant)
    {
        $nuevo_stock = 0;
        if (isset($v_idpro, $canstock, $nuevacant)) {
            $nuevo_stock = $canstock + $nuevacant;
        } else {
            exit;
        }

        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET stock = :p_stock where codigo = :codpro');
        $consulta->bindParam(':p_stock', $nuevo_stock);
        $consulta->bindParam(':codpro', $v_idpro);
        $consulta->execute();
        return $consulta;
    }

    public static function todosProductos()
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('SELECT COUNT(*) FROM ' . self::TABLA);
        $consulta->execute();
        return $consulta->fetchColumn();
    }

    public static function consultarProductoCod($codproducto)
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' where codigo = :codpro');
        $consulta->bindParam(':codpro', $codproducto);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtenerPorId(int $id): array|false
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' WHERE codigo = :codpro');
        $consulta->bindParam(':codpro', $id, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch();
    }

    public static function listarProductos(int $pagina = 1, int $porPagina = 6): array
    {
        if ($pagina < 1) {
            $pagina = 1;
        }

        $inicio = ($pagina - 1) * $porPagina;
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' ORDER BY codigo ASC LIMIT :inicio, :registros');
        $consulta->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $consulta->bindValue(':registros', $porPagina, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public static function totalRegistros(): int
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('SELECT COUNT(*) AS total FROM ' . self::TABLA);
        $consulta->execute();
        $fila = $consulta->fetch();
        return (int) ($fila['total'] ?? 0);
    }

    public static function totalPaginas(int $porPagina = 6): int
    {
        return (int) ceil(self::totalRegistros() / $porPagina);
    }

    public function eliminarproducto()
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE codigo = :codpro');
        $consulta->bindParam(':codpro', $this->codproducto);
        return $consulta->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? null;
    $producto = new datosProductos(
        $codigo ? (int) $codigo : null,
        trim($_POST['nom_producto'] ?? ''),
        (float) ($_POST['costoproducto'] ?? 0),
        (int) ($_POST['porc_ventapro'] ?? 0),
        (float) ($_POST['precio_ventapro'] ?? 0),
        trim($_POST['imagenpro'] ?? ''),
        (int) ($_POST['stockpro'] ?? 0),
        trim($_POST['fechapro'] ?? date('Y-m-d'))
    );

    if (!empty($codigo)) {
        $producto->actualizarProducto();
    } else {
        $producto->guardarProducto();
    }

    header('Location: datosProductos.php');
    exit;
}

if (isset($_GET['eliminar'])) {
    $producto = new datosProductos();
    $producto->set_codproducto((int) $_GET['eliminar']);
    $producto->eliminarproducto();
    $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    header('Location: datosProductos.php?pagina=' . $pagina);
    exit;
}

$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina < 1) {
    $pagina = 1;
}

$listaProductos = datosProductos::listarProductos($pagina, 6);
$totalPaginas = datosProductos::totalPaginas(6);
$datosEditar = null;
if (isset($_GET['editar'])) {
    $datosEditar = datosProductos::obtenerPorId((int) $_GET['editar']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ediciones Fares - Inventario</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
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
        <div class="panel-formulario">
            <div class="titulo"><?= $datosEditar ? 'Editar producto' : 'Ingresar datos del producto' ?></div>
            <form action="datosProductos.php" method="POST">
                <input type="hidden" name="codigo" value="<?= $datosEditar['codigo'] ?? '' ?>">

                <div class="campo">
                    <label>Nombre del producto</label>
                    <input type="text" name="nom_producto" value="<?= htmlspecialchars($datosEditar['nom_producto'] ?? '') ?>" placeholder="Nombre del producto" required>
                </div>

                <div class="campo">
                    <label>Costo</label>
                    <input type="number" step="0.01" name="costoproducto" value="<?= htmlspecialchars($datosEditar['costo'] ?? '') ?>" placeholder="Costo" required>
                </div>

                <div class="fila-doble">
                    <div class="campo">
                        <label>% venta</label>
                        <input type="number" name="porc_ventapro" value="<?= htmlspecialchars($datosEditar['porc_venta'] ?? '') ?>" placeholder="%" required>
                    </div>
                    <div class="campo">
                        <label>Precio venta</label>
                        <input type="number" step="0.01" name="precio_ventapro" value="<?= htmlspecialchars($datosEditar['precio_venta'] ?? '') ?>" placeholder="Precio venta" required>
                    </div>
                </div>

                <div class="campo">
                    <label>Imagen</label>
                    <input type="text" name="imagenpro" value="<?= htmlspecialchars($datosEditar['Imagen'] ?? '') ?>" placeholder="Ruta de la imagen">
                </div>

                <div class="fila-doble">
                    <div class="campo">
                        <label>Stock</label>
                        <input type="number" name="stockpro" value="<?= htmlspecialchars($datosEditar['stock'] ?? 0) ?>" required>
                    </div>
                    <div class="campo">
                        <label>Fecha</label>
                        <input type="date" name="fechapro" value="<?= htmlspecialchars($datosEditar['Fecha'] ?? date('Y-m-d')) ?>" required>
                    </div>
                </div>

                <button type="submit" class="btn-guardar"><?= $datosEditar ? 'Actualizar' : 'Guardar' ?></button>
            </form>
        </div>

        <div class="panel-tabla">
            <div class="titulo">Lista de productos</div>

            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Costo</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($listaProductos) > 0): ?>
                    <?php foreach ($listaProductos as $fila): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['codigo']) ?></td>
                            <td><?= htmlspecialchars($fila['nom_producto']) ?></td>
                            <td>$<?= number_format((float) $fila['costo'], 2, ',', '.') ?></td>
                            <td>$<?= number_format((float) $fila['precio_venta'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($fila['stock']) ?></td>
                            <td><?= htmlspecialchars($fila['Fecha']) ?></td>
                            <td>
                                <div class="acciones">
                                    <a class="btn-accion btn-editar"
                                       href="datosProductos.php?editar=<?= $fila['codigo'] ?>&pagina=<?= $pagina ?>"
                                       title="Editar">&#9998;</a>
                                    <a class="btn-accion btn-eliminar"
                                       href="datosProductos.php?eliminar=<?= $fila['codigo'] ?>&pagina=<?= $pagina ?>"
                                       title="Eliminar"
                                       onclick="return confirm('¿Desea eliminar este producto?');">&#128465;</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="sin-registros">No hay productos registrados.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <?php if ($totalPaginas > 1): ?>
                <div class="paginacion">
                    <?php if ($pagina > 1): ?>
                        <a href="datosProductos.php?pagina=<?= $pagina - 1 ?>">&laquo;</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <?php if ($i == $pagina): ?>
                            <span class="activa"><?= $i ?></span>
                        <?php else: ?>
                            <a href="datosProductos.php?pagina=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($pagina < $totalPaginas): ?>
                        <a href="datosProductos.php?pagina=<?= $pagina + 1 ?>">&raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
