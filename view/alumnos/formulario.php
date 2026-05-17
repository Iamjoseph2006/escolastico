<?php

require_once '../../model/Alumno.php';

$alumno = new Alumno();

$data = [
    'id_alumno' => '',
    'nombres' => '',
    'apellidos' => '',
    'correo' => '',
    'telefono' => '',
    'estado' => ''
];

if (isset($_GET['id'])) {
    $data = $alumno->obtenerPorId($_GET['id']) ?? $data;

}

$alumnos = $alumno->obtenerTodos();

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Alumnos</title>

  <!-- Bootstrap para el diseño visual -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Íconos de Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fb;
      font-family: 'Segoe UI', sans-serif;
      padding: 3rem 1rem;
    }

    .main-container {
      max-width: 1100px;
      margin: auto;
    }

    .card-custom {
      background-color: #ffffff;
      border-radius: 0.75rem;
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .title {
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 600;
      color: #333;
    }

    .form-control {
      height: 46px;
      border-radius: 0.375rem;
      padding-left: 2.5rem;
    }

    .form-select {
      height: 46px;
      border-radius: 0.375rem;
      padding-left: 2.5rem;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .form-group i {
      position: absolute;
      top: 50%;
      left: 0.75rem;
      transform: translateY(-50%);
      color: #6c757d;
      z-index: 2;
    }

    .btn-save {
      background-color: #007bff;
      border: none;
      color: white;
      font-weight: 500;
      padding: 0.75rem;
      width: 100%;
      border-radius: 0.375rem;
    }

    .btn-save:hover {
      background-color: #0069d9;
    }

    .btn-clear {
      width: 100%;
      font-weight: 500;
      border-radius: 0.375rem;
      padding: 0.75rem;
    }

    .table thead th {
      background-color: #007bff;
      color: white;
      font-weight: 500;
      border: none;
    }

    .table td, .table th {
      vertical-align: middle;
    }

    .badge-activo {
      background-color: #198754;
      color: white;
      padding: 0.35rem 0.65rem;
      border-radius: 0.375rem;
      font-size: 0.85rem;
    }

    .badge-inactivo {
      background-color: #dc3545;
      color: white;
      padding: 0.35rem 0.65rem;
      border-radius: 0.375rem;
      font-size: 0.85rem;
    }

    .action-btn {
      border: none;
      background: transparent;
      font-size: 1.2rem;
      margin: 0 0.25rem;
      text-decoration: none;
    }

    .edit-btn {
      color: #ffc107;
    }

    .delete-btn {
      color: #dc3545;
    }

    .back-link {
      text-decoration: none;
      color: #007bff;
      font-size: 0.95rem;
      display: inline-block;
      margin-bottom: 1rem;
    }
  </style>
</head>

<body>

<div class="main-container">

  <!-- Enlace para volver al dashboard -->
  <a href="../dashboard/dashboard.php" class="back-link">
    <i class="bi bi-arrow-left-circle"></i> Volver al dashboard
  </a>

  <!-- Tarjeta del formulario -->
  <div class="card-custom">

    <!-- Título dinámico: cambia entre Registro y Editar -->
    <h4 class="title">
      <i class="bi bi-person-plus-fill"></i>
      <?= isset($_GET['id']) ? 'Editar Alumno' : 'Registro de Alumno' ?>
    </h4>

    <!-- Formulario que envía los datos al controlador -->
    <form action="../../controller/AlumnoController.php" method="POST">

      <div class="row">

        <!-- Campo ID del alumno -->
        <div class="col-md-4">
          <div class="form-group">
            <i class="bi bi-hash"></i>
            <input 
              type="number" 
              class="form-control" 
              id="id_alumno" 
              name="id_alumno" 
              placeholder="Código del alumno"
              value="<?= htmlspecialchars($data['id_alumno']) ?>"
              <?= isset($_GET['id']) ? 'readonly' : '' ?>
              required>
          </div>
        </div>

        <!-- Campo nombres -->
        <div class="col-md-4">
          <div class="form-group">
            <i class="bi bi-person-fill"></i>
            <input 
              type="text" 
              class="form-control" 
              id="nombres" 
              name="nombres" 
              placeholder="Nombres"
              value="<?= htmlspecialchars($data['nombres']) ?>"
              required>
          </div>
        </div>

        <!-- Campo apellidos -->
        <div class="col-md-4">
          <div class="form-group">
            <i class="bi bi-person-lines-fill"></i>
            <input 
              type="text" 
              class="form-control" 
              id="apellidos" 
              name="apellidos" 
              placeholder="Apellidos"
              value="<?= htmlspecialchars($data['apellidos']) ?>"
              required>
          </div>
        </div>

        <!-- Campo correo -->
        <div class="col-md-4">
          <div class="form-group">
            <i class="bi bi-envelope-fill"></i>
            <input 
              type="email" 
              class="form-control" 
              id="correo" 
              name="correo" 
              placeholder="Correo electrónico"
              value="<?= htmlspecialchars($data['correo']) ?>">
          </div>
        </div>

        <!-- Campo teléfono -->
        <div class="col-md-4">
          <div class="form-group">
            <i class="bi bi-telephone-fill"></i>
            <input 
              type="text" 
              class="form-control" 
              id="telefono" 
              name="telefono" 
              placeholder="Teléfono"
              value="<?= htmlspecialchars($data['telefono']) ?>">
          </div>
        </div>

        <!-- Campo estado -->
        <div class="col-md-4">
          <div class="form-group">
            <i class="bi bi-toggle-on"></i>
            <select 
              class="form-select" 
              id="estado" 
              name="estado"
              required>
              <option value="">Seleccione estado</option>
              <option value="ACTIVO" <?= $data['estado'] == 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
              <option value="INACTIVO" <?= $data['estado'] == 'INACTIVO' ? 'selected' : '' ?>>Inactivo</option>
            </select>
          </div>
        </div>

      </div>

      <!-- Campo oculto para indicar edición -->
      <?php if (isset($_GET['id'])): ?>
        <input type="hidden" name="accion" value="editar">
      <?php endif; ?>

      <div class="d-flex gap-2 mt-2">

        <!-- Botón guardar o actualizar -->
        <button type="submit" class="btn-save">
          <i class="bi bi-save me-2"></i>
          <?= isset($_GET['id']) ? 'Actualizar Alumno' : 'Guardar Alumno' ?>
        </button>

        <!-- Botón limpiar -->
        <a href="formulario.php" class="btn btn-outline-secondary btn-clear">
          <i class="bi bi-x-circle me-2"></i>
          Limpiar
        </a>

      </div>

    </form>
  </div>

  <!-- Tarjeta de la tabla -->
  <div class="card-custom">

    <h4 class="title">
      <i class="bi bi-table"></i>
      Lista de Alumnos
    </h4>

    <div class="table-responsive">

      <table class="table table-hover align-middle">

        <thead>
          <tr>
            <th>ID Alumno</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Estado</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>

        <tbody>

          <!-- Se recorren todos los alumnos de la base de datos -->
          <?php foreach ($alumnos as $a): ?>

            <tr>
              <td><?= htmlspecialchars($a['id_alumno']) ?></td>
              <td><?= htmlspecialchars($a['nombres']) ?></td>
              <td><?= htmlspecialchars($a['apellidos']) ?></td>
              <td><?= htmlspecialchars($a['correo']) ?></td>
              <td><?= htmlspecialchars($a['telefono']) ?></td>

              <td>
                <?php if ($a['estado'] == 'ACTIVO'): ?>
                  <span class="badge-activo">Activo</span>
                <?php else: ?>
                  <span class="badge-inactivo">Inactivo</span>
                <?php endif; ?>
              </td>

              <td class="text-center">

                <!-- Botón editar -->
                <a 
                  href="formulario.php?id=<?= $a['id_alumno'] ?>" 
                  class="action-btn edit-btn" 
                  title="Editar">
                  <i class="bi bi-pencil-square"></i>
                </a>

                <!-- Botón eliminar -->
                <a 
                  href="../../controller/AlumnoController.php?eliminar=<?= $a['id_alumno'] ?>" 
                  class="action-btn delete-btn" 
                  title="Eliminar"
                  onclick="return confirm('¿Está seguro de eliminar este alumno?');">
                  <i class="bi bi-trash-fill"></i>
                </a>

              </td>
            </tr>

          <?php endforeach; ?>

        </tbody>

      </table>

    </div>
  </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>