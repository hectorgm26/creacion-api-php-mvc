<?php

// En el header le diremos que la aplicación es de tipo JSON, para que Postman y los navegadores lo entiendan.
// También permitimos solicitudes desde cualquier origen (CORS) y métodos GET y POST.
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST'); 
header('Content-Type: application/json; charset=utf-8');

// --------------------------------------------
// AUTORIZACIÓN USANDO TOKEN BEARER
// --------------------------------------------
// En esta sección validamos que el cliente (Postman, navegador, etc.) haya enviado una cabecera Authorization con un token válido.
// El formato esperado es: Authorization: Tipo Bearer = Uwu69
// Si no se envía o es incorrecto, devolvemos el código HTTP correspondiente: 
// 401 para "no autorizado", 403 para "prohibido", 500 para errores internos inesperados.
$_authorization = null;
$token_valido = 'Bearer UwU69'; // Definimos el token válido como una variable

try {
    // Verificamos si el encabezado Authorization fue enviado
    if (isset(getallheaders()['Authorization'])) {
        $_authorization = getallheaders()['Authorization'];

        // Validamos si el token es el esperado
        if ($_authorization !== $token_valido) {
            http_response_code(403); // Prohibido
            echo json_encode(['error' => 'Token inválido']);
            exit;
        }

        // Si el token es válido, continúa la ejecución normal del código
        // echo json_encode(['msg' => 'Token válido']); // (opcional para pruebas)

    } else {
        // Si no se proporciona el encabezado Authorization
        http_response_code(401); // No autorizado
        echo json_encode(['error' => 'Sin autorización']);
        exit;
    }
} catch (Exception $e) {
    // En caso de error inesperado durante la validación
    http_response_code(500); // Error interno del servidor
    echo json_encode(['error' => 'Error sin control', 'detalle' => $e->getMessage()]);
    exit;
}

// No se requiere la clase Conexion porque ya está incluida en el archivo Categoria.php
require ('../Models/Categoria.php');

$categoria = new Categoria();

$body = json_decode(file_get_contents("php://input"), true);
// Y como recibiremos la info en JSON, tenemos que decodificarla para que la entienda PHP. 
// En este caso, lo que nos interesa es el cuerpo de la petición, que es donde se encuentra el JSON que enviamos desde Postman.
// Todo lo recibiremos en el body, y lo convertiremos a un array asociativo para que PHP lo entienda.
// Por eso usamos la función json_decode, que convierte el JSON a un array asociativo.
// El file_get_contents("php://input") es una función de PHP que lee el cuerpo de la petición y lo convierte a un string.
// Luego, lo pasamos a la función json_decode para que lo convierta a un array asociativo.
// El segundo parámetro de la función json_decode es true, lo que indica que queremos que el resultado sea un array asociativo.
// Si no se pone, el resultado será un objeto de tipo stdClass.

// Validamos si se recibió el parámetro 'op' en la URL. Si no se recibe, se devuelve un mensaje de error en formato JSON y se detiene la ejecución del script.
// Esto es importante porque el switch depende del valor de 'op' para saber qué operación realizar.
if (!isset($_GET['op'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Parámetro 'op' no proporcionado"]);
    exit;
}

// Crear servicios a través de opciones con un switch.
// En la opción GET, tendremos el servicio para obtener toda la query select de categorías y traerla a Postman.
switch($_GET['op']) {

    case "GetAll":
        $datos = $categoria->getCategoria();
        // El resultado de la variable de datos es un array, y tenemos que convertirlo a JSON para que lo entienda Postman.
        http_response_code(200); // OK
        echo json_encode($datos); // el json_encode convierte el array con toda la info a JSON para que lo entienda Postman.
        // ESTO ES UN GET (EN POST se envían datos), por lo que solo se trae info.
    break;

    case "GetId":
        // El parámetro no será ni GET, ni POST, sino que será de tipo body indicándole que viene del JSON decode.
        // SE USARÁ EL MÉTODO POST EN POSTMAN PARA PODER ENVIARLE ID EN EL BODY

        // Validamos si se recibió el parámetro 'cat_id' en el cuerpo de la solicitud.
        // Si no se recibe, se devuelve un mensaje de error en formato JSON y se detiene la ejecución del script.
        if (!isset($body['cat_id'])) { 
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "cat_id es requerido"]);
            exit;
        }
        $datos = $categoria->getCategoriaPorID($body['cat_id']);
        http_response_code(200); // OK
        echo json_encode($datos);
    break;

    case "Insert":
        if (empty($body['cat_nom']) || empty($body['cat_obs'])) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "cat_nom y cat_obs son requeridos"]);
            exit;
        }
        $datos = $categoria->insertCategoria($body['cat_nom'], $body['cat_obs']);
        http_response_code(201); // Created
        echo "Datos insertados correctamente"; // Esto porque los insert no devuelven nada, solo un mensaje de que se insertaron correctamente
    break;

    case "Update":
        if (empty($body['cat_id']) || empty($body['cat_nom']) || empty($body['cat_obs'])) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "cat_id, cat_nom y cat_obs son requeridos"]);
            exit;
        }
        $datos = $categoria->updateCategoria($body['cat_id'], $body['cat_nom'], $body['cat_obs']);
        http_response_code(200); // OK
        echo "Datos actualizados correctamente";
    break;

    case "Delete":
        if (!isset($body['cat_id'])) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "cat_id es requerido"]);
            exit;
        }
        $datos = $categoria->deleteCategoria($body['cat_id']);
        http_response_code(200); // OK
        echo "Datos eliminados correctamente";
    break;

    default:
        http_response_code(404); // Not Found
        echo json_encode(["error" => "Opción no válida"]);
    break;
}

?>
