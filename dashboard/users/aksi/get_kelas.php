<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (isset($_POST['id_jurusan'])) {
    $id_jurusan = $_POST['id_jurusan'];
    $id_kelas = $_POST['id_kelas'];
    $query = "SELECT * FROM kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE kelas.id_jurusan = '$id_jurusan'";
    $result = mysqli_query($koneksi, $query);

    $kelas = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (count($kelas) > 0) {
        foreach ($kelas as $row) { ?>
            <option value="<?= $row['id_kelas'] ?>" <?= $id_kelas == $row['id_kelas'] ? 'selected' : '' ?> name="kelas"> <?= $row['tingkatan'] . ' ' . $row['jurusan'] . ' ' . $row['nama_kelas'] ?></option>;
<?php }
    } else {
        echo "<option value=''>Pilih Kelas</option>";
    }
} else {
    header("location:./");
}
?>