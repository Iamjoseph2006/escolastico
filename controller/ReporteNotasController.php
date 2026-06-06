<?php
require_once __DIR__ . '/../config/Auth.php';
require_once __DIR__ . '/../model/Notas.php';
require_once __DIR__ . '/../libs/fpdf/fpdf.php';

class ReporteNotasController {

    public function reporte() {
        require_alumno_or_secretaria();
        // Validar ID
        $id_nota = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id_nota <= 0) die("ID inválido");

        // Obtener datos del modelo
        $notasModel = new Notas();
        $data = $notasModel->obtenerPorIdParaReporte($id_nota);

        if (!$data) die("No se encontró la nota con ID: $id_nota");
        if (!can_access_alumno($data['id_alumno'])) deny_access();

        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);

        // Encabezado principal
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(37, 99, 235);
        $pdf->Cell(0, 15, "REPORTE DE NOTAS", 0, 1, 'C');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(2);

        // Línea divisoria
        $pdf->SetDrawColor(220, 230, 240);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(3);

        // Información del alumno
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8,utf8_decode("INFORMACIÓN DEL ALUMNO"), 0, 1);
        $pdf->Ln(2);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, 7, "ID / CEDULA:", 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($data['id_alumno']), 0, 1);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, 7, "NOMBRE COMPLETO:", 0, 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($data['nombres'] . ' ' . $data['apellidos']), 0, 1);

        $pdf->Ln(4);

        // Información de la nota
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode("INFORMACIÓN DE LA NOTA"), 0, 1);
        $pdf->Ln(2);

        // Tabla de notas
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(37, 99, 235);
        $pdf->SetTextColor(255, 255, 255);

        $pdf->Cell(60, 10, "MATERIA", 1, 0, 'C', true);
        $pdf->Cell(40, 10, "NOTA 1", 1, 0, 'C', true);
        $pdf->Cell(40, 10, "NOTA 2", 1, 0, 'C', true);
        $pdf->Cell(40, 10, "NOTA 3", 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(240, 248, 255);

        $pdf->Cell(60, 10, utf8_decode($data['materia']), 1, 0, 'C', true);
        $pdf->Cell(40, 10, $data['nota1'], 1, 0, 'C', true);
        $pdf->Cell(40, 10, $data['nota2'], 1, 0, 'C', true);
        $pdf->Cell(40, 10, $data['nota3'], 1, 1, 'C', true);

        // Promedio destacado
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(220, 230, 240);
        $pdf->Cell(100, 10, "PROMEDIO FINAL:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetFillColor(37, 99, 235);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(80, 10, number_format($data['npromedio'], 2), 1, 1, 'C', true);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(10);

        // Pie de página
        $pdf->SetFont('Arial', 'I', 9);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 6, 'Generado el: ' . date('d/m/Y H:i'), 0, 1, 'L');

        $pdf->Ln(15);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 8, '_' . str_repeat('_', 50), 0, 1, 'C');
        $pdf->Cell(0, 6, 'Firma del Docente', 0, 1, 'C');

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
