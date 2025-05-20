$(document).ready(function () { 
    // Produkte laden
    $.ajax({
        url: "../../Backend/logic/productHandler.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
            let allProducts = data;

            // Produkte darstellen
            function renderProducts(products) {
                let html = "";
                products.forEach(product => {
                    html += `
                        <div class="col-md-4 mb-4 product-card" 
                            data-name="${product.name.toLowerCase()}" 
                            data-price="${product.price}" 
                            draggable="true" 
                            data-id="${product.id}">
                            <div class="card">
                                <img src="../../Backend/logic/getProductImage.php?file=${encodeURIComponent(product.image)}" class="card-img-top" alt="${product.name}">

                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text">${product.description}</p>
                                    <p><strong>${product.price} &euro;</strong></p>
                                    <button class="btn btn-primary add-to-cart" data-id="${product.id}">In den Warenkorb</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $("#product-container").html(html);
            }

            // Erstes Rendern der Produkte
            renderProducts(allProducts);

            // Sucheingabe
            $("#searchInput").on("input", function () {
                filterAndSearch();
            });

            // Preisfilter ändern
            $("#priceFilter").on("change", function () {
                filterAndSearch();
            });

            // Filter und Suche kombinieren
            function filterAndSearch() {
                const search = $("#searchInput").val().toLowerCase();
                const priceFilter = $("#priceFilter").val();

                const filtered = allProducts.filter(product => {
                    const nameMatch = product.name.toLowerCase().includes(search);

                    let priceMatch = true;
                    if (priceFilter === "under50") {
                        priceMatch = product.price < 50000;
                    } else if (priceFilter === "50to100") {
                        priceMatch = product.price >= 50000 && product.price <= 100000;
                    } else if (priceFilter === "over100") {
                        priceMatch = product.price > 100000;
                    }

                    return nameMatch && priceMatch;
                });

                renderProducts(filtered);
            }
        },
        error: function () {
            $("#product-container").html("<p>Produkte konnten nicht geladen werden.</p>");
        }
    });

    // Produkt zum Warenkorb hinzufügen
    $("body").on("click", ".add-to-cart", function () {
        const productId = $(this).data("id");

        $.ajax({
            url: "../../Backend/logic/cartHandler.php",
            method: "POST",
            data: { productId: productId },
            dataType: "json",
            success: function (response) {
                alert(response.message);
                updateCartCount(); // Warenkorb-Anzahl aktualisieren
            },
            error: function () {
                alert("Fehler beim Hinzufügen zum Warenkorb.");
            }
        });
    });

    // Warenkorb-Anzahl aktualisieren
    function updateCartCount() {
        $.ajax({
            url: "../../Backend/logic/cartHandler.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                let totalItems = 0;
                data.forEach(product => {
                    totalItems += product.quantity;
                });
                $("#cart-count").text(totalItems);
            }
        });
    }

    // Drag & Drop: Drag starten
    $("body").on("dragstart", ".product-card", function (e) {
        const productId = $(this).data("id");
        e.originalEvent.dataTransfer.setData("text/plain", productId);
    });

    // Drag über Warenkorb erlauben
    $("#cart-icon").on("dragover", function (e) {
        e.preventDefault();
        $(this).css("background-color", "#0056b3");
    });

    // Drag verlassen
    $("#cart-icon").on("dragleave", function () {
        $(this).css("background-color", "#007bff");
    });

    // Produkt per Drag & Drop in den Warenkorb legen
    $("#cart-icon").on("drop", function (e) {
        e.preventDefault();
        $(this).css("background-color", "#007bff");

        const productId = e.originalEvent.dataTransfer.getData("text/plain");

        $.ajax({
            url: "../../Backend/logic/cartHandler.php",
            method: "POST",
            data: { productId: productId },
            dataType: "json",
            success: function (response) {
                alert(response.message);
                updateCartCount();
            },
            error: function () {
                alert("Fehler beim Hinzufügen per Drag & Drop.");
            }
        });
    });

    // Warenkorb-Anzahl direkt beim Laden aktualisieren
    updateCartCount();
});
