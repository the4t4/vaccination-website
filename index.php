<?php

require_once("utils/_init.php");

if (verify_get("logout")){

    if($_GET["logout"] == 1){
        $auth->logout();
        redirect("index.php");
    }
}

if (verify_get("unbook")){

    if($_GET["unbook"] == 1){
        $user = $auth->authenticated_user();
        $user["booking"] = "";
        $userStorage->update($user["id"], $user);
        $auth->login($user);
        redirect("index.php");
    }
}

?>

<?php require_once("partials/header.inc.php") ?>

<div class="container mt-3">
    <h1 class="text-center">Vaccination Time Slots</h1>
    <p class="text-start fs-5">We are organizing vaccinations at various times in our central building.
    Below, you can find available times for which you can schedule an appointment to get a coronavirus vaccination.
    Please provide complete and correct details while booking.
    If you would like to cancel or postpone an appointment, please do so <em>before the indicated day.</em><br>
    <strong>You must be logged in to perform any operation.</strong>
    </p>
    <br>
    <?php if($auth->is_authenticated() && $auth->authenticated_user()["booking"] !== "") :?>    
        <div class="card w-50 mx-auto text-center">
            <h5 class="card-header bg-info text-white"><b>Your booking</b></h5>
            <div class="card-body">
                <p class="card-text fs-4"><?= $auth->authenticated_user()["booking"] ?></p>
                <a class="btn btn-outline-primary" href="?unbook=1">Cancel booking</a>
            </div>
        </div>
        <br>
    <?php elseif($auth->authorize(["admin"])) : ?>
        <div class="card w-25 mx-auto">
            <a class="btn btn-secondary bg-dark fs-5" href="newdate.php">Post a new date</a>
        </div>
        <br>
    <?php endif; ?>
</div>

<div class="container w-75 h-75">
    <h2 class="text-center" id="monthYear"></h2>
    <table class="table bg-light text-center" id="calendar"></table>
    <button class="btn btn-secondary bg-dark" id="prev">Previous</button>
    <button class="btn btn-secondary bg-dark" style="float: right" id="next">Next</button>
</div>

<script src="index.js"></script>

<?php require_once("partials/footer.inc.php") ?>