// Dieser Code lädt Produktdaten und bindet deren Bilder über eine Anfrage ein
document.addEventListener('DOMContentLoaded', function () {
    fetch('../../Backend/logic/productHandler.php')
        .then(response => response.json())
        .then(data => {
            const productContainer = document.getElementById('product-container');
            data.forEach(product => {
                const productCard = `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="../../Backend/logic/getProductImage.php?file=${encodeURIComponent(product.image)}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>
                                <a href="productList.html" class="btn btn-primary">Mehr erfahren</a>
                            </div>
                        </div>
                    </div>
                `;
                productContainer.innerHTML += productCard;
            });
        })
        .catch(error => {
            console.error('Fehler beim Laden der Produkte:', error);
        });
});
