<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

// Security check
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'DS') {
    header("Location: ../login.php");
    exit();
}

$message = "";

// Fetch land list
$land_result = mysqli_query($conn, "SELECT land_id, village, plan_number, lot_number FROM land");

// Save problem
if (isset($_POST['save_problem'])) {

    $land_id = $_POST['land_id'];
    $problem_type = $_POST['problem_type'];
    $problem_description = $_POST['problem_description'];
    $ds_recommendation = $_POST['ds_recommendation'];
    $submitted_by = $_SESSION['user_id'];

    // Insert into problems table
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO problems (land_id, problem_type, problem_description, submitted_by)
         VALUES (?, ?, ?, ?)"
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "issi",
            $land_id,
            $problem_type,
            $problem_description,
            $submitted_by
        );

        if (mysqli_stmt_execute($stmt)) {

            $problem_id = mysqli_insert_id($conn);

            // Insert DS recommendation
            $stmt2 = mysqli_prepare(
                $conn,
                "INSERT INTO problem_remarks (problem_id, user_id, user_level, remark_text)
                 VALUES (?, ?, 'DS', ?)"
            );

            mysqli_stmt_bind_param($stmt2, "iis",
                $problem_id,
                $submitted_by,
                $ds_recommendation
            );

            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            $message = "Land problem submitted successfully";

        } else {
            $message = "Error saving problem";
        }

        mysqli_stmt_close($stmt);
    } else {
        $message = "Prepare failed";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Land Problem</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="login-box" style="width:520px;">
    <h2>Add Land Problem</h2>

    <?php if ($message != "") { ?>
        <p style="color:green;text-align:center;">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php } ?>

    <form method="post" action="">

        <div class="form-group">
            <label>Select Land</label>
            <select name="land_id" required>
                <option value="">-- Select Land --</option>
                <?php while ($row = mysqli_fetch_assoc($land_result)) { ?>
                    <option value="<?php echo $row['land_id']; ?>">
                        <?php
                        echo "Village: ".$row['village'].
                             " | Plan: ".$row['plan_number'].
                             " | Lot: ".$row['lot_number'];
                        ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label>Problem Type</label>
            <input type="text" name="problem_type" required>
        </div>

        <div class="form-group">
            <label>Problem Description</label>
            <textarea name="problem_description" required></textarea>
        </div>

        <div class="form-group">
            <label>DS Recommendation</label>
            <textarea name="ds_recommendation" required></textarea>
        </div>

        <button type="submit" name="save_problem" class="btn-login">
            Submit Problem
        </button>

        <br><br>
        <a href="dashboard.php">← Back to Dashboard</a>

    </form>
</div>

</body>
</html>
