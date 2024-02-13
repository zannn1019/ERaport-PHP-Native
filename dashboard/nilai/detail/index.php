<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1, 2])) {
    header("location:../../../");
    session_destroy();
}
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$id_guru = $_SESSION['id_guru'] ?? null;

$id_role = $role;
if ($role == 1) {
    $user = mysqli_query($koneksi, "SELECT * FROM admin INNER JOIN role ON admin.id_role = role.id_role WHERE username = '$username'");
} elseif ($role == 2) {
    $user = mysqli_query($koneksi, "SELECT * FROM guru INNER JOIN role ON guru.id_role = role.id_role WHERE guru.nama = '$username'");
    $cek_wakel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kelas WHERE wali_kelas = '$id_guru'"));
} elseif ($role == 3) {
    $user = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN role ON siswa.id_role = role.id_role WHERE siswa.nama = '$username'");
}
$data_user = mysqli_fetch_assoc($user);
$id = $_GET['id'];
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
                    <li><a href="../../users/siswa" class="d-list">Siswa</a></li>
                    <li><a href="../../users/guru" class="d-list">Guru</a></li>
                </ul>
            </div>
            <a href="../../mapel/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="fa fa-list icons">&nbsp;&nbsp;</i><b class="link_nama">Mapel</b></a>
            <a href="../../kelas/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="bx bx-chair icons">&nbsp;&nbsp;</i><b class="link_nama">Kelas</b></a>
            <a href="../ekskul/" class="icon-a <?= $role != 1 ?   'hidden'  : '' ?>"><i class="fa fa-book icons">&nbsp;&nbsp;</i><b class="link_nama ">ekskul</b></a>
            <a class="icon-a dropdown-btn active <?= $role == 1 || $role == 2 ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons ">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="../../nilai/akademik/" class="d-list">Akademik</a></li>
                    <?php if (isset($cek_wakel) || $role == 1) { ?>
                        <li><a href="../../nilai/kehadiran/" class="d-list">Kehadiran</a></li>
                    <?php } ?>
                    <li><a href="../../nilai/ekskul/" class="d-list">Ekskul</a></li>
                </ul>
            </div>
            <a href="../../pengaturan/" class="icon-a <?= $role == 1 ? '' : 'hidden' ?>"><i class="fa fa-gear icons">&nbsp;&nbsp;</i><b class="link_nama">Pengaturan</b></a>
        </div>
        <div class="logout">
            <a href="../../../logout.php" onclick="return confirm('Are you sure?')"><i class='bx bx-log-in'></i>&nbsp;&nbsp;<b id="logout-txt">Log Out</b></a>
        </div>
    </div>
    <div class="content">
        <div id="main">
            <div class="head">
                <div class="col-div-6">
                    <span class="nav">Detail Nilai Akademik</span>
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
                        <img alt="foto" src="../../../asset/img/admin/<?= $data_user['foto'] ?>" class="pro-img" />
                    </div>
                </div>
            </div>
            <div class="col-div-8">
                <div class="box-8">
                    <div class="content-box p0">
                        <?php
                        $detail = $_GET['detail'];
                        if ($detail == "nilai") {
                            if ($id_role == 1) {
                                $query = mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE nilai.id_siswa = '$id'");
                            } else {
                                $query = mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE id_nilai = '$id'");
                            }
                        } elseif ($detail == "absen") {
                            $query = mysqli_query($koneksi, "SELECT * FROM absensi INNER JOIN guru ON absensi.id_guru = guru.id_guru INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel INNER JOIN siswa ON absensi.id_siswa = siswa.id_siswa INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE id_absen = '$id'");
                        } else {
                            header("Location: ../../");
                        }
                        $nilai = mysqli_fetch_assoc($query);
                        if ($nilai == null) {
                            header("Location: ../../");
                        }
                        ?>
                    </div>
                    <div class="detail-user">
                        <div class="detail-head text-start">
                            <div class="info">
                                <img alt="foto" src="<?= $nilai['foto'] ?>" alt="" width="150" height="150">
                                <div class="data">
                                    <h1><?= $nilai['nama'] ?></h1>
                                    <h3><?= $nilai['tingkatan'] . ' ' .  $nilai['jurusan'] . ' ' . $nilai['nama_kelas'] ?></h3>
                                    <h3>SMKN 2 Cimahi</h3>
                                </div>
                            </div>
                            <a href="../<?= $detail == 'nilai' ? 'akademik' : 'kehadiran' ?>" class="back-btn"><i class='bx bx-arrow-back'></i></a>
                        </div>
                        <hr>
                        <div class="detail-info text-start">
                            <?php if ($detail == "nilai") { ?>
                                <?php if ($id_role == 1) { ?>
                                    <div class="nilai-siswa">
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
                                                $id_siswa = $_GET['id'];
                                                $semester = $_GET['semester'];
                                                $no = 1;
                                                $mapel = mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE siswa.id_siswa = '$id_siswa' AND mapel.jenis = 'umum'");
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
                                                    <th>Deskripsi UKK</th>
                                                </tr>
                                                <?php
                                                $mapel = mysqli_query($koneksi, "SELECT * FROM nilai INNER JOIN guru ON nilai.id_guru = guru.id_guru INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE siswa.id_siswa = '$id_siswa' AND mapel.jenis = 'kejuruan'");
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
                                    </div>
                                <?php } else { ?>
                                    <table>
                                        <tr>
                                            <td>
                                                <h2>Mata Pelajaran</h2>
                                            </td>
                                            <td>
                                                <p><?= $nilai['mapel'] ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>Nilai Harian</h2>
                                            </td>
                                            <td>
                                                <p><?= $nilai['nilai_harian'] ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>Nilai UTS</h2>
                                            </td>
                                            <td>
                                                <p><?= $nilai['nilai_uts'] ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>Nilai UAS</h2>
                                            </td>
                                            <td>
                                                <p><?= $nilai['nilai_uas'] ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>Predikat Pengetahuan</h2>
                                            </td>
                                            <td>
                                                <p><?= $nilai['grade_pengetahuan'] ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>Deskripsi Pengetahuan</h2>
                                            </td>
                                            <td>
                                                <p><?= $nilai['desk_pengetahuan'] ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>Nilai Keterampilan</h2>
                                            </td>
                                            <td>
                                                <p><?= $nilai['nilai_keterampilan'] ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>Predikat Keterampilan</h2>
                                            </td>
                                            <td>
                                                <p><?= $nilai['grade_ket'] ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2>Deskripsi Keterampilan</h2>
                                            </td>
                                            <td>
                                                <p><?= $nilai['desk_ket'] ?></p>
                                            </td>
                                        </tr>
                                        <?php if ($nilai['jenis'] == "kejuruan") { ?>
                                            <tr>
                                                <td>
                                                    <h2>Nilai UKK</h2>
                                                </td>
                                                <td>
                                                    <p><?= $nilai['nilai_ukk'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Deskripsi UKK</h2>
                                                </td>
                                                <td>
                                                    <p><?= $nilai['desk_ukk'] ?></p>
                                                </td>
                                            </tr>
                                        <?php }  ?>
                                    </table>
                                <?php } ?>
                            <?php } else { ?>
                                <table>
                                    <tr>
                                        <td>
                                            <h2>Izin</h2>
                                        </td>
                                        <td><?= $nilai['izin'] == 0 ? 'Tidak Pernah' : $nilai['izin'] . " kali" ?> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h2>Sakit</h2>
                                        </td>
                                        <td><?= $nilai['sakit'] == 0 ? 'Tidak Pernah' : $nilai['sakit'] . " kali" ?> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h2>Tanpa Keterangan</h2>
                                        </td>
                                        <td><?= $nilai['alfa'] == 0 ? 'Tidak Pernah' : $nilai['alfa'] . " kali" ?> </td>
                                    </tr>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../asset/jquery-3.6.4.min.js"></script>
    <script>
        $(document).on("click", function(event) {
            if (!$(event.target).is("#files, .files,.bx-folder-open")) {
                $(".files").css("display", "none");
            }
        });

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