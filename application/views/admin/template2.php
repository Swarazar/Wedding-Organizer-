<!DOCTYPE html>
<?php $this->M_proses->validasi(); ?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="<?php echo base_url('assets') ?>/front-end/images/favicon.ico">

    <title>Wedding Organizer</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('assets') ?>/back-end/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url('assets') ?>/back-end/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/back-end/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets') ?>/back-end/js/gritter/css/jquery.gritter.css" />
    <link href="<?php echo base_url('assets') ?>/back-end/js/advanced-datatable/media/css/demo_page.css"
        rel="stylesheet" />
    <link href="<?php echo base_url('assets') ?>/back-end/js/advanced-datatable/media/css/demo_table.css"
        rel="stylesheet" />
    <link rel="stylesheet"
        href="<?php echo base_url('assets') ?>/back-end/js/advanced-datatable/media/css/DT_bootstrap.css" />

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets') ?>/back-end/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url('assets') ?>/back-end/css/style-responsive.css" rel="stylesheet">
    <link href="<?php echo base_url('assets') ?>/back-end/css/jquery.datetimepicker.css" rel="stylesheet">
    <script src="<?php echo base_url('assets') ?>/back-end/js/chart-master/Chart.js"></script>
    <link rel="stylesheet"
        href="<?php echo base_url('assets') ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
</head>

<body>

    <section id="container">

        <header class="header black-bg">
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
            </div>
            <!--logo start-->
            <a href="index.php" class="logo"><b><span></span></b></a>
            <!--logo end-->

            <div class="top-menu">
                <ul class="nav pull-right top-menu">
                    <li><a class="logout" href="<?php echo base_url("admin/keluar") ?>"
                            style="background-color: yellow;color:black"><i class="fa fa-power-off"></i> Logout</a></li>
                </ul>
            </div>
        </header>
        <!--header end-->
        <aside>
            <?php $role = aoc_des($this->session->userdata(aoc_enc("Role"))) ?>

            <div id="sidebar" class="nav-collapse ">
                <!-- sidebar menu start-->
                <ul class="sidebar-menu" id="nav-accordion">

                    <p class="centered"><a href="<?php echo base_url("admin") ?>"><img
                                src="<?php echo base_url() ?>assets/front-end/images/logo.png" class="img-circle"
                                width="80"></a></p>
                    <h5 class="centered">Ajat Tenda WEDDING ORGANIZER</h5>
                    <?php if ($role == "admin"): ?>
                        <li class="mt">
                            <a class="<?php if ($mnuact == 'brd') {
                                echo "active";
                            } ?>" href="<?php echo base_url("admin") ?>">
                                <i class="fa fa-dashboard"></i>
                                <span style="color: red">DASHBOARD</span>
                            </a>
                        </li>
                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'pelanggan') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-user"></i>
                                <span style="color: red">PELANGGAN</span>
                            </a>
                            <ul class="sub">
                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('admin/hal/pelanggan') ?>">Data Pelanggan</a></li>
                            </ul>
                        </li>


                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'paket') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-gift"></i>
                                <span style="color: red">PAKET WEDDING</span>
                            </a>
                            <ul class="sub">
                                <!--
                          <li class="<?php //echo $form_input_paket                          ?>"><a  href="index.php?mod=paket&pg=form_input_paket">Input paket</a></li>
                        -->
                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('admin/hal/paket') ?>">Data Paket</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'transaksi') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-shopping-cart"></i>
                                <span style="color: red">TRANSAKSI PEMESANAN</span>
                            </a>
                            <ul class="sub">
                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('admin/transaksi') ?>">Data Pemesanan</a></li>
                            </ul>
                        </li>




                        <!-- start petugas -->
                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'petugas') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-user"></i>
                                <span style="color: red">PETUGAS LAPANGAN</span>
                            </a>
                            <ul class="sub">
                                <li class="<?php //echo $mnuact[1]                          ?>"><a href="#"
                                        onclick="tmbtbl('tbl_petugas')">Input
                                        Petugas Lapangan</a></li>
                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('admin/hal/petugas') ?>">Data Petugas Lapangan</a></li>
                            </ul>
                        </li>
                        <!-- end petugas -->
                        <!-- start bongkar -->
                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'jadwal') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-user"></i>
                                <span style="color: red">JADWAL</span>
                            </a>

                            <ul class="sub">

                                <li class="<?php //echo $mnuact[1]                          ?>"><a href="#"
                                        onclick="tmbtbl('tbl_jadwal')">Input
                                        Jadwal Pasang Bongkar</a></li>
                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('admin/hal/jadwal') ?>">Data Jadwal Pasang Bongkar</a>
                                </li>
                            </ul>
                        </li>
                        <!-- end bongkar -->
                    <?php endif ?>
                    <?php if ($role == "petugas"): ?>
                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'jadwal') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-user"></i>
                                <span style="color: red">JADWAL PASANG BONGKAR</span>
                            </a>
                            <ul class="sub">
                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('petugas/hal/jadwal') ?>">Data Jadwal Pasang Bongkar</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'peralatan_pesanan') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-user"></i>
                                <span style="color: red">DATA PERALATAN PESANAN</span>
                            </a>
                            <ul class="sub">
                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('petugas/data_peralatan_pesanan') ?>">Data Peralatan
                                        Pesanan</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'konfirmasi') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-user"></i>
                                <span style="color: red">KONFIRMASI PENGEMBALIAN PERALATAN</span>
                            </a>
                            <ul class="sub">
                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('petugas/konfirmasi') ?>">Data Konfirmasi</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif ?>
                    <?php if ($role == "admin"): ?>
                        <!-- start Peralatan -->
                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'peralatan') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-user"></i>
                                <span style="color: red">PERALATAN DEKORASI</span>
                            </a>
                            <ul class="sub">


                                <li class="<?php //echo $mnuact[1]                          ?>"><a href="#"
                                        onclick="tmbtbl('tbl_peralatan')">Input
                                        Peralatan</a></li>

                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('admin/hal/peralatan') ?>">Data Peralatan Dekorasi</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a class="<?php if ($mnuact == 'konfirmasi') {
                                echo "active";
                            } ?>" href="javascript:;">
                                <i class="fa fa-user"></i>
                                <span style="color: red">KONFIRMASI PENGEMBALIAN</span>
                            </a>
                            <ul class="sub">
                                <li class="<?php //echo $mnuact[1]                          ?>"><a
                                        href="<?php echo base_url('admin/konfirmasi') ?>">Data Konfirmasi</a>
                                </li>
                            </ul>
                        </li>
                        <!-- end Peralatan -->

                    <?php endif ?>

                </ul>
                <!-- sidebar menu end-->
            </div>
        </aside>

        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <?php echo $contents; ?>
                </div>
            </section>
        </section>
        <!--footer start-->
        <footer class="site-footer" style="color: black">
            <div class="text-center">
                2022 - Ajat Tenda Wedding Organizer
                <a href="#" class="go-top">
                    <i class="fa fa-angle-up"></i>
                </a>
            </div>
        </footer>
        <!--footer end-->
    </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url() ?>assets/back-end/js/jquery.js"></script>
    <script src="<?php echo base_url() ?>assets/back-end/js/jquery.datetimepicker.full.js"></script>
    <script src="<?php echo base_url() ?>assets/back-end/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript"
        src="<?php echo base_url() ?>assets/back-end/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo base_url() ?>assets/back-end/js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo base_url() ?>assets/back-end/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/back-end/js/jquery.sparkline.js"></script>
    <script type="text/javascript" language="javascript"
        src="<?php echo base_url() ?>assets/back-end/js/advanced-datatable/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript"
        src="<?php echo base_url() ?>assets/back-end/js/advanced-datatable/media/jsDT_bootstrap.js"></script>
    <!--common script for all pages-->
    <script src="<?php echo base_url() ?>assets/back-end/js/common-scripts.js"></script>
    <script src="<?php echo base_url('assets') ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/bootstrap-notify.js"></script>


    <!-- Modal -->
    <div id="mBantu" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="BantuKelas" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="fbody">

            </div>
        </div>
    </div>
    <!-- Modal Hapus -->
    <div id="mHps" class="modal modal-warning fade" tabindex="-1" role="dialog" aria-labelledby="HapusKelas"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4><i class="fa fa-question-circle"></i> Konfirmasi..</h4>
                </div>
                <div class="modal-body">
                    <h4>Yakin ingin menghapus data ini.?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal"><i
                            class="fa fa-close"></i> Tidak</button>
                    <a class="btn btn-outline" href="" id="thps"><i class="fa fa-trash"></i> Hapus</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function tmbtbl(a) {
            $("#mBantu").modal("show");
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('form_bantu/tabel') ?>/" + a + "/baru"
            })
                .done(function (msg) {
                    if (msg != "") {
                        $("#fbody").html(msg);
                        //$("#mBantu").modal("show");
                    } else { $.notify({ title: "<b>Gagal Memuat Form.!!</b>", message: "<br>Mohon ulangi beberapa saat lagi.!!" }, { type: "warning", timer: 4000, placement: { from: "top", align: "right" } }) }
                });
        }
    </script>
    <script>
        $('document').ready(function () { cari_trans(); })
        $('#datetimepicker').datetimepicker({ timepicker: false, format: 'd-m-Y' });
        $('#datetimepicker2').datetimepicker({ timepicker: false, format: 'd-m-Y' });
        $("#dtsiswa").DataTable();
        $(".ambltgl").datetimepicker({ timepicker: false, format: 'd-m-Y' });
        $(function () { $(".teksedt").wysihtml5(); })
        function GetxhrObject() {
            var xhr = null;
            try { xhr = new XMLHttpRequest(); }
            catch (e) {
                try { xhr = new ActiveXObject("Msxml2.XMLHTTP"); }
                catch (e) { xhr = new ActiveXObject("Microsoft.XMLHTTP"); }
            }
            return xhr;
        };
        <?php if ($this->session->flashdata('alert_jdl') != null) { ?>
            $.notify({
                title: "<h4><?php echo $this->session->flashdata('alert_jdl') ?></h4>",
                message: "<?php echo $this->session->flashdata('alert_pesan') ?>"
            }, {
                type: "<?php echo $this->session->flashdata('alert_jns') ?>",
                timer: 4000,
                placement: {
                    from: "top",
                    align: "right"
                }
            });
        <?php } ?>
    </script>
</body>

</html>