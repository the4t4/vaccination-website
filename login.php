<?php 

require_once("utils/_init.php");

if (verify_post("email", "password")) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
  
    $user = $auth->authenticate($email, $password);
    if ($user === NULL) {
      $errors[] = "Invalid username or password";
    }

    if (empty($errors)) {
      $auth->login($user);
      redirect("index.php");
    }
}

?>

<?php require_once("partials/header.inc.php") ?>

<h1 class="text-center">Login</h1>

<div class="container w-75">
    <?php foreach($errors as $error) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endforeach; ?>
    <form method="post" class="fs-5">
        <div class="form-group row">
            <label for="email" class="col-2 col-form-label">E-mail address</label>
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
        <br>
        <button class="btn btn-secondary bg-dark">Log in</button>
        <a class="btn btn-secondary btn-block" style="float: right" href="register.php">If you don't have an account, you can register here</a>
    </form>
    <br>
</div>

<?php require_once("partials/footer.inc.php") ?>