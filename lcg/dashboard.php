<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_level'] != 'LCG') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LCG Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .dashboard-container {
            width: 95%;
            margin: 20px auto;
        }

        .dashboard-header {
            background: #512e5f;
            color: white;
            padding: 20px;
            border-radius: 6px;
        }

        .card-row {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: white;
        }

        table th {
            background: #34495e;
            color: white;
            padding: 12px;
        }

        table td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        .action-btn {
            background: #8e44ad;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
        }

        .logout-btn {
            background: #c0392b;
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">
        <h2>Land Commissioner General Dashboard</h2>
        <p>Final Review & Decision</p>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Cards -->
    <div class="card-row">
        <div class="card">
            <h3>Problems for Final Decision</h3>
            <p>Forwarded by Provincial Offices</p>
        </div>
    </div>

    <!-- Problem List -->
    <h3 style="margin-top:30px;">Land Problem List</h3>

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
        $sql = "SELECT p.problem_id, p.problem_type, p.current_status,
                       l.ds_division, l.village
                FROM problems p
                JOIN land l ON p.land_id = l.land_id
                WHERE p.current_status = 'FORWARDED_TO_LCG'
                ORDER BY p.submitted_date DESC";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            echo "<tr><td colspan='6' style='text-align:center;'>No problems forwarded to LCG</td></tr>";
        }

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo $row['problem_id']; ?></td>
            <td><?php echo $row['ds_division']; ?></td>
            <td><?php echo $row['village']; ?></td>
            <td><?php echo $row['problem_type']; ?></td>
            <td><?php echo $row['current_status']; ?></td>
            <td>
                <a class="action-btn" href="review_problem.php?id=<?php echo $row['problem_id']; ?>">
                    Review
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>
