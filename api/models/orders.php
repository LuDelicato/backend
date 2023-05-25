<?php
require_once ("base.php");

class Orders extends Base
{
    public function create($user_id) {
        $query = $this->db->prepare("
        INSERT INTO orders
        (user_id)
        VALUES(?)
    ");

        $query->execute([ $user_id ]);

        $order_id = $this->db->lastInsertId();

        return $order_id;
    }
    public function createDetail($order_id, $product)
    {
        $query = $this->db->prepare("
        INSERT INTO orderdetails
        (order_id, product_id, quantity, price_each)
        VALUES(?, ?, ?, ?)
    ");

        return $query->execute([
            $order_id,
            $product["product_id"],
            $product["quantity"],
            $product["price"]
        ]);
    }
}

