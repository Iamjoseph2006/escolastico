<?php
require_once '../../config/Auth.php';
require_alumno_or_secretaria();

require_once '../../model/Archivo.php';
require_once '../../model/Alumno.php';

$archivo = new Archivo();
$alumno  = new Alumno();

$archivos = $archivo->obtenerTodo();

if (is_alumno()) {
    $idAlumno = auth_alumno_id();
    $archivos = array_values(array_filter($archivos, function ($a) use ($idAlumno) {
        return (string)$a['id_alumno'] === (string)$idAlumno;
    }));
}

$alumnos  = is_secretaria() ? $alumno->obtenerTodo() : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carga de Archivos PDF</title>

  <!-- Bootstrap para el diseño visual -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Íconos de Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --bg: #f4f7fb;
      --surface: rgba(255, 255, 255, 0.92);
      --text: #172033;
      --muted: #667085;
      --line: #e6eaf0;
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --primary-soft: #eaf1ff;
      --success: #16a34a;
      --success-soft: #e9f9ef;
      --danger: #dc2626;
      --danger-soft: #fff1f2;
      --warning: #d97706;
      --warning-soft: #fffbeb;
      --shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
      --radius-lg: 28px;
      --radius-md: 18px;
      --radius-sm: 14px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      min-height: 100vh;
      margin: 0;
      background:
        radial-gradient(circle at top left, rgba(37, 99, 235, 0.13), transparent 32rem),
        radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.11), transparent 30rem),
        var(--bg);
      color: var(--text);
      font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
      padding: 2rem 1rem 3rem;
    }

    .main-container {
      width: min(1160px, 100%);
      margin: 0 auto;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1.25rem;
      padding: 0.7rem 1rem;
      border: 1px solid var(--line);
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.76);
      color: var(--text);
      font-size: 0.95rem;
      font-weight: 700;
      text-decoration: none;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
      backdrop-filter: blur(16px);
      transition: all 0.2s ease;
    }

    .back-link:hover {
      border-color: rgba(37, 99, 235, 0.28);
      color: var(--primary);
      transform: translateY(-1px);
      box-shadow: 0 14px 32px rgba(37, 99, 235, 0.12);
    }

    .card-custom {
      position: relative;
      overflow: hidden;
      margin-bottom: 1.5rem;
      padding: 1.5rem;
      border: 1px solid rgba(230, 234, 240, 0.95);
      border-radius: var(--radius-lg);
      background: var(--surface);
      box-shadow: var(--shadow);
      backdrop-filter: blur(18px);
    }

    .card-custom::before {
      content: "";
      position: absolute;
      inset: 0 0 auto;
      height: 5px;
      background: linear-gradient(90deg, var(--primary), #0ea5e9);
    }

    .title {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      margin: 0 0 1.6rem;
      color: var(--text);
      font-size: 1.35rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      text-align: center;
    }

    .title i {
      display: inline-grid;
      place-items: center;
      width: 44px;
      height: 44px;
      border-radius: 15px;
      background: var(--primary-soft);
      color: var(--primary);
      font-size: 1.2rem;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.2rem;
    }

    .form-group i {
      position: absolute;
      top: 50%;
      left: 1rem;
      z-index: 2;
      color: var(--muted);
      transform: translateY(-50%);
      transition: color 0.2s ease;
    }

    .form-group:focus-within i {
      color: var(--primary);
    }

    .form-control,
    .form-select {
      min-height: 52px;
      border: 1px solid var(--line);
      border-radius: var(--radius-sm);
      background-color: rgba(255, 255, 255, 0.94);
      color: var(--text);
      font-size: 0.95rem;
      padding-left: 2.75rem;
      box-shadow: none;
      transition: all 0.2s ease;
    }

    .form-control::placeholder {
      color: #98a2b3;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: rgba(37, 99, 235, 0.55);
      background-color: #ffffff;
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .btn-save,
    .btn-clear {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      min-height: 52px;
      border-radius: var(--radius-sm);
      font-weight: 800;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .btn-save {
      width: 100%;
      border: none;
      background: linear-gradient(135deg, var(--primary), #0ea5e9);
      color: #ffffff;
      padding: 0 1.4rem;
      box-shadow: 0 14px 30px rgba(37, 99, 235, 0.22);
    }

    .btn-save:hover {
      background: linear-gradient(135deg, var(--primary-dark), #0284c7);
      transform: translateY(-1px);
      box-shadow: 0 18px 36px rgba(37, 99, 235, 0.28);
    }

    .btn-clear {
      width: 100%;
      border: 1px solid var(--line) !important;
      background: #ffffff !important;
      color: #475467 !important;
      padding: 0 1.25rem;
    }

    .btn-clear:hover {
      border-color: #cbd5e1 !important;
      background: #f8fafc !important;
      color: var(--text) !important;
      transform: translateY(-1px);
    }

    .table-responsive {
      padding: 0.15rem;
    }

    .table {
      margin: 0;
      border-collapse: separate;
      border-spacing: 0 0.55rem;
    }

    .table thead th {
      border: 0;
      background: transparent;
      color: var(--muted);
      font-size: 0.76rem;
      font-weight: 800;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 0.75rem 1rem;
      white-space: nowrap;
    }

    .table tbody tr {
      border-radius: var(--radius-md);
      box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
      transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
      transform: translateY(-1px);
      box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
    }

    .table tbody td {
      border-top: 1px solid var(--line);
      border-bottom: 1px solid var(--line);
      background: #ffffff;
      color: #344054;
      padding: 1rem;
      vertical-align: middle;
    }

    .table tbody td:first-child {
      border-left: 1px solid var(--line);
      border-top-left-radius: var(--radius-md);
      border-bottom-left-radius: var(--radius-md);
      color: var(--primary);
      font-weight: 800;
    }

    .table tbody td:last-child {
      border-right: 1px solid var(--line);
      border-top-right-radius: var(--radius-md);
      border-bottom-right-radius: var(--radius-md);
    }

    .badge-pdf {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.45rem 0.75rem;
      border-radius: 999px;
      background: var(--primary-soft);
      color: var(--primary);
      font-size: 0.82rem;
      font-weight: 800;
      white-space: nowrap;
    }

    .action-btn {
      display: inline-grid;
      place-items: center;
      width: 40px;
      height: 40px;
      margin: 0 0.12rem;
      border: 1px solid transparent;
      border-radius: 12px;
      background: transparent;
      font-size: 1.05rem;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .view-btn {
      background: var(--success-soft);
      color: var(--success);
    }

    .delete-btn {
      background: var(--danger-soft);
      color: var(--danger);
    }

    .action-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
    }

    .empty-row {
      text-align: center;
      color: var(--muted);
      font-weight: 600;
      padding: 1.4rem !important;
    }

    @media (max-width: 768px) {
      body {
        padding: 1rem 0.75rem 2rem;
      }

      .card-custom {
        padding: 1.15rem;
        border-radius: 22px;
      }

      .title {
        align-items: flex-start;
        justify-content: flex-start;
        text-align: left;
      }

      .btn-clear {
        width: 100%;
      }
    }
  </style>
</head>

<body>

<div class="main-container">

  <a href="../dashboard/dashboard.php" class="back-link">
    <i class="bi bi-arrow-left-circle"></i>
    Volver al dashboard
  </a>

  <?php if (is_secretaria()): ?>
  <div class="card-custom">
    <h2 class="title">
      <i class="bi bi-file-earmark-pdf-fill"></i>
      Carga de Archivos PDF
    </h2>

    <form action="../../controller/ArchivoController.php" method="POST" enctype="multipart/form-data">
      <div class="row">

        <div class="col-md-4">
          <div class="form-group">
            <i class="bi bi-file-earmark-text"></i>
            <input 
              type="text" 
              name="nombre_archivo" 
              class="form-control" 
              placeholder="Nombre Documento" 
              required>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <i class="bi bi-person-fill"></i>
            <select name="id_alumno" class="form-select" required>
              <option value="">Seleccione alumno</option>
              <?php foreach ($alumnos as $a): ?>
                <option value="<?= htmlspecialchars($a['id_alumno']) ?>">
                  <?= htmlspecialchars($a['nombres']) ?> <?= htmlspecialchars($a['apellidos']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <i class="bi bi-upload"></i>
            <input 
              type="file" 
              name="archivo_pdf" 
              accept=".pdf" 
              class="form-control" 
              required>
          </div>
        </div>

        <div class="col-md-10">
          <button type="submit" class="btn-save">
            <i class="bi bi-save"></i>
            Guardar Archivo
          </button>
        </div>

        <div class="col-md-2">
          <button type="reset" class="btn-clear">
            <i class="bi bi-x-circle"></i>
            Limpiar
          </button>
        </div>

      </div>
    </form>
  </div>
  <?php endif; ?>

  <div class="card-custom">
    <h2 class="title">
      <i class="bi bi-table"></i>
      <?= is_secretaria() ? 'Lista de Archivos' : 'Mis Archivos' ?>
    </h2>

    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID Registro</th>
            <th>Alumno</th>
            <th>Documento</th>
            <th>PDF</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody>
          <?php if (!empty($archivos)): ?>
            <?php foreach ($archivos as $a): ?>
              <tr>
                <td><?= htmlspecialchars($a['id_archivo']) ?></td>

                <td>
                  <?= htmlspecialchars($a['nombres']) ?>
                  <?= htmlspecialchars($a['apellidos']) ?>
                </td>

                <td>
                  <span class="badge-pdf">
                    <i class="bi bi-file-earmark-text"></i>
                    <?= htmlspecialchars($a['nombre_archivo']) ?>
                  </span>
                </td>

                <td>
                  <a 
                    href="../../uploads/<?= htmlspecialchars($a['archivo_pdf']) ?>" 
                    target="_blank" 
                    class="action-btn view-btn"
                    title="Ver PDF">
                    <i class="bi bi-file-earmark-pdf"></i>
                  </a>
                </td>

                <td><?= htmlspecialchars($a['fecha_subida']) ?></td>

                <td>
                  <?php if (is_secretaria()): ?>
                    <a
                      href="../../controller/ArchivoController.php?eliminar=<?= htmlspecialchars($a['id_archivo']) ?>"
                      class="action-btn delete-btn"
                      title="Eliminar"
                      onclick="return confirm('¿Eliminar archivo?')">
                      <i class="bi bi-trash-fill"></i>
                    </a>
                  <?php else: ?>
                    <span class="badge-pdf">
                      <i class="bi bi-eye-fill"></i>
                      Consulta
                    </span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="empty-row">
                No hay archivos registrados.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>

      </table>
    </div>
  </div>

</div>

</body>
</html>
