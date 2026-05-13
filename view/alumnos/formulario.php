<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Alumnos</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f6fa;
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem 1rem;
    }

    .main-container {
      max-width: 1100px;
      margin: auto;
    }

    .card-custom {
      background-color: #ffffff;
      border-radius: 14px;
      border: none;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .title {
      font-weight: 600;
      color: #222;
      margin-bottom: 1.5rem;
    }

    .form-group {
      position: relative;
      margin-bottom: 1rem;
    }

    .form-group i {
      position: absolute;
      top: 50%;
      left: 14px;
      transform: translateY(-50%);
      color: #777;
      font-size: 1rem;
    }

    .form-control,
    .form-select {
      height: 46px;
      border-radius: 8px;
      padding-left: 42px;
      font-size: 0.95rem;
    }

    .btn-save {
      height: 46px;
      border: none;
      border-radius: 8px;
      background-color: #0d6efd;
      color: white;
      font-weight: 500;
      padding: 0 1.5rem;
    }

    .btn-save:hover {
      background-color: #0b5ed7;
    }

    .btn-clear {
      height: 46px;
      border-radius: 8px;
      padding: 0 1.5rem;
    }

    .table {
      margin-bottom: 0;
    }

    .table thead th {
      background-color: #f1f3f5;
      color: #333;
      font-weight: 600;
      font-size: 0.9rem;
    }

    .table tbody td {
      vertical-align: middle;
      font-size: 0.92rem;
    }

    .badge-activo {
      background-color: #d1e7dd;
      color: #0f5132;
      padding: 0.45rem 0.7rem;
      border-radius: 20px;
      font-size: 0.8rem;
    }

    .badge-inactivo {
      background-color: #f8d7da;
      color: #842029;
      padding: 0.45rem 0.7rem;
      border-radius: 20px;
      font-size: 0.8rem;
    }

    .action-btn {
      border: none;
      background: none;
      font-size: 1rem;
      margin-right: 0.4rem;
    }

    .edit-btn {
      color: #0d6efd;
    }

    .delete-btn {
      color: #dc3545;
    }
  </style>
</head>

<body>

  <div class="main-container">

    <!-- FORMULARIO -->
    <div class="card-custom">
      <h4 class="title">
        <i class="bi bi-person-plus-fill"></i>
        Registro de Alumno
      </h4>

      <form action="" method="POST">

        <div class="row">

          <div class="col-md-4">
            <div class="form-group">
              <i class="bi bi-hash"></i>
              <input 
                type="number" 
                class="form-control" 
                id="id_alumno" 
                name="id_alumno" 
                placeholder="Código del alumno"
                required>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <i class="bi bi-person-fill"></i>
              <input 
                type="text" 
                class="form-control" 
                id="nombres" 
                name="nombres" 
                placeholder="Nombres"
                required>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <i class="bi bi-person-lines-fill"></i>
              <input 
                type="text" 
                class="form-control" 
                id="apellidos" 
                name="apellidos" 
                placeholder="Apellidos"
                required>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <i class="bi bi-envelope-fill"></i>
              <input 
                type="email" 
                class="form-control" 
                id="correo" 
                name="correo" 
                placeholder="Correo electrónico">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <i class="bi bi-telephone-fill"></i>
              <input 
                type="text" 
                class="form-control" 
                id="telefono" 
                name="telefono" 
                placeholder="Teléfono">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <i class="bi bi-toggle-on"></i>
              <select 
                class="form-select" 
                id="estado" 
                name="estado"
                required>
                <option value="">Seleccione estado</option>
                <option value="ACTIVO">Activo</option>
                <option value="INACTIVO">Inactivo</option>
              </select>
            </div>
          </div>

        </div>

        <div class="d-flex gap-2 mt-2">
          <button type="submit" class="btn-save">
            <i class="bi bi-save me-2"></i>
            Guardar Alumno
          </button>

          <button type="reset" class="btn btn-outline-secondary btn-clear">
            <i class="bi bi-x-circle me-2"></i>
            Limpiar
          </button>
        </div>

      </form>
    </div>

    <!-- TABLA -->
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
            <tr>
              <td>1001</td>
              <td>Juan Carlos</td>
              <td>Pérez López</td>
              <td>juan@correo.com</td>
              <td>77777777</td>
              <td>
                <span class="badge-activo">Activo</span>
              </td>
              <td class="text-center">
                <button class="action-btn edit-btn" title="Editar">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <button class="action-btn delete-btn" title="Eliminar">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>

            <tr>
              <td>1002</td>
              <td>María Fernanda</td>
              <td>Gómez Ruiz</td>
              <td>maria@correo.com</td>
              <td>66666666</td>
              <td>
                <span class="badge-inactivo">Inactivo</span>
              </td>
              <td class="text-center">
                <button class="action-btn edit-btn" title="Editar">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <button class="action-btn delete-btn" title="Eliminar">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
          </tbody>

        </table>
      </div>
    </div>

  </div>

</body>
</html>