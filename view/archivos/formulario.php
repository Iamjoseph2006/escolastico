<?php
require_once '../../model/Archivo.php';
require_once '../../model/Alumno.php';

$archivo = new Archivo();
$alumno  = new Alumno();

$archivos = $archivo->obtenerTodo();
$alumnos  = $alumno->obtenerTodo();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carga de Archivos PDF</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Íconos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --bg: #f4f7fb;
      --surface: rgba(255, 255, 255, 0.94);
      --text: #172033;
      --muted: #667085;
      --line: #dbe4ef;
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
      padding: 1.4rem 0 3rem;
    }

    .main-container {
      width: min(1160px, 100%);
      margin: 0 auto;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 0.55rem;
      margin-bottom: 1.4rem;
      padding: 0.85rem 1.2rem;
      border: 1px solid var(--line);
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.85);
      color: var(--text);
      font-size: 0.95rem;
      font-weight: 800;
      text-decoration: none;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
      backdrop-filter: blur(16px);
      transition: all 0.2s ease;
    }

    .back-link:hover {
      color: var(--primary);
      border-color: rgba(37, 99, 235, 0.28);
      transform: translateY(-1px);
    }

    .card-custom {
      position: relative;
      overflow: hidden;
      margin-bottom: 1.9rem;
      padding: 2rem 1.8rem 1.5rem;
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
      height: 6px;
      background: linear-gradient(90deg, var(--primary), #0ea5e9);
    }

    .title {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.85rem;
      margin: 0 0 1.8rem;
      color: var(--text);
      font-size: 1.45rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      text-align: center;
    }

    .title i {
      display: inline-grid;
      place-items: center;
      width: 52px;
      height: 52px;
      border-radius: 16px;
      background: var(--primary-soft);
      color: var(--primary);
      font-size: 1.3rem;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.2rem;
    }

    .form-group > i.icon-left {
      position: absolute;
      top: 50%;
      left: 1rem;
      z-index: 3;
      color: var(--muted);
      transform: translateY(-50%);
      transition: color 0.2s ease;
    }

    .form-group:focus-within > i.icon-left {
      color: var(--primary);
    }

    .form-control,
    .form-select {
      min-height: 60px;
      border: 1px solid var(--line);
      border-radius: var(--radius-sm);
      background-color: rgba(255, 255, 255, 0.96);
      color: var(--text);
      font-size: 0.98rem;
      padding-left: 3rem;
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

    /* INPUT FILE PERSONALIZADO */
    .file-upload-wrap {
      position: relative;
      min-height: 60px;
      border: 1px solid var(--line);
      border-radius: var(--radius-sm);
      background: rgba(255, 255, 255, 0.96);
      display: flex;
      align-items: stretch;
      overflow: hidden;
      transition: all 0.2s ease;
    }

    .file-upload-wrap:focus-within {
      border-color: rgba(37, 99, 235, 0.55);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
      background: #ffffff;
    }

    .file-upload-icon {
      width: 56px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--muted);
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    .file-upload-btn {
      display: flex;
      align-items: center;
      padding: 0 1.1rem;
      background: #ffffff;
      border-left: 1px solid var(--line);
      border-right: 1px solid var(--line);
      font-weight: 600;
      color: #344054;
      white-space: nowrap;
      flex-shrink: 0;
    }

    .file-upload-name {
      display: flex;
      align-items: center;
      padding: 0 1rem;
      color: #475467;
      font-size: 0.95rem;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      flex: 1;
      min-width: 0;
    }

    .file-input-native {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
    }

    .btn-save,
    .btn-clear {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      min-height: 60px;
      border-radius: var(--radius-sm);
      font-weight: 800;
      text-decoration: none;
      transition: all 0.2s ease;
      font-size: 0.98rem;
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
      padding: 0.2rem;
    }

    .table {
      margin: 0;
      border-collapse: separate;
      border-spacing: 0 0.7rem;
    }

    .table thead th {
      border: 0;
      background: transparent;
      color: var(--muted);
      font-size: 0.8rem;
      font-weight: 800;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 0.85rem 1rem;
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
      gap: 0.45rem;
      padding: 0.48rem 0.78rem;
      border-radius: 999px;
      background: var(--primary-soft);
      color: var(--primary);
      font-size: 0.83rem;
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

    @media (max-width: 1200px) {
      .main-container {
        width: min(100%, calc(100% - 48px));
      }
    }

    @media (max-width: 768px) {
      body {
        padding: 1rem 0 2rem;
      }

      .main-container {
        width: calc(100% - 24px);
      }

      .card-custom {
        padding: 1.15rem;
        border-radius: 22px;
      }

      .title {
        font-size: 1.2rem;
        justify-content: flex-start;
        text-align: left;
      }

      .file-upload-wrap {
        flex-wrap: wrap;
        min-height: auto;
      }

      .file-upload-icon {
        height: 58px;
      }

      .file-upload-btn {
        min-height: 58px;
      }

      .file-upload-name {
        width: 100%;
        min-height: 50px;
        border-top: 1px solid var(--line);
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

  <div class="card-custom">
    <h2 class="title">
      <i class="bi bi-file-earmark-pdf-fill"></i>
      Carga de Archivos PDF
    </h2>

    <form action="../../controller/ArchivoController.php" method="POST" enctype="multipart/form-data">
      <div class="row g-3">

        <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <i class="bi bi-file-earmark-text icon-left"></i>
            <input
              type="text"
              name="nombre_archivo"
              class="form-control"
              placeholder="Nombre Documento"
              required>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <i class="bi bi-person-fill icon-left"></i>
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

        <div class="col-lg-5 col-md-12">
          <div class="form-group">
            <label class="file-upload-wrap">
              <span class="file-upload-icon">
                <i class="bi bi-upload"></i>
              </span>

              <span class="file-upload-btn">Seleccionar archivo</span>

              <span class="file-upload-name" id="fileName">
                Sin archivos seleccionados
              </span>

              <input
                type="file"
                name="archivo_pdf"
                id="archivo_pdf"
                accept=".pdf"
                class="file-input-native"
                required>
            </label>
          </div>
        </div>

        <div class="col-lg-10 col-md-9">
          <button type="submit" class="btn-save">
            <i class="bi bi-save"></i>
            Guardar Archivo
          </button>
        </div>

        <div class="col-lg-2 col-md-3">
          <button type="reset" class="btn-clear" id="btnReset">
            <i class="bi bi-x-circle"></i>
            Limpiar
          </button>
        </div>

      </div>
    </form>
  </div>

  <div class="card-custom">
    <h2 class="title">
      <i class="bi bi-table"></i>
      Lista de Archivos
    </h2>

    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
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
                <td><?= htmlspecialchars($a['nombres']) ?> <?= htmlspecialchars($a['apellidos']) ?></td>
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
                  <a
                    href="../../controller/ArchivoController.php?eliminar=<?= htmlspecialchars($a['id_archivo']) ?>"
                    class="action-btn delete-btn"
                    title="Eliminar"
                    onclick="return confirm('¿Eliminar archivo?')">
                    <i class="bi bi-trash-fill"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="empty-row">No hay archivos registrados.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
  const archivoInput = document.getElementById('archivo_pdf');
  const fileName = document.getElementById('fileName');
  const btnReset = document.getElementById('btnReset');

  archivoInput.addEventListener('change', function () {
    if (this.files.length > 0) {
      fileName.textContent = this.files[0].name;
    } else {
      fileName.textContent = 'Sin archivos seleccionados';
    }
  });

  btnReset.addEventListener('click', function () {
    setTimeout(() => {
      fileName.textContent = 'Sin archivos seleccionados';
    }, 50);
  });
</script>

</body>
</html>