<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

// Security Check
if (!isset($_SESSION['user_id']) ||
    $_SESSION['user_level'] != 'DS') {

    header("Location: ../login.php");
    exit();
}

$message = "";

// Get Logged DS User

$user_id = $_SESSION['user_id'];

$user_query = mysqli_query(

$conn,

"SELECT ds_id
 FROM users
 WHERE id='$user_id'"

);

$user_data =
mysqli_fetch_assoc($user_query);

$ds_id =
$user_data['ds_id'];

// Fetch Only DS Lands

$land_result = mysqli_query(

$conn,

"SELECT

land_id,
village,
plan_number,
lot_number

FROM land

WHERE ds_id='$ds_id'"

);


// Save Problem
if (isset($_POST['save_problem'])) {

    $land_id = $_POST['land_id'];

    $problem_type =
    $_POST['problem_type'];

    $problem_description =
    $_POST['problem_description'];

    $ds_recommendation =
    $_POST['ds_recommendation'];

    $submitted_by =
    $_SESSION['user_id'];

    // Upload Folder

$upload_dir = "../uploads/";


// Document 1

$doc1 = "";

if(!empty($_FILES['document_1']['name'])) {

    $doc1 =
    time() . "_1_" .

    $_FILES['document_1']['name'];

    move_uploaded_file(

    $_FILES['document_1']['tmp_name'],

    $upload_dir . $doc1
    );
}


// Document 2

$doc2 = "";

if(!empty($_FILES['document_2']['name'])) {

    $doc2 =
    time() . "_2_" .

    $_FILES['document_2']['name'];

    move_uploaded_file(

    $_FILES['document_2']['tmp_name'],

    $upload_dir . $doc2
    );
}


// Document 3

$doc3 = "";

if(!empty($_FILES['document_3']['name'])) {

    $doc3 =
    time() . "_3_" .

    $_FILES['document_3']['name'];

    move_uploaded_file(

    $_FILES['document_3']['tmp_name'],

    $upload_dir . $doc3
    );
}


    // Get Land Area Details

    $land_query = mysqli_query(

    $conn,

    "SELECT

     province_id,
     district_id,
     ds_id

     FROM land

     WHERE land_id='$land_id'"

    );

    $land_data =
    mysqli_fetch_assoc($land_query);

    $province_id =
    $land_data['province_id'];

    $district_id =
    $land_data['district_id'];

    $ds_id =
    $land_data['ds_id'];


    // Insert Problem

    $stmt = mysqli_prepare(

        $conn,

"INSERT INTO problems

(

land_id,
province_id,
district_id,
ds_id,

problem_type,
problem_description,
submitted_by,

document_1,
document_2,
document_3,

current_status

)

VALUES

(

?, ?, ?, ?,
?, ?, ?,
?, ?, ?,

'PENDING_PLC')"
    );


    if ($stmt) {

       mysqli_stmt_bind_param(

$stmt,

"iiiississs",

$land_id,
$province_id,
$district_id,
$ds_id,

$problem_type,
$problem_description,
$submitted_by,

$doc1,
$doc2,
$doc3
);


        if (mysqli_stmt_execute($stmt)) {

            $problem_id =
            mysqli_insert_id($conn);


            // Save DS Recommendation

            $stmt2 = mysqli_prepare(

                $conn,

                "INSERT INTO problem_remarks

                (

                problem_id,
                user_id,
                user_level,
                remark_text

                )

                VALUES

                (?, ?, 'DS', ?)"
            );


            mysqli_stmt_bind_param(

                $stmt2,

                "iis",

                $problem_id,
                $submitted_by,
                $ds_recommendation
            );


            mysqli_stmt_execute($stmt2);

            mysqli_stmt_close($stmt2);

            $message =
            "Land problem submitted successfully";

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

    <title>Add Land Problem</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

    <style>

        body{
            font-family:Arial;
            background:#f4f6f8;
        }

        .container{
            width:60%;
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
        select,
        textarea{
            width:100%;
            padding:10px;
        }

        .btn{
            background:#27ae60;
            color:white;
            border:none;
            padding:10px 20px;
            border-radius:5px;
            cursor:pointer;
        }

        .message{
            color:green;
            margin-bottom:15px;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Add Land Problem</h2>

    <p class="message">
        <?php echo $message; ?>
    </p>

    <form method="post"
      enctype="multipart/form-data">

        <!-- Land -->

        <div class="form-group">

            <label>Select Land</label>

            <select name="land_id" required>

                <option value="">
                    -- Select Land --
                </option>

                <?php
                while($row =
                mysqli_fetch_assoc($land_result)) {
                ?>

                <option value="<?php
                echo $row['land_id']; ?>">

                    <?php

                    echo

                    "Village : " .
                    $row['village'] .

                    " | Plan : " .
                    $row['plan_number'] .

                    " | Lot : " .
                    $row['lot_number'];

                    ?>

                </option>

                <?php } ?>

            </select>

        </div>


        <!-- Problem Type -->

        <div class="form-group">

            <label>Problem Type</label>

            <input type="text"
                   name="problem_type"
                   required>

        </div>


        <!-- Description -->

        <div class="form-group">

            <label>Problem Description</label>

            <textarea name="problem_description"
                      required></textarea>

        </div>


        <!-- DS Recommendation -->

        <!-- Document 1 -->

<div class="form-group">

<label>
Upload PDF Document 1
(Max 5MB)
</label>

<input type="file"
name="document_1"
accept=".pdf">

</div>


<!-- Document 2 -->

<div class="form-group">

<label>
Upload PDF Document 2
(Max 5MB)
</label>

<input type="file"
name="document_2"
accept=".pdf">

</div>


<!-- Document 3 -->

<div class="form-group">

<label>
Upload PDF Document 3
(Max 5MB)
</label>

<input type="file"
name="document_3"
accept=".pdf">

</div>

        <div class="form-group">

            <label>DS Recommendation</label>

            <textarea name="ds_recommendation"
                      required></textarea>

        </div>


        <!-- Button -->

        <button type="submit"
                name="save_problem"
                class="btn">

            Submit Problem

        </button>

        <br><br>

        <a href="dashboard.php">

            ← Back to Dashboard

        </a>

    </form>

</div>

</body>
</html>