# Auto Webshop - Backlog

| ID  | Feature                           | Beschreibung                                             | Abschätzung | Abhängigkeiten  | Tags                    |
|-----|-----------------------------------|----------------------------------------------------------|-------------|-----------------|-------------------------|
| 1   | User Registration                 | Nutzer kann sich registrieren und seine Daten speichern. | 5           | -               | frontend, auth          |
| 2   | User Login & Session Management   | Nutzer kann sich anmelden und wird authentifiziert.      | 3           | 1               | frontend, backend, auth |
| 3   | User Profile & Account Management | Nutzer kann seine persönlichen Daten verwalten.          | 3           | 1, 2            | frontend, backend       |
| 4   | Product Listing & Filtering       | Anzeige von Produkten mit Filteroptionen.                | 5           | -               | frontend                |
| 5   | Product Details                   | Detailseite eines Produkts mit allen Informationen.      | 3           | 4               | frontend                |
| 6   | Product Search                    | Live-Suche für Produkte.                                | 3           | 4               | frontend, backend       |
| 7   | Shopping Cart Functionality       | Warenkorb mit Produktverwaltung.                         | 5           | 4, 5            | frontend, backend       |
| 8   | Checkout & Order Processing       | Nutzer kann Bestellungen aufgeben und bezahlen.         | 8           | 7               | frontend, backend, payment |
| 9   | Gutscheine Management             | Verwaltung und Nutzung von Gutscheinen beim Kauf.        | 5           | 8               | backend, payment        |
| 10  | Order History                     | Nutzer kann vergangene Bestellungen einsehen.            | 3           | 8               | frontend, backend       |
| 11  | Invoice Generation                | Erstellung von Rechnungen als PDF.                       | 4           | 8               | backend, finance        |
| 12  | Admin Panel - Product Management  | Admin kann Produkte verwalten.                           | 6           | 4, 5            | backend, admin          |
| 13  | Admin Panel - User Management     | Admin kann Nutzerkonten verwalten.                       | 4           | 2, 3            | backend, admin          |
| 14  | Admin Panel - Order Management    | Admin kann Bestellungen einsehen und bearbeiten.        | 5           | 8, 10           | backend, admin          |
| 15  | Admin Panel - Coupon Management   | Admin kann Gutscheine erstellen und verwalten.           | 4           | 9               | backend, admin          |
| 16  | System Security                   | Verschlüsselung von Passwörtern, Schutz gegen SQL-Injections. | 6       | -               | security, backend       |
| 17  | Frontend & Backend Separation     | Klare Trennung mit JSON-API.                             | 6           | -               | architecture            |
