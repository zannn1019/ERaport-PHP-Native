<?php
include '../../../koneksi.php';
session_start();
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
$id_jurusan = $_GET['jurusan'] ?? null;
$id_kelas = $_GET['kelas'] ?? null;
$id_semester = $_GET['semester'] ?? null;
$id_guru = $_SESSION['id_guru'] ?? null;
$no = 1;
if ($id_kelas == null && $id_semester == null) {
    if ($id_guru == null) {
        $query_page = "SELECT *,siswa.nama as nama_siswa FROM nilai INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan ORDER BY siswa.nis";
    } else {
        $query_page = "SELECT *,siswa.nama as nama_siswa FROM nilai INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE nilai.id_guru = '$id_guru' ORDER BY nilai.id_nilai DESC";
    }
} else {
    $query_page = "SELECT *,siswa.nama as nama_siswa FROM nilai INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE nilai.id_semester = '$id_semester' AND siswa.id_kelas = '$id_kelas' ORDER BY nilai.id_nilai DESC ";
}
$result_page = mysqli_query($koneksi, $query_page);
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Nilai.xls");
header("Pragma: no-cache");
header("Expires:0");
?>

<body>
    <table border='1'>
        <tr>
            <td colspan="13" style="text-align: center;">Data Nilai Akademik Siswa</td>
        </tr>
        <tr style='height:30px;vertical-align:middle;text-align:center;font-size:16px'>
            <th>No</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Harian</th>
            <th>PTS</th>
            <th>PAS</th>
            <th>Nilai Pengetahuan</th>
            <th>Predikat Pengetahuan</th>
            <th>Deskripsi Pengetahuan</th>
            <th>Nilai Keterampilan</th>
            <th>Predikat Keterampilan</th>
            <th>Deskripsi Keterampilan</th>
        </tr>
        <?php
        $no = 1;
        foreach ($result_page as $s) {
        ?>
            <tr>
                <td> <?= $no++ ?></td>
                <td><?= $s['nis'] ?></td>
                <td><?= $s['nama_siswa'] ?></td>
                <td><?= $s['tingkatan'] . ' ' . $s['jurusan'] . ' ' . $s['nama_kelas'] ?></td>
                <td><?= $s['nilai_harian'] ?></td>
                <td><?= $s['nilai_uts'] ?></td>
                <td><?= $s['nilai_uas'] ?></td>
                <td><?= $s['nilai_pengetahuan'] ?></td>
                <td><?= $s['grade_pengetahuan'] ?></td>
                <td><?= $s['desk_pengetahuan'] ?></td>
                <td><?= $s['nilai_keterampilan'] ?></td>
                <td><?= $s['grade_ket'] ?></td>
                <td><?= $s['desk_ket'] ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>