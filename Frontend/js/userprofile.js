$(document).ready(function () {
    // Prüfen, ob Benutzer eingeloggt ist
    $.ajax({
        url: "../../Backend/logic/checkLogin.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            if (!response.loggedIn) {
                // Nicht eingeloggt → Weiterleitung zur Login-Seite
                window.location.href = "login.html";
            } else {
                // Eingeloggt → Buttons anzeigen
                $("#edit-profile-btn").show();
                $("#view-orders-btn").show();
                $("#logout-btn").show();
            }
        },
        error: function () {
            alert("Fehler beim Überprüfen des Logins.");
        }
    });

    // Benutzerdaten laden
    $.ajax({
        url: "../../Backend/logic/userProfileHandler.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
            if (data.success) {
                $("#profile-view").html(`
                    <p><strong>Anrede:</strong> ${data.user.anrede}</p>
                    <p><strong>Vorname:</strong> ${data.user.vorname}</p>
                    <p><strong>Nachname:</strong> ${data.user.nachname}</p>
                    <p><strong>Adresse:</strong> ${data.user.adresse}</p>
                    <p><strong>PLZ:</strong> ${data.user.plz}</p>
                    <p><strong>Ort:</strong> ${data.user.ort}</p>
                    <p><strong>E-Mail:</strong> ${data.user.email}</p>
                    <p><strong>Benutzername:</strong> ${data.user.username}</p>
                    <p><strong>Zahlungsinformationen:</strong> ${data.user.zahlung ? data.user.zahlung : "Nicht angegeben"}</p>
                `);

                // Werte in Bearbeiten-Formular setzen
                $("#vorname").val(data.user.vorname);
                $("#nachname").val(data.user.nachname);
                $("#adresse").val(data.user.adresse);
                $("#zahlung").val(data.user.zahlung);
            } else {
                $("#profile-view").html("<p>Fehler beim Laden der Benutzerdaten oder nicht eingeloggt.</p>");
            }
        }
    });

    // Bearbeiten-Formular ein-/ausblenden
    $("#edit-profile-btn").click(function () {
        $("#edit-form").toggle();
    });

    // Zur Bestellübersicht weiterleiten
    $("#view-orders-btn").click(function () {
        window.location.href = "myOrders.html";
    });

    // Logout ausführen
    $("#logout-btn").click(function () {
        $.ajax({
            url: "../../Backend/logic/logoutHandler.php",
            method: "POST",
            success: function () {
                window.location.href = "index.html";
            },
            error: function () {
                alert("Fehler beim Ausloggen.");
            }
        });
    });

    // Profil bearbeiten und absenden
    $("#profile-edit-form").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "../../Backend/logic/userProfileUpdateHandler.php",
            method: "POST",
            data: {
                vorname: $("#vorname").val(),
                nachname: $("#nachname").val(),
                adresse: $("#adresse").val(),
                zahlung: $("#zahlung").val(),
                password_confirm: $("#password-confirm").val()
            },
            dataType: "json",
            success: function (response) {
                $("#update-message").text(response.message);
                if (response.success) {
                    location.reload();
                }
            },
            error: function () {
                $("#update-message").text("Fehler beim Aktualisieren der Daten.");
            }
        });
    });

    // Zahlungsarten laden
    function loadUserPayments() {
        $.ajax({
            url: "../../Backend/logic/getPaymentMethods.php",
            method: "GET",
            dataType: "json",
            success: function (res) {
                if (!res.success) return;
                let html = '';
                res.methods.forEach(m => {
                    html += `<li><strong>${m.name}</strong>${m.details ? ' – ' + m.details : ''}</li>`;
                });
                $("#payment-list").html(html);
            }
        });
    }

    // Formular zum Hinzufügen von Zahlungsarten anzeigen/verbergen
    $("#show-add-payment").click(function () {
        $("#add-payment-form").toggle();
    });

    // Neue Zahlungsart hinzufügen
    $("#add-payment-btn").click(function () {
        const name = $("#pm-name").val().trim();
        const details = $("#pm-details").val().trim();
        $.ajax({
            url: "../../Backend/logic/addPaymentMethod.php",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({ name, details }),
            dataType: "json",
            success: function (res) {
                $("#add-payment-msg")
                    .text(res.message)
                    .removeClass("text-danger text-success")
                    .addClass(res.success ? "text-success" : "text-danger");
                if (res.success) {
                    $("#pm-name,#pm-details").val('');
                    loadUserPayments();
                }
            }
        });
    });

    // Zahlungsarten direkt nach Laden anzeigen
    loadUserPayments();
});
