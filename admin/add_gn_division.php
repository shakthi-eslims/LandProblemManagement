<?php
session_start();
include("../config/db.php");

// Security
if (!isset($_SESSION['user_id']) ||
    $_SESSION['user_level'] != 'ADMIN') {

    header("Location: ../login.php");
    exit();
}

$message = "";

// Save GN Division
if (isset($_POST['save_gn'])) {

    $province_id = $_POST['province_id'];
    $district_id = $_POST['district_id'];
    $ds_id = $_POST['ds_id'];
    $gn_name = $_POST['gn_name'];

    $sql = "INSERT INTO gn_divisions
            (province_id, district_id, ds_id, gn_name)

            VALUES
            ('$province_id',
             '$district_id',
             '$ds_id',
             '$gn_name')";

    if (mysqli_query($conn, $sql)) {

        $message = "GN Division Added Successfully";

    } else {

        $message = "Error Saving GN Division";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Add GN Division</title>

    <style>

        body{
            font-family:Arial;
            background:#f4f6f8;
        }

        .container{
            width:50%;
            margin:30px auto;
            background:white;
            padding:20px;
            border-radius:5px;
        }

        .form-group{
            margin-bottom:15px;
        }

        label{
            display:block;
            margin-bottom:5px;
            font-weight:bold;
        }

        input, select{
            width:100%;
            padding:10px;
        }

        .btn{
            background:#27ae60;
            color:white;
            border:none;
            padding:10px 20px;
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

    <h2>Add GN Division</h2>

    <p class="message">
        <?php echo $message; ?>
    </p>

    <form method="post">

        <!-- Province -->

        <div class="form-group">

            <label>Select Province</label>

            <select name="province_id"
        id="province_id"
        required>

                <option value="">
                    -- Select Province --
                </option>

                <?php

                $provinces = mysqli_query($conn,
                             "SELECT * FROM provinces");

                while($row =
                      mysqli_fetch_assoc($provinces)) {

                ?>

                <option value="<?php
                echo $row['province_id']; ?>">

                    <?php
                    echo $row['province_name'];
                    ?>

                </option>

                <?php } ?>

            </select>

        </div>

        <!-- District -->

        <div class="form-group">

            <label>Select District</label>

            <div class="form-group">

    <label>Select District</label>

    <select name="district_id"
            id="district_id"
            required>

        <option value="">
            -- Select District --
        </option>

    </select>

</div>

        </div>

        <!-- DS Division -->

        <div class="form-group">

            <label>Select DS Division</label>

           <div class="form-group">

    <label>Select DS Division</label>

    <select name="ds_id"
            id="ds_id"
            required>

        <option value="">
            -- Select DS Division --
        </option>

    </select>

</div>

        </div>

        <!-- GN Division -->

        <div class="form-group">

            <label>GN Division Name</label>

            <input type="text"
                   name="gn_name"
                   required>

        </div>

        <!-- Button -->

        <button type="submit"
                name="save_gn"
                class="btn">

            Save GN Division

        </button>

    </form>

    <br><br>

<a href="dashboard.php"

style="background:#34495e;
color:white;
padding:10px 20px;
text-decoration:none;
border-radius:5px;">

← Back to Dashboard

</a>

</div>

<script>

// Province → District

document.getElementById('province_id')
.addEventListener('change', function() {

    var province_id = this.value;

    var district =
    document.getElementById('district_id');

    district.innerHTML =
    '<option>Loading...</option>';

    fetch('get_districts.php?province_id='
    + province_id)

    .then(response => response.text())

    .then(data => {

        district.innerHTML = data;

    });

});


// District → DS Division

document.getElementById('district_id')
.addEventListener('change', function() {

    var district_id = this.value;

    var ds =
    document.getElementById('ds_id');

    ds.innerHTML =
    '<option>Loading...</option>';

    fetch('get_ds.php?district_id='
    + district_id)

    .then(response => response.text())

    .then(data => {

        ds.innerHTML = data;

    });

});

</script>

</body>
</html>