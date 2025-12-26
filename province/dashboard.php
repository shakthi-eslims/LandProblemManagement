<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'PROVINCE') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Provincial Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .dashboard-container { width:95%; margin:20px auto; }
        .header { background:#1b4f72; color:#fff; padding:20px; border-radius:6px; }
        .header a { float:right; background:#c0392b; padding:8px 15px; color:#fff; text-decoration:none; border-radius:4px; }

        table { width:100%; border-collapse:collapse; margin-top:25px; background:#fff; }
        th, td { padding:10px; border:1px solid #ccc; }
        th { background:#f2f4f7; }

        .btn { padding:6px 10px; text-decoration:none; border-radius:4px; color:white; }
        .btn-review { background:#27ae60; }
        .status-pending { color:#d35400; font-weight:bold; }
        .status-rec { color:#2980b9; font-weight:bold; }
    </style>
</head>

<body>

<div class="dashboard-container">

    <div class="header">
        <h2>Provincial Office Dashboard</h2>
        <p>Review & Recommend Land Problems</p>
        <a href="../logout.php">Logout</a>
    </div>

    <h3 style="margin-top:25px;">Land Problem List</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>DS Division</th>
            <th>Village</th>
            <th>Problem Type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        $sql = "
        SELECT 
            p.problem_id,
            p.problem_type,
            p.current_status,
            l.ds_division,
            l.village
        FROM problems p
        JOIN land l ON p.land_id = l.land_id
        WHERE p.current_status IN ('PENDING','PROVINCE_RECOMMENDED')
        ORDER BY p.submitted_date DESC
        ";

        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo $row['problem_id']; ?></td>
            <td><?php echo $row['ds_division']; ?></td>
            <td><?php echo $row['village']; ?></td>
            <td><?php echo $row['problem_type']; ?></td>
            <td>
                <?php
                if ($row['current_status'] == 'PENDING') {
                    echo "<span class='status-pending'>Pending</span>";
                } else {
                    echo "<span class='status-rec'>Recommended</span>";
                }
                ?>
            </td>
            <td>
                <a class="btn btn-review"
                   href="review_problem.php?id=<?php echo $row['problem_id']; ?>">
                   Review
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>
