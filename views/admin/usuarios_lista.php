<?php require __DIR__ . '/../assets/header.php'; ?>
<section class="admin-usuarios">
    <h1>Gestión de usuarios</h1>
    <p>Administrá roles y estados de las cuentas. Las cuentas desactivadas y sus pedidos se conservan en el sistema.</p>
    <?php if (!empty($mensaje)): ?><p class="aviso-admin" role="status"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <div class="tabla-usuarios-contenedor">
        <table class="tabla-usuarios">
            <thead>
                <tr><th>Usuario</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Pedidos</th><th>Registro</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <?php $esActual = (int)$usuario['id'] === $idActual; ?>
                <tr class="<?= (int)$usuario['activo'] === 1 ? '' : 'usuario-inactivo' ?>">
                    <td><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido'], ENT_QUOTES, 'UTF-8') ?><?= $esActual ? ' (vos)' : '' ?></td>
                    <td><?= htmlspecialchars($usuario['correo'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><span class="rol-usuario"><?= htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8') ?></span></td>
                    <td><?= (int)$usuario['activo'] === 1 ? 'Activo' : 'Desactivado' ?></td>
                    <td><?= (int)$usuario['cantidad_pedidos'] ?></td>
                    <td><?= !empty($usuario['fecha_registro']) ? htmlspecialchars(date('d/m/Y', strtotime($usuario['fecha_registro'])), ENT_QUOTES, 'UTF-8') : '—' ?></td>
                    <td class="acciones-usuario">
                        <form action="index.php?pagina=admin-usuario-rol" method="post">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin_usuarios'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= (int)$usuario['id'] ?>">
                            <label class="solo-lectores" for="rol-<?= (int)$usuario['id'] ?>">Rol</label>
                            <select id="rol-<?= (int)$usuario['id'] ?>" name="rol">
                                <option value="cliente" <?= $usuario['rol'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                                <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="submit" <?= $esActual ? 'disabled title="No podés modificar tu propio rol"' : '' ?>>Guardar rol</button>
                        </form>
                        <form action="index.php?pagina=admin-usuario-activo" method="post">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin_usuarios'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= (int)$usuario['id'] ?>">
                            <input type="hidden" name="activo" value="<?= (int)$usuario['activo'] === 1 ? 0 : 1 ?>">
                            <button type="submit" class="<?= (int)$usuario['activo'] === 1 ? 'boton-desactivar' : 'boton-reactivar' ?>" <?= $esActual ? 'disabled title="No podés desactivar tu propia cuenta"' : '' ?>>
                                <?= (int)$usuario['activo'] === 1 ? 'Desactivar' : 'Reactivar' ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../assets/footer.php'; ?>
