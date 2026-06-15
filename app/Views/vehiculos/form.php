<?php
$vehiculo = $vehiculo ?? null;
$esEditar = is_array($vehiculo);

$titulo = $esEditar ? 'Editar vehículo' : 'Nuevo vehículo';
$action = ($esEditar && isset($vehiculo['id']))
    ? base_url('vehiculos/actualizar/' . $vehiculo['id'])
    : base_url('vehiculos/guardar');

$v = $esEditar
    ? ($vehiculo ?? [])
    : [
        'marca_id' => '',
        'modelo_id' => '',
        'tipo' => '',
        'año' => date('Y'),
        'placa' => '',
    ];

$errHtml = '';
if (session('errores')) {
    $errHtml = '<div class="alert alert-danger">';
    foreach (session('errores') as $e) {
        $errHtml .= '<div><i class="bi bi-x-circle"></i> ' . $e . '</div>';
    }
    $errHtml .= '</div>';
}

$rol = (string) (session('rol') ?? '');

$propietarioHtml = '';
if ($rol === 'usuario') {
    $propietarioHtml = '<input type="hidden" name="usuario_id" value="' . session('id') . '">';
} else {
    if ($esEditar) {
        $propietarioId = $v['usuario_id'] ?? '';
        $propietarioNombre = $v['propietario'] ?? '—';
        $propietarioHtml =
            '<input type="hidden" name="usuario_id" value="' . $propietarioId . '">'
            . '<div class="form-group mb-0">'
            . '  <label class="form-label">Propietario</label>'
            . '  <p class="form-control-plaintext">' . $propietarioNombre . '</p>'
            . '</div>';
    } else {
        $usuarios = $usuarios ?? [];
        $oldUsuarioId = old('usuario_id');
        $oldUsuarioId = $oldUsuarioId !== null ? $oldUsuarioId : ($v['usuario_id'] ?? '');

        $uopts = '';
        foreach ($usuarios as $u) {
            $sel = ((string) $oldUsuarioId !== '' && (string) $u['id'] === (string) $oldUsuarioId) ? 'selected' : '';
            $uopts .= '<option value="' . $u['id'] . '" ' . $sel . '>' . $u['nombre_usuario'] . '</option>';
        }

        $propietarioHtml =
            '<div class="form-group mb-0">'
            . '  <label class="form-label">Propietario <span class="required">*</span></label>'
            . '  <select name="usuario_id" class="form-select" required>'
            . $uopts
            . '  </select>'
            . '</div>';
    }
}

$marcas = $marcas ?? [];
$modelos = $modelos ?? [];

$marcaIdActual = (string) ($v['marca_id'] ?? '');
$modeloIdActual = (string) ($v['modelo_id'] ?? '');
$tipoActual = (string) ($v['tipo'] ?? '');

$marcaOptions = '';
foreach ($marcas as $m) {
    $selected = ($marcaIdActual !== '' && (string)$m['id'] === $marcaIdActual) ? 'selected' : '';
    $marcaOptions .= '<option value="' . $m['id'] . '" ' . $selected . '>' . $m['nombre'] . '</option>';
}

$allModelosOptions = '';
foreach ($modelos as $md) {
    $selected = ($modeloIdActual !== '' && (string)$md['id'] === $modeloIdActual) ? 'selected' : '';
    // Guardamos marca_id para filtrar modelos
    $allModelosOptions .= '<option value="' . $md['id'] . '" data-marca="' . $md['marca_id'] . '" data-tipo="' . $md['tipo'] . '" ' . $selected . '>' . $md['nombre'] . ' (' . $md['tipo'] . ')</option>';
}


$content = '
<div class="page-header">'
    . '    <h2><i class="bi ' . ($esEditar ? 'pencil' : 'plus-lg') . '"></i> ' . $titulo . '</h2>'
    . '    <p>Complete la información del vehículo</p>'
    . '</div>'
    . $errHtml
    . '<div class="card">'
    . '  <div class="card-body">'
    . '    <form action="' . $action . '" method="post">'
    . '      ' . $propietarioHtml
    . '      <div class="grid-2 mb-4">'
    . '        <div class="form-group mb-0">'
    . '          <label class="form-label">Marca <span class="required">*</span></label>'
    . '          <select name="marca_id" id="marcaSelect" class="form-select" required>'
    . '            <option value="">Seleccione una marca</option>'
    .            $marcaOptions
    . '          </select>'
    . '        </div>'
    . '        <div class="form-group mb-0">'
    . '          <label class="form-label">Modelo <span class="required">*</span></label>'
    . '          <select name="modelo_id" id="modeloSelect" class="form-select" required>'
    . '            <option value="">Seleccione un modelo</option>'
    . $allModelosOptions .
    '          </select>'
    . '        </div>'
    . '      </div>'
    . '      <div class="grid-3 mb-4">'
    . '        <div class="form-group mb-0">'
    . '          <label class="form-label">Tipo de vehículo</label>'
    . '          <input type="text" class="form-control" value="' . htmlspecialchars($tipoActual) . '" disabled>'
    . '          <input type="hidden" name="tipo" id="tipoHidden" value="' . htmlspecialchars($tipoActual) . '">'
    . '        </div>'
    . '        <div class="form-group mb-0">'
    . '          <label class="form-label">Año vehículo <span class="required">*</span></label>'
    . '          <input type="number" name="anio" class="form-control" value="' . htmlspecialchars((string)($v['año'] ?? '')) . '" min="1990" max="' . (date('Y') + 1) . '" required>'
    . '        </div>'
    . '        <div class="form-group mb-0">'
    . '          <label class="form-label">Placa <span class="required">*</span></label>'
    . '          <input type="text" name="placa" class="form-control input-placa" value="' . htmlspecialchars((string)($v['placa'] ?? '')) . '" placeholder="ABC-123" required>'
    . '        </div>'
    . '      </div>'
    . '      <hr>'
    . '      <button type="submit" class="btn btn-primary px-5"><i class="bi bi-floppy"></i> Guardar</button>'
    . '      <a href="' . base_url('vehiculos') . '" class="btn btn-outline-secondary px-4 ms-2">Cancelar</a>'
    . '    </form>'
    . '  </div>'
    . '</div>'
    . '<script>'
    . '  (function(){'
    . '    const marcaSelect = document.getElementById("marcaSelect");'
    . '    const modeloSelect = document.getElementById("modeloSelect");'
    . '    const tipoHidden = document.getElementById("tipoHidden");'
    . '    const tipoTextInput = document.querySelector("input[disabled]");'
    . '    function setModeloTipo(){'
    . '      const opt = modeloSelect.options[modeloSelect.selectedIndex];'
    . '      const tipo = opt ? opt.dataset.tipo : "";'
    . '      tipoHidden.value = tipo || "";'
    . '      if (tipoTextInput) tipoTextInput.value = tipo || "";'
    . '    }'
    . '    marcaSelect.addEventListener("change", function(){'
    . '      const marcaId = this.value;'
    . '      const options = Array.from(modeloSelect.options);'
    . '      options.forEach(function(o, idx){'
    . '        if (o.value === "") return;'
    . '        const modeloMarcaId = o.getAttribute("data-marca");'
    . '        const show = (!marcaId) || (modeloMarcaId === marcaId);'
    . '        o.style.display = show ? "" : "none";'
    . '      });'
    . '      const selectedOpt = modeloSelect.options[modeloSelect.selectedIndex];'
    . '      if (selectedOpt && selectedOpt.value !== "" && selectedOpt.style.display === "none") {'
    . '        modeloSelect.value = "";'
    . '      }'
    . '    });'
    . '    modeloSelect.addEventListener("change", setModeloTipo);'
    . '    Array.from(modeloSelect.options).forEach(function(o){'
    . '      if (!o.value) return;'
    . '      if (!o.getAttribute("data-marca")) {'
    . '      }'
    . '    });'
    . '    setModeloTipo();'
    . '  })();'
    . '</script>';
echo view('layout/main', ['titulo' => $titulo, 'content' => $content]);
?>

