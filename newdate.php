<?php

require_once("utils/_init.php");

if(!$auth->authorize(["admin"])){
    redirect("index.php");
}

if (verify_post("date", "time", "slots")) {
    $date = $_POST["date"];
    $time = $_POST["time"];
    $timeComponents = explode(':', $_POST["time"]);
    $slots = $_POST["slots"];

    if(empty($date)){
        $errors[] = "Date must not be empty";
    }
    else if(!strtotime($date)){
        $errors[] = "Invalid date";
    }

    if(empty($time)){
        $errors[] = "Time must not be empty";
    }
    else if(count($timeComponents)!=2){
        $errors[] = 'Invalid time format, expecting eg "12:45"';
    }
    else if($timeComponents[0] < 0 || $timeComponents[0] > 23 || $timeComponents[1] < 0 || $timeComponents[1] > 59){
        $errors[] = 'Invalid time format, expecting eg "12:45"';
    }

    if(empty($slots)){
        $errors[] = "Slots must not be empty";
    }
    else{
        if(!is_numeric($slots)){
            $errors[] = "Slots must be a number";
        }
        if($slots <= 0){
            $errors[] = "Slots must be a positive number";
        }
    }

    if (empty($errors)){
        $postStorage->add([
            "date"  => $date,
            "time"  => $time,
            "slots" => $slots
        ]);
        redirect("index.php");
    }
}

?>

<?php require_once("partials/header.inc.php") ?>

<h1 class="text-center">New date</h1>

<div class="container w-75">
    <form method="post" class="fs-5">
        <div class="form-group row">
            <label for="date" class="col-2 col-form-label">Date</label>
            <div class="col-10">
                <input class="form-control" type="date" name="date" id="date" value="<?= $date ?? "" ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="time" class="col-2 col-form-label">Time</label>
            <div class="col-10">
                <input class="form-control" type="time" name="time" id="time" value="<?= $time ?? "" ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="slots" class="col-2 col-form-label">Slots</label>
            <div class="col-10">
                <input class="form-control" type="number" name="slots" id="slots" value="<?= $slots ?? "" ?>">
            </div>
        </div>
        <br>
        <div class="card mx-auto w-25">
            <button class="btn btn-secondary bg-dark fs-5">Post</button>
        </div>
    </form>
    <br>
    <?php foreach($errors as $error) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endforeach; ?>
</div>

<?php require_once("partials/footer.inc.php") ?>