<?php
$vehiculos = $vehiculos ?? [];
$servicios = $servicios ?? [];
$servicioSeleccionado = (int) ($servicioSeleccionado ?? 0);

$content = '
<div class="page-header">
    <h2><i class="bi bi-cart-plus"></i> Nuevo pedido</h2>
    <p>Seleccione el vehiculo y los servicios a incluir</p>
</div>';

if (session('errores')) {
    $content .= '<div class="alert alert-danger">';
    foreach (session('errores') as $e) {
        $content .= '<div><i class="bi bi-x-circle"></i> ' . esc($e) . '</div>';
    }
    $content .= '</div>';
}

if (session('error')) {
    $content .= '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> ' . esc(session('error')) . '</div>';
}

$vehiculoOpts = '<option value="">Seleccione un vehiculo</option>';
foreach ($vehiculos as $v) {
    $marca = $v['marca'] ?? ('Marca ' . ($v['marca_id'] ?? ''));
    $modelo = $v['modelo'] ?? ('Modelo ' . ($v['modelo_id'] ?? ''));
    $label = trim($marca . ' ' . $modelo . ' - ' . ($v['placa'] ?? '') . ' (' . ($v['tipo'] ?? '') . ')');

    $propietarioNombre = $v['propietario'] ?? ($v['nombre_usuario'] ?? null);
    $propietarioId = $v['usuario_id'] ?? null;

    if (!empty($v['propietario'])) {
        $label .= ' - ' . $v['propietario'];
    }

    $vehiculoOpts .= '<option value="' . esc($v['id']) . '"'
        . ' data-tipo="' . esc($v['tipo'] ?? '') . '"'
        . ' data-usuario-nombre="' . esc($propietarioNombre ?? '') . '"'
        . ' data-usuario-id="' . esc($propietarioId ?? '') . '">' . esc($label) . '</option>';
}

$servCards = '';
foreach ($servicios as $s) {
    $checked = ((int) $s['id'] === $servicioSeleccionado) ? ' checked' : '';
    $precioBase = (float) ($s['precio'] ?? 0);

    $servCards .= ' 
                <div class="col-md-4 col-6">
                    <div class="card servicio-card">
                        <div class="card-body py-3 px-3">
                            <div class="form-check">
                                <input class="form-check-input servicio-check" type="checkbox" name="servicios[]" value="' . esc($s['id']) . '" id="svc' . esc($s['id']) . '"' . $checked . ' data-precio-base="' . $precioBase . '">
                                <label class="form-check-label fw-semibold" for="svc' . esc($s['id']) . '" style="font-size:var(--text-sm);">' . esc($s['nombre']) . '</label>
                            </div>
                            <div class="ms-4 mt-2">
                                <span class="badge badge-success" id="costoLabel' . esc($s['id']) . '">$ ' . number_format($precioBase, 0, ',', '.') . ' COP</span>
                                <div id="costoDetalle' . esc($s['id']) . '"></div>
                            </div>
                        </div>
                    </div>
                </div>';
}

$nombreClienteInicial = session('nombre') ?? '';
$usuarioIdInicial = session('id') ?? '';

$actionUrl = base_url('pedidos/guardar');
$cancelUrl = base_url('pedidos');
$costosUrl = base_url('servicios/costos');

$content .= <<<HTML

<div class="card">
    <div class="card-body">
        <form action="{$actionUrl}" method="post" id="formPedido">
            <div class="grid-2 mb-4">
                <div class="form-group mb-0">
                    <label class="form-label">Vehiculo <span class="required">*</span></label>
                    <select name="vehiculo_id" class="form-select" id="vehiculoSelect" required>{$vehiculoOpts}</select>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Cliente</label>
                    <input type="text" class="form-control" id="clienteNombre" value="{$nombreClienteInicial}" disabled>
                    <input type="hidden" name="usuario_id" id="clienteUsuarioId" value="{$usuarioIdInicial}">
                </div>
            </div>

            <hr>
            <h5 class="fw-bold mb-3" style="font-size:var(--text-base);">
                <i class="bi bi-list-check" style="color:var(--primary-500);"></i> Servicios disponibles
            </h5>
            <p class="text-muted mb-3" style="font-size:var(--text-sm);">Seleccione los servicios. El precio varia segun el tipo de vehiculo, marca y antiguedad.</p>

            <div class="row g-2 mb-4" id="serviciosList">{$servCards}</div>

            <div id="factorInfo" class="alert alert-info d-none d-flex align-items-center py-2 px-3 mb-2" style="font-size:var(--text-sm);border-radius:var(--radius-lg);" role="alert">
                <i class="bi bi-info-circle me-2"></i> <span id="factorInfoText"></span>
            </div>

            <div class="alert alert-info d-flex justify-content-between align-items-center" style="border-radius:var(--radius-lg);">
                <span class="fw-semibold"><i class="bi bi-calculator"></i> Total estimado</span>
                <span class="fs-5 fw-bold" style="color:var(--primary-600);" id="totalEstimado">$ 0 COP</span>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary btn-lg px-5"><i class="bi bi-floppy"></i> Crear pedido</button>
            <a href="{$cancelUrl}" class="btn btn-outline-secondary btn-lg px-4 ms-2">Cancelar</a>
        </form>
    </div>
</div>

<script>
const costoEndpoint = "{$costosUrl}";

document.getElementById("vehiculoSelect")?.addEventListener("change", function() {
    const opt = this.options[this.selectedIndex];
    const usuarioNombre = opt?.dataset?.usuarioNombre || '';
    const usuarioId = opt?.dataset?.usuarioId || '';

    document.getElementById("clienteNombre").value = usuarioNombre;
    document.getElementById("clienteUsuarioId").value = usuarioId;

    actualizarTodosPrecios();
});

(function initClienteDesdeVehiculo() {
    const vehiculoSelect = document.getElementById("vehiculoSelect");
    if (!vehiculoSelect) return;

    if (vehiculoSelect.value) {
        const opt = vehiculoSelect.options[vehiculoSelect.selectedIndex];
        document.getElementById("clienteNombre").value = opt?.dataset?.usuarioNombre || '';
        document.getElementById("clienteUsuarioId").value = opt?.dataset?.usuarioId || '';
    }
})();

function actualizarTodosPrecios() {
    const vehiculoId = document.getElementById("vehiculoSelect")?.value;
    const vehiculoOpt = document.getElementById("vehiculoSelect")?.selectedOptions[0];
    const tipoVehiculo = vehiculoOpt?.dataset?.tipo || '';

    if (!vehiculoId) return;

    const checks = document.querySelectorAll(".servicio-check");

    checks.forEach(cb => {
        const id = cb.value;
        actualizarPrecioServicio(id, vehiculoId, tipoVehiculo);
    });

    // Mostrar mensaje informativo
    if (tipoVehiculo) {
        const factorInfoDiv = document.getElementById("factorInfo");
        const factorInfoText = document.getElementById("factorInfoText");
        if (factorInfoDiv && factorInfoText) {
            factorInfoText.textContent = "El precio se ajusta seg\u00fan el tipo de veh\u00edculo, la marca y la antig\u00fcedad (seg\u00fan Configuraci\u00f3n → Factores de Precio).";
            factorInfoDiv.classList.remove("d-none");
        }
    }
}


function actualizarPrecioServicio(servicioId, vehiculoId, tipoVehiculo) {
    fetch(costoEndpoint + "?servicio_id=" + encodeURIComponent(servicioId) + "&vehiculo_id=" + encodeURIComponent(vehiculoId))
        .then(r => r.json())
        .then(data => {
            const label = document.getElementById("costoLabel" + servicioId);
            const detalle = document.getElementById("costoDetalle" + servicioId);

            if (label) {
                label.textContent = "$ " + (data.costo || 0).toLocaleString("es-CO") + " COP";
            }

            if (detalle && data.precio_base && data.factor) {
                const base = data.precio_base;
                const factor = data.factor;
                const incremento = (data.costo || 0) - base;
                const pct = Math.round((factor - 1) * 100);
                let texto = '';
                if (factor !== 1.00) {
                    texto = '<small class="text-muted d-block" style="font-size:10px;line-height:1.2;">';
                    texto += 'Base: $' + base.toLocaleString('es-CO') + ' | Factor: ' + factor.toFixed(2) + ' (' + (pct > 0 ? '+' : '') + pct + '%)';
                    if (incremento > 0) {
                        texto += '<br>Incremento: +$' + incremento.toLocaleString('es-CO');
                    }
                    texto += '</small>';
                }
                detalle.innerHTML = texto;
            }

            recalcularTotal();
        })
        .catch(() => {});
}

function recalcularTotal() {
    const checks = document.querySelectorAll(".servicio-check:checked");
    let total = 0;

    checks.forEach(cb => {
        const label = document.getElementById("costoLabel" + cb.value);
        if (label) {
            const texto = label.textContent.replace(/[^0-9]/g, '');
            const precio = parseInt(texto) || 0;
            total += precio;
        }
    });

    document.getElementById("totalEstimado").textContent = "$ " + total.toLocaleString("es-CO") + " COP";
}

document.querySelectorAll(".servicio-check").forEach(cb => {
    cb.addEventListener("change", function() {
        const vehiculoId = document.getElementById("vehiculoSelect")?.value;
        if (!vehiculoId) {
            alert("Seleccione un vehiculo");
            this.checked = false;
            return;
        }
        const opt = document.getElementById("vehiculoSelect")?.selectedOptions[0];
        const tipoVehiculo = opt?.dataset?.tipo || '';
        actualizarPrecioServicio(this.value, vehiculoId, tipoVehiculo);
    });
});

// Inicializar
(function() {
    const vehiculoSelect = document.getElementById("vehiculoSelect");
    if (vehiculoSelect && vehiculoSelect.value) {
        actualizarTodosPrecios();
    } else {
        let total = 0;
        document.querySelectorAll(".servicio-check:checked").forEach(cb => {
            const precioBase = parseFloat(cb.dataset.precioBase || 0);
            total += precioBase;
        });
        if (total > 0) {
            document.getElementById("totalEstimado").textContent = "$ " + total.toLocaleString("es-CO") + " COP";
        }
    }
})();
</script>
HTML;

echo view('layout/main', ['titulo' => 'Nuevo Pedido', 'content' => $content]);
?>