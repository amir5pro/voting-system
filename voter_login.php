<!-- voter_login.php -->
<?php
session_start();
if (isset($_SESSION['voter_id'])) {
    header("Location: vote.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "voting_system");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['student_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM voters WHERE student_id = '$id' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['voter_id'] = $row['id'];
        header("Location: vote.php");
        exit();
    } else {
        $error = "Invalid id or password";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Union Voting System - Voter Login</title>
    <link rel="stylesheet" href="voter_login.css">
</head>

<body>
    <div class="v_login">
        <div class="v_hawassa">
            <div class="v_top">
                <img class="v_logo" src="images/logo.png">
                <h2 class="v_inner">Hawassa University</h2>
            </div>
            <div class="v_home">
                <p><a href="logout.php">Home</a></p>
            </div>
        </div>
        <div class="voter_inner">
            <div>
            <img  src="images/phonevote.png">
            </div>
            <div class="v_form">
                <h1>Voter Login</h1>
                <p>Login to see real time voting results</p>
                <form action="" method="POST">
                    <input type="text" name="student_id" placeholder="Student ID" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button class="v_btn" type="submit">Login</button>
                </form>
                <?php if (isset($error)) {
                    echo $error;
                } ?>
                <p>not registered? <a class="v_register" href="voter_registration.php">register</a></p>
            </div>
        </div>
    </div>
</body>

</html>