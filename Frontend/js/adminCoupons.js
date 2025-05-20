// Überprüfen der Sitzung und der Admin-Berechtigung
$.ajax({
    url: "../../Backend/logic/sessionCheck.php",
    method: "GET",
    dataType: "json",
    success: function (response) {
        if (!response.loggedIn || response.rolle !== "admin") {
            alert("Zugriff verweigert! Sie haben keine Berechtigung, diese Seite zu betreten.");
            window.location.href = "../sites/index.html";
        } else {
            $("body").show(); // Seite anzeigen, wenn Berechtigung vorhanden
        }
    },
    error: function () {
        alert("Fehler bei der Überprüfung der Sitzung.");
        window.location.href = "../sites/index.html";
    }
});

// Wiederholte Sitzungskontrolle beim Laden der Seite
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

    // Funktion zum Laden aller Gutscheine
    function loadCoupons() {
        $.ajax({
            url: "../../Backend/logic/adminCouponHandler.php",
            method: "GET",
            dataType: "json",
            success: function (response) {
                if (response.length === 0) {
                    $("#coupon-list").html("<p>Keine Gutscheine vorhanden.</p>");
                    return;
                }

                let html = `<table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Wert (€)</th>
                            <th>Gültig bis</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                response.forEach(coupon => {
                    let statusClass = "";
                    if (coupon.status === "aktiv") {
                        statusClass = "text-success";
                    } else if (coupon.status === "eingelöst") {
                        statusClass = "text-primary";
                    } else if (coupon.status === "abgelaufen") {
                        statusClass = "text-danger";
                    }

                    let actionButton = "";
                    if (coupon.status === "aktiv") {
                        actionButton = `<button class="btn btn-sm btn-warning mark-used" data-id="${coupon.id}">Als eingelöst markieren</button>`;
                    }

                    html += `
                        <tr>
                            <td>${coupon.code}</td>
                            <td>${coupon.wert}</td>
                            <td>${coupon.gueltig_bis}</td>
                            <td class="${statusClass}">${coupon.status}</td>
                            <td>${actionButton}</td>
                        </tr>
                    `;
                });

                html += `</tbody></table>`;
                $("#coupon-list").html(html);
            },
            error: function () {
                $("#coupon-list").html("<p>Fehler beim Laden der Gutscheine.</p>");
            }
        });
    }

    loadCoupons(); // Direkt beim Laden Gutscheine anzeigen

    // Gutschein erstellen
    $("#new-coupon-form").submit(function (e) {
        e.preventDefault();
        const wert = $("#wert").val();
        const gueltigBis = $("#gueltig_bis").val();

        $.ajax({
            url: "../../Backend/logic/adminCouponHandler.php",
            method: "POST",
            data: {
                action: "create",
                wert: wert,
                gueltig_bis: gueltigBis
            },
            dataType: "json",
            success: function (response) {
                alert(response.message);
                $("#new-coupon-form")[0].reset(); // Formular zurücksetzen
                loadCoupons(); // Liste aktualisieren
            },
            error: function () {
                alert("Fehler beim Erstellen des Gutscheins.");
            }
        });
    });

    // Gutschein als eingelöst markieren
    $("body").on("click", ".mark-used", function () {
        const couponId = $(this).data("id");

        $.ajax({
            url: "../../Backend/logic/adminCouponHandler.php",
            method: "POST",
            data: {
                action: "markAsUsed",
                couponId: couponId
            },
            dataType: "json",
            success: function (response) {
                alert(response.message);
                loadCoupons(); // Liste neu laden
            },
            error: function () {
                alert("Fehler beim Aktualisieren des Gutscheins.");
            }
        });
    });

});
