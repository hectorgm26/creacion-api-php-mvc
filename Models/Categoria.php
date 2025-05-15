<?php

require ('../Config/Conexion.php');

class Categoria extends Conexion {

    public function __construct()
    {
        parent::__construct();
    }

    public function getCategoria() {
        try {
            $parametrizacion = $this->con->prepare("SELECT * FROM tm_categoria WHERE est = (?)");

            $parametrizacion->bind_param('i', $a);
            $a = 1;

            $parametrizacion->execute();

            $resultado = $parametrizacion->get_result(); // Se obtiene el resultado de la consulta en formato de objeto mysqli_result, que permite recorrer los resultados de la consulta como un array asociativo o numérico.

            $r = array();
            while ($fila = $resultado->fetch_assoc()) {
                $categoria = array(
                    'cat_id' => $fila['cat_id'],
                    'cat_nom' => $fila['cat_nom'],
                    'cat_obs' => $fila['cat_obs'],
                    'est' => $fila['est']
                );
                $r[] = $categoria;
            }
            return $r;

        } catch (Exception $ex) {
            return $ex;
        }
    }

    // Ahora crearemos un servicio para enviarle un id en formato json, postman nos devuelva otro json con la informacion de ese registro
    public function getCategoriaPorID($cat_id) {
        try {
            $parametrizacion = $this->con->prepare("SELECT * FROM tm_categoria WHERE est = (?) AND cat_id = (?)");

            $parametrizacion->bind_param('ii', $a, $b);
            $a = 1;
            $b = $cat_id;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();

            $r = array();
            while ($fila = $resultado->fetch_assoc()) {
                $categoria = array(
                    'cat_id' => $fila['cat_id'],
                    'cat_nom' => $fila['cat_nom'],
                    'cat_obs' => $fila['cat_obs'],
                    'est' => $fila['est']
                );
                $r[] = $categoria;
            }
            return $r;

        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function insertCategoria($cat_nom, $cat_obs) {
        try {
            $parametrizacion = $this->con->prepare("INSERT INTO tm_categoria (cat_id, cat_nom, cat_obs, est) VALUES (NULL, ?, ?, ?)");

            $parametrizacion->bind_param('ssi', $a, $b, $c);
            $a = $cat_nom;
            $b = $cat_obs;
            $c = 1;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();

            return $resultado; // Esto porque los insert no devuelven nada, ENTONCES SE ELIMINA TODO LO DEL WHILE Y EL RELLENADO DE FILAS EN UN ARRAY

        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function updateCategoria($cat_id, $cat_nom, $cat_obs) {
        try {
            $parametrizacion = $this->con->prepare("UPDATE tm_categoria SET cat_nom = ?, cat_obs = ? WHERE cat_id = (?)");

            $parametrizacion->bind_param('ssi', $a, $b, $c);
            $a = $cat_nom;
            $b = $cat_obs;
            $c = $cat_id;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();

            return $resultado;

        } catch (Exception $ex) {
            return $ex;
        }
    }

    // Para eliminar una categoria, simplemente se cambia el estado a 0
    public function deleteCategoria($cat_id) {
        try {
            $parametrizacion = $this->con->prepare("UPDATE tm_categoria SET est = ? WHERE cat_id = (?)");

            $parametrizacion->bind_param('ii', $a, $b);
            $a = 0;
            $b = $cat_id;

            $parametrizacion->execute();
            $resultado = $parametrizacion->get_result();

            return $resultado;

        } catch (Exception $ex) {
            return $ex;
        }
    }
}

?>