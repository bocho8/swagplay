<?php
include '../config/db_connect.php';
include 'verificar_sesion.php';
session_start();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // claramente chat me ayudo
        $sql = "SELECT p.*,
                GROUP_CONCAT(c.categoria) as categorias_nombres,
                GROUP_CONCAT(c.id_categoria) as categorias_ids
                FROM pelicula p
                LEFT JOIN pelicula_categoria pc ON p.id_pelicula = pc.id_pelicula
                LEFT JOIN categorias c ON pc.id_categoria = c.id_categoria
                GROUP BY p.id_pelicula";

        $result = $conn->query($sql);
        $peliculas = [];
        while ($row = $result->fetch_assoc()) {
            $row['calificacion_usuarios'] = number_format($row['calificacion_usuarios'], 2);
            $row['categorias'] = [];
            if ($row['categorias_nombres']) {
                $nombres = explode(',', $row['categorias_nombres']);
                $ids = explode(',', $row['categorias_ids']);
                for ($i = 0; $i < count($nombres); $i++) {
                    $row['categorias'][] = [
                        'id' => $ids[$i],
                        'nombre' => $nombres[$i]
                    ];
                }
            }
            // Eliminar las columnas auxiliares
            unset($row['categorias_nombres']);
            unset($row['categorias_ids']);
            $peliculas[] = $row;
        }
        echo json_encode(['peliculas' => $peliculas]);
        break;

    case 'POST':
        verificarPermisosGestor();
        $data = json_decode(file_get_contents('php://input'), true);

        $sql = "INSERT INTO pelicula (titulo, descripcion, calificacion_usuarios, foto, lanzamiento) 
                VALUES ('$data[titulo]', '$data[descripcion]', '$data[calificacion_usuarios]', '$data[foto]', '$data[lanzamiento]')";

        if ($conn->query($sql)) {
            $idPelicula = $conn->insert_id;

            // Insertar relaciones con categorías
            if (isset($data['categorias']) && is_array($data['categorias'])) {
                foreach ($data['categorias'] as $idCategoria) {
                    $sqlRelacion = "INSERT INTO pelicula_categoria (id_pelicula, id_categoria) 
                                    VALUES ('$idPelicula', '$idCategoria')";
                    $conn->query($sqlRelacion);
                }
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(value: ['success' => false, 'error' => $conn->error]);
        }
        break;


    case 'PUT':
        verificarPermisosGestor();
        $data = json_decode(file_get_contents('php://input'), true);

        // Actualizar datos de la película
        $sql = "UPDATE pelicula 
                    SET titulo = '$data[titulo]', 
                        descripcion = '$data[descripcion]', 
                        calificacion_usuarios = '$data[calificacion_usuarios]', 
                        foto = '$data[foto]', 
                        lanzamiento = '$data[lanzamiento]' 
                    WHERE id_pelicula = '$data[id_pelicula]'";

        if ($conn->query($sql)) {
            $idPelicula = $data['id_pelicula'];

            // Eliminar relaciones actuales con categorías
            $sqlDeleteRelaciones = "DELETE FROM pelicula_categoria WHERE id_pelicula = '$idPelicula'";
            $conn->query($sqlDeleteRelaciones);

            // Insertar nuevas relaciones con categorías
            if (isset($data['categorias']) && is_array($data['categorias'])) {
                foreach ($data['categorias'] as $idCategoria) {
                    $sqlRelacion = "INSERT INTO pelicula_categoria (id_pelicula, id_categoria) 
                                        VALUES ('$idPelicula', '$idCategoria')";
                    $conn->query($sqlRelacion);
                }
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        break;

    case 'DELETE':
        verificarPermisosGestor();
        $id_pelicula = $_GET['id_pelicula'];

        // Eliminar relaciones primero
        $sqlRelacion = "DELETE FROM pelicula_categoria WHERE id_pelicula = '$id_pelicula'";
        $conn->query($sqlRelacion);

        // Luego eliminar la película
        $sql = "DELETE FROM pelicula WHERE id_pelicula = '$id_pelicula'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;
}
