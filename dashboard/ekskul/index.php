<?php
include '../../koneksi.php';
session_start();
if (isset($_SESSION['username'])) {
} else {
    header("location:../../");
    session_destroy();
}
if (!in_array($_SESSION['role'], [1])) {
    header("location:./../../");
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
} elseif ($role == 3) {
    $user = mysqli_query($koneksi, "SELECT * FROM siswa INNER JOIN role ON siswa.id_role = role.id_role WHERE siswa.nama = '$username'");
}
$data_user = mysqli_fetch_assoc($user);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $title = basename(__DIR__) ?>
    <title><?= ucwords($title) ?></title>
    <link rel="stylesheet" href="../../asset/CSS/dashboard.css" type="text/css" />
    <link rel="stylesheet" href="../../asset/FontAwesome/css/all.min.css">
    <link rel="stylesheet" href="../../asset/boxicons-2.1.4/css/boxicons.css">
</head>

<body>
    <div id="mySidenav" class="sidenav">
        <div class="link">
            <p class="logo"><span>E</span>-Raport</p>
            <a href="../../dashboard/" class="icon-a "><i class="fa fa-dashboard icons">&nbsp;&nbsp;</i><b class="link_nama">Dashboard</b></a>
            <a class="icon-a dropdown-btn <?= $role != 1 ? 'hidden' : '' ?>" data-target="#user"><i class="fa fa-users icons ">&nbsp;&nbsp;</i><b class="link_nama">Pengguna</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="user">
                <ul>
                    <li><a href="../users/siswa/" class="d-list">Siswa</a></li>
                    <li><a href="../users/guru/" class="d-list">Guru</a></li>
                </ul>
            </div>
            <a href="./../mapel/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?> "><i class="fa fa-list icons">&nbsp;&nbsp;</i><b class="link_nama ">Mapel</b></a>
            <a href="../kelas/" class="icon-a <?= $role != 1 ? 'hidden' : '' ?>"><i class="bx bx-chair icons">&nbsp;&nbsp;</i><b class="link_nama">Kelas</b></a>
            <a href="./" class="icon-a <?= $role == 1  || $role == 2 ?   ''  : 'hidden' ?> active"><i class="fa fa-book icons">&nbsp;&nbsp;</i><b class="link_nama ">ekskul</b></a>
            <a class="icon-a dropdown-btn <?= $role == 1 || $role == 2 ? '' : 'hidden' ?>" data-target="#nilai"><i class="bx bxs-objects-vertical-bottom icons ">&nbsp;&nbsp;</i><b class="link_nama">Nilai</b><span class="drop-arrow"><i class="slash-kiri"></i><i class="slash-kanan"></i></span></a>
            <div class="dropdown" id="nilai">
                <ul>
                    <li><a href="../nilai/akademik/" class="d-list">Akademik</a></li>
                    <li><a href="../nilai/kehadiran/" class="d-list">Kehadiran</a></li>
                    <li><a href="../nilai/ekskul/" class="d-list">Ekskul</a></li>
                </ul>
            </div>
            <a href="../pengaturan/" class="icon-a <?= $role == 1 ? '' : 'hidden' ?>"><i class="fa fa-gear icons">&nbsp;&nbsp;</i><b class="link_nama">Pengaturan</b></a>
        </div>
        <div class="logout">
            <a href="../../logout.php" onclick="return confirm('Are you sure?')"><i class='bx bx-log-in'></i>&nbsp;&nbsp;<b id="logout-txt">Keluar</b></a>
        </div>
    </div>
    <div class="content">
        <div id="main">
            <div class="head">
                <div class="col-div-6">
                    <span class="nav">Data Ekskul</span>
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
                        <img alt="foto" src="../../asset/img/admin/<?= $data_user['foto'] ?>" class="pro-img" />
                    </div>
                </div>
            </div>
            <?php
            $limit = 10;
            $query_total = "SELECT COUNT(*) AS total_data FROM ekskul";
            $result_total = mysqli_query($koneksi, $query_total);
            $row_total = mysqli_fetch_assoc($result_total);
            $total_data = $row_total['total_data'];

            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = 1;
            }
            $offset = ($page - 1) * $limit;
            $no = 1;
            $query_page = "SELECT * FROM ekskul inner join guru on ekskul.id_guru = guru.id_guru ORDER BY id_mapel DESC LIMIT $limit OFFSET $offset";
            $total_page = ceil($total_data / $limit);
            $result_page = mysqli_query($koneksi, $query_page);
            $next = $page >= $total_page ? 1 : $page + 1;
            $prev = $page <= 1 ? 1 : $page - 1;
            $jumlah_data = mysqli_fetch_all($result_page, MYSQLI_ASSOC);
            if (count($jumlah_data) > 0) {
            ?>
                <div class="pagination">
                    <div class="pagination-link">
                        <a href="?page=<?= $prev ?>"><i class='bx bx-skip-previous'></i></a>
                        <div class="pg-num">
                            <?php for ($i = 1; $i <= $total_page; $i++) { ?>
                                <?php if ($i == $page) { ?>
                                    <a href="?page=<?= $i ?>" class="pagi-active"><?= $i ?></a>
                                <?php } else { ?>
                                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <a href="?page=<?= $next ?>"><i class='bx bx-skip-next'></i></a>
                    </div>
                </div>
                <div class="col-div-8">
                    <div class="box-8">
                        <div class="content-box">
                            <div class="tb-tittle">
                                <span></span>
                                <h1>Data Ekskul</h1>
                                <button class="add-btn"><i class='bx bx-plus'></i></button>
                            </div>
                            <table>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ekskul</th>
                                    <th>Nama Pembina</th>
                                    <th>Jumlah Siswa</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                                <?php
                                $per_page = 1 * $offset;
                                $no = $per_page + 1;
                                ?>
                                <?php foreach ($result_page as $m) {
                                    $id_ekskul = $m['id_ekskul'];
                                    $data_ekskul = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah_siswa FROM data_ekskul WHERE id_ekskul = '$id_ekskul'"));
                                ?>
                                    <tr>
                                        <td> <?= $no++ ?></td>
                                        <td><?= $m['nama_ekskul'] ?></td>
                                        <td><?= $m['nama'] ?></td>
                                        <td><?= $data_ekskul['jumlah_siswa'] ?? '-' ?></td>
                                        <td>
                                            <div class="aksi-field">
                                                <a href="../aksi/edit.php?edit=ekskul&id=<?= $m['id_ekskul'] ?>" class="edit-btn"><i class='bx bxs-edit'></i></a>
                                                <a href="../aksi/delete_ekskul.php?id=<?= $m['id_ekskul'] ?>" class="delete-btn" onclick="return confirm('Anda yakin untuk menghapus?')"><i class='bx bxs-trash'></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-div-8 ">
                    <div class="box-8 mb-5">
                        <div class="content-box">
                            <div class="tb-tittle">
                                <button class="add-btn" style="visibility: hidden;"><i class='bx bx-plus'></i></button>
                                <h1>Data ekskul</h1>
                                <button class="add-btn"><i class='bx bx-plus'></i></button>
                            </div>
                            <h5>Tidak ada data</h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="add-popup">
        <div class="popup-close">
            <span>Klik dimana saja untuk menutup</span>
        </div>
        <div class="parent-tambah">
            <form action="../aksi/tambah_ekskul.php" method="post" class="tambah-mapel">
                <h1>Tambah ekskul</h1>
                <div class="form-tambah">
                    <span>Nama Ekskul</span>
                    <span>:</span>
                    <input type="text" name="nama_ekskul" placeholder="Nama Ekskul" required>
                    <span>Nama Pembina</span>
                    <span>:</span>
                    <input type="search" id="wali_kelas" name="pembina">
                    <span></span>
                    <span></span>
                    <div class="walas">
                        <div id="walas-result"></div>
                    </div>
                </div>
                <input type="submit" value="tambah-ekskul" class="submit-btn" name="tambah-ekskul">
            </form>
        </div>
    </div>
    <script src="../../asset/jquery-3.6.4.min.js"></script>
    <script>
        $(document).on("click", function(event) {
            if (!$(event.target).is("#files, .files,.bx-folder-open")) {
                $(".files").css("display", "none");
            }
        });
        $(document).on('click', '.walas-result', function() {
            var result = $(this).text();
            $('#wali_kelas').val(result);
            $('#walas-result').css('display', 'none');
        });
        $(document).ready(function() {
            $("#search-input").on('keyup', function() {
                $('#search-list').css("display", 'inline-block')
                search($(this).val())
            })
            $('#wali_kelas').keyup(function() {
                var keyword = $(this).val();
                $('#walas_result').css('display', 'inline');
                if (keyword !== '') {
                    $.ajax({
                        url: 'get_pembina.php',
                        type: 'POST',
                        data: {
                            key: keyword
                        },
                        success: function(response) {
                            $('#walas-result').html(response);
                        }
                    });
                }
                if (keyword === '') {
                    $('#walas-result').css('display', 'none');
                } else {
                    $('#walas-result').css('display', 'inline');
                }
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
            $(".add-btn").click(function() {
                $(".add-popup").css({
                    'bottom': '',
                    'top': '0',
                    'left': '',
                    'right': '0'
                });
                $(".add-popup").toggle('display');
                $(".add-popup").css('display', 'flex');
            });
            $(".popup-close").click(function() {
                $(".add-popup").css({
                    'bottom': '0',
                    'top': '',
                    'left': '0',
                    'right': ''
                });
                $(".add-popup").toggle('display');
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