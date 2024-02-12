<?php
    session_start();
    $conn = mysqli_connect("localhost", "root", "", "voting_system");

    // Retrieve the vote count for each candidate
    $sql = "SELECT candidates.id, candidates.name, COUNT(votes.candidate_id) AS vote_count
            FROM candidates
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            GROUP BY candidates.id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) ==0){
        $message= "no candidates are added";
    }
  
    // Calculate total votes
    $total_votes = 0;
    $data = array(); // Array to store candidate data
    $colors = array(); // Array to store colors
    while ($row = mysqli_fetch_assoc($result)) {
        $total_votes += $row['vote_count'];
        $data[] = $row;
        $colors[] = '#' . substr(md5(rand()), 0, 6);
    }
    ?>


<!DOCTYPE html>
<html>

<head>
    <title>Student Union Voting System - Result</title>
    <link rel="stylesheet" href="result.css">
</head>

<body>
    <div class="result">
        <div class="result_h">
            <div class="result_top">
                <img class="result_logo" src="images/logo.png">
                <h2 class="result_inner">Hawassa University</h2>
            </div>
            <div class="result_home">
                <p><a href="logout.php">Home</a></p>
            </div>
        </div>
        <h1 class="r_header">Result</h1>
        <?php if(isset($message)){echo $message;}?>
        <div class="result_body">
            <div class="bar_result">
                <?php

                mysqli_data_seek($result, 0); // Reset result pointer
                while ($row = mysqli_fetch_assoc($result)) {
                    if($total_votes==0){
                        $message="no votes have been conducted";
                    }else{

                        $percentage = ($row['vote_count'] / $total_votes) * 100;
                    }

                ?>
                    <div class="bar_info">
                        <?php if(isset($message)){echo $message;}?>
                        <div class="infos">
                            <p>Name:<?php echo $row['name']; ?></p>
                            <p>Id:<?php echo $row['id']; ?></p>
                            <p> <?php echo $row['vote_count']; ?> votes</p>
                            <?php if(isset($percentage)){$percent = round($percentage, 2);} ?>
                        </div>
                        <div class="bar_parent">
                            <?php if(isset($percent)){?>
                            <div class="bar" style="width:<?php echo $percent; ?>%">
                                <?php echo $percent ?>%
                            </div>
                            <?php }?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div>
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>


    <script>
        const data = <?php echo json_encode($data); ?>;
        const colors = <?php echo json_encode($colors); ?>;
        const total = <?php echo $total_votes; ?>;
        const canvas = document.getElementById('myChart');
        const ctx = canvas.getContext('2d');

        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY);

        let startAngle = 0;
        for (let i = 0; i < data.length; i++) {
            const percentage = ((data[i].vote_count / total) * 100).toFixed(2);
            const endAngle = startAngle + (2 * Math.PI * data[i].vote_count) / total;

            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, startAngle, endAngle);
            ctx.fillStyle = colors[i];
            ctx.fill();

            // Display candidate name in the pie chart
            const angle = startAngle + (endAngle - startAngle) / 2;
            const textX = centerX + Math.cos(angle) * (radius / 2);
            const textY = centerY + Math.sin(angle) * (radius / 2);
            ctx.font = '14px Arial';
            ctx.fillStyle = '#000';
            ctx.textAlign = 'center';
            ctx.fillText(data[i].name, textX, textY);

            startAngle = endAngle;
        }
    </script>

</body>

</html>