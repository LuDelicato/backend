<?php

if ( !isset($id) || !is_numeric($id)){
    http_response_code(400);
    die("Request inválido");
}

require ("../models/products.php");

$model = new Products();

$products = $model->get($id);


if (!empty($url_parts[2])) {
    $id = $url_parts[2];
    $products = $model->get($id);
} else {
    $products = $model->get();
}
