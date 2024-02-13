<?php
include '../../koneksi.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
$no = 1;
$id = $_GET['id'];
$query_page = "SELECT * FROM kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan LEFT JOIN guru ON kelas.wali_kelas = guru.id_guru ORDER BY id_kelas ";
$result_page = mysqli_query($koneksi, $query_page);
$data_kelas = mysqli_fetch_assoc(mysqli_query($koneksi, $query_page));
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Semua Kelas .xls");
header("Pragma: no-cache");
header("Expires:0");
?>

<body>
    <table border='1'>
        <tr rowspan="5" style='height:30px;vertical-align:middle;text-align:center;font-size:16px; font-weight: bold;'>
            <td colspan="4" style="text-align: center;">DATA SEMUA KELAS </td>
        </tr>
        <tr style='height:30px;vertical-align:middle;text-align:center;font-size:16px'>
        <th>No</th>
        <th>Nama Kelas</th>
        <th>Wali Kelas</th>
        <th>Jumlah Siswa</th>
        </tr>
        <?php
        foreach ($result_page as $k) {
            $id_kelas = $k['id_kelas'];
            $jumlah = mysqli_query($koneksi, "SELECT count(id_kelas) as jumlah FROM siswa WHERE id_kelas = '$id_kelas'");
            $j_siswa = mysqli_fetch_array($jumlah);
        ?>
            <tr>
            <td> <?= $no++ ?></td>
            <td><?= $k['tingkatan'] . " " . $k['jurusan'] . " " .   $k['nama_kelas'] ?></td>
            <td><?= $k['nama'] == null ? "-" : $k['nama'] ?></td>
            <td><?= $j_siswa['jumlah'] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>