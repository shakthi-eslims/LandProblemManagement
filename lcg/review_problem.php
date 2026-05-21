<?php

session_start();
include("../config/db.php");

// Security Check

if (!isset($_SESSION['user_id']) ||
    $_SESSION['user_level'] != 'LCG') {

    header("Location: ../login.php");
    exit();
}


// Get Problem ID

$problem_id = $_GET['id'];


// Fetch Problem + Land Details

$sql = "

SELECT

p.*,

l.ds_division,
l.gn_division,
l.village,
l.plan_number,
l.lot_number,
l.extent,
l.extent_words,
l.boundaries,
l.alienation_method,
l.legal_owner,

pr.province_name

FROM problems p

JOIN land l
ON p.land_id = l.land_id

JOIN provinces pr
ON p.province_id = pr.province_id

WHERE p.problem_id = '$problem_id'

";

$result =
mysqli_query($conn, $sql);

$problem =
mysqli_fetch_assoc($result);


// Fetch DS Recommendation

$ds_remark = mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT remark_text

FROM problem_remarks

WHERE

problem_id='$problem_id'

AND

user_level='DS'"

)

);


// Fetch Province Recommendation

$province_remark = mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT remark_text

FROM problem_remarks

WHERE

problem_id='$problem_id'

AND

user_level='PROVINCE'"

)

);


// Submit Final Decision

if(isset($_POST['submit'])) {

    $decision =
    mysqli_real_escape_string(

    $conn,

    $_POST['final_decision']

    );


    // Save LCG Final Decision

    mysqli_query(

    $conn,

    "INSERT INTO problem_remarks

    (

    problem_id,
    user_level,
    remark_text

    )

    VALUES

    (

    '$problem_id',
    'LCG',
    '$decision'

    )"

    );


    // Update Status

    mysqli_query(

    $conn,

    "UPDATE problems

    SET current_status='DECIDED'

    WHERE problem_id='$problem_id'"

    );


    header("Location: dashboard.php");

    exit();
}

?>

<!DOCTYPE html>
<html>
<head>

<title>
LCG Final Review
</title>

<link rel="stylesheet"
href="../assets/css/style.css">

<style>

body{
    font-family:Arial;
    background:#f4f6f8;
}

.container{
    width:85%;
    margin:20px auto;
    background:white;
    padding:25px;
    border-radius:8px;
}

h2{
    color:#1b4f72;
}

.section{
    margin-bottom:25px;
}

.section h3{
    background:#1b4f72;
    color:white;
    padding:10px;
    border-radius:5px;
}

p{
    line-height:1.7;
}

textarea{
    width:100%;
    height:150px;
    padding:10px;
}

.btn{
    background:#27ae60;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:5px;
    cursor:pointer;
}

.pdf-link{
    display:block;
    margin-bottom:10px;
    color:#2e86de;
    text-decoration:none;
    font-weight:bold;
}

.back-btn{
    display:inline-block;
    margin-top:20px;
    background:#34495e;
    color:white;
    padding:10px 15px;
    text-decoration:none;
    border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h2>
LCG Final Review & Decision
</h2>


<!-- Land Details -->

<div class="section">

<h3>
Land Details
</h3>

<p>
<b>Province :</b>
<?php echo $problem['province_name']; ?>
</p>

<p>
<b>DS Division :</b>
<?php echo $problem['ds_division']; ?>
</p>

<p>
<b>GN Division :</b>
<?php echo $problem['gn_division']; ?>
</p>

<p>
<b>Village :</b>
<?php echo $problem['village']; ?>
</p>

<p>
<b>Plan Number :</b>
<?php echo $problem['plan_number']; ?>
</p>

<p>
<b>Lot Number :</b>
<?php echo $problem['lot_number']; ?>
</p>

<p>
<b>Extent :</b>

<?php echo $problem['extent']; ?>

(
<?php echo $problem['extent_words']; ?>
)

</p>

<p>
<b>Boundaries :</b><br>

<?php echo nl2br($problem['boundaries']); ?>

</p>

<p>
<b>Alienation Method :</b>

<?php echo $problem['alienation_method']; ?>

</p>

<p>
<b>Legal Owner :</b>

<?php echo $problem['legal_owner']; ?>

</p>

</div>


<!-- Problem Details -->

<div class="section">

<h3>
Problem Details
</h3>

<p>
<b>Problem Type :</b>

<?php echo $problem['problem_type']; ?>

</p>

<p>

<?php

echo nl2br(
$problem['problem_description']
);

?>

</p>

</div>


<!-- DS Recommendation -->

<div class="section">

<h3>
DS Recommendation
</h3>

<p>

<?php

echo nl2br(

$ds_remark['remark_text']
?? 'Not Available'

);

?>

</p>

</div>


<!-- Province Recommendation -->

<div class="section">

<h3>
Province Recommendation
</h3>

<p>

<?php

echo nl2br(

$province_remark['remark_text']
?? 'Not Available'

);

?>

</p>

</div>


<!-- Uploaded PDFs -->

<div class="section">

<h3>
Uploaded PDF Documents
</h3>


<!-- PDF 1 -->

<?php
if($problem['document_1'] != "") {
?>

<a class="pdf-link"

href="../uploads/<?php
echo $problem['document_1']; ?>"

target="_blank">

📄 Preview / Download PDF 1

</a>

<?php } ?>


<!-- PDF 2 -->

<?php
if($problem['document_2'] != "") {
?>

<a class="pdf-link"

href="../uploads/<?php
echo $problem['document_2']; ?>"

target="_blank">

📄 Preview / Download PDF 2

</a>

<?php } ?>


<!-- PDF 3 -->

<?php
if($problem['document_3'] != "") {
?>

<a class="pdf-link"

href="../uploads/<?php
echo $problem['document_3']; ?>"

target="_blank">

📄 Preview / Download PDF 3

</a>

<?php } ?>

</div>


<!-- Final Decision -->

<div class="section">

<h3>
Final LCG Decision
</h3>

<form method="post">

<textarea
name="final_decision"
required></textarea>

<br><br>

<button type="submit"
name="submit"
class="btn">

Finalize Decision

</button>

</form>

</div>


<a href="dashboard.php"
class="back-btn">

← Back to Dashboard

</a>

</div>

</body>
</html>