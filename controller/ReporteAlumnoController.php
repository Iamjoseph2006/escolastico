<?php
require_once __DIR__ . '/../model/Alumno.php';
require_once __DIR__ . '/../libs/fpdf/fpdf.php';
class ReporteAlumnoController {

    public function reporte() {
        // Validar ID
        $id_alumno = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id_alumno <= 0) die("ID inválido");

        // Obtener datos del modelo
        $alumnoModel = new Alumno();
        $data = $alumnoModel->obtenerPorIdParaReporte($id_alumno);

        if (!$data) die("No se encontró el alumno con ID: $id_alumno");

        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);

        // Encabezado
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, "REPORTE DE INDIVIDUAL DE ALUMNO", 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, "DATOS PERSONALES", 0, 1, 'C');
        $pdf->Ln(5);
        

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 8, "ID / CEDULA: " . utf8_decode($data['id_alumno']), 0, 1);
        $pdf->Cell(100, 8, "ALUMNO: " . utf8_decode($data['nombres'] . ' ' . $data['apellidos']), 0, 1);
        $pdf->Cell(100, 8, "CORREO: " . utf8_decode($data['correo']), 0, 1);
        $pdf->Cell(100, 8, "TELEFONO: " . utf8_decode($data['telefono']), 0, 1);
        $pdf->Cell(100, 8, "ESTADO: " . utf8_decode($data['estado']), 0, 1);
        $pdf->Ln(5);

        // Tabla de notas
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(220, 230, 240);
        $pdf->Cell(27, 10, "ID / CEDULA", 1, 0, 'C', true);
        $pdf->Cell(35, 10, "NOMBRE", 1, 0, 'C', true);
        $pdf->Cell(35, 10, "APELLIDOS", 1, 0, 'C', true);
        $pdf->Cell(65, 10, "CORREO", 1, 0, 'C', true);
        $pdf->Cell(30, 10, "TELEFONO", 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(27, 8, $data['id_alumno'], 1, 0, 'C');
        $pdf->Cell(35, 8, utf8_decode($data['nombres']), 1, 0, 'C');
        $pdf->Cell(35, 8, utf8_decode($data['apellidos']), 1, 0, 'C');
        $pdf->Cell(65, 8, utf8_decode($data['correo']), 1, 0, 'C');
        $pdf->Cell(30, 8, utf8_decode($data['telefono']), 1, 1, 'C');

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
$controller = new ReporteAlumnoController();
if (isset($_GET['accion']) && $_GET['accion'] === 'reporte') {
    $controller->reporte();
}
?>