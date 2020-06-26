<?php
$data = $this->db->query("SELECT
so.nospk, so.nama, so.alamat, so.jenis, so.telp, so.tipe, so.ket, so.datetime, sod.barang, sod.harga, sod.qty, sod.jumlah
            FROM
                spk so
            INNER JOIN spk_detail sod  ON
                so.nospk = sod.nospk
            WHERE
                so.nospk = '$nospk'");
?>
<html>
<style>
.body {
  font-family: "Times New Roman", Times, serif;
}
.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: grey;
  color: white;
  text-align: center;
}
</style>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
<body class="body">
<center><h2>CV REJEKI SUMBER TEKNIK</h2>
</center>

      <table class='table table-bordered table-striped'>
			
			<tr>
			<?php $row = $data->row();?>
				<td width="100px">No Transaksi</td>
				<td width="200px">
					<?php echo $row->nospk; ?>
				<td width="100px">Tanggal</td>
				<td width="100px">
					<?php echo $row->datetime; ?>

      </tr>
      <tr>
				<td>Nama</td>
        <td><?php echo $row->nama; ?></td>
        <td>Alamat</td>
        <td><?php echo $row->alamat; ?></td>
      </tr>
      <tr>
				<td>Telp</td>
        <td><?php echo $row->telp; ?></td>
        <td>Jenis</td>
        <td><?php echo $row->jenis; ?></td>
			</tr>
			<tr>
				<td>Tipe</td>
        <td><?php echo $row->tipe; ?></td>
        <td>Keterangan</td>
        <td><?php echo $row->ket; ?></td>
			</tr>
			</table>
<div class="box-body">
    <div style="padding-bottom: 10px;">
    <table class="table table-bordered table-striped" id="mydata">
			<thead>
				<tr>
					<th>Nama Barang</th>
					<th>Harga</th>
					<th>QTY</th>
					<th>Jumlah</th>
				</tr>
			</thead>
			<tbody id="show_data">
      <?php 
      $sum = 0;
      foreach ($data->result_array() as $isi) {?>
			<tr>
			<td><?php echo $isi['barang']; ?></td>
			<td><?php echo rupiah($isi['harga']); ?></td>
			<td><?php echo $isi['qty']; ?></td>
			<td><?php echo rupiah($isi['jumlah']); ?></td>
			</tr>
      <?php 
      $sum+= $isi['jumlah'];
      }
;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3">Total</td>
          <td><?php echo rupiah($sum); ?></td>
        </tr>
      </tfoot>
    </table>
    
</div>
</div>
<div class="footer">
  <p>Dicetak tanggal <?php echo date('Y-m-d H:i:s'); ?></p>
</div>
</body>
</html>