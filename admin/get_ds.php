<?php
include("../config/db.php");

$district_id = $_GET['district_id'];

$sql = "SELECT * FROM ds_divisions
        WHERE district_id='$district_id'";

$result = mysqli_query($conn, $sql);

echo '<option value="">
      -- Choose --
      </option>';
      
while($row = mysqli_fetch_assoc($result)) {

    echo '<option value="' .
    $row['ds_id'] . '">' .

    $row['ds_name'] .

    '</option>';
}
?>