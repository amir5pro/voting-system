<?php
session_start();
if (isset($_SESSION['voter_id'])) {
    header("Location: vote.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "voting_system");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(strlen(trim($_POST['name']))<1 || strlen(trim($_POST['student_id']))<1 ){
        $error="please enter a valid character ";
    }else{
        $name = $_POST['name'];
        $student_id = $_POST['student_id'];
        $password = $_POST['password'];
    
        // Check if the student ID is valid (6 digits long)
        if (strlen($student_id) != 6) {
            $error = "Student ID must be 6 digits long.";
        } else {
            // Check if the student ID is unique
            $sql = "SELECT * FROM voters WHERE student_id = '$student_id'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $error = "Student ID already exists.";
            } else {
                $sql = "INSERT INTO voters (name, student_id, password) VALUES ('$name', '$student_id', '$password')";
                mysqli_query($conn, $sql);
    
                $_SESSION['voter_id'] = mysqli_insert_id($conn);
    
                header("Location: vote.php");
                exit();
            }
        }
    }
  
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Union Voting System - Voter Registration</title>
    <link rel="stylesheet" href="voterRegister.css">
</head>

<body>
    <div class="register">
        <div class="hawassa">
            <div class="h_top">
                <img class="h_logo" src="images/logo.png">
                <h2 class="h_inner">Hawassa University</h2>
            </div>
            <div class="home">
                <p><a href="logout.php">Home</a></p>
            </div>
        </div>
        <div class="r_inner">
            <div>
                <img class="image" src="images/handvote.png">
            </div>
            <div class="form">
                <h1>Welcome!</h1>
                <p>Register as a voter on the student union voting platform to vote for your preferred candidate</p>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="text" name="student_id" placeholder="Student ID" required>
                   
                    <input type="password" name="password" placeholder="Password" required>
                    <button class="btn" type="submit">Register</button>
                </form>
                <?php if (isset($error)) {
                        echo $error;
                    } ?>
                <p>Already registered? <a class="login" href="voter_login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>

</html>