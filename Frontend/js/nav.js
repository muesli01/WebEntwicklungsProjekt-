document.addEventListener("DOMContentLoaded", function () {
    const nav = document.getElementById("nav-placeholder");

    let navPath;
    let baseHref;

    if (window.location.pathname.includes("/Frontend/sites/")) {
        navPath = "../includes/nav.html";
        baseHref = "../sites/";
    } else {
        navPath = "Frontend/includes/nav.html";
        baseHref = "Frontend/sites/";
    }

    // 
    fetch(navPath)
        .then(response => {
            if (!response.ok) throw new Error("Navigation konnte nicht geladen werden.");
            return response.text();
        })
        .then(navHtml => {
            // 
            return fetch("../../Backend/logic/sessionCheck.php")
                .then(response => response.json())
                .then(session => ({ navHtml, session }));
        })
        .then(({ navHtml, session }) => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(navHtml, 'text/html');
            const navItems = doc.querySelector("ul.navbar-nav");

            // 
            navItems.innerHTML = "";

            // 
            const homeLink = createNavItem("Home", `${baseHref}index.html`);
            const productsLink = createNavItem("Produkte", `${baseHref}productList.html`);

            navItems.appendChild(homeLink);
            navItems.appendChild(productsLink);

            if (!session.loggedIn) {
                navItems.appendChild(createNavItem("Warenkorb", `${baseHref}warenkorb.html`));
                navItems.appendChild(createNavItem("Registrieren", `${baseHref}register.html`, "btn btn-primary text-white"));
                navItems.appendChild(createNavItem("Login", `${baseHref}login.html`, "btn btn-primary text-white"));
            } else if (session.rolle === "user") {
                navItems.appendChild(createNavItem("Mein Konto", `${baseHref}userprofile.html`));
                navItems.appendChild(createNavItem("Warenkorb", `${baseHref}warenkorb.html`));
            } else if (session.rolle === "admin") {
                navItems.appendChild(createNavItem("Admin Dashboard", `${baseHref}adminDashboard.html`));
                navItems.appendChild(createNavItem("Mein Konto", `${baseHref}userprofile.html`));
                
            }

            nav.innerHTML = doc.body.innerHTML;
        })
        .catch(error => {
            console.error("Fehler beim Laden der Navigation:", error);
        });

    // Хелпер для создания пунктов меню
    function createNavItem(text, href, extraClass = "") {
        const li = document.createElement("li");
        li.className = "nav-item";

        const a = document.createElement("a");
        a.className = `nav-link ${extraClass}`;
        a.href = href;
        a.textContent = text;

        li.appendChild(a);
        return li;
    }
});
