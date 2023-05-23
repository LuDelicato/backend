<?php

if ( !isset($id) || is_numeric($id)) {
    http_response_code(400);
    die("Request Inválido");
}

require ("../models/categories.php");

$model = new Categories();

$categories = $model->get($id);

if ( !empty($url_parts[2])) {
    $id = $url_parts[2];
    $categories = $model->get($id);
} else {
    $categories = $model->get();
}