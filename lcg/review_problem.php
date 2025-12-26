<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'LCG') {
    header("Location: ../login.php");
    exit();
}

$problem_id = $_GET['id'];

// Get problem + land details
$sql = "SELECT 
            p.problem_id,
            p.problem_type,
            p.problem_description,
            p.current_status,
            l.ds_division,
            l.gn_division,
            l.village,
            l.plan_number,
            l.lot_number,
            l.extent,
            l.extent_words,
            l.boundaries,
            l.alienation_method,
            l.legal_owner
        FROM problems p
        JOIN land l ON p.land_id = l.land_id
        WHERE p.problem_id = $problem_id";

$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

// Get remarks history
$remarks_sql = "SELECT user_level, remark_text, remark_date
                FROM problem_remarks
                WHERE problem_id = $problem_id
                ORDER BY remark_date ASC";
$remarks = mysqli_query($conn, $remarks_sql);

// Save LCG decision
if (isset($_POST['save_decision'])) {
    $decision = $_POST['decision'];

    mysqli_query($conn, "
        INSERT INTO problem_remarks 
        (problem_id, user_id, user_level, remark_text)
        VALUES 
        ($problem_id, {$_SESSION['user_id']}, 'LCG', '$decision')
    ");

    mysqli_query($conn, "
        UPDATE problems 
        SET current_status = 'DECIDED'
        WHERE problem_id = $problem_id
    ");

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LCG Review</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="dashboard-container">

<h2>Land Problem – Full Review (LCG)</h2>

<h3>Land Details</h3>
<ul>
    <li><b>DS Division:</b> <?php echo $data['ds_division']; ?></li>
    <li><b>GN Division:</b> <?php echo $data['gn_division']; ?></li>
    <li><b>Village:</b> <?php echo $data['village']; ?></li>
    <li><b>Plan / Lot:</b> <?php echo $data['plan_number']; ?> / <?php echo $data['lot_number']; ?></li>
    <li><b>Extent:</b> <?php echo $data['extent']; ?> (<?php echo $data['extent_words']; ?>)</li>
    <li><b>Boundaries:</b> <?php echo $data['boundaries']; ?></li>
    <li><b>Alienation Method:</b> <?php echo $data['alienation_method']; ?></li>
    <li><b>Legal Owner:</b> <?php echo $data['legal_owner']; ?></li>
</ul>

<h3>Problem Details</h3>
<p><b>Type:</b> <?php echo $data['problem_type']; ?></p>
<p><?php echo $data['problem_description']; ?></p>

<h3>Recommendation History</h3>
<?php while ($r = mysqli_fetch_assoc($remarks)) { ?>
    <p>
        <b><?php echo $r['user_level']; ?>:</b>
        <?php echo $r['remark_text']; ?><br>
        <small><?php echo $r['remark_date']; ?></small>
    </p>
<?php } ?>

<h3>LCG Final Decision</h3>

<form method="post">
    <textarea name="decision" required></textarea><br><br>
    <button type="submit" name="save_decision" class="btn-login">
        Submit Final Decision
    </button>
</form>

<br>
<a href="dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>
