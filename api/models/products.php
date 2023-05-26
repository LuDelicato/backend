<?php
require_once("base.php");

class Products extends Base
{
    public function get()
    {
        $query = $this->db->prepare("
        SELECT product_id, name, image, stock, description, modified
        FROM products
    ");

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItem($id)
    {

        $query = $this->db->prepare("
                SELECT product_id, name, description, price, stock, image, modified
                FROM products
                WHERE product_id = ?
            ");

        $query->execute([
            $id
        ]);

        return $query->fetch(PDO::FETCH_ASSOC);

    }

    public function getByCategory($id) {
        $query = $this->db->prepare("
            SELECT
                p.product_id,
                p.image,
                p.name,
                p.price,
                p.stock,
                p.description,
                cn.name AS category_name,
                bn.name AS brand_name,
                p.modified
            FROM
                products AS p
            LEFT JOIN
                categories AS cn ON p.category_id = cn.id
            LEFT JOIN
                brands AS bn ON p.brandID = bn.id
            WHERE
                cn.id = ?
        ");

        $query->execute([$id]);
        return $query->fetchAll();
    }

   /* for later
    public function getByBrand($id) {
        $query = $this->db->prepare("
            SELECT p.product_id, p.name, p.name, p.price, p.stock, p.description, b.name AS brand, c.name AS category, p.modified
            FROM products AS p
            INNER JOIN brands AS b ON p.brandID = b.id
            INNER JOIN categories AS c ON p.category_id = c.id
            WHERE b.id = ?
        ");

        $query->execute([$id]);

        return $query->fetchAll();
    }
   */

    public function create($data)
    {
        $query = $this->db->prepare("
        INSERT INTO products 
        (category_id, brandID, image, name, price, stock, description)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

        $query->execute([
            $data['category_id'],
            $data['brandID'],
            $data['image'],
            $data['name'],
            $data['price'],
            $data['stock'],
            $data['description']
        ]);

        $data["product_id"] = $this->db->lastInsertId();

        return $data;
    }


    public function updateItem($id, $data)
    {
        $query = $this->db->prepare("
        UPDATE products
        SET category_id = ?, brandID = ?, image = ?, name = ?, price = ?, stock = ?, description = ?, modified = current_timestamp()
        WHERE product_id = ?
    ");

        $query->execute([
            $data['category_id'],
            $data['brandID'],
            $data['image'],
            $data['name'],
            $data['price'],
            $data['stock'],
            $data['description'],
            $id
        ]);

        if ($query->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function deleteItem($id, $data)
    {
        $query = $this->db->prepare("
        DELETE FROM products
        WHERE product_id = ?
        ");

        if ($query->execute([$id])) {

            return true;
        }
        return false;
    }
}