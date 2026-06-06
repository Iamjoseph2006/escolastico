<?php
require_once __DIR__ . '/../config/Auth.php';
require_once __DIR__ . '/../model/Asistencia.php';
require_once __DIR__ . '/../libs/fpdf/fpdf.php';

class ReporteAsistenciaController {

    public function reporte() {
        require_alumno_or_secretaria();
        // Validar ID
        $id_asistencia = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id_asistencia <= 0) die("ID invalido");

        // Obtener datos del modelo
        $asistenciaModel = new Asistencia();
        $data = $asistenciaModel->obtenerPorIdParaReporte($id_asistencia);

        if (!$data) die("No se encontro la asistencia con ID: $id_asistencia");
        if (!can_access_alumno($data['id_alumno'])) deny_access();

        // Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 20);

        // Encabezado principal
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(37, 99, 235);
        $pdf->Cell(0, 15, "REPORTE DE ASISTENCIA", 0, 1, 'C');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(2);

        // Linea divisoria
        $pdf->SetDrawColor(220, 230, 240);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(3);

        // Informacion del alumno
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, "INFORMACION DEL ALUMNO", 0, 1);
        $pdf->Ln(2);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(50, 7, "ID / CEDULA:", 0, 0);
        $pdf->Cell(0, 7, utf8_decode($data['id_alumno']), 0, 1);

        $pdf->Cell(50, 7, "NOMBRE COMPLETO:", 0, 0);
        $pdf->Cell(0, 7, utf8_decode($data['nombres'] . ' ' . $data['apellidos']), 0, 1);

        $pdf->Ln(4);

        // Informacion de la asistencia
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, "INFORMACION DE LA ASISTENCIA", 0, 1);
        $pdf->Ln(2);

        // Tabla de asistencia
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(37, 99, 235);
        $pdf->SetTextColor(255, 255, 255);

        $pdf->Cell(60, 10, "MATERIA", 1, 0, 'C', true);
        $pdf->Cell(40, 10, "CREDITOS", 1, 0, 'C', true);
        $pdf->Cell(40, 10, "HORAS CREDITO", 1, 0, 'C', true);
        $pdf->Cell(40, 10, "FALTAS", 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(240, 248, 255);

        $pdf->Cell(60, 10, utf8_decode($data['materia']), 1, 0, 'C', true);
        $pdf->Cell(40, 10, $data['creditos'], 1, 0, 'C', true);
        $pdf->Cell(40, 10, number_format($data['horas_credito'], 2), 1, 0, 'C', true);
        $pdf->Cell(40, 10, $data['numero_faltas'], 1, 1, 'C', true);

        $porcentaje_asistencia = (float)$data['porcentaje_asistencia'];

        if ($porcentaje_asistencia >= 90) {
            $estado = "EXCELENTE";
            $color = [34, 197, 94];
        } elseif ($porcentaje_asistencia >= 80) {
            $estado = "BUENO";
            $color = [59, 130, 246];
        } elseif ($porcentaje_asistencia >= 70) {
            $estado = "ACEPTABLE";
            $color = [251, 146, 60];
        } else {
            $estado = "DEFICIENTE";
            $color = [239, 68, 68];
        }

        // Resumen destacado
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(220, 230, 240);
        $pdf->Cell(100, 10, "HORAS DE FALTAS:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetFillColor(37, 99, 235);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(80, 10, number_format($data['horas_faltas'], 2), 1, 1, 'C', true);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(220, 230, 240);
        $pdf->Cell(100, 10, "PORCENTAJE DE ASISTENCIA:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetFillColor(37, 99, 235);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(80, 10, number_format($data['porcentaje_asistencia'], 2) . "%", 1, 1, 'C', true);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(220, 230, 240);
        $pdf->Cell(100, 10, "PORCENTAJE DE INASISTENCIA:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetFillColor(37, 99, 235);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(80, 10, number_format($data['porcentaje_inasistencia'], 2) . "%", 1, 1, 'C', true);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(220, 230, 240);
        $pdf->Cell(100, 10, "ESTADO DE ASISTENCIA:", 1, 0, 'R', true);
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetFillColor($color[0], $color[1], $color[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(80, 10, $estado, 1, 1, 'C', true);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(10);

        // Pie de pagina
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

// Ejecutar metodo si se llama por URL
$controller = new ReporteAsistenciaController();
if (isset($_GET['accion']) && $_GET['accion'] === 'reporte') {
    $controller->reporte();
}
?>
