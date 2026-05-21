<?php

session_start();
include("../config/db.php");

// Security check
if (!isset($_SESSION['user_id']) ||
    $_SESSION['user_level'] != 'DS') {

    header("Location: ../login.php");
    exit();
}

$message = "";

// Logged User
$user_id = $_SESSION['user_id'];

// Get User Area
$user_query = mysqli_query($conn,

"SELECT

 province_id,
 district_id,
 ds_id

 FROM users

 WHERE id='$user_id'");

$user_data =
mysqli_fetch_assoc($user_query);

$province_id =
$user_data['province_id'];

$district_id =
$user_data['district_id'];

$ds_id =
$user_data['ds_id'];


$ds_query = mysqli_query(

$conn,

"SELECT ds_name
 FROM ds_divisions
 WHERE ds_id='$ds_id'"

);

$ds_data =
mysqli_fetch_assoc($ds_query);

$ds_name =
$ds_data['ds_name'];


// Save Land
if (isset($_POST['save_land'])) {

    $ds_division = $_POST['ds_division'];

    $gn_division = $_POST['gn_division'];

    $village = $_POST['village'];

    $plan_number = $_POST['plan_number'];

    $lot_number = $_POST['lot_number'];

    $extent = $_POST['extent'];

    $extent_words = $_POST['extent_words'];

    $boundaries = $_POST['boundaries'];

    $alienation_method =
    $_POST['alienation_method'];

    $legal_owner =
    $_POST['legal_owner'];


    // Insert Query

    $stmt = mysqli_prepare(

        $conn,

        "INSERT INTO land

        (

            province_id,
            district_id,
            ds_id,

            ds_division,
            gn_division,
            village,
            plan_number,
            lot_number,
            extent,
            extent_words,
            boundaries,
            alienation_method,
            legal_owner

        )

        VALUES

        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );


    if ($stmt) {

        mysqli_stmt_bind_param(

            $stmt,

            "iiissssssssss",

            $province_id,
            $district_id,
            $ds_id,

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

            $message =
            "Land details saved successfully";

        } else {

            $message =
            "Execute Error : " .
            mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);

    } else {

        $message =
        "Prepare Error : " .
        mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Add Land Details</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

    <style>

        body{
            font-family:Arial;
            background:#f4f6f8;
        }

        .container{
            width:70%;
            margin:30px auto;
            background:white;
            padding:25px;
            border-radius:8px;
        }

        .form-group{
            margin-bottom:15px;
        }

        label{
            display:block;
            margin-bottom:5px;
            font-weight:bold;
        }

        input,
        textarea{
            width:100%;
            padding:10px;
        }

        .btn{
            background:#27ae60;
            color:white;
            border:none;
            padding:10px 20px;
            cursor:pointer;
            border-radius:5px;
        }

        .message{
            color:green;
            margin-bottom:15px;
        }

        .area-box{
            background:#ecf0f1;
            padding:15px;
            margin-bottom:20px;
            border-radius:5px;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Add Land Details</h2>

    <!-- User Area -->

    <div class="area-box">

        <strong>
            Your DS Area Land Registration
        </strong>

    </div>

    <!-- Message -->

    <p class="message">
        <?php echo $message; ?>
    </p>

    <!-- Form -->

    <form method="post">

        <!-- DS Division -->

<div class="form-group">

<label>DS Division</label>

<input type="text"

value="<?php echo $ds_name; ?>"

readonly>

</div>

<input type="hidden"

name="ds_division"

value="<?php echo $ds_name; ?>">

        <!-- GN Division -->

        <div class="form-group">

            <label>GN Division</label>

            <input type="text"
                   name="gn_division"
                   required>

        </div>

        <!-- Village -->

        <div class="form-group">

            <label>Village</label>

            <input type="text"
                   name="village"
                   required>

        </div>

        <!-- Plan Number -->

        <div class="form-group">

            <label>Plan Number</label>

            <input type="text"
                   name="plan_number">

        </div>

        <!-- Lot Number -->

        <div class="form-group">

            <label>Lot Number</label>

            <input type="text"
                   name="lot_number">

        </div>

        <!-- Extent -->

        <div class="form-group">

            <label>Extent</label>

            <input type="text"
                   name="extent">

        </div>

        <!-- Extent Words -->

        <div class="form-group">

            <label>Extent (In Words)</label>

            <input type="text"
                   name="extent_words">

        </div>

        <!-- Boundaries -->

        <div class="form-group">

            <label>Boundaries</label>

            <textarea name="boundaries"></textarea>

        </div>

        <!-- Alienation -->

        <div class="form-group">

            <label>Alienation Method</label>

            <input type="text"
                   name="alienation_method">

        </div>

        <!-- Owner -->

        <div class="form-group">

            <label>Legal Owner</label>

            <input type="text"
                   name="legal_owner">

        </div>

        <!-- Button -->

        <button type="submit"
                name="save_land"
                class="btn">

            Save Land

        </button>

        <br><br>

        <a href="dashboard.php">

            ← Back to Dashboard

        </a>

    </form>

</div>

</body>
</html>
