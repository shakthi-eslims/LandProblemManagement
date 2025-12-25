<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

// security check
if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'DS') {
    header("Location: ../login.php");
    exit();
}

$message = "";


echo "<pre>";
print_r($_POST);
echo "</pre>";


if (isset($_POST['save_land'])) {

    $ds_division = $_POST['ds_division'];
    $gn_division = $_POST['gn_division'];
    $village = $_POST['village'];
    $plan_number = $_POST['plan_number'];
    $lot_number = $_POST['lot_number'];
    $extent = $_POST['extent'];
    $extent_words = $_POST['extent_words'];
    $boundaries = $_POST['boundaries'];
    $alienation_method = $_POST['alienation_method'];
    $legal_owner = $_POST['legal_owner'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO land
        (ds_division, gn_division, village, plan_number, lot_number, extent, extent_words, boundaries, alienation_method, legal_owner)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssss",
            $ds_division,
            $gn_division,
            $village,
            $plan_number,
            $lot_number,
            $extent,
            $extent_words,
            $boundaries,
            $alienation_method,
            $legal_owner
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Land details saved successfully";
        } else {
            $message = "Execute error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        $message = "Prepare error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Land Details</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="login-box">
    <h2>Add Land Details</h2>

    <?php if ($message != "") { ?>
        <p style="color:green; text-align:center;">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php } ?>

    <form method="post" action="">


        <div class="form-group">
            <label>DS Division</label>
            <input type="text" name="ds_division" required>
        </div>

        <div class="form-group">
            <label>GN Division</label>
            <input type="text" name="gn_division" required>
        </div>

        <div class="form-group">
            <label>Village</label>
            <input type="text" name="village" required>
        </div>

        <div class="form-group">
            <label>Plan Number</label>
            <input type="text" name="plan_number">
        </div>

        <div class="form-group">
            <label>Lot Number</label>
            <input type="text" name="lot_number">
        </div>

        <div class="form-group">
            <label>Extent</label>
            <input type="text" name="extent">
        </div>

        <div class="form-group">
            <label>Extent (in words)</label>
            <input type="text" name="extent_words">
        </div>

        <div class="form-group">
            <label>Boundaries</label>
            <textarea name="boundaries"></textarea>
        </div>

        <div class="form-group">
            <label>Alienation Method</label>
            <input type="text" name="alienation_method">
        </div>

        <div class="form-group">
            <label>Legal Owner</label>
            <input type="text" name="legal_owner">
        </div>

<button type="submit" name="save_land" class="btn-login">
    Save Land
</button>


        <br><br>
        <a href="dashboard.php">← Back to Dashboard</a>

    </form>
</div>

</body>
</html>
