<?php
include "config/conexion.php";

$busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : "";

/* GUARDAR */
if(isset($_POST['guardar'])){
    $stmt = $conexion->prepare("INSERT INTO guardarropa 
    (nombre, congregacion, celular, descripcion, ubicacion, ficha, casillero, estado) 
    VALUES (?,?,?,?,?,?,?,?)");

    $stmt->execute([
        $_POST['nombre'],
        $_POST['congregacion'],
        $_POST['celular'],
        $_POST['descripcion'],
        $_POST['ubicacion'],
        $_POST['ficha'],
        $_POST['casillero'],
        $_POST['estado']
    ]);

    header("Location: guardarropa.php");
    exit;
}

/* ACTUALIZAR */
if(isset($_POST['actualizar'])){
    $stmt = $conexion->prepare("UPDATE guardarropa SET 
    nombre=?, congregacion=?, celular=?, descripcion=?, ubicacion=?, ficha=?, casillero=?, estado=? WHERE id=?");

    $stmt->execute([
        $_POST['nombre'], 
        $_POST['congregacion'], 
        $_POST['celular'],
        $_POST['descripcion'], 
        $_POST['ubicacion'], 
        $_POST['ficha'],
        $_POST['casillero'], 
        $_POST['estado'], 
        $_POST['id']
    ]);

    header("Location: guardarropa.php");
    exit;
}

/* ELIMINAR */
if(isset($_GET['eliminar'])){
    $stmt = $conexion->prepare("DELETE FROM guardarropa WHERE id=?");
    $stmt->execute([$_GET['eliminar']]);
    header("Location: guardarropa.php");
    exit;
}

/* CONSULTA */
if($busqueda != ""){
    $stmt = $conexion->prepare("SELECT * FROM guardarropa WHERE nombre LIKE ? OR ficha LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$busqueda%", "%$busqueda%"]);
    $datos = $stmt->fetchAll();
}else{
    $datos = $conexion->query("SELECT * FROM guardarropa ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🧥 Guardarropa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ⭐ DISEÑO EXACTO DEL OBJETOS PERDIDOS ⭐ -->
<style>
html, body{
    margin:0; padding:0; min-height:100vh;
    font-family:'Segoe UI', sans-serif;
    background: linear-gradient(-45deg, #308576, #FF8000, #2f7d6d, #ff944d);
    background-size: 400% 400%;
    animation: gradientMove 10s ease infinite;
}
@keyframes gradientMove{
    0%{ background-position: 0% 50%; }
    50%{ background-position: 100% 50%; }
    100%{ background-position: 0% 50%; }
}
.container-box{
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    border-radius:25px; padding:25px;
    box-shadow:0 10px 40px rgba(0,0,0,0.2);
    color:white;
}
.btn-modern{
    background: linear-gradient(135deg,#308576,#FF8000);
    color:white; border:none; padding:10px 16px;
    border-radius:14px; display:flex; align-items:center;
    gap:6px; font-size:14px; transition:0.3s; text-decoration:none;
}
.btn-modern:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 25px rgba(0,0,0,0.25); color:white;
}
.table-custom{
    border-radius:25px; overflow:hidden;
    background:white; color:#333; margin-bottom:0;
}
.table-custom thead{ background:#308576; color:white; }
.table-custom tbody tr{
    background: rgba(255,255,255,0.95);
    border-bottom: 5px solid transparent;
    transition: all 0.3s ease;
}
.table-custom tbody tr:hover{
    background: rgba(255,255,255,1);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-bottom: 5px solid #308576;
}
.btn-action{
    width:42px; height:42px; border:none; border-radius:12px;
    display:flex; align-items:center; justify-content:center;
    font-size:16px; font-weight:bold; transition:all 0.3s ease;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
    margin:0 2px; text-decoration:none; color:white;
}
.btn-action:hover{
    transform:translateY(-3px) scale(1.05);
    box-shadow:0 8px 20px rgba(0,0,0,0.25);
    border:none !important; color:white;
}
.btnVer{ background:linear-gradient(135deg,#3b82f6,#1d4ed8) !important; }
.btnVer:hover{ background:linear-gradient(135deg,#1d4ed8,#1e3a8a) !important; }
.btnEditar{ background:linear-gradient(135deg,#10b981,#059669) !important; }
.btnEditar:hover{ background:linear-gradient(135deg,#059669,#047857) !important; }
.btnEliminar{ background:linear-gradient(135deg,#ef4444,#dc2626) !important; }
.btnEliminar:hover{ background:linear-gradient(135deg,#dc2626,#b91c1c) !important; }

.badge-ok{
    background:#d1fae5; color:#065f46;
    padding:6px 10px; border-radius:10px; font-weight:500;
}
.badge-pend{
    background:#fef3c7; color:#92400e;
    padding:6px 10px; border-radius:10px; font-weight:500;
}

/* ===== SECCIONES MODAL VER (ADAPTADAS PARA GUARDARROPA) ===== */
.seccion-info{
    background:rgba(0,0,0,0.04);
    border-radius:12px; padding:12px 15px; margin:8px 0;
}
.seccion-datos{
    background:linear-gradient(135deg,rgba(48,133,118,0.08),rgba(48,133,118,0.15));
    border-left:4px solid #308576;
    border-radius:12px; padding:12px 15px; margin:8px 0;
}
.seccion-titulo{
    font-size:13px; font-weight:700;
    letter-spacing:1px; text-transform:uppercase; margin-bottom:8px;
}
.seccion-titulo.verde{ color:#308576; }
.seccion-fila{
    display:flex; align-items:center;
    gap:8px; margin-bottom:5px;
    font-size:14px; color:#333;
}
.seccion-fila b{ min-width:100px; color:#555; font-size:13px; }

.form-control{
    border-radius:12px; padding:10px;
    border:none; background:rgba(255,255,255,0.95);
}
.modal-content{ border-radius:20px; }

@media(max-width:768px){
    .container-box{ padding:15px; }
    .table-custom thead{ display:none; }
    .table-custom, .table-custom tbody,
    .table-custom tr, .table-custom td{
        display:block; width:100%;
    }
    .table-custom tr{
        margin-bottom:8px !important; border-radius:15px;
        padding:15px; box-shadow:0 4px 12px rgba(0,0,0,0.1);
    }
    .table-custom td{
        border:none; position:relative; padding-left:50%;
    }
    .table-custom td::before{
        content:attr(data-label) ": ";
        font-weight:bold; font-size:12px; color:#6c757d;
        position:absolute; left:15px; white-space:nowrap;
    }
    .btn-action{ width:100%; height:45px; margin-bottom:5px; font-size:18px; }
    .d-flex{ flex-direction:column; gap:5px; }
}
</style>
</head>
<body>

<div class="container mt-4">
<div class="container-box">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <h4 class="mb-2">🧥 Guardarropa</h4>
    <div class="d-flex gap-2 flex-wrap">
        <a href="index.php" class="btn-modern">🏠 Inicio</a>
        <button onclick="location.href='guardarropa.php'" class="btn-modern">🔄 Actualizar</button>
        <button class="btn-modern" data-bs-toggle="modal" data-bs-target="#modalAgregar">➕ Registrar</button>
    </div>
</div>

<!-- BUSCADOR -->
<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="buscar" class="form-control"
               placeholder="🔍 Buscar nombre o ficha..." value="<?= htmlspecialchars($busqueda) ?>">
        <button class="btn-modern" type="submit">Buscar</button>
        <a href="guardarropa.php" class="btn btn-secondary">Limpiar</a>
    </div>
</form>

<!-- TABLA -->
<table class="table table-custom">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Ficha</th>
            <th>Casillero</th>
            <th>Congregación</th>
            <th>Celular</th>
            <th>Ubicación</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($datos as $r): ?>
        <tr>
            <td data-label="Nombre"><?= htmlspecialchars($r['nombre']) ?></td>
            <td data-label="Ficha"><?= htmlspecialchars($r['ficha']) ?></td>
            <td data-label="Casillero"><?= htmlspecialchars($r['casillero']) ?></td>
            <td data-label="Congregación"><?= htmlspecialchars($r['congregacion']) ?></td>
            <td data-label="Celular"><?= htmlspecialchars($r['celular']) ?></td>
            <td data-label="Ubicación"><?= htmlspecialchars($r['ubicacion']) ?></td>
            <td data-label="Fecha"><?= date('d/m/Y H:i', strtotime($r['fecha_registro'])) ?></td>
            <td data-label="Estado">
                <?php if($r['estado']=="pendiente"): ?>
                    <span class="badge-pend">⏳ Pendiente</span>
                <?php else: ?>
                    <span class="badge-ok">✅ Entregado</span>
                <?php endif; ?>
            </td>
            <td data-label="Acciones">
                <div class="d-flex gap-1 justify-content-center">
                    <!-- 👁 VER -->
                    <button class="btn-action btnVer"
                            data-json='<?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>'
                            title="Ver detalle">👁</button>
                    
                    <!-- ✏️ EDITAR -->
                    <button class="btn-action btnEditar"
                            data-id="<?= $r['id'] ?>"
                            data-nombre="<?= htmlspecialchars($r['nombre']) ?>"
                            data-congregacion="<?= htmlspecialchars($r['congregacion']) ?>"
                            data-celular="<?= htmlspecialchars($r['celular']) ?>"
                            data-descripcion="<?= htmlspecialchars($r['descripcion']) ?>"
                            data-ubicacion="<?= htmlspecialchars($r['ubicacion']) ?>"
                            data-ficha="<?= htmlspecialchars($r['ficha']) ?>"
                            data-casillero="<?= htmlspecialchars($r['casillero']) ?>"
                            data-estado="<?= $r['estado'] ?>"
                            title="Editar">✏️</button>
                    
                    <!-- 🗑️ ELIMINAR -->
                    <a href="?eliminar=<?= $r['id'] ?>"
                       class="btn-action btnEliminar"
                       onclick="return confirm('¿Eliminar este registro?')"
                       title="Eliminar">🗑️</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>
</div>

<!-- MODAL VER -->
<div class="modal fade" id="modalVer" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">👁 Detalle del Registro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div id="detalle"></div>
</div>
</div>
</div>

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregar">
<div class="modal-dialog modal-dialog-centered modal-lg">
<div class="modal-content p-3">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">➕ Nuevo Registro</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<form method="POST">
    <input name="nombre" class="form-control mb-3" placeholder="👤 Nombre completo" required>
    <input name="congregacion" class="form-control mb-3" placeholder="⛪ Congregación" required>
    <input name="celular" class="form-control mb-3" placeholder="📱 Celular" required>
    <input name="ficha" class="form-control mb-3" placeholder="🆔 Ficha (ej: 2024-001)" required>
    <input name="casillero" class="form-control mb-3" placeholder="📦 Casillero (ej: A-15)" required>
    
    <select name="ubicacion" class="form-control mb-3" required>
        <option value="" disabled selected>📍 Ubicación</option>
        <option>Auditorio principal</option>
        <option>Auditorio octogonal</option>
    </select>

    <textarea name="descripcion" class="form-control mb-3" rows="3" placeholder="📝 Descripción (talla, color, etc...)"></textarea>

    <select name="estado" class="form-control mb-3" required>
        <option value="pendiente">⏳ Pendiente</option>
        <option value="entregado">✅ Entregado</option>
    </select>

    <button name="guardar" class="btn btn-success w-100 btn-modern">💾 Guardar Registro</button>
</form>
</div>
</div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar">
<div class="modal-dialog modal-dialog-centered modal-lg">
<div class="modal-content p-3">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">✏️ Editar Registro</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<form method="POST">
    <input type="hidden" name="id" id="edit_id">
    
    <input name="nombre" id="edit_nombre" class="form-control mb-3" placeholder="👤 Nombre completo" required>
    <input name="congregacion" id="edit_congregacion" class="form-control mb-3" placeholder="⛪ Congregación" required>
    <input name="celular" id="edit_celular" class="form-control mb-3" placeholder="📱 Celular" required>
    <input name="ficha" id="edit_ficha" class="form-control mb-3" placeholder="🆔 Ficha" required>
    <input name="casillero" id="edit_casillero" class="form-control mb-3" placeholder="📦 Casillero" required>
    
    <select name="ubicacion" id="edit_ubicacion" class="form-control mb-3" required>
        <option value="">📍 Ubicación</option>
        <option>Auditorio principal</option>
        <option>Auditorio octogonal</option>
    </select>

    <textarea name="descripcion" id="edit_descripcion" class="form-control mb-3" rows="3" placeholder="📝 Descripción"></textarea>

    <select name="estado" id="edit_estado" class="form-control mb-3" required>
        <option value="pendiente">⏳ Pendiente</option>
        <option value="entregado">✅ Entregado</option>
    </select>

    <button name="actualizar" class="btn btn-primary w-100 btn-modern">💾 Actualizar</button>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// EDITAR
document.querySelectorAll(".btnEditar").forEach(btn=>{
    btn.onclick=()=>{
        document.getElementById('edit_id').value = btn.dataset.id;
        document.getElementById('edit_nombre').value = btn.dataset.nombre;
        document.getElementById('edit_congregacion').value = btn.dataset.congregacion;
        document.getElementById('edit_celular').value = btn.dataset.celular;
        document.getElementById('edit_ficha').value = btn.dataset.ficha;
        document.getElementById('edit_casillero').value = btn.dataset.casillero;
        document.getElementById('edit_ubicacion').value = btn.dataset.ubicacion;
        document.getElementById('edit_descripcion').value = btn.dataset.descripcion || '';
        document.getElementById('edit_estado').value = btn.dataset.estado;
        
                new bootstrap.Modal(document.getElementById('modalEditar')).show();
    }
});

// VER
document.querySelectorAll(".btnVer").forEach(btn=>{
    btn.onclick=()=>{
        try {
            let d = JSON.parse(btn.dataset.json);
            document.getElementById('detalle').innerHTML = `
            <div class="text-start">

                <div class="seccion-info">
                    <div class="seccion-fila"><b>👤 Nombre:</b> ${d.nombre}</div>
                    <div class="seccion-fila"><b>🆔 Ficha:</b> ${d.ficha}</div>
                    <div class="seccion-fila"><b>📦 Casillero:</b> ${d.casillero}</div>
                    <div class="seccion-fila"><b>📍 Ubicación:</b> ${d.ubicacion}</div>
                    <div class="seccion-fila"><b>📅 Fecha:</b> ${new Date(d.fecha_registro).toLocaleString('es-ES')}</div>
                </div>

                <div class="seccion-datos">
                    <div class="seccion-titulo verde">📋 Datos del Usuario</div>
                    <div class="seccion-fila"><b>⛪ Congregación:</b> ${d.congregacion}</div>
                    <div class="seccion-fila"><b>📱 Celular:</b> ${d.celular}</div>
                    <div class="seccion-fila"><b>📝 Descripción:</b> ${d.descripcion || 'Sin descripción'}</div>
                </div>

                <div class="text-center mt-3">
                    <span class="${d.estado === 'entregado' ? 'badge-ok' : 'badge-pend'}" style="font-size:15px; padding:8px 18px;">
                        ${d.estado === 'entregado' ? '✅ Entregado' : '⏳ Pendiente'}
                    </span>
                </div>

            </div>`;
            new bootstrap.Modal(document.getElementById('modalVer')).show();
        } catch(e) {
            console.error('Error:', e);
        }
    }
});
</script>

</body>
</html>