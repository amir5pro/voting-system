<!-- admin_login.php -->
<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: admin_panel.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "voting_system");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = true;
        header("Location: admin_panel.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Union Voting System - Admin Login</title>
    <link rel="stylesheet" href="admin_login.css">
</head>

<body>
    <div class="admin_login">
        <div class="a_hawassa">
            <div class="a_top">
                <img class="a_logo" src="images/logo.png">
                <h2 class="a_inner">Hawassa University</h2>
            </div>
            <div class="a_home">
                <p><a href="logout.php">Home</a></p>
            </div>
        </div>
        <div class="admin_inner">
            <div>
                <img  src="images/admin.png">
            </div>
            <div class="a_form">
            <h1>Admin Login</h1>
            <p>Login to add candidates</p>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button class="a_btn" type="submit">Login</button>
            </form>
            <?php if (isset($error)) {
                echo $error;
            } ?>
            </div>
        </div>
    </div>
</body>

</html>