<?php
include '../../../koneksi.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:../../../");
    session_destroy();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$id_role = $role;
if ($role == 1) {
    $user = mysqli_query($koneksi, "SELECT * FROM admin INNER JOIN role ON admin.id_role = role.id_role WHERE username = '$username'");
} elseif ($role == 2) {
    $user = mysqli_query($koneksi, "SELECT * FROM guru INNER JOIN role ON guru.id_role = role.id_role WHERE guru.nama = '$username'");
} elseif ($role == 3) {
    $user = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN role ON siswa.id_role = role.id_role WHERE siswa.nama = '$username'");
}
$data_user = mysqli_fetch_assoc($user);
$nomor_induk = $_GET['nis'] ?? $_GET['nip'];
$data = $_GET['data'];
$no = 1;
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
            <a class="icon-a dropdown-btn active <?= $role != 1 ? 'hidden' : '' ?>" data-target="#user"><i class="fa fa-users icons ">&nbsp;&nbsp;</i><b class="link_nama">Pengguna</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="user">
                <ul>
                    <li><a href="./../siswa/" class="d-list">Siswa</a></li>
                    <li><a href="./../guru/" class="d-list">Guru</a></li>
                </ul>
            </div>
            <a href="../../mapel/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="fa fa-list icons">&nbsp;&nbsp;</i><b class="link_nama">Mapel</b></a>
            <a href="../../kelas/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="bx bx-chair icons">&nbsp;&nbsp;</i><b class="link_nama">Kelas</b></a>
            <a href="../../ekskul/" class="icon-a <?= $role != 1 ?   'hidden'  : '' ?>"><i class="fa fa-book icons">&nbsp;&nbsp;</i><b class="link_nama ">ekskul</b></a>
            <a class="icon-a dropdown-btn <?= $role == 1 || $role == 2 ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons ">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="../../nilai/akademik/" class="d-list">Akademik</a></li>
                    <li><a href="../../nilai/kehadiran/" class="d-list">Kehadiran</a></li>
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
                    <span class="nav">Detail <?= $data ?></span>
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
                    <div class="content-box">
                        <?php
                        switch ($data) {
                            case 'siswa':
                                $sql = mysqli_query($koneksi, "SELECT *  FROM siswa  INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas INNER JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan WHERE siswa.nis = '$nomor_induk'");
                                $detail_user =  mysqli_fetch_array($sql);
                        ?>
                                <div class="detail-user">
                                    <div class="detail-head text-start">
                                        <div class="info">
                                            <img alt="foto" src="<?= $detail_user['foto'] ?>" alt="" width="150" height="150">
                                            <div class="data">
                                                <h1><?= $detail_user['nama'] ?></h1>
                                                <h3><?= $detail_user['tingkatan'] ?> <?= $detail_user['jurusan'] ?> <?= $detail_user['nama_kelas'] ?></h3>
                                                <h3>SMKN 2 Cimahi</h3>
                                            </div>
                                        </div>
                                        <a href="../<?= $data ?>" class="back-btn"><i class='bx bx-arrow-back'></i></a>
                                    </div>
                                    <hr>
                                    <div class="detail-info text-start">
                                        <table>
                                            <tr>
                                                <td>
                                                    <h2>NIS</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['nis'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Password</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['password'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Email</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['email'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Gender</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['gender'] == "L" ? "Laki-laki" : "Perempuan" ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Agama</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['agama'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>No. Telp</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['no_telp'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Alamat</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['alamat'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Nama Orang Tua</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['nama_ortu'] ?></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php break;
                            case 'guru':
                                $sql = mysqli_query($koneksi, "SELECT *,mapel.mapel FROM guru INNER JOIN mapel ON guru.id_mapel = mapel.id_mapel WHERE nip = '$nomor_induk'");
                                $detail_user = mysqli_fetch_array($sql);
                            ?>
                                <div class="detail-user">
                                    <div class="detail-head text-start">
                                        <div class="info">
                                            <img alt="foto" src="<?= $detail_user['foto'] ?>" alt="" width="150" height="150">
                                            <div class="data">
                                                <h1><?= $detail_user['nama'] ?></h1>
                                                <h3><?= $detail_user['mapel'] ?></h3>
                                                <h3>SMKN 2 Cimahi</h3>
                                            </div>
                                        </div>
                                        <a href="../<?= $role == "siswa" ? "siswa" : "guru" ?>" class="back-btn"><i class='bx bx-arrow-back'></i></a>
                                    </div>
                                    <hr>
                                    <div class="detail-info text-start">
                                        <table>
                                            <tr>
                                                <td>
                                                    <h2>NIP</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['nip'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Password</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['password'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Email</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['email'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Gender</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['gender'] == "L" ? "Laki-laki" : "Perempuan" ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Agama</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['agama'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>No. Telp</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['no_telp'] ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h2>Alamat</h2>
                                                </td>
                                                <td>
                                                    <p><?= $detail_user['alamat'] ?></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                        <?php break;
                            default:
                                header("Location:../siswa/");
                                break;
                        }
                        ?>
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