<?php
include '../koneksi.php';
session_start();
if (isset($_SESSION['username'])) {
} else {
    header("location:../");
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
    $cek_ekskul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM ekskul WHERE id_guru = '$id_guru'"));
} elseif ($role == 3) {
    $user = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN role ON siswa.id_role = role.id_role WHERE siswa.nama = '$username'");
}
$guru = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah_guru FROM guru");
$siswa = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah_siswa FROM siswa");
$mapel = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah_mapel FROM mapel");
$kelas = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah_kelas FROM kelas");
$data_user = mysqli_fetch_assoc($user);
$jumlah_guru = mysqli_fetch_assoc($guru);
$jumlah_siswa = mysqli_fetch_assoc($siswa);
$jumlah_mapel = mysqli_fetch_assoc($mapel);
$jumlah_kelas = mysqli_fetch_assoc($kelas);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $title = basename(__DIR__) ?>
    <title><?= ucwords($title) ?></title>
    <link rel="stylesheet" href="../asset/CSS/dashboard.css" type="text/css" />
    <link rel="stylesheet" href="../asset/FontAwesome/css/all.min.css">
    <link rel="stylesheet" href="../asset/boxicons-2.1.4/css/boxicons.css">
</head>

<body>
    <div id="mySidenav" class="sidenav">
        <div class="link">
            <p class="logo"><span>E</span>-Raport</p>
            <a href="./" class="icon-a active"><i class="fa fa-dashboard icons">&nbsp;&nbsp;</i><b class="link_nama">Dashboard</b></a>
            <a class="icon-a dropdown-btn <?= $role != 1 ? 'hidden' : '' ?>" data-target="#user"><i class="fa fa-users icons ">&nbsp;&nbsp;</i><b class="link_nama">Pengguna</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="user">
                <ul>
                    <li><a href="./users/siswa" class="d-list">Siswa</a></li>
                    <li><a href="./users/guru" class="d-list">Guru</a></li>
                </ul>
            </div>
            <a href="./mapel/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="fa fa-list icons">&nbsp;&nbsp;</i><b class="link_nama">Mapel</b></a>
            <a href="./kelas/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="bx bx-chair icons">&nbsp;&nbsp;</i><b class="link_nama">Kelas</b></a>
            <a href="./ekskul/" class="icon-a <?= $role != 1  ?   'hidden'  : '' ?>"><i class="fa fa-book icons">&nbsp;&nbsp;</i><b class="link_nama ">ekskul</b></a>
            <a href="./nilai/nilai siswa/" class="icon-a <?= $role != 3 ? 'hidden' : '' ?>"><i class="bx bxs-objects-vertical-bottom icons">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b></a>
            <a class="icon-a dropdown-btn <?= $role == 1 || $role == 2 ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons ">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="./nilai/akademik/" class="d-list">Akademik</a></li>
                    <?php if (isset($cek_wakel) || $role == 1) { ?>
                        <li><a href="./nilai/kehadiran/" class="d-list">Kehadiran</a></li>
                    <?php } ?>
                    <?php if (isset($cek_ekskul) || $role == 1) { ?>
                        <li><a href="./nilai/ekskul/" class="d-list">Ekskul</a></li>
                    <?php } ?>
                </ul>
            </div>
            <a href="./pengaturan/" class="icon-a <?= $role == 1 ? '' : 'hidden' ?>"><i class="fa fa-gear icons">&nbsp;&nbsp;</i><b class="link_nama">Pengaturan</b></a>
        </div>
        <div class="logout">
            <a href="../logout.php" onclick="return confirm('Are you sure?')"><i class='bx bx-log-in'></i>&nbsp;&nbsp;<b id="logout-txt">Keluar</b></a>
        </div>
    </div>
    <div class="content">
        <div id="main">
            <div class="head">
                <div class="col-div-6">
                    <span class="nav">Dashboard</span>
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
                        <img alt="foto" src="../asset/img/admin/<?= $data_user['foto'] ?>" class="pro-img" />
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
            <br />
            <div class="col-div-3">
                <div class="box">
                    <p><?= $jumlah_guru['jumlah_guru'] ?><br /><span>Guru</span></p>
                    <i class="fa fa-chalkboard-user box-icon"></i>
                </div>
            </div>
            <div class="col-div-3">
                <div class="box">
                    <p><?= $jumlah_siswa['jumlah_siswa'] ?><br /><span>Siswa</span></p>
                    <i class="fa fa-users box-icon"></i>
                </div>
            </div>
            <div class="col-div-3">
                <div class="box">
                    <p><?= $jumlah_mapel['jumlah_mapel'] ?><br /><span>Mapel</span></p>
                    <i class="fa fa-book box-icon"></i>
                </div>
            </div>
            <div class="col-div-3">
                <div class="box">
                    <p><?= $jumlah_kelas['jumlah_kelas'] ?><br /><span>Kelas</span></p>
                    <i class="fa fa-tasks box-icon"></i>
                </div>
            </div>
            <div class="clearfix"></div>
            <br /><br />
            <div class="col-div-8">
                <div class="box-8">
                    <div class="content-box">
                        <label>VISI</label>
                        <br />
                        <p class="">Menjadi Sekolah Menengah Kejuruan yang Unggul dalam Pendidikan dan Pelatihan
                            Keterampilan untuk Meningkatkan Kualitas Sumber Daya Manusia</p>
                    </div>
                    <div class="content-box">
                        <label>Misi</label>
                        <br />
                        <ol>
                            <li>Menyediakan pendidikan dan pelatihan keterampilan yang berkualitas tinggi dan relevan
                                dengan kebutuhan dunia industri. </li>
                            <li>Mengembangkan kurikulum yang mengikuti perkembangan teknologi dan tuntutan industri.
                            </li>
                            <li>Menumbuhkan semangat wirausaha dan kreativitas siswa agar siap menjadi pengusaha sukses
                                di masa depan.</li>
                            <li>Mendorong pengembangan kemampuan akademik dan non-akademik siswa, seperti kepemimpinan,
                                kerja tim, dan kepedulian sosial.</li>
                            <li>Memfasilitasi siswa untuk memperoleh pengalaman praktis melalui magang, kerja lapangan,
                                dan proyek-proyek industri.</li>
                            <li>Memelihara lingkungan belajar yang aman, nyaman, dan kondusif bagi siswa dan staf
                                pendidik.</li>
                            <li>Meningkatkan kualitas staf pendidik dan kecakapan mereka dalam mengajar, memotivasi, dan
                                membimbing siswa.</li>
                            <li>Menjalin kemitraan yang kuat dengan industri dan lembaga pendidikan lainnya untuk
                                memperluas peluang kerja dan pendidikan siswa setelah lulus.</li>
                            <li>Menyediakan layanan bimbingan dan konseling yang efektif bagi siswa untuk membantu
                                mereka mencapai potensi penuh mereka secara pribadi dan akademis.</li>
                            <li>Menjunjung tinggi nilai-nilai moral dan etika dalam pendidikan, dan mempersiapkan siswa
                                menjadi warga negara yang baik dan bertanggung jawab.</li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>


        <script src="../asset/jquery-3.6.4.min.js"></script>
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
                $("#mySidenav").not($(".icon-a")).click(function() {
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