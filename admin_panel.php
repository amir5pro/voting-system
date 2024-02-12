<!-- admin_panel.php -->
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "voting_system");

// Handle new candidate submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(strlen(trim($_POST['name']))<1){
        $error="please enter valid name";
    }else{
        $name = $_POST['name'];

        // Insert the new candidate into the database
        $sql = "INSERT INTO candidates (name) VALUES ('$name')";
        mysqli_query($conn, $sql);
    
        // Provide feedback to the admin
        $successMessage = "Candidate added successfully!";
    }
    
   
}

// Fetch existing candidates from the database
$sql = "SELECT * FROM candidates";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) ==0){
    $message= "no candidates are added";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Union Voting System - Admin Panel</title>
    <link rel="stylesheet" href="admin_panel.css">
</head>

<body>
    <div class="panel">
        <div class="panel_h">
            <div class="panel_top">
                <img class="panel_logo" src="images/logo.png">
                <h2 class="panel_inner">Hawassa University</h2>
            </div>
            <div class="panel_home">
                <p><a href="logout.php">Home</a></p>
            </div>
        </div>
        <h1 class="panel_header">Admin Panel</h1>
        <div class="panel_body">
            <div class="add_candidate">
                <!-- Add new candidate form -->
                <h2>Add New Candidate</h2>
               
                <form action="" method="POST">
                    <input type="text" name="name" placeholder="Candidate Name" required>
                     <?php if(isset($error)) {echo $error;} ?>
                    <button class="add_btn" type="submit">Add Candidate</button>
                </form>
                <button class="see_btn"><a href="result.php" class="b_link">see results</a></button>
                <?php if (isset($successMessage)) {
                    echo "<p>$successMessage</p>";
                } ?>
            </div>
            <div class="display_candidate">
                <!-- Display existing candidates -->
                <h2>Existing Candidates</h2>
                <?php if(isset($message)){echo $message;}?>
                <div class="avatars">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="avatar">
                            <?php $f_name=$row['name']?>
                            <div class="i_candidate" ><?php echo $f_name[0]; ?></div>
                            <div class="avatar_info">
                                <p>Name: <?php echo $row['name']; ?></p>
                                <p>ID: <?php echo $row['id']; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>