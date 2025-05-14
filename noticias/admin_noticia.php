<?php
require_once 'conexion.php';
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Procesar eliminación de noticia
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $query = "DELETE FROM noticia WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$id]);
    header('Location: admin_noticias.php');
    exit;
}

// Obtener todas las noticias
$query = "SELECT id, titulo, contenido, imagen, imagen_tipo, fecha_publicacion 
          FROM noticia ORDER BY fecha_publicacion DESC";
$stmt = $conexion->prepare($query);
$stmt->execute();
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Noticias</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f7fa;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .user-name {
            font-weight: 500;
        }
        .logout-btn {
            padding: 8px 16px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .main-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-add {
            background-color: #2ecc71;
            color: white;
        }
        .btn-add:hover {
            background-color: #27ae60;
        }
        .noticias-list {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .noticia-item {
            display: flex;
            border-bottom: 1px solid #eee;
            padding: 20px;
            align-items: center;
        }
        .noticia-item:last-child {
            border-bottom: none;
        }
        .noticia-imagen {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 20px;
        }
        .noticia-content {
            flex-grow: 1;
        }
        .noticia-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .noticia-date {
            color: #7f8c8d;
            font-size: 0.85rem;
        }
        .noticia-actions {
            display: flex;
            gap: 10px;
        }
        .btn-edit {
            background-color: #3498db;
            color: white;
        }
        .btn-edit:hover {
            background-color: #2980b9;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        .no-noticias {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }
        @media (max-width: 768px) {
            .noticia-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .noticia-imagen {
                width: 100%;
                height: 150px;
                margin-right: 0;
                margin-bottom: 15px;
            }
            .noticia-actions {
                margin-top: 15px;
                width: 100%;
                justify-content: flex-end;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                padding: 15px;
            }
            .user-info {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Panel de Noticias</h1>
        <div class="user-info">
            <span class="user-name">Usuario: <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
            <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
        </div>
    </div>

    <div class="main-container">
        <div class="actions">
            <a href="agregar_noticia.php" class="btn btn-add">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                Agregar Noticia
            </a>
        </div>

        <div class="noticias-list">
            <?php if (empty($noticias)): ?>
                <div class="no-noticias">
                    <p>No hay noticias registradas</p>
                </div>
            <?php else: ?>
                <?php foreach ($noticias as $noticia): ?>
                    <div class="noticia-item">
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
                            <div class="noticia-imagen" style="background-color: #f5f7fa; display: flex; align-items: center; justify-content: center; color: #95a5a6;">
                                Sin imagen
                            </div>
                        <?php endif; ?>

                        <div class="noticia-content">
                            <h3 class="noticia-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                            <p class="noticia-date"><?php echo date('d/m/Y H:i', strtotime($noticia['fecha_publicacion'])); ?></p>
                        </div>

                        <div class="noticia-actions">
                            <a href="editar_noticia.php?id=<?php echo $noticia['id']; ?>" class="btn btn-edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                </svg>
                                Editar
                            </a>
                            <a href="admin_noticias.php?eliminar=<?php echo $noticia['id']; ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de eliminar esta noticia?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                                Eliminar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>