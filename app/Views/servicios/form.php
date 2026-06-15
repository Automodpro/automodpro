<?php
$servicio = $servicio ?? [];
$esEditar = !empty($servicio) && isset($servicio['id']);
$s = [
    'nombre'      => $servicio['nombre'] ?? '',
    'descripcion' => $servicio['descripcion'] ?? '',
    'precio'      => $servicio['precio'] ?? '',
];

$titulo = $esEditar ? 'Editar servicio' : 'Nuevo servicio';
$action = ($esEditar && isset($servicio['id'])) ? base_url('servicios/actualizar/' . $servicio['id']) : base_url('servicios/guardar');

$content = '
<div class="page-header">
    <h2><i class="bi bi-' . ($esEditar ? 'pencil' : 'plus-lg') . '"></i> ' . $titulo . '</h2>
    <p>Configure el precio base del servicio</p>
</div>';

if (session('errores')) {
    $content .= '<div class="alert alert-danger">';
    foreach (session('errores') as $e) {
        $content .= '<div><i class="bi bi-x-circle"></i> ' . esc($e) . '</div>';
    }
    $content .= '</div>';
}

$content .= '
<div class="card">
    <div class="card-body">
        <form action="' . $action . '" method="post">
            <div class="grid-2 mb-4">
                <div class="form-group mb-0">
                    <label class="form-label">Nombre del servicio <span class="required">*</span></label>
                    <input type="text" name="nombre" class="form-control" value="' . esc($s['nombre']) . '" required>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Precio base <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="100" min="0" name="precio" class="form-control" value="' . esc($s['precio']) . '" required>
                        <span class="input-group-text">COP</span>
                    </div>
                </div>
            </div>
            <div class="form-group mb-4">
                <label class="form-label">Descripcion</label>
                <textarea name="descripcion" class="form-control" rows="2" placeholder="Describe el servicio...">' . esc($s['descripcion']) . '</textarea>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary px-5"><i class="bi bi-floppy"></i> Guardar</button>
            <a href="' . base_url('servicios') . '" class="btn btn-outline-secondary px-4 ms-2">Cancelar</a>
        </form>
    </div>
</div>';

echo view('layout/main', ['titulo' => $titulo, 'content' => $content]);
?>
