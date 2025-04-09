<!DOCTYPE html>
<html lang="de">

<?php 
    include 'Frontend/includes/pagehead.html'
    ?>
<body>

 
    <?php include 'Frontend/includes/nav.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
    <header class="text-center p-5 bg-light">
        <h1>Willkommen im Auto Webshop!</h1>
        <p>Finden Sie Ihr Traumauto mit nur wenigen Klicks.</p>
    </header>

    <div class="container my-5">
        <h2 class="text-center">Unsere neuesten Autos</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="Frontend/res/img/car1.jpg" class="card-img-top" alt="Auto 1">
                    <div class="card-body">
                        <h5 class="card-title">VW Golf</h5>
                        <p class="card-text">Sportliches und luxuriöses SUV.</p>
                        <a href="#" class="btn btn-primary">Mehr erfahren</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="Frontend/res/img/car2.jpg" class="card-img-top" alt="Auto 2">
                    <div class="card-body">
                        <h5 class="card-title">VW Tiguan</h5>
                        <p class="card-text">Elegante Limousine mit modernster Technologie.</p>
                        <a href="#" class="btn btn-primary">Mehr erfahren</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="Frontend/res/img/car3.jpg" class="card-img-top" alt="Auto 3">
                    <div class="card-body">
                        <h5 class="card-title">Audi RS 7</h5>
                        <p class="card-text">Komfortable und zuverlässige Limousine.</p>
                        <a href="#" class="btn btn-primary">Mehr erfahren</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'Frontend/includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
