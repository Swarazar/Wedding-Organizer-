<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	private function check_login()
	{
		$role = aoc_des($this->session->userdata(aoc_enc("Role")));
		if (!$role or $role != "admin") {
			$this->session->sess_destroy();

			redirect(base_url("admin/login"));
		}
	}

	public function index()
	{
		$this->check_login();
		$data["jdlapp"] = "Wedding Organizer";
		$data["jdlhal"] = "Halaman Utama";
		$data["deshal"] = "";
		$data["mnuact"] = "brd";

		$this->template->load('admin/template2', 'admin/beranda', $data);
	}

	function hal($nm = null, $ke = 1)
	{
		$this->check_login();
		$data["jdlapp"] = "Wedding Organizer";
		$data["jdlhal"] = "Data Tabel";
		$data["deshal"] = "";
		$data["mnuact"] = $nm;
		$data["tbl"] = $nm;
		if ($nm != null) {
			$data["tabel"] = $this->M_data->data("master_tbl", array("nama_tbl" => ("tbl_" . $nm)))->row();
			// $data["tabel"] = $this->db->field_data(aoc_des($nm));
			$data["dtbl"] = $this->M_data->data(("tbl_" . $nm), null, 10, (($ke - 1) * 10))->result();
			$data["tothal"] = ceil($this->M_data->data(("tbl_" . $nm))->num_rows() / 10);
			$data["nmtbl"] = ("tbl_" . $nm);
			$this->template->load('admin/template2', 'admin/tabel', $data);
		} else {
			redirect(base_url("admin"));
		}
	}

	function rinci($nmtbl, $rjk1)
	{
		$this->check_login();
		$data["jdlapp"] = "Wedding Organizer";
		$data["jdlhal"] = "Rincian Data";
		$data["deshal"] = "";
		$data["mnuact"] = 'rinci';
		$data["nmtbl"] = $nmtbl;

		$fldtbl = json_decode($this->M_data->data("master_tbl", array("nama_tbl" => $nmtbl))->row()->tabel);
		foreach ($fldtbl as $k => $v) {
			if ($v->pk == 'Y') {
				$whr[$k] = $rjk1;
			}
		}
		$data["rcndata"] = $this->M_data->data($nmtbl, $whr)->row();

		$this->template->load('admin/template2', 'admin/rinci', $data);
	}

	private function kurangi_stok_peralatan($id_paket)
	{
		$peralatan = $this->db->select("tbl_stok_paket.*, tbl_peralatan.nama_peralatan, tbl_peralatan.unit")->join("tbl_peralatan", "tbl_peralatan.id_peralatan=tbl_stok_paket.id_peralatan")->from("tbl_stok_paket")->where("id_paket", $id_paket)->get()->result();

		foreach ($peralatan as $p) {
			$this->db->query("UPDATE tbl_peralatan SET stok = stok - $p->stok_dibutuhkan WHERE id_peralatan = $p->id_peralatan");
		}
	}


	private function tambah_peralatan_pesanan($id, $transaksi, $alamat)
	{
		$id_paket = $this->M_data->data("tbl_keranjang", ["id_transaksi" => $id])->row()->id_paket;

		$data = [
			"id_pelanggan" => $transaksi->id_pelanggan,
			"nama_pelanggan" => $transaksi->nama_pelanggan,
			"alamat" => $alamat->nama_prov . "-" . $alamat->nama_kabkot . "-" . $alamat->nama_kec . "-" . $transaksi->catatan,
			"jadwal" => $transaksi->tgl_pernikahan,
			"id_paket" => $id_paket
		];

		$this->M_proses->tambah('tbl_peralatan_pelanggan', $data);
		$this->kurangi_stok_peralatan($id_paket);
	}

	private function pasang_tambah_bongkar_pasang($id)
	{
		$tgl = $this->M_data->data('tbl_transaksi', ["id_transaksi" => $id])->row()->tgl_pernikahan;
		$tgl_pasang = new DateTime($tgl);
		$tgl_pasang = $tgl_pasang->modify("-1 day");
		$tgl_bongkar = new DateTime($tgl);
		$tgl_bongkar = $tgl_bongkar->modify("+2 days");

		$transaksi = $this->db->select("tbl_pelanggan.nama_pelanggan, tbl_pelanggan.no_telp, tbl_keranjang.id_pelanggan, tbl_keranjang.id_paket, tbl_transaksi.*")->from("tbl_keranjang")->join("tbl_pelanggan", "tbl_keranjang.id_pelanggan=tbl_pelanggan.id_pelanggan")->join("tbl_transaksi", "tbl_keranjang.id_transaksi=tbl_transaksi.id_transaksi")->where("tbl_keranjang.id_transaksi", $id)->get()->row();

		$alamat = $this->db->select("tbl_kabkot.nama_kabkot, tbl_prov.nama_prov, tbl_kec.nama_kec")->join('tbl_kabkot', "tbl_kabkot.id_kabkot=tbl_kec.id_kabkot")->join("tbl_prov", "tbl_prov.id_prov=tbl_kec.id_prov")->from("tbl_kec")->where("tbl_kec.id_kec=$transaksi->id_kec")->get()->row();


		$data = [
			"jadwal_pasang" => $tgl_pasang->format('Y-m-d'),
			"jadwal_bongkar" => $tgl_bongkar->format('Y-m-d'),
			"id_pelanggan" => $transaksi->id_pelanggan,
			"nama_pelanggan" => $transaksi->nama_pelanggan,
			"alamat" => $alamat->nama_prov . "-" . $alamat->nama_kabkot . "-" . $alamat->nama_kec . "-" . $transaksi->catatan,
			"no_tlp" => $transaksi->no_telp,
			"jadwal" => $transaksi->tgl_pernikahan
		];


		$this->M_proses->tambah('tbl_jadwal', $data);
		$this->db->query("UPDATE tbl_paket SET stok = stok - 1 WHERE id_paket = $transaksi->id_paket");
		$this->tambah_peralatan_pesanan($id, $transaksi, $alamat);
	}

	function transaksi($id = null, $stt = null)
	{
		$this->check_login();
		if ($stt and $stt == "Lunas") {
			$this->pasang_tambah_bongkar_pasang($id);

		}
		if ($stt and $stt == "Kirimkan%20bukti%20transaksi%20ulang") {
			$stt = "Kirim Bukti Ulang";
		}
		if ($stt and $stt == "Tidak%20Tersedia") {
			$stt = "Tidak Tersedia";
		}
		if ($stt and $stt == "Tersedia")
			$stt = "Paket Tersedia, Silahkan lakukan pembayaran selama 1x24 jam, jika tidak transaksi akan terhapus";
		if ($stt != null) {
			$this->M_proses->ubah("tbl_transaksi", array("status" => $stt), array("id_transaksi" => $id));
		}

		//cek transaksi lewat 1 hari
		$dt1 = $this->db->query("SELECT * FROM tbl_transaksi WHERE status != 'Lunas'")->result();
		foreach ($dt1 as $r) {
			$idtrans = $r->id_transaksi;

			$tgl2 = substr($r->tgl_transaksi, 0, 10);
			$sekarang = date('Y-m-d');
			$pengurangan = $this->db->query("SELECT DATEDIFF('$sekarang','$tgl2') AS pengurangan")->row()->pengurangan;
			if ($pengurangan >= 1) {
				$this->M_proses->hapus("tbl_transaksi", array("id_transaksi" => $idtrans));
				$this->M_proses->hapus("tbl_keranjang", array("id_transaksi" => $idtrans));
			}
		}
		//----

		$data["jdlapp"] = "Wedding Organizer";
		$data["jdlhal"] = "Transaksi WO";
		$data["deshal"] = "";
		$data["mnuact"] = 'transaksi';

		$data["dtrans"] = $this->M_data->data("tbl_transaksi", array("id_transaksi" => $id))->result();

		$this->template->load('admin/template2', 'admin/data_transaksi', $data);
	}

	function konfirmasi()
	{
		$this->check_login();
		$data["jdlapp"] = "Wedding Organizer";
		$data["jdlhal"] = "Konfirmasi Pengembalian Peralatan";
		$data["deshal"] = "";
		$data["mnuact"] = 'konfirmasi';
		$this->template->load('admin/template2', "admin/konfirmasi_pengembalian", $data);
	}

	function konfirmasi_pengembalian($id)
	{
		$this->M_proses->ubah("tbl_jadwal", ["status" => "Dikembalikan"], ["id" => $id]);
		redirect(base_url("/admin/konfirmasi"));
	}
	function laporan()
	{
		$data["jdlapp"] = "Wedding Organizer";
		$data["jdlhal"] = "Laporan WO";
		$data["deshal"] = "";
		$data["mnuact"] = 'laporan';

		$this->template->load('admin/template2', 'admin/lap_wo', $data);
	}

	function detail_paket($id)
	{
		$this->check_login();
		$data["jdlapp"] = "Wedding Organizer";
		$data["jdlhal"] = "Detail Transaksi";
		$data["deshal"] = "";
		$data["mnuact"] = array('', 'active', '', '', '', '', '', '', '');

		$data["dtlpms"] = $this->db->query("SELECT * FROM tbl_paket,tbl_keranjang,tbl_kategori
                  WHERE tbl_paket.id_paket=tbl_keranjang.id_paket
                  AND tbl_paket.id_kategori=tbl_kategori.id_kategori 
                  AND tbl_keranjang.id_transaksi='$id'")->result();


		$this->template->load('admin/template2', 'admin/detail_pemesanan', $data);
	}

	function cetak($tp, $tgl1 = null, $tgl2 = null)
	{
		$this->check_login();
		if ($tp == 'pms') {
			if ($tgl1 == null) {
				$data["dt1"] = $this->db->query("SELECT * FROM tbl_paket,tbl_keranjang,tbl_transaksi
                               WHERE tbl_paket.id_paket=tbl_keranjang.id_paket
                               AND tbl_keranjang.id_transaksi=tbl_transaksi.id_transaksi
                               GROUP BY tbl_keranjang.id_transaksi
                               ORDER BY tbl_transaksi.id_transaksi DESC")->result();
			} else {
				$tgl1 = date("Y-m-d", strtotime($tgl1));
				$tgl2 = date("Y-m-d", strtotime($tgl2));

				$data["dt1"] = $this->db->query("SELECT * FROM tbl_paket,tbl_keranjang,tbl_transaksi
                               WHERE tbl_paket.id_paket=tbl_keranjang.id_paket
                               AND tbl_keranjang.id_transaksi=tbl_transaksi.id_transaksi
                               AND tbl_transaksi.tgl_transaksi 
                               BETWEEN '$tgl1' AND '$tgl2'
                               GROUP BY tbl_keranjang.id_transaksi
                               ORDER BY tbl_transaksi.id_transaksi DESC")->result();
			}
			$this->load->view("admin/cetak_pemesanan", $data);
		} else if ($tp == 'wo') {
			$this->load->view("admin/cetak_wo");
		} else {
			if ($tgl1 == null) {
				$data["dt1"] = $this->db->query("SELECT *,SUM(tbl_keranjang.jumlah_beli) AS total,
                                       SUM(tbl_keranjang.jumlah_harga) AS total_harga 
                                       FROM tbl_keranjang,tbl_transaksi
                                       WHERE tbl_keranjang.id_transaksi=tbl_transaksi.id_transaksi")->result();
			} else {
				$tgl1 = date("Y-m-d", strtotime($tgl1));
				$tgl2 = date("Y-m-d", strtotime($tgl2));

				$data["dt1"] = $this->db->query("SELECT *,SUM(tbl_keranjang.jumlah_beli) AS total,
                                       SUM(tbl_keranjang.jumlah_harga) AS total_harga 
                                       FROM tbl_keranjang,tbl_transaksi
                                       WHERE tbl_keranjang.id_transaksi=tbl_transaksi.id_transaksi
                                       AND tbl_transaksi.tgl_transaksi 
                                       BETWEEN '$tgl1' AND '$tgl2'")->result();
			}
			$this->load->view("admin/cetak_terlaris", $data);
		}
	}

	function data_foto($id)
	{
		$this->check_login();
		$data["jdlapp"] = "Wedding Organizer";
		$data["jdlhal"] = "Halaman Daftar Foto";
		$data["deshal"] = "";
		$data["mnuact"] = array('', 'active', '', '', '', '', '', '', '');
		$data["dtfoto"] = $this->M_data->data("tbl_foto", array("id_paket" => $id))->result();
		$data["idpkt"] = $id;

		$this->template->load('admin/template2', 'admin/data_foto', $data);
	}


	function login()
	{
		$data["jdlapp"] = "WO || Login";
		$this->load->view('admin/login', $data);
	}

	function keluar()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}
}
