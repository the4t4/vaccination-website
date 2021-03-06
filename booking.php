<?php

require_once("utils/_init.php");


if(verify_get("date","time")){
    
    $date = $_GET["date"];
    $time = $_GET["time"];
    $user = $auth->authenticated_user();
    if($user == NULL){
      redirect("login.php");
    }
    if($user["booking"] !== ""){
      redirect("index.php");
    }
    $value = 0;

    if(verify_get("termsAndConds")){
        $checked = $_GET["termsAndConds"];
        if($checked == 1) {
            $state = "checked"; //checkbox checked
            $user["booking"] = $date . " " . $time;
            $userStorage->update($user["id"], $user);
            $auth->login($user);
            redirect("success.php");
        }
        else{
            $state = ""; //checkbox unchecked
            $errors[] = "You must accept the Terms and Conditions";
        }
    }

}
else redirect("index.php");

?>

<?php require_once("partials/header.inc.php") ?>

<div class="container w-75">
<br>
  <?php if($auth->authorize(["admin"])) : ?>
    <ul class="list-group">
      <li class="list-group-item list-group-item-primary text-center fs-4 ">
        Booked users for <?= $date . " " . $time ?>
      </li>
      <?php $users = $userStorage->findMany( function ($user) use($auth, $date, $time){
        $bookingDate = explode('.', $date);
        return $auth->compareUserBookingDate($user, $bookingDate[0], $bookingDate[1], $bookingDate[2], $time );
      });
      foreach($users as $user): ?>
        <li class="list-group-item list-group-item-action fs-5"><?= $user["fullname"] . " | " . $user["ssn"] . " | " . $user["email"] ?></li>
      <?php endforeach; ?>
    </ul>
    <br>
    <a class="btn btn-secondary bg-dark" href="index.php">Home</a>
  <?php else : ?>
    <h1 class="text-center">Booking details</h1>
        <form class="fs-5" method="get">
            <div class="form-group row">
                <label for="fullname" class="col-2 col-form-label">Full name</label>
                <div class="col-10">
                    <input class="form-control" type="text" id="fullname" value="<?= $user["fullname"]?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-2 col-form-label">Address</label>
                <div class="col-10">
                    <input class="form-control" type="text" id="fullname" value="<?= $user["address"]?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="ssn" class="col-2 col-form-label">SSN</label>
                <div class="col-10">
                    <input class="form-control" type="text" id="ssn" value="<?= $user["ssn"]?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="date" class="col-2 col-form-label">Appointment date</label>
                <div class="col-10">
                    <input class="form-control" type="text" id="date" name="date" value="<?= $date ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="time" class="col-2 col-form-label">Appointment time</label>
                <div class="col-10">
                    <input class="form-control" type="text" id="time" name="time" value="<?= $time ?>" readonly>
                </div>
            </div>
            <br>

            <div class="card">
              <div class="card-header bg-warning">
                Terms and Conditions
              </div>
              <div class="card-body">
                <p class="card-text">It is mandatory to show up on the appointment after booking it. If you would like to cancel or postpone
                  the appointment, you may do so until the day of the appointment on the home page.<br><br> 

                  Your data should be correct and complete.<br><br>

                  <b class="text-danger">Failing to comply to the terms will result in an indefinate ban of your account.</b><br><br>

                  We are not liable for any side effects the coronavirus vaccine may cause nor do we guarantee the effectiveness of it.
                  Taking the vaccine is completely voluntary and of your own free will.
                  
                </p>
                <input type="hidden" name="termsAndConds" value="0">
                <input class="form-check-input" type="checkbox" onclick="this.previousElementSibling.value=1-this.previousElementSibling.value">
                <label class="form-check-label" for="termsAndConds"> 
                  I have read and accept the Terms and Conditions
                </label>
              </div>
            </div>
            
            <br><br>
            <div class="text-center">
                <button class="btn btn-secondary bg-dark fs-4 w-25">Confirm</button>
            </div>
        </form>
        <br>
        <?php foreach($errors as $error) : ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once("partials/footer.inc.php") ?>