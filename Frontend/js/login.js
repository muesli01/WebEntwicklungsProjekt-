// Event-Handler für das Login-Formular
$("#login-form").submit(function (e) {
    e.preventDefault(); // Verhindert das standardmäßige Absenden des Formulars

    // AJAX-Anfrage zum Login-Handler
    $.ajax({
        url: "../../Backend/logic/loginHandler.php", // Pfad zur PHP-Datei, die das Login verarbeitet
        method: "POST", // Methode: POST
        data: {
            email: $("#email").val(), // Holt den eingegebenen Wert aus dem E-Mail-Feld
            password: $("#password").val(), // Holt das Passwort aus dem Formular
            rememberMe: $("#rememberMe").is(":checked") ? 1 : 0 // Setzt "remember me" als 1 oder 0 je nach Häkchen
        },
        dataType: "json", // Erwartetes Rückgabeformat ist JSON
        success: function (response) {
            if (response.success) {
                // Bei erfolgreichem Login zur Profilseite weiterleiten
                window.location.href = "userprofile.html";
            } else {
                // Fehlermeldung anzeigen, falls Login nicht erfolgreich
                $("#login-message").text(response.message);
            }
        },
        error: function () {
            // Fehlermeldung bei technischer Störung (z. B. Server nicht erreichbar)
            $("#login-message").text("Fehler beim Login.");
        }
    });
});
