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

// Hilfsfunktion zum sicheren Hochladen von Bildern
function handleImageUpload($file) {
    $uploadDir = realpath(__DIR__ . "/../productpictures");

    // Если папка не существует — создаём
    if (!$uploadDir) {
        $uploadDir = __DIR__ . "/../productpictures";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    }

    $fileName = basename($file["name"]);
    $targetFilePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        return $fileName;
    }

    return false;
}

// POST: Neues Produkt erstellen oder bearbeiten oder löschen
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Produkt löschen
    if (isset($_POST["action"]) && $_POST["action"] === "delete") {
        $id = intval($_POST["id"]);
        $deleted = $productObj->deleteProduct($id);

        echo json_encode([
            "message" => $deleted ? "Produkt wurde gelöscht." : "Fehler beim Löschen."
        ]);
        exit;
    }

    // Produkt bearbeiten
    if (isset($_POST["action"]) && $_POST["action"] === "edit") {
        $id = intval($_POST["id"]);
        $name = $_POST["name"];
        $description = $_POST["description"];
        $price = floatval($_POST["price"]);

        $updateImage = "";
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
            $uploadResult = handleImageUpload($_FILES["image"]);
            if ($uploadResult) {
                $updateImage = $uploadResult;
            }
        }

        $updated = $productObj->updateProduct($id, $name, $description, $price, $updateImage);
        echo json_encode([
            "message" => $updated ? "Produkt erfolgreich aktualisiert." : "Fehler beim Aktualisieren."
        ]);
        exit;
    }

    // Neues Produkt anlegen
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = floatval($_POST["price"]);

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $uploadResult = handleImageUpload($_FILES["image"]);

        if ($uploadResult) {
            $created = $productObj->createProduct($name, $description, $price, $uploadResult);
            echo json_encode([
                "message" => $created ? "Produkt erfolgreich erstellt." : "Fehler beim Erstellen des Produkts."
            ]);
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
