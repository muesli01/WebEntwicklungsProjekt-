// Sitzung vor dem Anzeigen der Seite überprüfen
$.ajax({
    url: "../../Backend/logic/sessionCheck.php",
    method: "GET",
    dataType: "json",
    success: function (response) {
        if (!response.loggedIn || response.rolle !== "admin") {
            alert("Zugriff verweigert! Sie haben keine Berechtigung, diese Seite zu betreten.");
            window.location.href = "../sites/index.html";
        } else {
            // Seite nur anzeigen, wenn Benutzer angemeldet und admin ist
            $("body").show();
        }
    },
    error: function () {
        alert("Fehler bei der Überprüfung der Sitzung.");
        window.location.href = "../sites/index.html";
    }
});

// Zusätzliche Prüfung bei DOM-Initialisierung
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

    // Kundenliste vom Server laden
    function loadCustomers() {
        $.ajax({
            url: "../../Backend/logic/adminCustomerHandler.php",
            method: "GET",
            dataType: "json",
            success: function (customers) {
                let html = `
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Benutzername</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                // Kundenzeilen generieren
                customers.forEach(customer => {
                    html += `
                        <tr>
                            <td>${customer.id}</td>
                            <td>${customer.username}</td>
                            <td>${customer.email}</td>
                            <td>${customer.active == 1 ? 'Aktiv' : 'Inaktiv'}</td>
                            <td>
                                <button class="btn btn-sm ${customer.active == 1 ? 'btn-danger' : 'btn-success'} toggle-active" data-id="${customer.id}">
                                    ${customer.active == 1 ? 'Deaktivieren' : 'Aktivieren'}
                                </button>
                                <a href="adminCustomerOrders.html?userId=${customer.id}" class="btn btn-sm btn-info">Bestellungen ansehen</a>
                            </td>
                        </tr>
                    `;
                });

                html += `</tbody></table>`;
                $("#customer-list").html(html);
            },
            error: function () {
                $("#customer-list").html("<p>Fehler beim Laden der Kunden.</p>");
            }
        });
    }

    // Kunden beim Laden der Seite initial abrufen
    loadCustomers();

    // Status (aktiv/inaktiv) umschalten
    $("body").on("click", ".toggle-active", function () {
        const userId = $(this).data("id");

        $.ajax({
            url: "../../Backend/logic/adminCustomerHandler.php",
            method: "POST",
            data: { action: "toggle", id: userId },
            dataType: "json",
            success: function (response) {
                alert(response.message); // Rückmeldung anzeigen
                loadCustomers(); // Tabelle neu laden
            },
            error: function () {
                alert("Fehler beim Ändern des Status.");
            }
        });
    });
});
