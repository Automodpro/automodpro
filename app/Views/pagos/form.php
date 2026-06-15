<?php

$pedidos = $pedidos ?? [];

ob_start();
?>

<div class="page-header">
    <h2><i class="bi bi-credit-card" style="color:var(--success-500);"></i> Registrar pago</h2>
    <p>Registre el pago de una orden de servicio</p>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('pagos/guardar') ?>" method="post">
            <div class="grid-2 mb-4">
                <div class="form-group mb-0">
                    <label class="form-label">Pedido <span class="required">*</span></label>
                    <select name="pedido_id" class="form-select" id="pedidoSelect" required>
                        <option value="">Seleccione un pedido</option>
                        <?php foreach ($pedidos as $p): ?>
                            <?php
                                $pedidoId = $p['id'];
                                $total = $p['total'];
                                $placa = $p['placa'] ?? 'N/A';
                                $usuarioNombre = $p['usuario_nombre'] ?? 'N/A';
                            ?>
                            <option value="<?= esc($pedidoId) ?>" data-total="<?= esc($total) ?>" data-cliente="<?= esc($usuarioNombre) ?>">
                                #<?= esc($pedidoId) ?> — <?= esc($placa) ?> — <?= esc($usuarioNombre) ?> — $ <?= number_format((float)$total, 0, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Cliente</label>
                    <input type="text" id="clienteDisplay" class="form-control" readonly placeholder="Seleccione un pedido primero">
                </div>
            </div>

            <div class="grid-3 mb-4">
                <div class="form-group mb-0">
                    <label class="form-label">Total del pedido (base)</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="100" name="base_total" class="form-control" id="baseTotalInput" readonly>
                        <span class="input-group-text">COP</span>
                    </div>
                </div>

                <div class="form-group mb-0 mt-2" style="display:block;">
                    <div style="font-size:var(--text-sm);color:var(--gray-600);">Recargo: <span id="recargoLabel">+0 COP</span></div>
                    <div style="font-size:var(--text-sm);color:var(--gray-600);">Total final: <span id="totalFinalLabel">0 COP</span></div>
                </div>

                <div class="form-group mb-0 mt-2">
                    <label class="form-label">Monto a pagar</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="100" name="monto" class="form-control" id="montoInput" readonly>
                        <span class="input-group-text">COP</span>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Método de pago <span class="required">*</span></label>
                    <select name="metodo_pago" class="form-select" id="metodoSelect" required>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta débito/crédito</option>
                        <option value="transferencia">Transferencia bancaria</option>
                        <option value="otros">Otros</option>
                    </select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" id="referenciaLabel">Comprobante</label>
                    <input type="text" name="referencia" id="referenciaInput" class="form-control" placeholder="N° de referencia">
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-success btn-lg px-5"><i class="bi bi-check-lg"></i> Registrar pago</button>
            <a href="<?= base_url('pagos') ?>" class="btn btn-outline-secondary btn-lg px-4 ms-2">Cancelar</a>
        </form>
    </div>
</div>

<script>
    const baseTotalInput = document.getElementById("baseTotalInput");
    const montoInput = document.getElementById("montoInput");
    const metodoSelect = document.getElementById("metodoSelect");
    const recargoLabel = document.getElementById("recargoLabel");
    const totalFinalLabel = document.getElementById("totalFinalLabel");
    const clienteDisplay = document.getElementById("clienteDisplay");
    const referenciaInput = document.getElementById("referenciaInput");
    const referenciaLabel = document.getElementById("referenciaLabel");

    function updateReferenciaRequired() {
        const metodo = metodoSelect.value;
        if (metodo === 'efectivo') {
            referenciaInput.required = false;
            referenciaLabel.innerHTML = 'Comprobante';
            referenciaInput.placeholder = 'N° de referencia (opcional)';
        } else {
            referenciaInput.required = true;
            referenciaLabel.innerHTML = 'Comprobante <span class="required">*</span>';
            referenciaInput.placeholder = 'N° de referencia (obligatorio)';
        }
    }

    function recargoPorMetodo(metodo) {
        switch (metodo) {
            case 'tarjeta':
                return 0.05;
            case 'transferencia':
                return 0.02;
            case 'otros':
                return 0.03;
            case 'efectivo':
            default:
                return 0.0;
        }
    }

    function calcularTotalFinal() {
        const base = parseFloat(baseTotalInput?.value || '0');
        const metodo = metodoSelect?.value || 'efectivo';
        const factor = recargoPorMetodo(metodo);
        const recargo = base * factor;
        const totalFinal = base + recargo;

        if (montoInput) montoInput.value = Math.round(totalFinal);

        if (recargoLabel) {
            recargoLabel.textContent = '+' + Math.round(recargo).toLocaleString('es-CO') + ' COP (' + Math.round(factor * 100) + '%)';
        }

        if (totalFinalLabel) {
            totalFinalLabel.textContent = Math.round(totalFinal).toLocaleString('es-CO') + ' COP';
        }
    }

    document.getElementById("pedidoSelect")?.addEventListener("change", function () {
        const opt = this.options[this.selectedIndex];
        const total = opt?.dataset.total ? parseFloat(opt.dataset.total) : 0;
        const cliente = opt?.dataset.cliente || '';

        if (baseTotalInput) baseTotalInput.value = total;
        if (clienteDisplay) clienteDisplay.value = cliente;
        calcularTotalFinal();
    });

    metodoSelect?.addEventListener('change', () => {
        calcularTotalFinal();
        updateReferenciaRequired();
    });

    // Inicializar estados
    if (baseTotalInput) calcularTotalFinal();
    updateReferenciaRequired();
</script>

<?php
$content = ob_get_clean();
echo view('layout/main', ['titulo' => 'Registrar Pago', 'content' => $content]);
?>
