<?php

header("Content-Type: application/json");

$allowed_options = ["products"];

if(isset($url_parts[2])) {
    $option = $url_parts[2];
}

if(!empty($url_parts[3])) {
    $resource_id = $url_parts[3];
}

if(empty($option)) {

    http_response_code(400);
    die('{"message" : "Bad Request"}');
}

else if(!in_array($option, $allowed_options)) {

    http_response_code(404);
    die('{"message" : "Not Found"}');
}

require("models/" .$option. ".php");

$className = ucwords($option);

$model = new $className();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $response = isset($resource_id) ? $model->getItem($resource_id) : $model->get();

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $body = file_get_contents("php://input");
    $data = json_decode($body, true);

    http_response_code(202);
    $response = $model->create($data);

} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $body = file_get_contents("php://input");
    $data = json_decode($body, true);

    http_response_code(202);
    $response = isset($resource_id) ? $model->updateItem($resource_id, $data) : null;


} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $body = file_get_contents("php://input");
    $data = json_decode($body, true);

    if (isset($resource_id)) {

        $success = $model->deleteItem($resource_id, $data);

        if ($success) {

            http_response_code(200);
            $message = "Item with ID " . $resource_id . " was successfully deleted.";
        } else {

            http_response_code(500);
            $message = "Failed to delete item with ID " . $resource_id . ".";
        }
    } else {

        http_response_code(400);
        $message = "Bad Request: Missing resource ID.";
    }

    $response = array('message' => $message);

}

else {

    http_response_code(405);
    die('{"message" : "Method Not Allowed"}');

}

if (empty($response)) {

    http_response_code(404);
    die('{"message" : "Not Found"}');

}

echo json_encode($response);