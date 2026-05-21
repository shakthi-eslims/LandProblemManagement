<?php

session_start();
include("../config/db.php");

// Security Check
if (!isset($_SESSION['user_id']) ||
    $_SESSION['user_level'] != 'LCG') {

    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Get User Details

$user_query = mysqli_query(

$conn,

"SELECT full_name
 FROM users
 WHERE id='$user_id'"

);

$user_data =
mysqli_fetch_assoc($user_query);

$full_name =
$user_data['full_name'];


// Pending LCG

$pending_query = mysqli_query(

$conn,

"SELECT COUNT(*) AS total

FROM problems

WHERE current_status='PENDING_LCG'"

);

$pending_data =
mysqli_fetch_assoc($pending_query);

$pending_total =
$pending_data['total'];


// Decided Cases

$decided_query = mysqli_query(

$conn,

"SELECT COUNT(*) AS total

FROM problems

WHERE current_status='DECIDED'"

);

$decided_data =
mysqli_fetch_assoc($decided_query);

$decided_total =
$decided_data['total'];

?>

<!DOCTYPE html>
<html>
<head>

<title>
LCG Dashboard
</title>

<link rel="stylesheet"
href="../assets/css/style.css">

<style>

body{
    font-family:Arial;
    background:#f4f6f8;
    margin:0;
}

.dashboard-container{
    width:92%;
    margin:20px auto;
}

.dashboard-header{
    background:#1b4f72;
    color:white;
    padding:25px;
    border-radius:8px;
}

.user-box{
    margin-top:15px;
    background:rgba(255,255,255,0.15);
    padding:12px;
    border-radius:5px;
}

.summary-row{
    display:flex;
    gap:20px;
    margin-top:25px;
    flex-wrap:wrap;
}

.summary-box{
    flex:1;
    min-width:220px;
    color:white;
    padding:20px;
    border-radius:8px;
}

.summary-box h2{
    margin:0;
    font-size:32px;
}

.blue{
    background:#3498db;
}

.green{
    background:#27ae60;
}

.card-row{
    display:flex;
    gap:20px;
    margin-top:30px;
    flex-wrap:wrap;
}

.card{
    flex:1;
    min-width:240px;
    background:white;
    padding:25px;
    border-radius:8px;
    box-shadow:0 0 8px rgba(0,0,0,0.1);
    text-align:center;
}

.card h3{
    color:#2c3e50;
}

.btn{
    display:inline-block;
    margin-top:15px;
    padding:10px 15px;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

.btn-orange{
    background:#e67e22;
}

.btn-red{
    background:#c0392b;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    margin-top:20px;
}

th, td{
    border:1px solid #ccc;
    padding:10px;
}

th{
    background:#34495e;
    color:white;
}

.review-btn{
    background:#27ae60;
    color:white;
    padding:6px 10px;
    text-decoration:none;
    border-radius:4px;
}

</style>

</head>

<body>

<div class="dashboard-container">

<!-- Header -->

<div class="dashboard-header">

<h2>
Land Commissioner General Dashboard
</h2>

<p>
National Land Problem Management System
</p>

<div class="user-box">

<strong>User:</strong>
<?php echo $full_name; ?>

<br><br>

<strong>Authority Level:</strong>
National Level (All Provinces)

</div>

</div>


<!-- Summary -->

<div class="summary-row">

<div class="summary-box blue">

<h2>
<?php echo $pending_total; ?>
</h2>

<p>
Pending Final Decisions
</p>

</div>

<div class="summary-box green">

<h2>
<?php echo $decided_total; ?>
</h2>

<p>
Completed Decisions
</p>

</div>

</div>


<!-- Cards -->

<div class="card-row">

<!-- Decisions -->

<div class="card">

<h3>
📜 Decisions & History
</h3>

<p>
View all province forwarded
problems and decisions
</p>

<a href="decision_list.php"
class="btn btn-orange">

View Decisions

</a>

</div>


<!-- Logout -->

<div class="card">

<h3>
🚪 Logout
</h3>

<p>
Exit from the system safely
</p>

<a href="../logout.php"
class="btn btn-red">

Logout

</a>

</div>

</div>


<!-- Problems Table -->

<div class="card"
style="margin-top:30px; text-align:left;">

<h3>
Province Forwarded Problems
</h3>

<table>

<tr>

<th>ID</th>

<th>Province</th>

<th>DS Division</th>

<th>Village</th>

<th>Problem Type</th>

<th>Status</th>

<th>Action</th>

</tr>

<?php

$sql = "SELECT

p.problem_id,
p.problem_type,
p.current_status,

l.ds_division,
l.village,

pr.province_name

FROM problems p

JOIN land l
ON p.land_id = l.land_id

JOIN provinces pr
ON p.province_id = pr.province_id

WHERE p.current_status='PENDING_LCG'

ORDER BY p.problem_id DESC";

$result =
mysqli_query($conn, $sql);

while($row =
mysqli_fetch_assoc($result)) {

?>

<tr>

<td>
<?php echo $row['problem_id']; ?>
</td>

<td>
<?php echo $row['province_name']; ?>
</td>

<td>
<?php echo $row['ds_division']; ?>
</td>

<td>
<?php echo $row['village']; ?>
</td>

<td>
<?php echo $row['problem_type']; ?>
</td>

<td>
<?php echo $row['current_status']; ?>
</td>

<td>

<a class="review-btn"

href="review_problem.php?id=
<?php echo $row['problem_id']; ?>">

Review

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>