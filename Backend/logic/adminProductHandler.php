<?php
session_start();
header("Content-Type: application/json");

require_once "../models/productClass.php";


if (!isset($_SESSION["user_id"]) || $_SESSION["rolle"] !== "admin") {
    echo json_encode(["message" => "Zugriff verweigert."]);
    exit;
}

$productObj = new Product();

// GET: Alle Produkte laden
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $products = $productObj->getAllProductsArray();
    echo json_encode($products);
    exit;
}

// POST: Neues Produkt erstellen oder Produkt löschen
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Produkt löschen
    if (isset($_POST["action"]) && $_POST["action"] === "delete") {
        $id = intval($_POST["id"]);

        $deleted = $productObj->deleteProduct($id);
        if ($deleted) {
            echo json_encode(["message" => "Produkt wurde gelöscht."]);
        } else {
            echo json_encode(["message" => "Fehler beim Löschen."]);
        }
        exit;
    }
    // Produkt bearbeiten
if (isset($_POST["action"]) && $_POST["action"] === "edit") {
    $id = intval($_POST["id"]);
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = floatval($_POST["price"]);

    $updateImage = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $targetDir = "../../Frontend/res/img/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $updateImage = $fileName;
        }
    }

    $updated = $productObj->updateProduct($id, $name, $description, $price, $updateImage);
    if ($updated) {
        echo json_encode(["message" => "Produkt erfolgreich aktualisiert."]);
    } else {
        echo json_encode(["message" => "Fehler beim Aktualisieren."]);
    }
    exit;
}


    // Neues Produkt anlegen
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = floatval($_POST["price"]);

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $targetDir = "../../Frontend/res/img/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Bild hochladen
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $created = $productObj->createProduct($name, $description, $price, $fileName);
            if ($created) {
                echo json_encode(["message" => "Produkt erfolgreich erstellt."]);
            } else {
                echo json_encode(["message" => "Fehler beim Erstellen des Produkts."]);
            }
        } else {
            echo json_encode(["message" => "Fehler beim Hochladen des Bildes."]);
        }
    } else {
        echo json_encode(["message" => "Kein Bild hochgeladen."]);
    }

    exit;
}

// Ungültige Anfrage
echo json_encode(["message" => "Ungültige Anfrage."]);
