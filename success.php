<?php 

require_once("utils/_init.php");

?>

<?php require_once("partials/header.inc.php") ?>

<div class="container mt-5 w-50">
    <div class="card">
        <div class="card-header alert alert-success fs-4 text-center">
            <b>Booking successful</b>
        </div>
        <div class="card-body">
        <p class="card-text">Your booking has been successfully saved. You can now see your appointment in the home page.</p>
        <a class="btn btn-secondary bg-dark" href="index.php">Home</a>
        </div>
    </div>
</div>

<?php require_once("partials/footer.inc.php") ?>


