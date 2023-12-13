<?php
require "includes/header.php";
require "config/database.php";
require "autoloader.php";

$connection = new Database("127.0.0.1", "root", "", "market");
$pdo = $connection->connect();
User::$pdo = $pdo;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = User::createUser($username, $password);

    if ($result == $password) {
        setcookie('Log', $password, time() + 3600);
        header("Location: ../index.php");
        exit;
    } else {
        echo $result;
    }
}

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Login</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require "includes/footer.php";
?>