// Prüft, ob der Benutzer eingeloggt und ein Admin ist
$.ajax({
    url: "../../Backend/logic/sessionCheck.php",
    method: "GET",
    dataType: "json",
    success: function (response) {
        if (!response.loggedIn || response.rolle !== "admin") {
            alert("Zugriff verweigert! Sie haben keine Berechtigung, diese Seite zu betreten.");
            window.location.href = "../sites/index.html";
        } else {
            // Nur wenn alles OK ist — Seite anzeigen
            $("body").show();
        }
    },
    error: function () {
        alert("Fehler bei der Überprüfung der Sitzung.");
        window.location.href = "../sites/index.html";
    }
});

$(document).ready(function () {
    // Produkte vom Server laden und anzeigen
    function loadProducts() {
        $.ajax({
            url: "../../Backend/logic/adminProductHandler.php",
            method: "GET",
            dataType: "json",
            success: function (response) {
                let html = "";
                response.forEach(product => {
                    html += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="../../Backend/logic/getProductImage.php?file=${encodeURIComponent(product.image)}" class="card-img-top" alt="${product.name}">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p>${product.description}</p>
                                    <p><strong>${product.price} €</strong></p>
                                    <button class="btn btn-warning btn-sm edit-product" data-id="${product.id}">Bearbeiten</button>
                                    <button class="btn btn-danger btn-sm delete-product" data-id="${product.id}">Löschen</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $("#product-list").html(html);
            },
            error: function () {
                $("#product-list").html("<p>Fehler beim Laden der Produkte.</p>");
            }
        });
    }

    // Initiales Laden der Produkte
    loadProducts();

    // Neues Produkt über das Formular erstellen
    $("#new-product-form").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "../../Backend/logic/adminProductHandler.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert(response.message);
                $("#new-product-form")[0].reset();
                loadProducts();
            },
            error: function () {
                alert("Fehler beim Erstellen des Produkts.");
            }
        });
    });

    // Produkt löschen per Klick auf Löschen-Button
    $("body").on("click", ".delete-product", function () {
        if (confirm("Willst du dieses Produkt wirklich löschen?")) {
            const id = $(this).data("id");

            $.ajax({
                url: "../../Backend/logic/adminProductHandler.php",
                method: "POST",
                data: { action: "delete", id: id },
                dataType: "json",
                success: function (response) {
                    alert(response.message);
                    loadProducts();
                },
                error: function () {
                    alert("Fehler beim Löschen.");
                }
            });
        }
    });

    // Produkt bearbeiten per Klick auf Bearbeiten-Button
    $("body").on("click", ".edit-product", function () {
        const id = $(this).data("id");
        const card = $(this).closest(".card");
        const name = card.find(".card-title").text();
        const description = card.find(".card-body p").first().text();
        const priceText = card.find(".card-body strong").text();
        const price = priceText.replace("€", "").trim();

        $("#edit-id").val(id);
        $("#edit-name").val(name);
        $("#edit-description").val(description);
        $("#edit-price").val(price);

        const editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
        editModal.show();
    });

    // Änderungen am Produkt speichern
    $("#edit-product-form").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("action", "edit");

        $.ajax({
            url: "../../Backend/logic/adminProductHandler.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert(response.message);
                const editModal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
                editModal.hide();
                loadProducts();
            },
            error: function () {
                alert("Fehler beim Aktualisieren.");
            }
        });
    });
});
