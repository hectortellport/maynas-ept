<?php
require_once 'conexion.php';

if (!isset($_GET['id'])) {
    header('Location: ../index.php');
    exit;
}

$id = $_GET['id'];

// Obtener la noticia completa
$query = "SELECT n.id, n.titulo, n.contenido, n.imagen, n.imagen_tipo, n.fecha_publicacion, u.nombre as autor 
          FROM noticia n 
          JOIN usuario u ON n.usuario_id = u.id 
          WHERE n.id = ?";
$stmt = $conexion->prepare($query);
$stmt->execute([$id]);
$noticia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$noticia) {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($noticia['titulo']); ?> - Portal de Noticias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .imagen-noticia {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
        }
        .fecha, .autor {
            color: #666;
            font-size: 0.9em;
        }
        .volver {
            display: inline-block;
            margin-top: 20px;
            color: #0066cc;
            text-decoration: none;
        }
        .volver:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../index.php" class="volver">&larr; Volver a noticias</a>
        
        <h1><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        
        <p class="autor">Por: <?php echo htmlspecialchars($noticia['autor']); ?></p>
        <p class="fecha">Publicado el: <?php echo date('d/m/Y H:i', strtotime($noticia['fecha_publicacion'])); ?></p>
        
        <?php if ($noticia['imagen']): ?>
            <?php 
            $imagen_data = $noticia['imagen'];
            $imagen_tipo = $noticia['imagen_tipo'];
            $imagen_base64 = base64_encode($imagen_data);
            ?>
            <img src="data:<?php echo $imagen_tipo; ?>;base64,<?php echo $imagen_base64; ?>" 
                 alt="<?php echo htmlspecialchars($noticia['titulo']); ?>" 
                 class="imagen-noticia">
        <?php endif; ?>
        
        <p><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
    </div>
</body>
</html>