<?php
require_once 'conexion.php';

// Configuración de paginación
$noticiasPorPagina = 8; // Mostrar 8 noticias inicialmente
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $noticiasPorPagina;

// Obtener el total de noticias
$queryTotal = "SELECT COUNT(*) as total FROM noticia";
$stmtTotal = $conexion->query($queryTotal);
$totalNoticias = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
$totalPaginas = ceil($totalNoticias / $noticiasPorPagina);

// Obtener noticias para la página actual
$query = "SELECT id, titulo, SUBSTRING(contenido, 1, 150) as resumen, 
          imagen, imagen_tipo, fecha_publicacion 
          FROM noticia ORDER BY fecha_publicacion DESC 
          LIMIT :limit OFFSET :offset";
$stmt = $conexion->prepare($query);
$stmt->bindParam(':limit', $noticiasPorPagina, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Noticias</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            width: 100%;
            min-height: 100vh;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 25px 0;
            text-align: center;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 {
            font-size: 2.2rem;
            font-weight: 600;
        }
        .main-container {
            width: 100%;
            padding: 30px 5%;
            background-color: #f8f9fa;
        }
        .noticias-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            max-width: 1800px;
            margin: 0 auto;
        }
        .noticia {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background: #fff;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0,0,0,0.05);
        }
        .noticia-imagen-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background-color: #f5f7fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .noticia-imagen {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .noticia-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .noticia h2 {
            
            margin: 12px 0 12px 0;
            color: #2c3e50;
            font-size: 1.2rem;
            line-height: 1.4;
            font-weight: 600;
        }
        .fecha {
            color: #7f8c8d;
            font-size: 0.8rem;
            margin-bottom: 15px;
        }
        .resumen {
            font-size: 0.95rem;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
        }
        .leer-mas {
            display: inline-block;
            padding: 10px 18px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            text-align: center;
            transition: all 0.3s ease;
            align-self: flex-start;
            font-weight: 500;
        }
        .paginacion {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
            gap: 8px;
        }
        .paginacion a {
            padding: 8px 16px;
            background-color: #ecf0f1;
            color: #2c3e50;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .paginacion a:hover, .paginacion a.activa {
            background-color: #3498db;
            color: white;
        }
        .contador-noticias {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        @media (max-width: 1200px) {
            .noticias-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 900px) {
            .noticias-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 600px) {
            .noticias-grid {
                grid-template-columns: 1fr;
            }
            .paginacion {
                gap: 5px;
            }
            .paginacion a {
                padding: 6px 12px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Últimas Noticias</h1>
    </div>
    
    <div class="main-container">
        <?php if (empty($noticias)): ?>
            <div class="no-noticias">
                <p>No hay noticias disponibles en este momento.</p>
            </div>
        <?php else: ?>
            <div class="noticias-grid">
                <?php foreach ($noticias as $noticia): ?>
                    <div class="noticia">
                        <div class="noticia-imagen-container">
                            <?php if ($noticia['imagen']): ?>
                                <?php 
                                $imagen_data = $noticia['imagen'];
                                $imagen_tipo = $noticia['imagen_tipo'];
                                $imagen_base64 = base64_encode($imagen_data);
                                ?>
                                <img src="data:<?php echo $imagen_tipo; ?>;base64,<?php echo $imagen_base64; ?>" 
                                     alt="<?php echo htmlspecialchars($noticia['titulo']); ?>" 
                                     class="noticia-imagen">
                            <?php else: ?>
                                <div class="sin-imagen">Imagen no disponible</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="noticia-content">
                            <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                            <p class="fecha"><?php echo date('d M Y', strtotime($noticia['fecha_publicacion'])); ?></p>
                            
                            <p class="resumen"><?php echo htmlspecialchars($noticia['resumen']); ?></p>
                            
                            <a href="noticias/detalle_noticia.php?id=<?php echo $noticia['id']; ?>" class="leer-mas">Leer más</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="contador-noticias">
                Mostrando <?php echo count($noticias); ?> de <?php echo $totalNoticias; ?> noticias
            </div>

            <?php if ($totalPaginas > 1): ?>
                <div class="paginacion">
                    <?php if ($paginaActual > 1): ?>
                        <a href="?pagina=<?php echo $paginaActual - 1; ?>">&laquo; Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <a href="?pagina=<?php echo $i; ?>" <?php echo ($i == $paginaActual) ? 'class="activa"' : ''; ?>>
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>