$(document).ready(function () {
    let rabatt = 0,
        eingelösterCode = "";

    // 1) Prüfen, ob der Benutzer eingeloggt ist, und Zahlungsmethoden laden
    $.ajax({
        url: "../../Backend/logic/sessionCheck.php",
        method: "GET",
        dataType: "json",
        success: function (resp) {
            if (resp.loggedIn) {
                $("#gutschein-section, #payment-method-section").show();
                loadPaymentMethods();
            }
        }
    });

    // 2) Warenkorb laden
    loadCart();

    function loadCart() {
        $.ajax({
            url: "../../Backend/logic/cartHandler.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                if (data.length === 0) {
                    $("#cart-content").html("<p>Ihr Warenkorb ist leer.</p>");
                    $("#order-section, #gutschein-section, #payment-method-section").hide();
                    return;
                }
                let html = "", total = 0;
                data.forEach(p => {
                    const sub = p.price * p.quantity;
                    total += sub;
                    html += `
            <div class="col-md-12 mb-3 border-bottom d-flex align-items-center cart-item">
              <img src="../../Backend/logic/getProductImage.php?file=${encodeURIComponent(p.image)}"
                   class="cart-img me-3" style="width:80px" alt="">
              <div class="flex-grow-1">
                <h5>${p.name}</h5>
                <div class="d-flex mb-2">
                  <button class="btn btn-secondary btn-sm me-2 change-qty" data-id="${p.id}" data-action="decrease">-</button>
                  <span class="me-2">${p.quantity}</span>
                  <button class="btn btn-secondary btn-sm me-2 change-qty" data-id="${p.id}" data-action="increase">+</button>
                </div>
                <p>Preis: ${p.price} € | Gesamt: <span class="subtotal">${sub.toFixed(2)}</span> €</p>
                <button class="btn btn-danger btn-sm remove-from-cart" data-id="${p.id}">Entfernen</button>
              </div>
            </div>`;
                });
                html += `<div id="total-price" class="mt-4"><h4>Gesamtpreis: ${total.toFixed(2)} €</h4></div>`;
                $("#cart-content").html(html);
                $("#order-section, #gutschein-section, #payment-method-section").show();
            },
            error: function () {
                $("#cart-content").html("<p>Fehler beim Laden des Warenkorbs.</p>");
                $("#order-section, #gutschein-section, #payment-method-section").hide();
            }
        });
    }

    // 3) Menge ändern und Produkte entfernen
    $("body").on("click", ".change-qty, .remove-from-cart", function () {
        const id = $(this).data("id"),
            action = $(this).data("action") || "remove";
        $.ajax({
            url: "../../Backend/logic/cartHandler.php",
            method: "POST",
            data: { action: action, productId: id },
            dataType: "json",
            success: loadCart,
            error: () => alert("Fehler beim Aktualisieren des Warenkorbs.")
        });
    });

    // 4) Gutschein einlösen
    $("#apply-gutschein").click(function () {
        const code = $("#gutschein-code").val().trim();
        if (code === "") {
            $("#gutschein-message").html('<div class="alert alert-warning">Bitte Gutscheincode eingeben.</div>');
            return;
        }
        $.ajax({
            url: "../../Backend/logic/gutscheinHandler.php",
            method: "POST",
            data: { gutscheincode: code },
            dataType: "json",
            success: function (r) {
                if (r.success) {
                    rabatt = r.amount;
                    eingelösterCode = code;
                    $("#gutschein-message").html(`<div class="alert alert-success">${r.message} Rabatt: ${rabatt.toFixed(2)} €</div>`);
                    const originalTotal = parseFloat($("#total-price h4").text().replace(/[^\d.,]/g, '').replace(',', '.'));
                    const newTotal = Math.max(0, originalTotal - rabatt);
                    $("#total-price h4").html(`Gesamtpreis: ${newTotal.toFixed(2)} €`);

                    updateTotalPrice();  // Hier ggf. weitere Aktualisierungen durchführen
                } else {
                    $("#gutschein-message").html(`<div class="alert alert-danger">${r.message}</div>`);
                }
            },
            error: () => $("#gutschein-message").html('<div class="alert alert-danger">Einlösefehler.</div>')
        });
    });

    // 5) Bestellung abschicken
    $("#submit-order").click(function () {
        const pm = $("#payment-method-select").val();
        if (pm === "") {
            $("#order-message").html('<div class="alert alert-warning">Bitte wählen Sie eine Zahlungsmethode.</div>');
            return;
        }
        $.ajax({
            url: "../../Backend/logic/orderHandler.php",
            method: "POST",
            data: {
                rabatt: rabatt,
                gutscheincode: eingelösterCode,
                paymentMethodId: pm
            },
            dataType: "json",
            success: function (r) {
                if (r.success) {
                    $("#order-message").html(`<div class="alert alert-success">${r.message}<br>Bestellnummer: <strong>${r.bestellnummer}</strong></div>`);
                    setTimeout(() => location.href = "productList.html", 3000);
                } else {
                    $("#order-message").html(`<div class="alert alert-danger">${r.message}</div>`);
                }
            },
            error: () => $("#order-message").html('<div class="alert alert-danger">Fehler beim Abschicken der Bestellung.</div>')
        });
    });

    // 6) Zahlungsmethoden laden
    function loadPaymentMethods() {
        $.ajax({
            url: "../../Backend/logic/getPaymentMethods.php",
            method: "GET",
            dataType: "json",
            success: function (resp) {
                if (resp.success) {
                    let opts = '<option value="">Bitte wählen</option>';
                    resp.methods.forEach(m => {
                        opts += `<option value="${m.id}">${m.name}${m.details ? " – " + m.details : ""}</option>`;
                    });
                    $("#payment-method-select").html(opts);
                }
            },
            error: () => alert("Fehler beim Laden der Zahlungsmethoden")
        });
    }
});
