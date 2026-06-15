<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    protected ?Dompdf $dompdf = null;

    public function __construct()
    {
        // Verificación de seguridad por si la librería no está instalada
        if (!class_exists(\Dompdf\Dompdf::class)) {
            throw new \RuntimeException('La librería Dompdf no está instalada. Ejecute: composer require dompdf/dompdf');
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        
        $this->dompdf = new Dompdf($options);
    }

    /**
     * Renderiza una vista de CI4 y la convierte en PDF
     * 
     * @param string $viewPath Ruta de la vista
     * @param array $data Datos para la vista
     * @param string $filename Nombre del archivo de salida
     * @param string $paper Tamaño de papel (A4, letter, etc)
     * @param string $orientation (portrait o landscape)
     */
    public function generate(string $viewPath, array $data = [], string $filename = 'reporte.pdf', string $paper = 'A4', string $orientation = 'portrait'): void
    {
        // Añadir datos globales del sistema para el encabezado/pie
        $globalData = [
            'empresa' => "AutoMod Pro",
            'fecha_generacion' => date('d/m/Y H:i:s'),
            'generado_por' => session('nombre_usuario') ?? 'Sistema'
        ];
        $data = array_merge($globalData, $data);

        // Cargar el HTML desde la vista de CI4
        $html = view($viewPath, $data);

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();

        // Agregar numeración de páginas (Script de Dompdf)
        $canvas = $this->dompdf->getCanvas();
        $canvas->page_text(520, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, [0, 0, 0]);

        // Forzar descarga o visualización en el navegador
        /**
         * Dompdf::stream() envía headers HTTP y el contenido del PDF directamente.
         * Para seguir las mejores prácticas de CI4, terminamos la ejecución aquí
         * para evitar que el framework intente renderizar algo más.
         */
        $this->dompdf->stream($filename, ["Attachment" => false]);

        exit();
    }
}