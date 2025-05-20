// Sitzung und Admin-Rolle prüfen
$.ajax({
    url: "../../Backend/logic/sessionCheck.php",
    method: "GET",
    dataType: "json",
    success: function (response) {
        if (!response.loggedIn || response.rolle !== "admin") {
            alert("Zugriff verweigert! Sie haben keine Berechtigung, diese Seite zu betreten.");
            window.location.href = "../sites/index.html";
        } else {
            $("body").show(); // Seite anzeigen, wenn berechtigt
        }
    },
    error: function () {
        alert("Fehler bei der Überprüfung der Sitzung.");
        window.location.href = "../sites/index.html";
    }
});

// Bestelldetails laden
function loadOrderDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('orderId');

    if (!orderId) {
        $("#order-details").html("<p>Keine Bestellung ausgewählt.</p>");
        return;
    }

    $.ajax({
        url: "../../Backend/logic/adminOrderHandler.php",
        method: "GET",
        data: { orderId: orderId },
        dataType: "json",
        success: function (data) {
            if (!data.length) {
                $("#order-details").html("<p>Bestellung nicht gefunden.</p>");
                return;
            }

            const order = data[0];
            let html = `
                <h5>Bestellnummer: ${order.order_id}</h5>
                <p>Datum: ${order.bestelldatum}</p>
                <p>Status: ${order.status}</p>
                <p>Gesamtpreis: ${order.gesamtpreis} €</p>
                <h5 class="mt-4">Produkte:</h5>
                <ul class="list-group">
            `;

            data.forEach(item => {
                const review = item.review;
                const stars = review ? renderStars(review.rating) : '';
                const comment = review && review.comment ? `<br><em>"${$('<div>').text(review.comment).html()}"</em>` : '';

                html += `
                    <li class="list-group-item d-flex justify-content-between align-items-start flex-column">
                        <div class="w-100">
                            Produkt ID: ${item.product_id} | Menge: ${item.quantity} | Preis: ${item.price} €
                            <button class="btn btn-danger btn-sm float-end remove-product" data-id="${item.item_id}">Entfernen</button>
                        </div>
                        ${review ? `<div class="mt-2"><strong>Bewertung:</strong> ${stars}${comment}</div>` : ''}
                    </li>`;
            });

            html += `</ul>
                <a href="../../Backend/logic/downloadInvoice.php?orderId=${order.order_id}" target="_blank" class="btn btn-primary mt-4">Rechnung herunterladen</a>
            `;

            $("#order-details").html(html);
        },
        error: function () {
            $("#order-details").html("<p>Fehler beim Laden der Bestelldetails.</p>");
        }
    });
}

// Bewertungssterne anzeigen
function renderStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += `<i class="bi ${i <= rating ? 'bi-star-fill text-warning' : 'bi-star text-muted'}"></i>`;
    }
    return stars;
}

// Produkt aus Bestellung entfernen
$(document).on("click", ".remove-product", function () {
    const itemId = $(this).data("id");

    if (confirm("Willst du dieses Produkt wirklich aus der Bestellung entfernen?")) {
        $.ajax({
            url: "../../Backend/logic/adminOrderItemHandler.php",
            method: "POST",
            data: { action: "deleteItem", itemId: itemId },
            dataType: "json",
            success: function (response) {
                alert(response.message);
                loadOrderDetails(); // Details neu laden
            },
            error: function () {
                alert("Fehler beim Entfernen des Produkts.");
            }
        });
    }
});

// Bestelldetails laden, sobald DOM bereit
$(document).ready(function () {
    loadOrderDetails();
});
