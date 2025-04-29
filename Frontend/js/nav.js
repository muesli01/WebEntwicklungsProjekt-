document.addEventListener("DOMContentLoaded", function () {
    const nav = document.getElementById("nav-placeholder");

    // Ermittle Basis-Pfad zur nav.html
    let navPath;
    let baseHref;

    if (window.location.pathname.includes("/Frontend/sites/")) {
        navPath = "../includes/nav.html";
        baseHref = "../sites/";
    } else {
        navPath = "Frontend/includes/nav.html";
        baseHref = "Frontend/sites/";
    }

    // lade nav.html
    fetch(navPath)
        .then(response => {
            if (!response.ok) throw new Error("Navigation konnte nicht geladen werden.");
            return response.text();
        })
        .then(data => {
            // ersetze alle Link-Ziele dynamisch
            data = data.replace(/href="..\/*sites\//g, `href="${baseHref}`);
            data = data.replace(/href="index.html"/, baseHref === "../sites/" ? `href="../../index.html"` : `href="index.html"`);

            nav.innerHTML = data;
        })
        .catch(error => {
            console.error("Fehler beim Laden der Navigation:", error);
        });
});
