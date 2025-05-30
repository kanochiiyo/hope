<?php
session_start();

require_once (__DIR__ . "/functions/authentication.php");


if (isset($_POST["register"])) {
  $result = register($_POST);
  if ($result) {
    echo "<script>
    alert('Sign up berhasil.');
    document.location.href = 'login.php';
    </script>";
  }
}

if (isLogged()) {
  header("Location:index.php");
}

include (__DIR__ . "/templates/header.php");
?>


    <section id="login">
        <div class="container-fluid d-flex justify-content-center align-items-center">
            <div class="card p-4 shadow rounded-4 border-0" style="max-width: 400px; width: 100%;">
                <div class="card-body">
                    <h2 class="fw-bold text-center">REGISTER</h2>
                    <p class="text-muted text-center">Already have an account? Sign in <a href="login.php">here</a></p>
                    <form id="loginForm" method="post">
                        <div class="mb-3 text-start">
                            <input type="text" class="form-control" placeholder="Username"  id="username" name="username" >
                        </div>
                         <div class="mb-3 text-start">
                            <input type="email" class="form-control" placeholder="Email"  id="email" name="email" >
                        </div>
                        <div class="mb-3 text-start">
                            <input type="password" class="form-control" placeholder="Password"  id="password" name="password" >
                        </div>
                         <div class="mb-3 text-start">
                            <input type="password" class="form-control" placeholder="Confirm Password"  id="confirmpassword" name="confirmpassword" >
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                        <button type="submit" 
                            class="btn d-flex justify-content-center align-items-center gradient-btn border-0 rounded-4 fw-semibold text-white mt-1"
                            style="width: 124px; height: 52px"
                            name="register" id="register">Register
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php
include (__DIR__ . "/templates/footer.php");
?>