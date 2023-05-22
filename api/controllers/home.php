<?php
require ("../models/products.php");

$model = new Products();

$products = $model->get();
