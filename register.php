<?php 

require_once("utils/_init.php");

if (verify_post("fullname", "ssn", "address", "email", "password", "confirm-password")) {
    $fullname = trim($_POST["fullname"]);
    $ssn = trim($_POST["ssn"]);
    $address = trim($_POST["address"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];

    if (empty($fullname)) {
        $errors[] = "Full name must not be empty";
    }

    if (empty($ssn)) {
        $errors[] = "SSN number must not be empty";
    }
    else{
        if(strlen($ssn) !== 9){
            $errors[] = "SSN number must be 9 digits";
        }
        if(!is_numeric($ssn)){
            $errors[] = "SSN number must only contain numbers";
        }
        if($ssn < 0){
            $errors[] = "SSN number must be positive";
        }
    }  

    if (empty($address)) {
        $errors[] = "Address must not be empty";
    }
    
    if (empty($email)) {
        $errors[] = "E-mail address name must not be empty";
    }
    else if(!strpos($email, "@") || !strpos($email, ".")){
        $errors[] = "E-mail address is in an incorrect format";
    }
    else if($auth->user_exists($email)){
        $errors[] = "E-mail already registered";
    }

    if (strlen($password) < 5) {
        $errors[] = "Password must be at least 5 characters long";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if(empty($errors)){
        $auth->register([
            "fullname" => $fullname,
            "ssn"      => $ssn,
            "address"  => $address,
            "email"    => $email,
            "password" => $password,
          ]);
        redirect("login.php");
    }
}

?>

<?php require_once("partials/header.inc.php") ?>

<h1 class="text-center">Register</h1>
<div class="container w-75">
    <form method="post" class="fs-5">
        <div class="form-group row">
            <label for="fullname" class="col-2 col-form-label">Full name</label>
            <div class="col-10">
                <input class="form-control" type="text" name="fullname" id="fullname" value="<?= $fullname ?? "" ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="username" class="col-2 col-form-label">SSN number</label>
            <div class="col-10">
                <input class="form-control" type="number" name="ssn" id="ssn" value="<?= $ssn ?? "" ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="username" class="col-2 col-form-label">Address</label>
            <div class="col-10">
                <input class="form-control" type="text" name="address" id="address" value="<?= $address ?? "" ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="username" class="col-2 col-form-label">E-mail</label>
            <div class="col-10">
                <input class="form-control" type="email" name="email" id="email" value="<?= $email ?? "" ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-2 col-form-label">Password</label>
            <div class="col-10">
                <input class="form-control" type="password" name="password" id="password">
            </div>
        </div>
        <div class="form-group row" >
            <label for="confirm-password" class="col-2 col-form-label">Confirm password</label>
            <div class="col-10">
                <input class="form-control" type="password" name="confirm-password" id="confirm-password">
            </div>
        </div>
        <br>
        <button class="btn btn-secondary bg-dark">Submit</button>
        <a class="btn btn-secondary btn-block" style="float: right" href="login.php">If you already have an account, you can log in here</a>
    </form>
    <br>
    <?php foreach($errors as $error) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endforeach; ?>
</div>

<?php require_once("partials/footer.inc.php") ?>