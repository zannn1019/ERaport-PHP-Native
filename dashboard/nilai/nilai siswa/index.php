<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}

if (!in_array($_SESSION['role'], [3])) {
    header("location:../../../");
    session_destroy();
}
$semester = $_SESSION['semester'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$id_role = $role;
$user = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN role ON siswa.id_role = role.id_role INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE siswa.nama = '$username'");
$sekolah = mysqli_query($koneksi, "SELECT * FROM informasi_sekolah");
$data_sekolah = mysqli_fetch_assoc($sekolah);
$data_user = mysqli_fetch_assoc($user);
$id_siswa = $data_user['id_siswa'];
$id_kelas = $data_user['id_kelas'];
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $title = basename(__DIR__) ?>
    <title><?= ucwords($title) ?></title>
    <link rel="stylesheet" href="../../../asset/CSS/dashboard.css" type="text/css" />
    <link rel="stylesheet" href="../../../asset/FontAwesome/css/all.min.css">
    <link rel="stylesheet" href="../../../asset/boxicons-2.1.4/css/boxicons.css">
</head>

<body>
    <div id="mySidenav" class="sidenav">
        <div class="link">
            <p class="logo"><span>E</span>-Raport</p>
            <a href="../../../dashboard/" class="icon-a "><i class="fa fa-dashboard icons">&nbsp;&nbsp;</i><b class="link_nama">Dashboard</b></a>
            <a class="icon-a dropdown-btn <?= $role != 1 ? 'hidden' : '' ?>" data-target="#user"><i class="fa fa-users icons ">&nbsp;&nbsp;</i><b class="link_nama">Pengguna</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="user">
                <ul>
                    <li><a href="../../users/siswa/" class="d-list">Siswa</a></li>
                    <li><a href="../../users/guru/" class="d-list">Guru</a></li>
                </ul>
            </div>
            <a href="../../mapel/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="fa fa-list icons">&nbsp;&nbsp;</i><b class="link_nama">Mapel</b></a>
            <a href="../../kelas/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="bx bx-chair icons">&nbsp;&nbsp;</i><b class="link_nama">Kelas</b></a>
            <a href="./" class="icon-a active <?= $role != 3 ? 'hidden' : '' ?>"><i class="bx bxs-objects-vertical-bottom icons">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b></a>
            <a class="icon-a dropdown-btn  <?= $role = ($role == 1 || $role == 2) ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons icons">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="./" class="d-list">Akademik</a></li>
                    <li><a href="../kehadiran/" class="d-list">Kehadiran</a></li>
                </ul>
            </div>
            <a href="../../pengaturan/" class="icon-a <?= $role == 1 ? '' : 'hidden' ?>"><i class="fa fa-gear icons">&nbsp;&nbsp;</i><b class="link_nama">Pengaturan</b></a>
        </div>
        <div class="logout">
            <a href="../../../logout.php" onclick="return confirm('Are you sure?')"><i class='bx bx-log-in'></i>&nbsp;&nbsp;<b id="logout-txt">Keluar</b></a>
        </div>
    </div>
    <div class="content">
        <div id="main">
            <div class="head">
                <div class="col-div-6">
                    <span class="nav">Nilai Siswa</span>
                </div>
                <div class="col-div-6">
                    <div class="profile">
                        <label class="switch" for="checkbox">
                            <div class="theme-icon">
                                <i class='bx bx-moon'></i>
                                <i class='bx bx-sun'></i>
                            </div>
                            <input type="checkbox" id="checkbox" />
                            <div class="slider round"></div>
                        </label>
                        <p><?= $data_user['nama'] ?><span><?= $data_user['role'] ?></span></p>
                        <img alt="foto" src="<?= $data_user['foto'] ?>" class="pro-img" />
                    </div>
                </div>
            </div>
            <div class="col-div-8">
                <div class="box-8">
                    <div class="content-box">
                        <div class="tb-tittle">
                            <a href="export.php?id=<?= $data_user['id_siswa'] ?>" class="export-btn" id="export"><i class='bx bx-export'></i></a>
                            <h1>Nilai Siswa</h1>
                            <button style="visibility: hidden;" class="export-btn" id="files"><i class='bx bx-export'></i></button>
                        </div>
                        <br>
                        <hr>
                        <div class="data-nilai">
                            <div class="info-sekolah">
                                <h2>Laporan Hasil Belajar Siswa</h2>
                                <h1><?= $data_sekolah['nama_sekolah'] ?></h1>
                                <p><?= $data_sekolah['alamat_sekolah'] ?></p>
                                <span><b>Kontak Sekolah : </b><?= $data_sekolah['notelp_sekolah'] ?> |
                                    <?= $data_sekolah['email_sekolah'] ?> |
                                    <?= $data_sekolah['website_sekolah'] ?></span>
                            </div>
                            <div class="info-siswa">
                                <p><b>Nama</b> : <?= $data_user['nama'] ?></p>
                                <p><b>NIS</b> : <?= $data_user['nis'] ?></p>
                                <p><b>Kelas</b> : <?= $data_user['tingkatan'] ?> <?= $data_user['jurusan'] ?>
                                    <?= $data_user['nama_kelas'] ?></p>
                                <p><b>Semester</b> : <?= $semester == "1" ? 'Ganjil' : 'Genap' ?></p>
                                <p><b>Tahun Pelajaran</b> : <?= $data_user['tahun_ajaran'] ?></p>
                                <br>
                                <hr>
                            </div>
                            <div class="nilai-siswa">
                                <h2>Pencapaian Kompetensi Peserta Didik</h2>
                                <div class="akademik">
                                    <h4>A. Nilai Akademik</h4>
                                    <table>
                                        <tr>
                                            <th>NO</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Pengetahuan</th>
                                            <th>Predikat</th>
                                            <th>Deskripsi</th>
                                            <th>Keterampilan</th>
                                            <th>Predikat</th>
                                            <th>Deskripsi</th>
                                        </tr>
                                        <?php
                                        // $mapel = mysqli_query($koneksi, "SELECT * FROM mapel WHERE jenis = 'umum'");
                                        $mapel = mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE siswa.id_siswa = '$id_siswa' AND mapel.jenis = 'umum'");
                                        $no = 1;
                                        foreach ($mapel as $mapel) {
                                            $id_mapel = $mapel['id_mapel'];
                                            $nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel  WHERE mapel.id_mapel = '$id_mapel' AND id_siswa = '$id_siswa' AND id_semester = '$semester'"));
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $mapel['mapel'] ?></td>
                                                <td><?= $nilai['nilai_pengetahuan'] ?? '-' ?></td>
                                                <td><?= $nilai['grade_pengetahuan'] ?? '-' ?></td>
                                                <td><?= $nilai['desk_pengetahuan'] ?? '-' ?></td>
                                                <td><?= $nilai['nilai_keterampilan'] ?? '-' ?></td>
                                                <td><?= $nilai['grade_ket'] ?? '-' ?></td>
                                                <td><?= $nilai['desk_ket'] ?? '-' ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                                <div class="akademik">
                                    <h4>B. Nilai Kejuruan</h4>
                                    <table>
                                        <tr>
                                            <th>NO</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Pengetahuan</th>
                                            <th>Predikat</th>
                                            <th>Deskripsi</th>
                                            <th>Keterampilan</th>
                                            <th>Predikat</th>
                                            <th>Deskripsi</th>
                                            <th>Nilai UKK</th>
                                            <th>Deskripsi</th>
                                        </tr>
                                        <?php
                                        $mapel = mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE siswa.id_siswa = '$id_siswa' AND mapel.jenis = 'kejuruan'");
                                        $walas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas INNER JOIN guru ON kelas.wali_kelas = guru.id_guru WHERE id_kelas = '$id_kelas'"));
                                        $no = 1;
                                        foreach ($mapel as $mapel) {
                                            $id_mapel = $mapel['id_mapel'];
                                            $nilai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE mapel.id_mapel = '$id_mapel' AND id_siswa = '$id_siswa' AND id_semester = '$semester'"));
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $mapel['mapel'] ?></td>
                                                <td><?= $nilai['nilai_pengetahuan'] ?? '-' ?></td>
                                                <td><?= $nilai['grade_pengetahuan'] ?? '-' ?></td>
                                                <td><?= $nilai['desk_pengetahuan'] ?? '-' ?></td>
                                                <td><?= $nilai['nilai_keterampilan'] ?? '-' ?></td>
                                                <td><?= $nilai['grade_ket'] ?? '-' ?></td>
                                                <td><?= $nilai['desk_ket'] ?? '-' ?></td>
                                                <td><?= $nilai['nilai_ukk'] ?? '-' ?></td>
                                                <td><?= $nilai['desk_ukk'] ?? '-' ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                                <div class="akademik">
                                    <?php
                                    $ekskul = mysqli_query($koneksi, "SELECT * FROM data_ekskul INNER JOIN ekskul ON data_ekskul.id_ekskul = ekskul.id_ekskul WHERE id_siswa = '$id_siswa'");
                                    ?>
                                    <h4>D. Nilai Ekskul</h4>
                                    <table>
                                        <tr>
                                            <th>Nama Ekskul</th>
                                            <th>Nilai</th>
                                            <th>Keterangan</th>
                                        </tr>
                                        <?php foreach ($ekskul as $e) { ?>
                                            <tr>
                                                <td><?= $e['nama_ekskul'] ?? '-' ?></td>
                                                <td><?= $e['nilai'] ?? '-' ?></td>
                                                <td><?= $e['keterangan'] ?? '-' ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                                <div class="absensi">
                                    <?php
                                    $absensi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM absensi WHERE id_siswa = '$id_siswa'"));
                                    ?>
                                    <h4>E. Kehadiran</h4>
                                    <table>
                                        <tr>
                                            <td>Sakit</td>
                                            <td><?= $absensi['sakit'] ?? '-' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Izin</td>
                                            <td><?= $absensi['izin'] ?? '-' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanpa Keterangan</td>
                                            <td><?= $absensi['alfa'] ?? '-' ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="ttd">
                                    <div class="guru-ortu">
                                        <div class="ortu">
                                            <span>Mengetahui</span> <br>
                                            <span>Orang Tua/Wali,</span>
                                            <br><br><br><br><br>
                                            <hr>
                                        </div>
                                        <div class="guru">
                                            <span>Cimahi, <?= date("d M Y") ?></span> <br>
                                            <span>Wali Kelas,</span>
                                            <br><br><br><br><br>
                                            <b><span><?= ucwords($walas['nama']) ?></span></b>
                                            <hr>
                                            <span>NIP. <?= $walas['nip'] ?></span>
                                        </div>
                                    </div>
                                    <div class="kepsek-wrap">
                                        <div class="kepsek">
                                            <span>Mengetahui</span> <br>
                                            <span>Kepala Sekolah</span>
                                            <br><br><br><br><br>
                                            <b><span><?= $data_sekolah['kepala_sekolah'] ?></span></b>
                                            <hr>
                                            <span>NIP.<?= $data_sekolah['nip'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../../../asset/jquery-3.6.4.min.js"> </script>
        <script>
            $(document).ready(function() {
                $("#files").click(function() {
                    $(".files").css("display", "flex");
                });
                if (localStorage.getItem("mode") == "dark") {
                    $("body").addClass("dark-mode");
                    $("#checkbox").prop("checked", true);
                }
                $("#checkbox").change(function() {
                    if ($(this).is(":checked")) {
                        $("body").addClass("dark-mode");
                        localStorage.setItem("mode", "dark");
                    } else {
                        $("body").removeClass("dark-mode");
                        localStorage.setItem("mode", "light");
                    }
                });
                $(".content").click(function() {
                    $("#mySidenav").css('width', '70px');
                    $("#main").css('margin-left', '70px');
                    $(".logo").css('visibility', 'hidden');
                    $(".logo span").css('visibility', 'visible');
                    $(".logo span").css('margin-left', '-10px');
                    $(".icon-a").css('visibility', 'hidden');
                    $(".icons").css('visibility', 'visible');
                    $(".icons").css('margin-left', '-8px');
                    $(".active").css("border", "none");
                    $(".link_nama").css("display", "none");
                    $(".dropdown").css("display", "none");
                    $("#logout-txt").css("display", "none");
                    $(".logout").css("left", "0");
                    $(".drop-arrow").css("visibility", "hidden");
                });
                $(".dropdown-btn").click(function() {
                    $(".dropdown").not($(this).data("target")).hide();
                    $($(this).data("target")).toggle();
                    $(this).find(".slash-kiri").toggleClass("rotate-kiri")
                    $(this).find(".slash-kanan").toggleClass("rotate-kanan")
                    $(".dropdown-btn").not($(this)).find(".slash-kiri").removeClass("rotate-kiri");
                    $(".dropdown-btn").not($(this)).find(".slash-kanan").removeClass("rotate-kanan");
                })
                $("#mySidenav").click(function() {
                    $("#mySidenav").css('width', '300px');
                    $("#main").css('margin-left', '300px');
                    $(".logo").css('visibility', 'visible');
                    $(".icon-a").css('visibility', 'visible');
                    $(".icons").css('visibility', 'visible');
                    $(".active").css("border-left", "10px solid white");
                    $(".link_nama").css("display", "inline");
                    $("#logout-txt").css("display", "inline");
                    $("#logout").css("padding-left", "0px");
                    $(".logout").css("left", "60px");
                    $(".drop-arrow").css("visibility", "visible");
                });
            });
        </script>
</body>

</html>