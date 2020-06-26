<?php

if (empty($tanggal_a) || empty($tanggal_b)) {
    $query = $this->db->query("SELECT *, sum(jumlah) as total
                                FROM spk 
                                INNER JOIN spk_detail ON spk.nospk = spk_detail.nospk
                                GROUP BY spk.nospk
                                ");
} else {
    $query = $this->db->query("SELECT *, sum(jumlah) as total
                                FROM spk 
                                INNER JOIN spk_detail ON spk.nospk = spk_detail.nospk
                                WHERE spk.datetime >= '$tanggal_a' AND spk.datetime <= '$tanggal_b' 
                                GROUP BY spk.nospk");
}


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

<div class="box-body">
    <div style="padding-bottom: 10px;">
    <table class='table table-bordered' width="100%" >
        <tr>
            <td>No</td>
            <td>No SPK</td>
            <td>Nama</td>
            <td>Alamat</td>
            <td>Telp</td>
            <td>Jenis</td>
            <td>Tipe</td>
            <td>Ket</td>
            <td>Status</td>
            <td>Jumlah</td>
        </tr>
        <?php
        $no = 1;
        foreach ($query->result_array() as $data) {
        if ($data['status']==0){
            $status = 'Open';
        }elseif($data['status']==1){
            $status = 'Closed';
        }
        ?>
        <tr>
            <td> <?php echo $no; ?></td>
            <td> <?php echo $data['nospk']; ?></td>
            <td> <?php echo $data['nama']; ?></td>
            <td> <?php echo $data['alamat']; ?></td>
            <td> <?php echo $data['telp']; ?></td>
            <td> <?php echo $data['jenis']; ?></td>
            <td> <?php echo $data['tipe']; ?></td>   
            <td> <?php echo $data['ket']; ?></td>
            <td> <?php echo $status; ?></td>
            <td> <?php echo rupiah($data['total']); ?></td>
        </tr>
        <?php $no++ ?>
        <?php } ?>
</table>
</div>
</div>
<div class="footer">
  <p>Dicetak tanggal <?php echo date('Y-m-d H:i:s'); ?></p>
</div>
</body>
</html>