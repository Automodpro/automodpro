const CI4_BASE = window.location.origin + '/automodpro/public';

class CarRenderer {
    constructor(canvasId) {
        this.canvas = typeof canvasId === 'string' ? document.getElementById(canvasId) : canvasId;
        if (!this.canvas) return;
        this.ctx = this.canvas.getContext('2d');
        this.config = {
            color_carroceria: '#CC0000', color_rines: '#C0C0C0', tamano_rines: 15,
            tipo_rin: 'estandar', polarizado_frontal: 70, polarizado_trasero: 50,
            polarizado_parabrisas: 100, altura_suspension: 15, suspension_deportiva: false,
            aleron_trasero: 'ninguno', kit_aerodinamico: false, sistema_escape: 'estandar',
            silenciador_deportivo: false, luces_neon: false, faros_led: false, turbo: false,
        };
        this._initResize();
    }

    _initResize() {
        const ro = new ResizeObserver(() => this.resize());
        ro.observe(this.canvas.parentElement);
        setTimeout(() => this.resize(), 50);
    }

    resize() {
        const rect = this.canvas.parentElement.getBoundingClientRect();
        const dpr = window.devicePixelRatio || 1;
        this.canvas.width = rect.width * dpr;
        this.canvas.height = rect.height * dpr;
        this.canvas.style.width = rect.width + 'px';
        this.canvas.style.height = rect.height + 'px';
        this.ctx.scale(dpr, dpr);
        this.w = rect.width;
        this.h = rect.height;
        this.render();
    }

    update(key, value) { if (key in this.config) { this.config[key] = value; this.render(); } }
    updateAll(config) { Object.assign(this.config, config); this.render(); }

    render() {
        const ctx = this.ctx, w = this.w, h = this.h;
        ctx.clearRect(0, 0, w, h);
        const cx = w / 2, cy = h / 2 + 20;
        const scale = Math.min(w, h) / 600;
        ctx.save();
        ctx.translate(cx, cy);
        ctx.scale(scale, scale);
        const off = (15 - this.config.altura_suspension) * 1.5;
        this.drawShadow(ctx, off);
        if (this.config.kit_aerodinamico) this.drawBodyKit(ctx, off);
        this.drawCarBody(ctx, off);
        this.drawWindows(ctx, off);
        this.drawHeadlights(ctx, off);
        this.drawTaillights(ctx, off);
        this.drawExhaust(ctx, off);
        this.drawSpoiler(ctx, off);
        if (this.config.luces_neon) this.drawUnderGlow(ctx, off);
        this.drawWheels(ctx, off);
        ctx.restore();
    }

    drawShadow(ctx, y) { ctx.save(); ctx.beginPath(); ctx.ellipse(0, 150 + y, 220, 12, 0, 0, Math.PI * 2); ctx.fillStyle = 'rgba(0,0,0,0.15)'; ctx.fill(); ctx.restore(); }

    drawCarBody(ctx, y) {
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(-210, 60 + y); ctx.lineTo(-205, 55 + y);
        ctx.bezierCurveTo(-200, 48 + y, -190, 40 + y, -170, 35 + y);
        ctx.lineTo(-80, 10 + y);
        ctx.bezierCurveTo(-60, 3 + y, -40, -2 + y, -20, -5 + y);
        ctx.lineTo(60, -5 + y);
        ctx.bezierCurveTo(100, -5 + y, 140, 0 + y, 170, 15 + y);
        ctx.lineTo(190, 25 + y);
        ctx.bezierCurveTo(200, 30 + y, 208, 38 + y, 212, 48 + y);
        ctx.lineTo(215, 60 + y); ctx.lineTo(215, 90 + y); ctx.lineTo(-215, 90 + y); ctx.lineTo(-215, 60 + y);
        ctx.closePath();
        const g = ctx.createLinearGradient(0, -50 + y, 0, 100 + y);
        g.addColorStop(0, this.lighten(this.config.color_carroceria, 30));
        g.addColorStop(0.4, this.config.color_carroceria);
        g.addColorStop(0.8, this.darken(this.config.color_carroceria, 20));
        g.addColorStop(1, this.darken(this.config.color_carroceria, 40));
        ctx.fillStyle = g; ctx.fill();
        ctx.strokeStyle = this.darken(this.config.color_carroceria, 50);
        ctx.lineWidth = 1.5; ctx.stroke();
        ctx.beginPath(); ctx.moveTo(-210, 60 + y); ctx.lineTo(-215, 90 + y); ctx.lineTo(215, 90 + y); ctx.lineTo(215, 60 + y); ctx.stroke();
        this.drawLines(ctx, y);
        ctx.restore();
    }

    drawLines(ctx, y) {
        ctx.save();
        ctx.strokeStyle = 'rgba(255,255,255,0.12)'; ctx.lineWidth = 2;
        ctx.beginPath(); ctx.moveTo(-190, 50 + y); ctx.bezierCurveTo(-150, 38 + y, -50, 20 + y, 50, 20 + y); ctx.bezierCurveTo(120, 20 + y, 180, 32 + y, 200, 45 + y); ctx.stroke();
        ctx.beginPath(); ctx.moveTo(-200, 70 + y); ctx.bezierCurveTo(-150, 62 + y, -50, 55 + y, 50, 55 + y); ctx.bezierCurveTo(130, 55 + y, 190, 62 + y, 205, 70 + y); ctx.stroke();
        ctx.restore();
    }

    drawWindows(ctx, y) {
        ctx.save();
        const ft = 1 - (this.config.polarizado_frontal / 100) * 0.9, rt = 1 - (this.config.polarizado_trasero / 100) * 0.9, wt = 1 - (this.config.polarizado_parabrisas / 100) * 0.7;
        ctx.beginPath(); ctx.moveTo(-195, 55 + y); ctx.bezierCurveTo(-190, 48 + y, -180, 40 + y, -160, 36 + y); ctx.lineTo(-80, 22 + y); ctx.lineTo(-90, 55 + y); ctx.closePath();
        ctx.fillStyle = `rgba(0,0,0,${wt})`; ctx.fill(); ctx.strokeStyle = 'rgba(0,0,0,0.3)'; ctx.lineWidth = 1; ctx.stroke();
        ctx.beginPath(); ctx.moveTo(-85, 22 + y); ctx.bezierCurveTo(-65, 15 + y, -45, 10 + y, -20, 8 + y); ctx.lineTo(50, 8 + y); ctx.bezierCurveTo(80, 8 + y, 110, 12 + y, 140, 20 + y); ctx.lineTo(165, 30 + y); ctx.lineTo(150, 55 + y); ctx.lineTo(-20, 55 + y); ctx.lineTo(-85, 55 + y); ctx.closePath();
        ctx.fillStyle = `rgba(0,0,0,${ft})`; ctx.fill(); ctx.strokeStyle = 'rgba(0,0,0,0.3)'; ctx.lineWidth = 1; ctx.stroke();
        ctx.beginPath(); ctx.moveTo(170, 32 + y); ctx.bezierCurveTo(185, 38 + y, 195, 44 + y, 200, 50 + y); ctx.lineTo(200, 55 + y); ctx.lineTo(155, 55 + y); ctx.lineTo(170, 32 + y); ctx.closePath();
        ctx.fillStyle = `rgba(0,0,0,${rt})`; ctx.fill(); ctx.strokeStyle = 'rgba(0,0,0,0.3)'; ctx.lineWidth = 1; ctx.stroke();
        ctx.restore();
    }

    drawHeadlights(ctx, y) {
        ctx.save();
        const led = this.config.faros_led;
        const g = ctx.createRadialGradient(-205, 50 + y, 2, -205, 50 + y, 15);
        if (led) { g.addColorStop(0, '#FFF'); g.addColorStop(0.3, '#E0F0FF'); g.addColorStop(0.6, '#80B0FF'); g.addColorStop(1, '#4060AA'); }
        else { g.addColorStop(0, '#FFFFF0'); g.addColorStop(0.3, '#FFE080'); g.addColorStop(1, '#CC8800'); }
        ctx.beginPath(); ctx.ellipse(-207, 52 + y, 15, 8, -0.1, 0, Math.PI * 2); ctx.fillStyle = g; ctx.fill(); ctx.strokeStyle = 'rgba(0,0,0,0.4)'; ctx.lineWidth = 1; ctx.stroke();
        if (led) { ctx.beginPath(); ctx.ellipse(-205, 50 + y, 4, 3, -0.1, 0, Math.PI * 2); ctx.fillStyle = 'rgba(255,255,255,0.9)'; ctx.fill(); }
        ctx.restore();
    }

    drawTaillights(ctx, y) {
        ctx.save();
        ctx.beginPath(); ctx.ellipse(210, 58 + y, 12, 8, 0.1, 0, Math.PI * 2);
        const g = ctx.createRadialGradient(210, 58 + y, 1, 210, 58 + y, 12);
        g.addColorStop(0, '#FF2200'); g.addColorStop(1, '#880000'); ctx.fillStyle = g; ctx.fill(); ctx.strokeStyle = 'rgba(0,0,0,0.4)'; ctx.lineWidth = 1; ctx.stroke();
        ctx.beginPath(); ctx.ellipse(212, 65 + y, 6, 4, 0.1, 0, Math.PI * 2); ctx.fillStyle = '#FF4400'; ctx.fill();
        ctx.restore();
    }

    drawWheels(ctx, y) {
        const r = this.config.tamano_rines, rr = 38 * (0.9 + (r / 15) * 0.4);
        this.drawWheel(ctx, -130, 90 + y, rr, this.config.color_rines, this.config.tipo_rin);
        this.drawWheel(ctx, 135, 90 + y, rr, this.config.color_rines, this.config.tipo_rin);
    }

    drawWheel(ctx, x, y, r, color, type) {
        ctx.save();
        ctx.beginPath(); ctx.arc(x, y, r + 14, 0, Math.PI * 2); ctx.fillStyle = '#1a1a1a'; ctx.fill(); ctx.strokeStyle = '#333'; ctx.lineWidth = 2; ctx.stroke();
        ctx.beginPath(); ctx.arc(x, y, r + 14, 0, Math.PI * 2); ctx.fillStyle = '#2a2a2a'; ctx.fill();
        const g = ctx.createRadialGradient(x, y, r * 0.2, x, y, r + 14);
        g.addColorStop(0, color); g.addColorStop(0.5, this.lighten(color, 20)); g.addColorStop(0.8, this.darken(color, 10)); g.addColorStop(1, this.darken(color, 30));
        ctx.beginPath(); ctx.arc(x, y, r, 0, Math.PI * 2); ctx.fillStyle = g; ctx.fill(); ctx.strokeStyle = this.darken(color, 50); ctx.lineWidth = 1.5; ctx.stroke();
        if (type === 'deportivo') this.drawSportRim(ctx, x, y, r, color);
        else if (type === 'cromado') this.drawChromeRim(ctx, x, y, r, color);
        else if (type === 'black') this.drawBlackRim(ctx, x, y, r, color);
        else this.drawStandardRim(ctx, x, y, r, color);
        ctx.beginPath(); ctx.arc(x, y, r * 0.15, 0, Math.PI * 2); ctx.fillStyle = this.darken(color, 40); ctx.fill(); ctx.strokeStyle = this.darken(color, 60); ctx.lineWidth = 1; ctx.stroke();
        ctx.restore();
    }

    drawStandardRim(ctx, x, y, r, c) { ctx.save(); ctx.strokeStyle = this.darken(c, 30); ctx.lineWidth = 3; for (let i = 0; i < 5; i++) { const a = (i / 5) * Math.PI * 2; ctx.beginPath(); ctx.moveTo(x, y); ctx.lineTo(x + Math.cos(a) * r * 0.8, y + Math.sin(a) * r * 0.8); ctx.stroke(); } ctx.restore(); }
    drawSportRim(ctx, x, y, r, c) { ctx.save(); ctx.strokeStyle = this.darken(c, 20); ctx.lineWidth = 4; for (let i = 0; i < 10; i++) { const a = (i / 10) * Math.PI * 2; ctx.beginPath(); ctx.arc(x, y, r * 0.75, a, a + Math.PI / 12); ctx.stroke(); } ctx.beginPath(); ctx.arc(x, y, r * 0.55, 0, Math.PI * 2); ctx.strokeStyle = this.darken(c, 40); ctx.lineWidth = 2; ctx.stroke(); ctx.restore(); }
    drawChromeRim(ctx, x, y, r, c) { ctx.save(); const g = ctx.createRadialGradient(x - r * 0.2, y - r * 0.2, r * 0.1, x, y, r); g.addColorStop(0, '#FFF'); g.addColorStop(0.3, '#E0E0E0'); g.addColorStop(0.6, '#A0A0A0'); g.addColorStop(1, '#606060'); ctx.beginPath(); ctx.arc(x, y, r * 0.85, 0, Math.PI * 2); ctx.fillStyle = g; ctx.fill(); ctx.strokeStyle = 'rgba(255,255,255,0.3)'; ctx.lineWidth = 1; for (let i = 0; i < 6; i++) { const a = (i / 6) * Math.PI * 2; ctx.beginPath(); ctx.moveTo(x + Math.cos(a) * r * 0.3, y + Math.sin(a) * r * 0.3); ctx.lineTo(x + Math.cos(a) * r * 0.8, y + Math.sin(a) * r * 0.8); ctx.stroke(); } ctx.restore(); }
    drawBlackRim(ctx, x, y, r, c) { ctx.save(); const g = ctx.createRadialGradient(x, y, 0, x, y, r); g.addColorStop(0, '#333'); g.addColorStop(0.5, '#222'); g.addColorStop(1, '#111'); ctx.beginPath(); ctx.arc(x, y, r * 0.85, 0, Math.PI * 2); ctx.fillStyle = g; ctx.fill(); ctx.strokeStyle = '#444'; ctx.lineWidth = 2; for (let i = 0; i < 8; i++) { const a = (i / 8) * Math.PI * 2; ctx.beginPath(); ctx.moveTo(x + Math.cos(a) * r * 0.25, y + Math.sin(a) * r * 0.25); ctx.lineTo(x + Math.cos(a) * r * 0.8, y + Math.sin(a) * r * 0.8); ctx.stroke(); } ctx.restore(); }

    drawSpoiler(ctx, y) {
        if (this.config.aleron_trasero === 'ninguno') return;
        const sz = { pequeno: 1.0, medio: 1.5, grande: 2.2 } [this.config.aleron_trasero] || 1;
        ctx.save();
        const g = ctx.createLinearGradient(130, 25 + y, 130, 25 + y - 15 * sz);
        g.addColorStop(0, this.darken(this.config.color_carroceria, 20));
        g.addColorStop(1, this.darken(this.config.color_carroceria, 50));
        ctx.beginPath(); ctx.moveTo(130, 25 + y); ctx.lineTo(210, 20 + y - 5 * sz); ctx.lineTo(215, 15 + y - 10 * sz); ctx.lineTo(180, 10 + y - 15 * sz); ctx.lineTo(130, 20 + y - 5 * sz); ctx.closePath();
        ctx.fillStyle = g; ctx.fill(); ctx.strokeStyle = this.darken(this.config.color_carroceria, 60); ctx.lineWidth = 1.5; ctx.stroke();
        ctx.restore();
    }

    drawExhaust(ctx, y) {
        const t = this.config.sistema_escape, sm = this.config.silenciador_deportivo;
        ctx.save();
        let l = 12, w = 6;
        if (t === 'deportivo') { l = 18; w = 8; } else if (t === 'libre') { l = 25; w = 10; }
        ctx.beginPath(); ctx.moveTo(205, 82 + y); ctx.lineTo(205 + l, 82 + y); ctx.lineTo(205 + l + 3, 82 + y + w); ctx.lineTo(205 + l, 82 + y + w); ctx.lineTo(205, 82 + y + w); ctx.closePath();
        ctx.fillStyle = t === 'libre' ? '#555' : '#888'; ctx.fill(); ctx.strokeStyle = '#333'; ctx.lineWidth = 1; ctx.stroke();
        if (t === 'libre') { ctx.beginPath(); ctx.arc(210 + l, 85 + y + w / 2, 4, 0, Math.PI * 2); ctx.fillStyle = 'rgba(0,0,0,0.3)'; ctx.fill(); }
        if (sm) { ctx.save(); ctx.beginPath(); ctx.ellipse(195, 84 + y + w / 2, 8, 6, 0, 0, Math.PI * 2); ctx.fillStyle = '#444'; ctx.fill(); ctx.strokeStyle = '#222'; ctx.lineWidth = 1; ctx.stroke(); ctx.font = '6px Arial'; ctx.fillStyle = '#888'; ctx.textAlign = 'center'; ctx.fillText('SPORT', 195, 87 + y + w / 2); ctx.restore(); }
        ctx.restore();
    }

    drawBodyKit(ctx, y) {
        ctx.save();
        const c = this.darken(this.config.color_carroceria, 30);
        const g = ctx.createLinearGradient(0, 75 + y, 0, 90 + y);
        g.addColorStop(0, c); g.addColorStop(1, this.darken(c, 20));
        ctx.beginPath(); ctx.moveTo(-200, 90 + y); ctx.lineTo(-200, 82 + y); ctx.bezierCurveTo(-190, 80 + y, -100, 75 + y, 0, 75 + y); ctx.bezierCurveTo(100, 75 + y, 190, 80 + y, 200, 82 + y); ctx.lineTo(200, 90 + y); ctx.closePath();
        ctx.fillStyle = g; ctx.fill(); ctx.strokeStyle = this.darken(c, 40); ctx.lineWidth = 1; ctx.stroke();
        ctx.restore();
    }

    drawUnderGlow(ctx, y) {
        ctx.save(); ctx.globalAlpha = 0.6;
        const g = ctx.createLinearGradient(-190, 95 + y, 190, 95 + y);
        g.addColorStop(0, 'rgba(0,200,255,0.4)'); g.addColorStop(0.2, 'rgba(0,200,255,0.6)'); g.addColorStop(0.5, 'rgba(0,200,255,0.8)'); g.addColorStop(0.8, 'rgba(0,200,255,0.6)'); g.addColorStop(1, 'rgba(0,200,255,0.4)');
        ctx.beginPath(); ctx.moveTo(-195, 92 + y); ctx.lineTo(200, 92 + y); ctx.lineTo(200, 98 + y); ctx.lineTo(-195, 98 + y); ctx.closePath(); ctx.fillStyle = g; ctx.fill();
        ctx.shadowColor = '#00CCFF'; ctx.shadowBlur = 20;
        ctx.beginPath(); ctx.moveTo(-185, 93 + y); ctx.lineTo(190, 93 + y); ctx.strokeStyle = '#00CCFF'; ctx.lineWidth = 2; ctx.stroke();
        ctx.restore();
    }

    lighten(h, p) { const n = parseInt(h.replace('#',''),16), a = Math.round(2.55*p); return `rgb(${Math.min(255,(n>>16)+a)},${Math.min(255,((n>>8)&0xFF)+a)},${Math.min(255,(n&0xFF)+a)})`; }
    darken(h, p) { const n = parseInt(h.replace('#',''),16), a = Math.round(2.55*p); return `rgb(${Math.max(0,(n>>16)-a)},${Math.max(0,((n>>8)&0xFF)-a)},${Math.max(0,(n&0xFF)-a)})`; }
}

class CarCustomizer2 {
    constructor(renderer) {
        this.renderer = renderer;
        this.modificaciones = {};
        this.init();
    }

    getModificaciones() { return this.modificaciones; }

    init() {
        this.crearFormulario();
        document.querySelectorAll('[data-mod2]').forEach(el => {
            const mod = el.dataset.mod2;
            const tipo = el.dataset.type2 || 'text';
            const handler = () => {
                let val;
                if (tipo === 'color') val = el.value;
                else if (tipo === 'rango') val = parseFloat(el.value);
                else if (tipo === 'booleano') val = el.checked;
                else if (tipo === 'lista') val = el.value;
                else val = el.value;
                this.renderer.update(mod, val);
                this.modificaciones[mod] = val;
                const display = el.dataset.display2;
                if (display) {
                    const dEl = document.getElementById(display);
                    if (dEl) {
                        if (tipo === 'lista') dEl.textContent = el.options[el.selectedIndex]?.text || val;
                        else dEl.textContent = val + (el.dataset.unit2 || '');
                    }
                }
                this.actualizar();
            };
            if (tipo === 'color') el.addEventListener('input', handler);
            else if (tipo === 'rango') el.addEventListener('input', handler);
            else if (tipo === 'booleano') el.addEventListener('change', handler);
            else if (tipo === 'lista') el.addEventListener('change', handler);
            handler();
        });

        document.querySelectorAll('.preset-color[data-target="color_carroceria2"]').forEach(el => {
            el.addEventListener('click', () => {
                const input = document.querySelector('[data-mod2="color_carroceria"]');
                if (input) { input.value = el.dataset.color; input.dispatchEvent(new Event('input', {bubbles:true})); }
            });
        });

        document.querySelectorAll('.tab-btn[data-tab2]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn[data-tab2]').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                document.querySelectorAll('.tab-panel2').forEach(p => p.classList.remove('active'));
                document.getElementById(btn.dataset.tab2)?.classList.add('active');
            });
        });

        this.actualizar();
    }

    crearFormulario() {
        const container = document.getElementById('personalizadorFormulario');
        if (!container) return;
        container.innerHTML = `
            <div class="panel-tab2 active" id="tab-car2">
                <div class="grupo-control"><div class="etiqueta-control"><span>🎨 Color carrocería</span></div><input type="color" data-mod2="color_carroceria" data-type2="color" value="#CC0000"></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>🏁 Kit aerodinámico</span></div><div class="toggle-wrapper"><div class="toggle-label"><span>Body Kit</span><span>Faldones + difusor</span></div><label class="toggle-switch"><input type="checkbox" data-mod2="kit_aerodinamico" data-type2="booleano"><span class="toggle-slider"></span></label></div></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>🪽 Alerón</span><span class="valor" id="valAleron2">Ninguno</span></div><select data-mod2="aleron_trasero" data-type2="lista" data-display2="valAleron2"><option value="ninguno">Ninguno</option><option value="pequeno">Pequeño</option><option value="medio">Medio</option><option value="grande">Grande</option></select></div>
            </div>
            <div class="panel-tab2" id="tab-llan2">
                <div class="grupo-control"><div class="etiqueta-control"><span>🎨 Color rines</span></div><input type="color" data-mod2="color_rines" data-type2="color" value="#C0C0C0"></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>📐 Tamaño rines</span><span class="valor" id="valRin2">15 pulg</span></div><input type="range" data-mod2="tamano_rines" data-type2="rango" data-display2="valRin2" data-unit2=" pulg" min="13" max="22" value="15"></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>💿 Tipo rin</span><span class="valor" id="valTipoRin2">Estándar</span></div><select data-mod2="tipo_rin" data-type2="lista" data-display2="valTipoRin2"><option value="estandar">Estándar</option><option value="deportivo">Deportivo</option><option value="cromado">Cromado</option><option value="black">Black</option></select></div>
            </div>
            <div class="panel-tab2" id="tab-vid2">
                <div class="grupo-control"><div class="etiqueta-control"><span>🪟 Polarizado frontal</span><span class="valor" id="valFront2">70%</span></div><div class="desc-control">Mín. 70% según Res. 3754</div><input type="range" data-mod2="polarizado_frontal" data-type2="rango" data-display2="valFront2" data-unit2="%" min="0" max="100" value="70"></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>🪟 Polarizado trasero</span><span class="valor" id="valRear2">50%</span></div><div class="desc-control">Mín. 50% según Res. 3754</div><input type="range" data-mod2="polarizado_trasero" data-type2="rango" data-display2="valRear2" data-unit2="%" min="0" max="100" value="50"></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>🪟 Parabrisas</span><span class="valor" id="valWs2">100%</span></div><div class="desc-control">⚠️ NO puede tener polarizado</div><input type="range" data-mod2="polarizado_parabrisas" data-type2="rango" data-display2="valWs2" data-unit2="%" min="0" max="100" value="100"></div>
            </div>
            <div class="panel-tab2" id="tab-sus2">
                <div class="grupo-control"><div class="etiqueta-control"><span>📏 Altura suspensión</span><span class="valor" id="valSus2">15 cm</span></div><div class="desc-control">Mín. 12 cm - Máx. 30 cm</div><input type="range" data-mod2="altura_suspension" data-type2="rango" data-display2="valSus2" data-unit2=" cm" min="5" max="30" value="15"></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>🏎️ Suspensión deportiva</span></div><div class="toggle-wrapper"><div class="toggle-label"><span>Suspensión competición</span><span>Rigidez ajustable</span></div><label class="toggle-switch"><input type="checkbox" data-mod2="suspension_deportiva" data-type2="booleano"><span class="toggle-slider"></span></label></div></div>
            </div>
            <div class="panel-tab2" id="tab-mot2">
                <div class="grupo-control"><div class="etiqueta-control"><span>🔧 Turbo</span></div><div class="toggle-wrapper"><div class="toggle-label"><span>Turbo compresor</span><span>Requiere certificación</span></div><label class="toggle-switch"><input type="checkbox" data-mod2="turbo" data-type2="booleano"><span class="toggle-slider"></span></label></div></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>💻 ECU Remap</span></div><div class="toggle-wrapper" style="border-color:rgba(239,68,68,0.2);"><div class="toggle-label"><span>Remapeo centralita</span><span style="color:var(--accent-red);">⚠️ ILEGAL sin certificación</span></div><label class="toggle-switch"><input type="checkbox" data-mod2="ecu_remap" data-type2="booleano"><span class="toggle-slider"></span></label></div></div>
            </div>
            <div class="panel-tab2" id="tab-esc2">
                <div class="grupo-control"><div class="etiqueta-control"><span>💨 Escape</span><span class="valor" id="valEsc2">Estándar</span></div><select data-mod2="sistema_escape" data-type2="lista" data-display2="valEsc2"><option value="estandar">Estándar</option><option value="deportivo">Deportivo</option><option value="libre">Libre (sin silenciador)</option></select></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>🔇 Silenciador deportivo</span></div><div class="toggle-wrapper"><div class="toggle-label"><span>Alto flujo</span><span>Requiere cert. ruido</span></div><label class="toggle-switch"><input type="checkbox" data-mod2="silenciador_deportivo" data-type2="booleano"><span class="toggle-slider"></span></label></div></div>
            </div>
            <div class="panel-tab2" id="tab-luc2">
                <div class="grupo-control"><div class="etiqueta-control"><span>💡 Faros LED</span></div><div class="toggle-wrapper"><div class="toggle-label"><span>Faros LED</span><span>Requieren alineación</span></div><label class="toggle-switch"><input type="checkbox" data-mod2="faros_led" data-type2="booleano"><span class="toggle-slider"></span></label></div></div>
                <div class="grupo-control"><div class="etiqueta-control"><span>💎 Luces neón</span></div><div class="toggle-wrapper"><div class="toggle-label"><span>Neón bajo</span><span>Solo blanco o ámbar</span></div><label class="toggle-switch"><input type="checkbox" data-mod2="luces_neon" data-type2="booleano"><span class="toggle-slider"></span></label></div></div>
            </div>`;
    }

    async actualizar() {
        const precios = {
            kit_aerodinamico: 2500000, aleron_trasero: 800000,
            suspension_deportiva: 3500000, turbo: 5000000, ecu_remap: 1500000,
            silenciador_deportivo: 1200000, faros_led: 800000, luces_neon: 350000,
        };
        let total = 0;
        for (const [k, v] of Object.entries(this.modificaciones)) {
            if (v === true && precios[k]) total += precios[k];
        }
        const tp = document.getElementById('precioTotal2');
        if (tp) tp.textContent = '$' + total.toLocaleString('es-CO');

        try {
            const resp = await fetch(CI4_BASE + '/personalizador/validar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ modificaciones: this.modificaciones })
            });
            const data = await resp.json();
            if (data.success) {
                const badge = document.getElementById('insigniaEstado2');
                if (badge) {
                    badge.textContent = data.estado_global.toUpperCase();
                    badge.className = 'badge-estado ' + data.estado_global;
                }
                const multa = document.getElementById('montoMulta2');
                if (multa) multa.textContent = '$' + (data.multa_total || 0).toLocaleString('es-CO');
            }
        } catch(e) {}
    }
}
