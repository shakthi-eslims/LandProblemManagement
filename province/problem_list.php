<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'PROVINCE') {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT p.problem_id, p.problem_type, p.current_status, l.village, l.ds_division
        FROM problems p
        JOIN land l ON p.land_id = l.land_id
        ORDER BY p.problem_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Land Problem List</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="dashboard">
    <h2>Land Problem List</h2>

    <div class="table-box">
        <table>
            <tr>
                <th>ID</th>
                <th>DS Division</th>
                <th>Village</th>
                <th>Problem Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['problem_id']; ?></td>
                <td><?php echo $row['ds_division']; ?></td>
                <td><?php echo $row['village']; ?></td>
                <td><?php echo $row['problem_type']; ?></td>
                <td><?php echo $row['current_status']; ?></td>
                <td>
                    <a href="problem_view.php?id=<?php echo $row['problem_id']; ?>">
                        View
                    </a>
                </td>
            </tr>
            <?php } ?>

        </table>
    </div>

    <br>
    <a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
