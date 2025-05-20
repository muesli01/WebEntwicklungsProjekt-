$(document).ready(function () {
    loadOrders();
});

// Bestellungen laden und anzeigen
function loadOrders() {
    $.ajax({
        url: "../../Backend/logic/getOrders.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (response.success) {
                if (response.orders.length === 0) {
                    $("#orders-container").html("<p>Sie haben noch keine Bestellungen.</p>");
                    return;
                }

                let html = `<table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Bestellnummer</th>
                            <th>Datum</th>
                            <th>Gesamtpreis</th>
                            <th>Status</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>`;

                response.orders.forEach(order => {
                    html += `
                    <tr>
                        <td>${order.bestellnummer}</td>
                        <td>${new Date(order.bestelldatum).toLocaleDateString()}</td>
                        <td>${order.gesamtpreis} &euro;</td>
                        <td>${order.status}</td>
                        <td>
                            <a href="../../Backend/logic/downloadInvoice.php?bestellnummer=${order.bestellnummer}" class="btn btn-sm btn-success mb-2">Rechnung herunterladen</a><br>
                            <button class="btn btn-sm btn-primary" onclick="showOrderDetails(${order.id})">Details</button>
                        </td>
                    </tr>`;
                });

                html += `</tbody></table>`;
                $("#orders-container").html(html);
            } else {
                $("#orders-container").html(`<p>${response.message}</p>`);
            }
        },
        error: function () {
            $("#orders-container").html("<p>Fehler beim Laden der Bestellungen.</p>");
        }
    });
}

// Bestelldetails und Bewertungen anzeigen
function showOrderDetails(orderId) {
    $.ajax({
        url: "../../Backend/logic/getOrderDetails.php",
        method: "GET",
        data: { orderId },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                const items = response.items;

                // Bewertungen für alle Produkte im Auftrag laden
                let reviewPromises = items.map(item => {
                    return $.ajax({
                        url: "../../Backend/logic/reviewHandler.php",
                        method: "GET",
                        data: {
                            order_id: orderId,
                            product_id: item.product_id
                        },
                        dataType: "json"
                    }).then(res => {
                        if (res.success) {
                            return { product_id: item.product_id, review: res.review };
                        } else {
                            return { product_id: item.product_id, review: null };
                        }
                    }).catch(() => {
                        return { product_id: item.product_id, review: null };
                    });
                });

                // Nachdem alle Bewertungen geladen wurden
                Promise.all(reviewPromises).then(results => {
                    let reviews = {};
                    results.forEach(r => {
                        if (r.review) {
                            reviews[r.product_id] = r.review;
                        }
                    });

                    let html = `<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produkt ID</th>
                                <th>Name</th>
                                <th>Preis</th>
                                <th>Menge</th>
                                <th>Bewertung</th>
                            </tr>
                        </thead>
                        <tbody>`;

                    items.forEach(item => {
                        const review = reviews[item.product_id];
                        const stars = review ? renderStars(review.rating) : '';
                        const comment = review?.comment ? `<p><em>${$('<div>').text(review.comment).html()}</em></p>` : '';

                        html += `
                            <tr>
                                <td>${item.product_id}</td>
                                <td>${item.product_name}</td>
                                <td>${item.price} &euro;</td>
                                <td>${item.quantity}</td>
                                <td id="review-cell-${item.product_id}">
                                    ${review ? `
                                        <div>${stars}</div>${comment}
                                    ` : `
                                        <form onsubmit="submitReview(event, ${item.product_id}, ${orderId})">
                                            <div class="star-rating mb-1">
                                                ${[5, 4, 3, 2, 1].map(i =>
                                                    `<input type="radio" name="rating" value="${i}" id="${i}-${item.product_id}">
                                                     <label for="${i}-${item.product_id}"><i class="bi bi-star-fill"></i></label>`).join('')}
                                            </div>
                                            <input type="text" name="comment" placeholder="Kommentar (optional)" class="form-control mb-1" />
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Abschicken</button>
                                            <div class="review-feedback text-success mt-2"></div>
                                        </form>
                                    `}
                                </td>
                            </tr>`;
                    });

                    html += `</tbody></table>`;
                    $("#details-container").html(html);
                    $("#order-details").show();
                    $('html, body').animate({ scrollTop: $("#order-details").offset().top }, 500);
                }).catch(error => {
                    console.error('Fehler bei der Verarbeitung der Bewertungsdaten:', error);
                    $("#details-container").html("<p>Fehler beim Laden der Bewertungen.</p>");
                    $("#order-details").show();
                });
            } else {
                $("#details-container").html(`<p>${response.message}</p>`);
                $("#order-details").show();
            }
        },
        error: function () {
            $("#details-container").html("<p>Fehler beim Laden der Bestelldetails.</p>");
            $("#order-details").show();
        }
    });
}

// Bewertung absenden
function submitReview(event, productId, orderId) {
    event.preventDefault();
    const form = event.target;
    const rating = form.rating.value;
    const comment = form.comment.value;

    $.ajax({
        url: "../../Backend/logic/reviewHandler.php",
        method: "POST",
        data: {
            product_id: productId,
            order_id: orderId,
            rating: rating,
            comment: comment
        },
        dataType: "json",
        success: function (response) {
            const cellId = `#review-cell-${productId}`;
            if (response.success) {
                const starsHtml = renderStars(rating);
                const commentHtml = comment ? `<p><em>${$('<div>').text(comment).html()}</em></p>` : "";
                $(cellId).html(`<div>${starsHtml}</div>${commentHtml}`);
            } else {
                $(form).find(".review-feedback")
                    .text(response.message)
                    .removeClass("text-success")
                    .addClass("text-danger");
            }
        },
        error: function () {
            $(form).find(".review-feedback")
                .text("Fehler beim Senden der Bewertung.")
                .addClass("text-danger");
        }
    });
}

// Sterne-HTML für Bewertung rendern
function renderStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += `<i class="bi ${i <= rating ? 'bi-star-fill text-warning' : 'bi-star text-muted'}"></i>`;
    }
    return stars;
}
