// Sitzung prüfen vor Anzeige der Seite
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

// Sitzung erneut prüfen beim Laden des DOM
document.addEventListener("DOMContentLoaded", function () {

    $.ajax({
        url: "../../Backend/logic/sessionCheck.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.loggedIn || response.rolle !== "admin") {
                alert("Zugriff verweigert! Sie haben keine Berechtigung, diese Seite zu betreten.");
                window.location.href = "../sites/index.html";
            }
        },
        error: function () {
            alert("Fehler bei der Überprüfung der Sitzung.");
            window.location.href = "../sites/index.html";
        }
    });
});

$(document).ready(function () {
    // Parameter aus URL lesen (z. B. ?userId=123)
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('userId');

    // Wenn keine userId übergeben wurde
    if (!userId) {
        $("#orders-list").html("<p>Kein Kunde ausgewählt.</p>");
        return;
    }

    // Bestellungen für den ausgewählten Kunden abrufen
    $.ajax({
        url: "../../Backend/logic/adminCustomerOrdersHandler.php",
        method: "GET",
        data: { userId: userId },
        dataType: "json",
        success: function (response) {
            if (response.length === 0) {
                $("#orders-list").html("<p>Keine Bestellungen vorhanden.</p>");
                return;
            }

            let html = `
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Bestellnummer</th>
                            <th>Datum</th>
                            <th>Gesamtpreis</th>
                            <th>Status</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            response.forEach(order => {
                html += `
                    <tr>
                        <td>${order.id}</td>
                        <td>${order.bestelldatum}</td>
                        <td>${order.gesamtpreis} €</td>
                        <td>
                            <select class="form-select form-select-sm change-status" data-id="${order.id}">
                                <option value="offen" ${order.status === 'offen' ? 'selected' : ''}>Offen</option>
                                <option value="bezahlt" ${order.status === 'bezahlt' ? 'selected' : ''}>Bezahlt</option>
                                <option value="storniert" ${order.status === 'storniert' ? 'selected' : ''}>Storniert</option>
                            </select>
                        </td>
                        <td>
                            <a href="adminOrderDetails.html?orderId=${order.id}" class="btn btn-sm btn-info">Details</a>
                        </td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;
            $("#orders-list").html(html);
        },

        error: function () {
            $("#orders-list").html("<p>Fehler beim Laden der Bestellungen.</p>");
        }
    });

    // Statusänderung einer Bestellung absenden
    $("body").on("change", ".change-status", function () {
        const orderId = $(this).data("id");
        const newStatus = $(this).val();

        $.ajax({
            url: "../../Backend/logic/adminOrderHandler.php",
            method: "POST",
            data: {
                action: "updateStatus",
                orderId: orderId,
                status: newStatus
            },
            dataType: "json",
            success: function (response) {
                if (response.message) {
                    alert(response.message); // Erfolgreich aktualisiert
                } else if (response.error) {
                    alert(response.error); // Fehler vom Server
                }
            },
            error: function () {
                alert("Fehler beim Aktualisieren des Status.");
            }
        });
    });
});
