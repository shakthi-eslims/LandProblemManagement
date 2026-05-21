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

// Create User
if (isset($_POST['create_user'])) {

    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_level = $_POST['user_level'];

    $province_id = $_POST['province_id'];
    $district_id = $_POST['district_id'];
    $ds_id = $_POST['ds_id'];
    $gn_id = $_POST['gn_id'];

    $sql = "INSERT INTO users

    (
        full_name,
        username,
        password,
        user_level,
        province_id,
        district_id,
        ds_id,
        gn_id
    )

    VALUES

    (
        '$full_name',
        '$username',
        '$password',
        '$user_level',
        '$province_id',
        '$district_id',
        '$ds_id',
        '$gn_id'
    )";

    if (mysqli_query($conn, $sql)) {

        $message = "User Created Successfully";

    } else {

        $message = "Error Creating User";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Admin Dashboard</title>

    <style>

        body{
            font-family:Arial;
            background:#f4f6f8;
        }

        .container{
            width:90%;
            margin:30px auto;
        }

        .header{
            background:#1b4f72;
            color:white;
            padding:20px;
            border-radius:5px;
        }

        .card{
            background:white;
            padding:20px;
            margin-top:20px;
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

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table th{
            background:#34495e;
            color:white;
            padding:10px;
        }

        table td{
            border:1px solid #ccc;
            padding:10px;
        }

        .message{
            color:green;
            margin-bottom:15px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h2>Admin Dashboard</h2>

        <a href="../logout.php"
           style="color:white;">

           Logout

        </a>

    </div>

<!-- Management Cards -->

<div class="card">

    <h3>System Management</h3>

    <div style="display:flex;
                gap:20px;
                margin-top:20px;
                flex-wrap:wrap;">

        <!-- Add District -->

        <div style="background:#3498db;
                    color:white;
                    padding:20px;
                    width:220px;
                    border-radius:8px;
                    text-align:center;">

            <h3>🏙 District</h3>

            <p>
                Add Districts
            </p>

            <a href="add_district.php"

               style="background:white;
                      color:#3498db;
                      padding:8px 15px;
                      text-decoration:none;
                      border-radius:5px;">

               Open

            </a>

        </div>

        <!-- Add DS Division -->

        <div style="background:#27ae60;
                    color:white;
                    padding:20px;
                    width:220px;
                    border-radius:8px;
                    text-align:center;">

            <h3>🏢 DS Division</h3>

            <p>
                Add DS Divisions
            </p>

            <a href="add_ds_division.php"

               style="background:white;
                      color:#27ae60;
                      padding:8px 15px;
                      text-decoration:none;
                      border-radius:5px;">

               Open

            </a>

        </div>

        <!-- Add GN Division -->

        <div style="background:#8e44ad;
                    color:white;
                    padding:20px;
                    width:220px;
                    border-radius:8px;
                    text-align:center;">

            <h3>📍 GN Division</h3>

            <p>
                Add GN Divisions
            </p>

            <a href="add_gn_division.php"

               style="background:white;
                      color:#8e44ad;
                      padding:8px 15px;
                      text-decoration:none;
                      border-radius:5px;">

               Open

            </a>

        </div>

    </div>

</div>


    <!-- User Form -->

    <div class="card">

        <h3>Create User</h3>

        <p class="message">
            <?php echo $message; ?>
        </p>

        <form method="post">

            <!-- Full Name -->

            <div class="form-group">

                <label>Full Name</label>

                <input type="text"
                       name="full_name"
                       required>

            </div>

            <!-- Username -->

            <div class="form-group">

                <label>Username</label>

                <input type="text"
                       name="username"
                       required>

            </div>

            <!-- Password -->

            <div class="form-group">

                <label>Password</label>

                <input type="password"
                       name="password"
                       required>

            </div>

            <!-- User Level -->

            <div class="form-group">

                <label>User Level</label>

                <select name="user_level" required>

                    <option value="">
                        Select Role
                    </option>

                    <option value="DS">DS</option>

                    <option value="PROVINCE">
                        PROVINCE
                    </option>

                    <option value="LCG">
                        LCG
                    </option>

                    <option value="ADMIN">
                        ADMIN
                    </option>

                </select>

            </div>

            <!-- Province -->

            <div class="form-group">

                <label>Province</label>

                <select name="province_id"
        id="province_id">

                    <option value="">
                        Select Province
                    </option>

                    <?php

                    $provinces =
                    mysqli_query($conn,
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

                <label>District</label>

                <div class="form-group">

    <label>District</label>

    <select name="district_id"
            id="district_id">

        <option value="">
            -- Choose --
        </option>

    </select>

</div>

            </div>

            <!-- DS Division -->

            <div class="form-group">

                <label>DS Division</label>

                <div class="form-group">

    <label>DS Division</label>

    <select name="ds_id"
            id="ds_id">

        <option value="">
            -- Choose --
        </option>

    </select>

</div>

            </div>

           
            <!-- Button -->

            <button type="submit"
                    name="create_user"
                    class="btn">

                Create User

            </button>

        </form>

    </div>

    <!-- User List -->

    <div class="card">

        <h3>System Users</h3>

        <table>

            <tr>

                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>User Level</th>

            </tr>

            <?php

            $users =
            mysqli_query($conn,
            "SELECT * FROM users");

            while($row =
            mysqli_fetch_assoc($users)) {

            ?>

            <tr>

                <td>
                    <?php echo $row['id']; ?>
                </td>

                <td>
                    <?php echo $row['full_name']; ?>
                </td>

                <td>
                    <?php echo $row['username']; ?>
                </td>

                <td>
                    <?php echo $row['user_level']; ?>
                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

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