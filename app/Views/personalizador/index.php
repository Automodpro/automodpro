<?php 
$p = $personalizacion ?? ['color_carro' => '#e74c3c', 'color_ruedas' => '#2c3e50', 'color_vidrios' => '#85c1e9', 'tipo_aleron' => 'ninguno', 'tipo_faros' => 'normales'];

$content = '
<div class="page-header">
    <h2><i class="bi bi-palette"></i> Personalizador 2D</h2>
    <p>Personaliza tu vehículo en 2D con colores y accesorios</p>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card" style="background:linear-gradient(135deg, #f8fafc, #e2e8f0);">
            <div class="card-body text-center py-5">
                <canvas id="carCanvas" width="600" height="400" class="w-100" style="max-width:600px;margin:0 auto;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="font-size:var(--text-base);"><i class="bi bi-sliders2" style="color:var(--primary-500);margin-right:8px;"></i> Personalizar vehículo</h5>
                <form action="' . base_url("personalizador/guardar") . '" method="post">
                    <div class="form-group">
                        <label class="form-label">Color del Carro</label>
                        <input type="color" name="color_carro" class="form-control form-control-color" value="' . $p["color_carro"] . '" id="colorCarro" style="height:44px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Color de Ruedas</label>
                        <input type="color" name="color_ruedas" class="form-control form-control-color" value="' . $p["color_ruedas"] . '" id="colorRuedas" style="height:44px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Color de Vidrios</label>
                        <input type="color" name="color_vidrios" class="form-control form-control-color" value="' . $p["color_vidrios"] . '" id="colorVidrios" style="height:44px;">
                    </div>
                    <div class="grid-2 mb-3" style="gap:12px;">
                        <div class="form-group mb-0">
                            <label class="form-label">Alerón</label>
                            <select name="tipo_aleron" class="form-select" id="tipoAleron">
                                <option value="ninguno"' . ($p["tipo_aleron"] === "ninguno" ? " selected" : "") . '>Ninguno</option>
                                <option value="deportivo"' . ($p["tipo_aleron"] === "deportivo" ? " selected" : "") . '>Deportivo</option>
                                <option value="grande"' . ($p["tipo_aleron"] === "grande" ? " selected" : "") . '>Grande</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Faros</label>
                            <select name="tipo_faros" class="form-select" id="tipoFaros">
                                <option value="normales"' . ($p["tipo_faros"] === "normales" ? " selected" : "") . '>Normales</option>
                                <option value="led"' . ($p["tipo_faros"] === "led" ? " selected" : "") . '>LED</option>
                                <option value="angeles"' . ($p["tipo_faros"] === "angeles" ? " selected" : "") . '>Ángeles</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2"><i class="bi bi-floppy"></i> Guardar Personalización</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const canvas = document.getElementById("carCanvas");
const ctx = canvas.getContext("2d");

function dibujarCarro() {
    const c = document.getElementById("colorCarro").value;
    const r = document.getElementById("colorRuedas").value;
    const v = document.getElementById("colorVidrios").value;
    const al = document.getElementById("tipoAleron").value;
    const fa = document.getElementById("tipoFaros").value;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Sombra
    ctx.fillStyle = "rgba(0,0,0,0.08)";
    ctx.beginPath();
    ctx.ellipse(300, 350, 180, 18, 0, 0, Math.PI * 2);
    ctx.fill();

    // Carrocería principal
    ctx.fillStyle = c;
    ctx.shadowColor = "rgba(0,0,0,0.1)";
    ctx.shadowBlur = 10;
    ctx.beginPath();
    ctx.moveTo(100, 278);
    ctx.lineTo(120, 218);
    ctx.lineTo(200, 198);
    ctx.lineTo(250, 158);
    ctx.lineTo(380, 158);
    ctx.lineTo(420, 198);
    ctx.lineTo(500, 218);
    ctx.lineTo(520, 278);
    ctx.closePath();
    ctx.fill();
    ctx.shadowBlur = 0;
    ctx.strokeStyle = "rgba(0,0,0,0.2)";
    ctx.lineWidth = 2;
    ctx.stroke();

    // Vidrios
    ctx.fillStyle = v;
    ctx.shadowBlur = 0;
    ctx.beginPath();
    ctx.moveTo(210, 196);
    ctx.lineTo(255, 163);
    ctx.lineTo(370, 163);
    ctx.lineTo(370, 196);
    ctx.closePath();
    ctx.fill();
    ctx.strokeStyle = "rgba(0,0,0,0.15)";
    ctx.stroke();

    // Ventana trasera
    ctx.beginPath();
    ctx.moveTo(130, 216);
    ctx.lineTo(200, 198);
    ctx.lineTo(200, 216);
    ctx.closePath();
    ctx.fill();
    ctx.stroke();

    // Faros delanteros
    ctx.fillStyle = fa === "led" ? "#f0f8ff" : fa === "angeles" ? "#ffd700" : "#fff8dc";
    ctx.shadowColor = fa === "angeles" ? "#ffd700" : fa === "led" ? "#87ceeb" : "#ffa500";
    ctx.shadowBlur = fa === "angeles" ? 25 : fa === "led" ? 15 : 10;
    ctx.beginPath();
    ctx.ellipse(510, 238, 12, 10, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.beginPath();
    ctx.ellipse(510, 263, 12, 10, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.shadowBlur = 0;

    // Faros traseros
    ctx.fillStyle = "#dc2626";
    ctx.shadowColor = "#ef4444";
    ctx.shadowBlur = 10;
    ctx.beginPath();
    ctx.ellipse(100, 238, 10, 8, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.beginPath();
    ctx.ellipse(100, 263, 10, 8, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.shadowBlur = 0;

    // Ruedas
    ctx.fillStyle = r;
    ctx.shadowColor = "rgba(0,0,0,0.2)";
    ctx.shadowBlur = 8;
    ctx.beginPath();
    ctx.ellipse(175, 290, 32, 38, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.beginPath();
    ctx.ellipse(435, 290, 32, 38, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.shadowBlur = 0;

    // Rines
    ctx.fillStyle = "#94a3b8";
    ctx.shadowBlur = 0;
    ctx.beginPath();
    ctx.ellipse(175, 290, 16, 20, 0, 0, Math.PI * 2);
    ctx.fill();
    ctx.beginPath();
    ctx.ellipse(435, 290, 16, 20, 0, 0, Math.PI * 2);
    ctx.fill();

    // Detalles del rin
    ctx.fillStyle = "#64748b";
    for (let a = 0; a < 5; a++) {
        let angle = (a / 5) * Math.PI * 2;
        let cx1 = 175 + Math.cos(angle) * 12;
        let cy1 = 290 + Math.sin(angle) * 15;
        ctx.beginPath();
        ctx.arc(cx1, cy1, 3, 0, Math.PI * 2);
        ctx.fill();
        let cx2 = 435 + Math.cos(angle) * 12;
        let cy2 = 290 + Math.sin(angle) * 15;
        ctx.beginPath();
        ctx.arc(cx2, cy2, 3, 0, Math.PI * 2);
        ctx.fill();
    }

    // Alerón
    if (al !== "ninguno") {
        ctx.fillStyle = c;
        const yOff = al === "deportivo" ? 12 : 22;
        ctx.shadowColor = "rgba(0,0,0,0.1)";
        ctx.shadowBlur = 5;
        ctx.fillRect(275, 143 - yOff, 90, yOff);
        ctx.fillRect(265, 143 - yOff, 6, 22);
        ctx.fillRect(369, 143 - yOff, 6, 22);
        ctx.shadowBlur = 0;
    }

    // Parrilla
    ctx.fillStyle = "#1e293b";
    ctx.fillRect(488, 233, 22, 32);
    ctx.fillStyle = "#334155";
    for (let i = 0; i < 5; i++) {
        ctx.fillRect(490, 235 + i * 6, 18, 2);
    }

    // Capó línea
    ctx.strokeStyle = "rgba(0,0,0,0.1)";
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(130, 248);
    ctx.lineTo(490, 248);
    ctx.stroke();
}

document.querySelectorAll("#colorCarro, #colorRuedas, #colorVidrios, #tipoAleron, #tipoFaros").forEach(el => {
    el.addEventListener("input", dibujarCarro);
});

dibujarCarro();
</script>';

echo view('layout/main', ['titulo' => 'Personalizador 2D', 'content' => $content]);
?>
