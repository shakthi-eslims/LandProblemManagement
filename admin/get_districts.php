<?php
include("../config/db.php");

$province_id = $_GET['province_id'];

$sql = "SELECT * FROM districts
        WHERE province_id='$province_id'";

$result = mysqli_query($conn, $sql);

echo '<option value="">
      -- Choose --
      </option>';

while($row = mysqli_fetch_assoc($result)) {

    echo '<option value="' .
    $row['district_id'] . '">' .

    $row['district_name'] .

    '</option>';
}
?>