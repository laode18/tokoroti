<?php
header("Content-Type: application/json");
include("../config/database.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "POST": // CREATE (dengan upload gambar)
        if (isset($_POST['name'], $_POST['price'], $_POST['stock'])) {
            $name  = $_POST['name'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];

            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = time() . "_" . uniqid() . "." . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $imageName);
            }

            $stmt = $conn->prepare("INSERT INTO products (name, price, stock, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdis", $name, $price, $stock, $imageName);
            $stmt->execute();

            echo json_encode(["status"=>true, "message"=>"Product created"]);
        } else {
            echo json_encode(["status"=>false, "message"=>"Missing required fields"]);
        }
        break;

            case "POST": // UPDATE dengan gambar
        if (isset($_POST['id'])) {
            $id    = $_POST['id'];
            $name  = $_POST['name'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];

            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = time() . "_" . uniqid() . "." . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $imageName);

                $stmt = $conn->prepare("UPDATE products SET name=?, price=?, stock=?, image=? WHERE id=?");
                $stmt->bind_param("sdisi", $name, $price, $stock, $imageName, $id);
            } else {
                $stmt = $conn->prepare("UPDATE products SET name=?, price=?, stock=? WHERE id=?");
                $stmt->bind_param("sdii", $name, $price, $stock, $id);
            }

            $stmt->execute();
            echo json_encode(["status"=>true, "message"=>"Product updated"]);
        }
        break;

            case "DELETE":
        parse_str(file_get_contents("php://input"), $data);
        $id = $data['id'];

        // Hapus gambar dulu
        $q = $conn->prepare("SELECT image FROM products WHERE id=?");
        $q->bind_param("i", $id);
        $q->execute();
        $res = $q->get_result()->fetch_assoc();
        if ($res && $res['image'] && file_exists("../uploads/" . $res['image'])) {
            unlink("../uploads/" . $res['image']);
        }

        $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(["status"=>true, "message"=>"Product deleted"]);
        break;
    }