<?php

if (!defined('BASEPATH')) {
 exit('No direct script access allowed');
}

class Spk_model extends CI_Model
{

 public $table = 'spk';
 public $id    = 'id';
 public $order = 'DESC';

 public function __construct()
 {
  parent::__construct();
 }

 // datatables
 public function json()
 {
  $this->datatables->select('spk.id,spk.nospk,spk.nama,spk.ket,spk.datetime,spk.status');
  $this->datatables->from('spk');
  //add this line for join
  //$this->datatables->join('tab_barang', 'spk.kode_barang = tab_barang.kode_barang');
  //$this->datatables->add_column('action', anchor(site_url('spk/retur/$1'), '<i class="fa fa-eye" aria-hidden="true"></i>', array('class' => 'btn btn-danger btn-sm')), 'nospk');
  $this->datatables->add_column('status', '$1', 'rename_string_open(status)');
  $this->datatables->add_column('action', anchor(site_url('spk/read/$1'), '<i class="fa fa-eye" aria-hidden="true"></i>', array('class' => 'btn btn-danger btn-sm')), 'nospk');
  return $this->datatables->generate();
 }

 // get all
 public function get_all()
 {
  $this->db->order_by($this->id, $this->order);
  return $this->db->get($this->table)->result();
 }

 // get data by id
 public function get_by_id($id)
 {
  $this->db->where($this->id, $id);
  return $this->db->get($this->table)->row();
 }

 // get total rows
 public function total_rows($q = null)
 {
  $this->db->like('id', $q);
  $this->db->or_like('kode_barang', $q);
  $this->db->or_like('stok', $q);
  $this->db->or_like('ket', $q);
  $this->db->or_like('datetime', $q);
  $this->db->from($this->table);
  return $this->db->count_all_results();
 }

 // get data with limit and search
 public function get_limit_data($limit, $start = 0, $q = null)
 {
  $this->db->order_by($this->id, $this->order);
  $this->db->like('id', $q);
  $this->db->or_like('kode_barang', $q);
  $this->db->or_like('stok', $q);
  $this->db->or_like('ket', $q);
  $this->db->or_like('datetime', $q);
  $this->db->limit($limit, $start);
  return $this->db->get($this->table)->result();
 }

 // insert data
 public function insert($data)
 {
  $this->db->insert($this->table, $data);

  //INSERT LOG
  $data2 = array(
   'ket'         => $data['ket'],
   'kode_barang' => $data['kode_barang'],
   'qty'         => $data['stok'],
   'datetime'    => $data['datetime'],
   'tipe'        => 'A',
  );
  $this->db->insert('log', $data2);
  //END INSERT LOG
 }

 // update data
 public function update($id, $data)
 {
  $this->db->where($this->id, $id);
  $this->db->update($this->table, $data);
 }

 // delete data
 public function delete($id)
 {
  $this->db->where($this->id, $id);
  $this->db->delete($this->table);
 }
 public function barang_list()
 {
  $hasil = $this->db->query("SELECT temp_spk.id, temp_spk.nospk, temp_spk.barang, temp_spk.qty, temp_spk.harga, temp_spk.jumlah, temp_spk.datetime FROM temp_spk");
  return $hasil->result();
 }

 public function simpan_barang($barang, $qty, $harga, $nospk, $datetime)
 {
  $jumlah = $qty * $harga;
  $hasil = $this->db->query("INSERT INTO temp_spk (nospk,barang,harga,qty,jumlah,datetime)VALUES('$nospk','$barang','$harga','$qty','$jumlah','$datetime')");
  return $hasil;
 }

 public function get_barang_by_kode($id)
 {
  $hsl = $this->db->query("SELECT temp_spk.id, temp_spk.nospk, temp_spk.barang, temp_spk.qty, temp_spk.harga , temp_spk.datetime FROM temp_spk
                         WHERE temp_spk.id='$id'");
  if ($hsl->num_rows() > 0) {
   foreach ($hsl->result() as $data) {
    $hasil = array(
     'id' => $data->id,
     'barang'         => $data->barang,
     'qty'            => $data->qty,
     'harga'          => $data->harga,
    );
   }
  }
  return $hasil;
 }

 public function update_barang($barang, $harga, $qty, $id)
 {
  $jumlah = $qty * $harga;
  $hasil = $this->db->query("UPDATE temp_spk SET barang='$barang', harga='$harga', qty='$qty', jumlah='$jumlah' WHERE id='$id'");
  return $hasil;
 }

 public function hapus_barang($id)
 {
  $hasil = $this->db->query("DELETE FROM temp_spk WHERE id='$id'");
  return $hasil;
 }

 public function insert_trans($notrans, $id_user, $nama, $alamat, $telp, $jenis, $tipe, $ket, $datetime)
 {

  //insert into spk
  $q1 = $this->db->query("INSERT into spk (nospk, nama, alamat, telp, jenis, tipe, ket, id_user, datetime) VALUES ('$notrans','$nama','$alamat','$telp','$jenis','$tipe','$ket', '$id_user', '$datetime')");
  //insert into spk_detail
  $q2 = $this->db->query("INSERT into spk_detail (nospk, barang, harga, qty, jumlah, datetime) SELECT nospk, barang, harga, qty, jumlah, datetime FROM temp_spk WHERE nospk = '$notrans'");
  //delete temp_spk
  $q3 = $this->db->query("DELETE FROM temp_spk WHERE nospk='$notrans'");

  //INSERT LOG
//   $log = $this->db->query("SELECT * FROM spk_detail WHERE nospk = '$notrans'")->result_array();
//   foreach ($log as $data) {
//    $kode_barang = $data['kode_barang'];
//    $ket         = $notrans;
//    $qty         = $data['stok'];
//    $datetime    = date('Y-m-d H:i:s');
//    $this->db->query("INSERT INTO log (ket, kode_barang, qty, tipe, datetime) VALUES ('$ket', '$kode_barang', '$qty', 'A', '$datetime')");
//   }
  //END INSERT LOG

  //UPDATE STOK BARANG
//   $barang = $this->db->query("SELECT * FROM tab_barang WHERE kode_barang IN (SELECT kode_barang FROM spk_detail WHERE nospk = '$notrans')")->result_array();
//   foreach ($barang as $data2) {
//    $kode_barang = $data2['kode_barang'];
//    $_stok       = $data2['stok'];
//    $stockopname = $this->db->query("SELECT nospk, kode_barang, stok FROM spk_detail WHERE nospk = '$notrans' AND kode_barang = '$kode_barang'")->row();

//    $stok_akhir = $_stok + $stockopname->stok;
//    $this->db->query("UPDATE tab_barang SET stok = '$stok_akhir' WHERE kode_barang = $kode_barang");
//   }
  //UPDATE STOK BARANG

  //UPDATE COUNTER A
  $query    = $this->db->query("SELECT counter FROM counter WHERE id='A'");
  $ret      = $query->row();
  $_counter = $ret->counter;
  $_counter++;
  $query = $this->db->query("UPDATE counter SET counter = '$_counter' WHERE id='A'");
  //END UPDATE COUNTER A

 }
 

}

/* End of file spk_model.php */
/* Location: ./application/models/spk_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-12-04 08:41:50 */
/* http://harviacode.com */
