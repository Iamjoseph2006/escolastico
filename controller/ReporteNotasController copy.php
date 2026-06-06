<?php
require_once __DIR__ . '/../model/Notas.php';
require_once __DIR__ . '/../libs/fpdf/fpdf.php';
class ReporteNotasController {

    public function reporte() {
        // Validar ID
        $id_nota = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id_nota <= 0) die("ID inválido");

        // Obtener datos del modelo
        $notasModel = new Notas();
        $data = $notasModel->obtenerPorIdParaReporte($id_nota);

        if (!$data) die("No se encontró la nota con ID: $id_nota");

        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);

        // Encabezado
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, "REPORTE DE NOTAS", 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 8, "ALUMNO: " . utf8_decode($data['nombres'] . ' ' . $data['apellidos']), 0, 1);
        $pdf->Cell(100, 8, "MATERIA: " . utf8_decode($data['materia']), 0, 1);
        $pdf->Ln(5);

        // Tabla de notas
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(220, 230, 240);
        $pdf->Cell(47, 10, "Nota 1", 1, 0, 'C', true);
        $pdf->Cell(47, 10, "Nota 2", 1, 0, 'C', true);
        $pdf->Cell(47, 10, "Nota 3", 1, 0, 'C', true);
        $pdf->Cell(47, 10, "Promedio", 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(47, 8, $data['nota1'], 1, 0, 'C');
        $pdf->Cell(47, 8, $data['nota2'], 1, 0, 'C');
        $pdf->Cell(47, 8, $data['nota3'], 1, 0, 'C');
        $pdf->Cell(47, 8, $data['npromedio'], 1, 1, 'C');

        // Pie de página
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Generado el: ' . date('d/m/Y'), 0, 1, 'L');

        $pdf->Ln(20);
        $pdf->Cell(0, 10, 'Firma del Docente', 0, 1, 'C');

        // Salida PDF
        $pdf->Output();
    }
}

// Ejecutar método si se llama por URL
$controller = new ReporteNotasController();
if (isset($_GET['accion']) && $_GET['accion'] === 'reporte') {
    $controller->reporte();
}
?>