// Überprüfung der Sitzung direkt beim Laden der Seite
$.ajax({
    url: "../../Backend/logic/sessionCheck.php",
    method: "GET",
    dataType: "json",
    success: function (response) {
        // Wenn Benutzer nicht eingeloggt ist oder keine Admin-Rechte hat
        if (!response.loggedIn || response.rolle !== "admin") {
            alert("Zugriff verweigert! Sie haben keine Berechtigung, diese Seite zu betreten.");
            window.location.href = "../sites/index.html"; // Weiterleitung zur Startseite
        } else {
            // Nur wenn alles OK ist — Seite anzeigen
            $("body").show(); // Body sichtbar machen
        }
    },
    error: function () {
        // Fehler beim Abrufen der Sitzung
        alert("Fehler bei der Überprüfung der Sitzung.");
        window.location.href = "../sites/index.html"; // Weiterleitung zur Startseite
    }
});

// Sicherstellen, dass bei DOM-Initialisierung ebenfalls die Sitzung geprüft wird
document.addEventListener("DOMContentLoaded", function () {
    $.ajax({
        url: "../../Backend/logic/sessionCheck.php",
        method: "GET",
        dataType: "json",
        success: function (response) {
            // Erneute Prüfung auf Admin-Berechtigung
            if (!response.loggedIn || response.rolle !== "admin") {
                alert("Zugriff verweigert! Sie haben keine Berechtigung, diese Seite zu betreten.");
                window.location.href = "../sites/index.html"; // Weiterleitung bei fehlender Berechtigung
            }
        },
        error: function () {
            // Fehler beim Abrufen der Sitzung
            alert("Fehler bei der Überprüfung der Sitzung.");
            window.location.href = "../sites/index.html"; // Weiterleitung zur Startseite
        }
    });
});
