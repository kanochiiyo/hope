<?php

session_start();

require_once(__DIR__ . "/functions/authentication.php");
// require_once (__DIR__ . "/functions/functions.php");

if (isLogged()) {
    if (isStaff()) {
        header("Location:staff/index.php");
    } elseif (isAdmin()) {
        header("Location:admin/index.php");
    } else {
        header("Location:user/index.php");
    }
}


if (isset($_POST["login"])) {
    $result = loginAttempt($_POST);
    if ($result) {
        if (isStaff()) {
            header("Location:staff/index.php");
        } elseif (isAdmin()) {
            header("Location:admin/index.php");
        } else {
            header("Location:user/index.php");
        }
    }
}

include(__DIR__ . "/templates/header.php");
?>


<section id="login">
    <div class="container-fluid d-flex justify-content-center align-items-center">
        <div class="card p-4 shadow rounded-4 border-0" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h2 class="fw-bold text-center">LOGIN</h2>
                <p class="text-muted text-center">Don't have an account? Sign up <a href="register.php">here</a></p>
                <form id="loginForm" method="post">
                    <div class="mb-3 text-start">
                        <input type="text" class="form-control" placeholder="Username" id="username" name="username">
                    </div>
                    <div class="mb-3 text-start">
                        <input type="password" class="form-control" placeholder="Password" id="password"
                            name="password">
                    </div>
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="submit" name="login"
                            class="btn d-flex justify-content-center align-items-center gradient-btn border-0 rounded-4 fw-semibold text-white"
                            style="width: 124px; height: 52px">Login
                        </button>
                    </div>
                </form>

                <div class="text-muted text-center my-3">Login with Others</div>

                <button class="social-btn rounded-4 mb-2">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google">
                    Login with <strong>Google</strong>
                </button>

                <button class="social-btn rounded-4">
                    <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" alt="Facebook">
                    Login with <strong>Facebook</strong>
                </button>

            </div>
        </div>
    </div>
</section>

<?php
include(__DIR__ . "/templates/footer.php");
?>