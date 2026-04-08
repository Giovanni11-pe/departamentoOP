<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Panel Principal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* ===== FONDO ANIMADO ===== */
body{
    height:100vh;
    margin:0;
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

/* ===== CENTRADO ===== */
.main-box{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:15px;
}

/* ===== CARD ===== */
.card-panel{
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    border-radius:25px;
    padding:40px;
    width:100%;
    max-width:420px;
    text-align:center;
    color:white;
    box-shadow:0 10px 40px rgba(0,0,0,0.2);
}

/* ===== TÍTULO ===== */
.card-panel h2{
    font-weight:600;
    margin-bottom:25px;
}

/* ===== BOTONES PRO ===== */
.btn-menu{
    width:100%;
    padding:14px;
    border:none;
    border-radius:15px;
    margin-bottom:12px;
    font-size:16px;
    font-weight:500;
    color:white;

    background: linear-gradient(135deg, #308576, #FF8000);

    text-decoration: none;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;

    transition: all 0.3s ease;
}

/* Quitar subrayado SIEMPRE */
.btn-menu:link,
.btn-menu:visited,
.btn-menu:hover,
.btn-menu:active{
    text-decoration:none;
    color:white;
}

/* HOVER PRO */
.btn-menu:hover{
    transform: translateY(-4px) scale(1.03);
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
    filter: brightness(1.1);
}

/* CLICK */
.btn-menu:active{
    transform: scale(0.97);
}

/* FOOTER */
.footer{
    margin-top:15px;
    font-size:12px;
    opacity:0.8;
}

/* ===== RESPONSIVE ===== */
@media(max-width:480px){

    .card-panel{
        padding:25px;
    }

    .card-panel h2{
        font-size:20px;
    }

    .btn-menu{
        font-size:15px;
        padding:12px;
    }
}

</style>
</head>

<body>

<div class="main-box">

<div class="card-panel">

<h2>✨ Sistema General</h2>

<a href="objetos.php" class="btn-menu">
📦 Objetos Perdidos
</a>

<a href="guardarropa.php" class="btn-menu">
🧥 Guardarropa
</a>

</div>

</div>

</body>
</html>