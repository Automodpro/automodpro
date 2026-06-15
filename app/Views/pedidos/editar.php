<?php

$pedido = $pedido ?? [];
$estados = $estados ?? ['pendiente', 'aprobado', 'en_proceso', 'completado', 'cancelado'];

ob_start();
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-pencil"></i> Editar pedido #<?= esc($pedido['id'] ?? '') ?></h2>
            <p>Actualiza estado, total y notas</p>
        </div>
        <a href="<?= base_url('pedidos/ver/' . ($pedido['id'] ?? '')) ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= esc(session('error')) ?></div>
        <?php endif; ?>

        <?php if (session('errores')): ?>
            <div class="alert alert-danger">
                <?php foreach (session('errores') as $e): ?>
                    <div><i class="bi bi-x-circle"></i> <?= esc($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('pedidos/actualizar/' . ($pedido['id'] ?? '')) ?>" method="post">
            <div class="grid-2 mb-4">
                <div class="form-group mb-0">
                    <label class="form-label">Estado <span class="required">*</span></label>
                    <select name="estado" class="form-select" required>
                        <?php $estadoActual = $pedido['estado'] ?? 'pendiente'; ?>
                        <?php foreach ($estados as $e): ?>
                            <option value="<?= esc($e) ?>" <?= ($estadoActual === $e ? 'selected' : '') ?>><?= esc(ucfirst(str_replace('_', ' ', (string)$e))) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Total <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="total" class="form-control" required value="<?= esc($pedido['total'] ?? 0) ?>">
                        <span class="input-group-text">COP</span>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Notas</label>
                <textarea name="notas" class="form-control" rows="4"><?= esc($pedido['notas'] ?? '') ?></textarea>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary btn-lg px-5"><i class="bi bi-save"></i> Actualizar</button>
            <a href="<?= base_url('pedidos/ver/' . ($pedido['id'] ?? '')) ?>" class="btn btn-outline-secondary btn-lg px-4 ms-2">Cancelar</a>
        </form>

    </div>
</div>

<?php
$content = ob_get_clean();
echo view('layout/main', ['titulo' => 'Editar Pedido', 'content' => $content]);
?>

