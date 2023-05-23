<?php
require_once("base.php");

class Products extends Base
{
    public function get()
    {
        $query = $this->db->prepare("
        SELECT id, title, cover, qty, description, modified
        FROM products
    ");

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItem($id)
    {

        $query = $this->db->prepare("
                SELECT id, title, description, price, qty, cover, modified
                FROM products
                WHERE id = ?
            ");

        $query->execute([
            $id
        ]);

        return $query->fetch(PDO::FETCH_ASSOC);

    }

    public function getByCategory($id) {
        $query = $this->db->prepare("
            SELECT
                p.id,
                p.cover,
                p.title,
                p.price,
                p.qty,
                p.description,
                cn.name AS category_name,
                bn.name AS brand_name,
                tn.name AS type_name,
                p.modified
            FROM
                products AS p
            LEFT JOIN
                categories AS cn ON p.categoryID = cn.id
            LEFT JOIN
                brands AS bn ON p.brandID = bn.id
            LEFT JOIN
                product_types AS tn ON p.typeID = tn.id
            WHERE
                cn.id = ?
        ");

        $query->execute([$id]);
        return $query->fetchAll();
    }



    public function create($data)
    {
        $query = $this->db->prepare("
        INSERT INTO products 
        (categoryID, brandID, typeID, cover, title, price, qty, description)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

        $query->execute([
            $data['categoryID'],
            $data['brandID'],
            $data['typeID'],
            $data['cover'],
            $data['title'],
            $data['price'],
            $data['qty'],
            $data['description']
        ]);

        $data["id"] = $this->db->lastInsertId();

        return $data;
    }


    public function updateItem($id, $data)
    {
        $query = $this->db->prepare("
        UPDATE products
        SET categoryID = ?, brandID = ?, typeID = ?, cover = ?, title = ?, price = ?, qty = ?, description = ?, modified = current_timestamp()
        WHERE id = ?
    ");

        $query->execute([
            $data['categoryID'],
            $data['brandID'],
            $data['typeID'],
            $data['cover'],
            $data['title'],
            $data['price'],
            $data['qty'],
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
        WHERE id = ?
        ");

        if ($query->execute([$id])) {

            return true;
        }
        return false;
    }
}