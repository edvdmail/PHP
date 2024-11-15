<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de Archivos</title>
    <!-- Vincular el archivo CSS -->
    <style>
        h2 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-align: center;
            padding: 15px 0;
            margin-bottom: 20px;
            background-color: #0196d4;
            color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        li {
            background-color: #f0f8ff;
            color: #333;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 8px;
            transition: background-color 0.3s, box-shadow 0.3s;
            list-style-type: none;
            box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.1);
        }

        li:hover {
            background-color: #e0f2ff;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .button {
            background-color: #0196d4;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #ff7a39;
        }

        .button:active {
            background-color: #01ae6c;
        }

        iframe {
            width: 100%;
            height: 600px;
            border: none;
            margin-top: 20px;
        }

    </style>
</head>
<body>

<?php
session_start();

if (isset($_POST['path'])) {
    $newPath = $_POST['path'];
    $newPath = urldecode($newPath);
    if (is_dir($newPath)) {
        $_SESSION['path'] = $newPath;
    } else {
        unset($_SESSION['path']);
        echo "<script>alert('La carpeta no existe. Se ha vuelto a la ruta inicial.');</script>";
    }
}

$folderPath = isset($_SESSION['path']) ? $_SESSION['path'] : '.';

if (substr($folderPath, -1) !== '/') {
    $folderPath .= '/';
}

$baseUrl = 'https://teker.maxapex.net/FILES_PROD_TEKER/gral/';

if (is_dir($folderPath)) {
    $files = scandir($folderPath);

    echo "<h2>Documentos Generales</h2>";
    echo "<ul>";

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $filePath = $folderPath . $file;
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), ['css', 'php', 'html'])) {
                if (is_dir($filePath)) {
                    $dirUrl = urlencode($filePath);
                    echo "<li>
                            <form method='POST' style='display:inline;'>
                               <button class='button' type='submit' name='path' value='" . $dirUrl . "'>" . rtrim($file, '/') . "</button>
                            </form>
                          </li>";
                } else {
                    $fileNameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
                    $extension = strtolower($fileExtension);

                    // Modificar para que el archivo se cargue en el iframe
                    echo "<li><a href='#' onclick='loadFile(\"" . $filePath . "\")' data-extension='$extension'><span>$fileNameWithoutExtension</span></a></li>";
                }
            }
        }
    }

    echo "</ul>";

    if (isset($_SESSION['path']) && $_SESSION['path'] !== '.') {
        $parentPath = dirname($folderPath);
        echo "<p>
                <form method='POST' style='display:inline;'>
                    <button type='submit' class='button' name='path' value='" . urlencode($parentPath) . "'>Volver a la carpeta anterior</button>
                </form>
              </p>";
    }

} else {
    echo "La carpeta especificada no existe.";
}
?>

<!-- iframe para mostrar el archivo seleccionado -->
<iframe id="fileViewer" src="" frameborder="0"></iframe>

<script>
// Función para cargar el archivo seleccionado en el iframe
function loadFile(filePath) {
    var iframe = document.getElementById('fileViewer');
    iframe.src = filePath; // Establecer la fuente del iframe con la ruta del archivo
}
</script>

</body>
</html>
