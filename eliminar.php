<?php
require_once __DIR__ . '/classes/Cliente.php';

if (isset($_GET['id'])) {
    $cliente = new Cliente();
    $cliente->eliminar((int) $_GET['id']);
}

// Mantiene la página actual al volver del borrado
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
header("Location: frmcliente.php?pagina={$pagina}");
exit;
