<?
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize(false);


use Controller\Users\Register;
use View\Templates\Pages;

/**
 * Class for the register page
 */
class RegisterPage extends Pages
{

    protected string $errorPath = '/register.php';
    public function __construct()
    {
        $this->pageTitle = 'Create Page';

        parent::__construct();
    }

    protected function pageContent()
    {
?>

        <form method="Post">
            <div class="form-group">
                <label for="usernameInput">Username</label>
                <input type="text" class="form-control" id="usernameInput" name="username" aria-describedby="usernameHelp" placeholder="Enter username" maxlength="64" required>
            </div>

            <div class="form-group">
                <label for="emailInput">Email address</label>
                <input type="email" class="form-control" id="emailInput" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label for="passwordInput">Password</label>
                <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" required>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="tosCheckbox" required>
                <label class="form-check-label" for="tosCheckbox">I agree to the <a href="#">Terms and Services</a></label>
            </div>

            <input type="submit" class="btn btn-primary" value="Register">
        </form>

        <div class="d-block">
            <p>Already have an account? <a href="login.php" class="btn btn-link">Login</a></p>
        </div>


<?php
    }

    public function handlePostRequest(array &$Request)
    {
        // try to create user
        $handler = new Register();
        $username = $handler->register($_POST);


        // redirecting user to the login page
        // easier to let user login that it is to login the user directly
        header("Location: register.php?err=$username created Successfully&type=success");
    }
}

// rendering page
$page = new RegisterPage();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $page->handlePostRequest($_POST);
    } else {
        $page->renderOnlyContent();
    }
} catch (Error $error) {
    $page->errorRedirect('An error occurred when trying to create the user!', $error->getMessage());
}
