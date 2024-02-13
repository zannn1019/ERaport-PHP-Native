<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT * FROM guru WHERE nama LIKE '$search%' OR nip LIKE '$search%' LIMIT 5";
    $result = mysqli_query($koneksi, $query);
    if ($search != '') {
        foreach ($result as $guru) {
            echo "<a href='../detail/?data=guru&nip=" . $guru['nip']. "'><div class='search-items'><b>" . $guru['nama'] . "</b> (" . $guru['nip'] . ") </div></a>";
        }
    }
} else {
    header("location:./");
}