<?php 
require("includes/db.php");
include("includes/header.php"); 
?>

<header class="bg-primary text-white text-center p-5">
    <div class="container">
        <h1>Rejoins des défis sportifs 🔥</h1>
        <p>Teste tes limites, progresse et compare-toi aux autres !</p>
        <a href="register.php" class="btn btn-light btn-lg">Rejoins la communauté</a>
    </div>
</header>


<section class="container my-5">
    <h2 class="text-center mb-4">Défis populaires</h2>

    <div class="row">

        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">🏃 Courir 10 km</h5>
                    <p class="card-text">Teste ton endurance sur 10 kilomètres.</p>
                    <button class="btn btn-success">Participer</button>
                </div>
            </div>
        </div>

        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">💪 100 pompes</h5>
                    <p class="card-text">Un défi de force pour les plus motivés.</p>
                    <button class="btn btn-success">Participer</button>
                </div>
            </div>
        </div>

        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">🚴 20 km vélo</h5>
                    <p class="card-text">Un challenge pour les amateurs de vélo.</p>
                    <button class="btn btn-success">Participer</button>
                </div>
            </div>
        </div>

    </div>
</section>
<div class="row row-cols-1 row-cols-md-3 g-4">

    <div class="col">
        <div class="card">
            <img src="..." class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">Texte...</p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="..." class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">Texte...</p>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <img src="..." class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">Texte...</p>
            </div>
        </div>
    </div>

</div>





<?php include("includes/footer.php"); ?>
