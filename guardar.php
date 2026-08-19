<?php
require_once __DIR__ . '/classes/Cliente.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cliente = new Cliente();
    $cliente->setNombre(trim($_POST['nombre'] ?? ''));
    $cliente->setDireccion(trim($_POST['direccion'] ?? ''));
    $cliente->setTelefonoResidencial(trim($_POST['telefono_residencial'] ?? ''));
    $cliente->setCelular(trim($_POST['celular'] ?? ''));
    $cliente->setEmail(trim($_POST['email'] ?? ''));

    if (!empty($_POST['id_cliente'])) {
        // Modo edición: actualizar cliente existente
        $cliente->setId((int) $_POST['id_cliente']);
        $cliente->actualizar();
    } else {
        // Modo creación: nuevo cliente
        $cliente->guardar();
    }
}

header("Location: frmcliente.php");
exit;
