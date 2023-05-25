<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

$allowed_options = [
    "products",
    "categories",
    "brands"
];

if(isset($url_parts[2])) {
    $option = $url_parts[2];
}

if(!empty($url_parts[3])) {
    $resource_id = $url_parts[3];
}

if (!empty($url_parts[4])) {
    $additionalDetail = $url_parts[4];
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

    if ( isset($resource_id) && isset( $additionalDetail)){

        require ("models/" .$additionalDetail. ".php");

        $className = ucwords($additionalDetail);

        $detailModel = new $className();

        $response = $detailModel->getByCategory($resource_id);

    }
        else if (isset($resource_id)) {

        $response = $model->getItem($resource_id);

        if ($response) {

            http_response_code(200);

        }
        else {

            http_response_code(404);
            $response = array('message' => 'Item not found.');

        }
    }
    else {
        $response = $model->get();
        http_response_code(200);
    }


} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $body = file_get_contents("php://input");
    $data = json_decode($body, true);

    $response = $model->create($data);

    if ($response) {

        http_response_code(200);
        $response = array('message' => 'Item created successfully.', 'item' => $response);

    }
    else {
        http_response_code(400);
        $response = array('message' => 'Failed to create item.');

    }
}
else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $body = file_get_contents("php://input");
    $data = json_decode($body, true);

    if (isset($resource_id)) {

        if ($model->updateItem($resource_id, $data)) {

            http_response_code(200);
            $response = array('message' => 'Item with ID ' . $resource_id . ' was successfully updated.');

        }
        else {
            http_response_code(400);
            $response = array('message' => 'Failed to update item with ID ' . $resource_id . '.');
        }
    }
    else {
        http_response_code(400);
        $response = array('message' => 'Bad Request: Missing resource ID.');
    }
}

else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $body = file_get_contents("php://input");
    $data = json_decode($body, true);

    if (isset($resource_id)) {

        $success = $model->deleteItem($resource_id, $data);

        if ($success) {

            http_response_code(200);
            $message = "Item with ID " . $resource_id . " was successfully deleted.";
        } else {

            http_response_code(400);
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