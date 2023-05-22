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

    public function create($data)
    {
        $query = $this->db->prepare("
            INSERT INTO products 
            (categoryID, brandID, typeID, cover, title, price, qty, description)
            VALUES (?,?, ?, ?, ?, ?, ?, ?)
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
            $updatedItem = $this->getItem($id);
            $message = "Item with ID " . $id . " was successfully updated.";

            return array('item' => $updatedItem, 'message' => $message);

        }
        else
        {
            $message = "Failed to update item with ID " . $id . ".";

            return array('item' => null, 'message' => $message);
        }
    }


    public function deleteItem($id, $data)
    {
        $query = $this->db->prepare("
        DELETE FROM products
        WHERE id = ?
        ");

        if ($query->execute([$id])) {
            return "Item with ID " . $id . " was successfully deleted.";
        }

        return "Failed to delete item with ID " . $id . ".";
    }
}