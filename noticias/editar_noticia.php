<?php
require_once 'conexion.php';
session_start();

// Verificar sesión y permisos
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar que se proporcionó un ID válido
if (!isset($_GET['id'])) {
    header('Location: admin_noticias.php');
    exit;
}

$id = $_GET['id'];

// Obtener la noticia a editar
$query = "SELECT * FROM noticia WHERE id = ?";
$stmt = $conexion->prepare($query);
$stmt->execute([$id]);
$noticia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$noticia) {
    header('Location: admin_noticias.php');
    exit;
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar botón Cancelar
    if (isset($_POST['cancelar'])) {
        header('Location: admin_noticia.php');
        exit;
    }

    // Procesar botón Guardar
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    
    if ($_FILES['imagen']['size'] > 0) {
        // Si se subió una nueva imagen
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        $imagen_tipo = $_FILES['imagen']['type'];
        
        $query = "UPDATE noticia SET titulo = ?, contenido = ?, imagen = ?, imagen_tipo = ? WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$titulo, $contenido, $imagen, $imagen_tipo, $id]);
    } else {
        // Mantener la imagen actual
        $query = "UPDATE noticia SET titulo = ?, contenido = ? WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$titulo, $contenido, $id]);
    }
    
    header('Location: admin_noticias.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
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
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .form-container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
        }
        textarea {
            min-height: 200px;
            resize: vertical;
        }
        .imagen-preview {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 4px;
            display: block;
        }
        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }
        .btn-guardar {
            background-color: #2ecc71;
            color: white;
        }
        .btn-guardar:hover {
            background-color: #27ae60;
        }
        .btn-cancelar {
            background-color: #95a5a6;
            color: white;
        }
        .btn-cancelar:hover {
            background-color: #7f8c8d;
        }
        .current-image {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 5px;
        }
        @media (max-width: 768px) {
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
            .main-container {
                padding: 0 15px;
            }
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Editar Noticia</h1>
        <div class="user-info">
            <span class="user-name">Usuario: <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
            <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
        </div>
    </div>

    <div class="main-container">
        <div class="form-container">
            <form action="editar_noticia.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="contenido">Contenido:</label>
                    <textarea id="contenido" name="contenido" required><?php echo htmlspecialchars($noticia['contenido']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="imagen">Imagen (opcional):</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                    
                    <?php if ($noticia['imagen']): ?>
                        <p class="current-image">Imagen actual:</p>
                        <?php 
                        $imagen_data = $noticia['imagen'];
                        $imagen_tipo = $noticia['imagen_tipo'];
                        $imagen_base64 = base64_encode($imagen_data);
                        ?>
                        <img src="data:<?php echo $imagen_tipo; ?>;base64,<?php echo $imagen_base64; ?>" 
                             alt="Imagen actual" 
                             class="imagen-preview">
                    <?php else: ?>
                        <p class="current-image">No hay imagen actual</p>
                    <?php endif; ?>
                </div>
                
                <div class="btn-group">
                    <button type="submit" name="cancelar" class="btn btn-cancelar">Cancelar</button>
                    <button type="submit" class="btn btn-guardar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mostrar vista previa de la nueva imagen seleccionada
        document.getElementById('imagen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.createElement('img');
                    preview.src = event.target.result;
                    preview.className = 'imagen-preview';
                    preview.alt = 'Vista previa de la nueva imagen';
                    
                    const currentPreview = document.querySelector('.imagen-preview');
                    if (currentPreview) {
                        currentPreview.replaceWith(preview);
                    } else {
                        const currentImageText = document.querySelector('.current-image');
                        currentImageText.after(preview);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>