<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'PROVINCE') {
    header("Location: ../login.php");
    exit();
}

$problem_id = $_GET['id'];

// Fetch full problem + land
$sql = "
SELECT p.*, 
       l.ds_division, l.gn_division, l.village, l.plan_number, l.lot_number,
       l.extent, l.extent_words, l.boundaries, l.alienation_method, l.legal_owner
FROM problems p
JOIN land l ON p.land_id = l.land_id
WHERE p.problem_id = $problem_id
";
$problem = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Fetch DS recommendation
$ds_remark = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT remark_text FROM problem_remarks 
     WHERE problem_id=$problem_id AND user_level='DS'")
);

if (isset($_POST['submit'])) {
    $rec = $_POST['recommendation'];

    mysqli_query($conn,
        "INSERT INTO problem_remarks (problem_id, user_level, remark_text)
         VALUES ($problem_id, 'PROVINCE', '$rec')"
    );

    mysqli_query($conn,
        "UPDATE problems SET current_status='PENDING_LCG'
         WHERE problem_id=$problem_id"
    );

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Province Review</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="login-box" style="width:80%;">
<h2>Land Problem Review (Province)</h2>

<h3>Land Details</h3>
<p><b>DS Division:</b> <?= $problem['ds_division'] ?></p>
<p><b>GN Division:</b> <?= $problem['gn_division'] ?></p>
<p><b>Village:</b> <?= $problem['village'] ?></p>
<p><b>Plan / Lot:</b> <?= $problem['plan_number'] ?> / <?= $problem['lot_number'] ?></p>
<p><b>Extent:</b> <?= $problem['extent'] ?> (<?= $problem['extent_words'] ?>)</p>
<p><b>Boundaries:</b><br><?= nl2br($problem['boundaries']) ?></p>
<p><b>Alienation:</b> <?= $problem['alienation_method'] ?></p>
<p><b>Legal Owner:</b> <?= $problem['legal_owner'] ?></p>

<hr>

<h3>Problem Details</h3>
<p><b>Type:</b> <?= $problem['problem_type'] ?></p>
<p><?= nl2br($problem['problem_description']) ?></p>

<hr>

<h3>DS Recommendation</h3>
<p><?= nl2br($ds_remark['remark_text'] ?? 'Not available') ?></p>

<hr>

<form method="post">
<h3>Province Recommendation</h3>
<textarea name="recommendation" required></textarea><br><br>
<button type="submit" name="submit" class="btn-login">
Submit & Forward to LCG
</button>
</form>

</div>
</body>
</html>
