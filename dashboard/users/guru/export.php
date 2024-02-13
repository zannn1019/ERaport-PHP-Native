<?php
include '../../../koneksi.php';

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
$query_page = "SELECT * FROM guru ";
$result_page = mysqli_query($koneksi, $query_page);
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Guru.xls");
header("Pragma: no-cache");
header("Expires:0");
?>

<body>
    <table border='1'>
        <tr style="text-align: center;">
            <td colspan="6" style="text-align: center;">Data Seluruh guru</td>
        </tr>
        <tr style='height:30px;vertical-align:middle;text-align:center;font-size:6px'>
            <th>No</th>
            <th>NIP</th>
            <th colspan="3">Nama</th>
            <th>No. Telp</th>
        </tr>
        <?php
        foreach ($result_page as $g) {
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $g['nip'] ?></td>
                <td colspan="3"><?= $g['nama'] ?></td>
                <td><?= $g['no_telp'] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>