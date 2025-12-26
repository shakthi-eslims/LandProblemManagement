<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'LCG') {
    header("Location: ../login.php");
    exit();
}

$problem_id = $_GET['id'];

if (isset($_POST['save_decision'])) {
    $decision = $_POST['decision'];

    // save LCG decision
    $sql1 = "INSERT INTO problem_remarks 
             (problem_id, user_id, user_level, remark_text)
             VALUES 
             ($problem_id, {$_SESSION['user_id']}, 'LCG', '$decision')";

    // update problem status
    $sql2 = "UPDATE problems 
             SET current_status = 'DECIDED' 
             WHERE problem_id = $problem_id";

    mysqli_query($conn, $sql1);
    mysqli_query($conn, $sql2);

    $message = "Final decision saved successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LCG Decision</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="login-box">
    <h2>Final Decision</h2>

    <?php if (!empty($message)) { ?>
        <p style="color:green;"><?php echo $message; ?></p>
    <?php } ?>

    <form method="post">
        <div class="form-group">
            <label>LCG Final Decision</label>
            <textarea name="decision" required></textarea>
        </div>

        <button type="submit" name="save_decision" class="btn-login">
            Save Decision
        </button>

        <br><br>
        <a href="dashboard.php">← Back to Dashboard</a>
    </form>
</div>

</body>
</html>
