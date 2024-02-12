<?php
session_start();
if (!isset($_SESSION['voter_id'])) {
    header("Location: voter_login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "voting_system");

$student_id = $_SESSION['voter_id'];



// Check if the voter has already voted
$sql = "SELECT * FROM votes WHERE voter_id = '$student_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    header("Location: result.php");
    exit();
}


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(empty($_POST['candidate'])){
            $error="please select your preferred candidate!";
        }else{

            $candidate_id = $_POST['candidate'];
        
            // Check if the voter's ID exists in the voters table
            $sql = "SELECT * FROM voters WHERE id = '$student_id'";
            $result = mysqli_query($conn, $sql);
        
            if (mysqli_num_rows($result) > 0) {
                // Insert vote into database
                $sql = "INSERT INTO votes (voter_id, candidate_id) VALUES ('$student_id', '$candidate_id')";
                mysqli_query($conn, $sql);
        
                header("Location: result.php");
                exit();
            } else {
                echo "Invalid voter ID";
            }
        }
    }
    
    // Retrieve the list of candidates
    $sql = "SELECT * FROM candidates";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) ==0){
        $message= "no candidates are added";
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Union Voting System - Vote</title>
    <link rel="stylesheet" href="vote.css">
</head>

<body>
    <div class="vote_day">
        <div class="vote_h">
            <div class="vote_top">
                <img class="vote_logo" src="images/logo.png">
                <h2 class="vote_inner">Hawassa University</h2>
            </div>
            <div class="vote_home">
                <p><a href="logout.php">Home</a></p>
            </div>
        </div>
        <h1 class="vote_header">Cast Your Vote</h1>
        <div class="vote_body">
            <div class="vote_image">
                <img src="images/voteday.png">
            </div>
            <div class="candidates">
                <h2>Candidates</h2>
                <?php if(isset($message)){echo $message;}?>
                <form action="" method="POST">
                    <div class="grid-container">
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <div class="vote_avatar">
                                <?php $f_name = $row['name'] ?>
                                <div class="v_candidate"><?php echo $f_name[0]; ?></div>
                                <p>Name: <?php echo $row['name']; ?></p>
                                <p>ID: <?php echo $row['id']; ?></p>
                                <input class="radio" type="radio" name="candidate" value="<?php echo $row['id']; ?>" required>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if(isset($error)){echo $error;}?>
                    <button class="vote_btn" type="submit">Vote</button>
                </form>

            </div>
        </div>
    </div>
</body>

</html>