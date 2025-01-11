<?php

class Petugas extends CI_Controller
{


  function login()
  {
    $data["jdlapp"] = "WO || Login";
    $this->load->view('petugas/login', $data);
  }

  private function cek_login()
  {
    $role = aoc_des($this->session->userdata(aoc_enc('Role')));

    if (!$role or $role != "petugas") {
      $this->session->sess_destroy();
      redirect("petugas/login");
    }
  }

  function pros_login()
  {

    $petugas = $this->M_data->data('tbl_petugas', ["username" => $this->input->post("username"), "password" => aoc_enc($this->input->post("password"))])->row();
    if (!$petugas)
      return "N";

    $svdata = array(
      aoc_enc("AdminID") => aoc_enc("001100PTG"),
      aoc_enc("UserName") => aoc_enc($petugas->username),
      aoc_enc("Nama") => aoc_enc($petugas->nama_petugas),
      aoc_enc("UserLvl") => aoc_enc("0"),
      aoc_enc("Role") => aoc_enc("petugas")
    );
    $this->session->set_userdata($svdata);
    echo "Y";
  }

  function data_peralatan_pesanan()
  {
    $this->cek_login();
    $data["jdlapp"] = "Wedding Organizer";
    $data["jdlhal"] = "Data Peralatan Pesanan";
    $data["deshal"] = "";
    $data["mnuact"] = 'peralatan_pesanan';
    $this->template->load('admin/template2', "petugas/data_peralatan_pesanan", $data);
  }
  function konfirmasi()
  {
    $this->cek_login();
    $data["jdlapp"] = "Wedding Organizer";
    $data["jdlhal"] = "Konfirmasi Pengembalian Peralatan";
    $data["deshal"] = "";
    $data["mnuact"] = 'konfirmasi';
    $this->template->load('admin/template2', "petugas/konfirmasi_pengembalian", $data);
  }

  function detail_peralatan_pelanggan($id)
  {
    $this->cek_login();
    $data["jdlapp"] = "Wedding Organizer";
    $data["jdlhal"] = "Peralatan Pesanan";
    $data["deshal"] = "";
    $data["mnuact"] = 'detail_peralatan_pesanan';
    $id_paket = $this->M_data->data("tbl_peralatan_pelanggan", ["id_peralatan_pelanggan" => $id])->row()->id_paket;

    $data["peralatan"] = $this->db->select("tbl_stok_paket.*, tbl_peralatan.nama_peralatan, tbl_peralatan.unit")->join("tbl_peralatan", "tbl_peralatan.id_peralatan=tbl_stok_paket.id_peralatan")->from("tbl_stok_paket")->where("id_paket", $id_paket)->get()->result();


    $this->template->load('admin/template2', "petugas/detail_peralatan_pesanan", $data);
  }

  function konfirmasi_pengembalian($id)
  {
    $this->M_proses->ubah("tbl_jadwal", ["status" => "Menunggu konfirmasi"], ["id" => $id]);
    redirect(base_url("/petugas/konfirmasi"));
  }

  function hal($nm = null, $ke = 1)
  {
    $this->cek_login();
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
}