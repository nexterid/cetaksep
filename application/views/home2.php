<style>
  p.ex {
  color: red;
}
</style>
<div class="container">
    <div class="row">
        <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
          <h1 class="display-4"></h1>
          <p class="lead">DATA REGISTRASI RAWAT JALAN PASIEN</p>
          <div class="col-xs-12">
              <table class="table table-bordered">
                  <tr>
                    <th style="text-align:center">No Reg</th>
                    <th style="text-align:center">Tanggal Reg</th>
                    <th style="text-align:center">Nama Pasien</th>                   
                    <th style="text-align:center">Alamat</th>
                    <th style="text-align:center">Nama Poli</th>
                    <th style="text-align:center">Dokter</th>
                  </tr>
                  <tr>
                    <td><?= $this->session->userdata('no_reg');?></td>
                    <td><?= tgl_lengkap($this->session->userdata('tgl_reg'));?></td>
                    <td><?= $this->session->userdata('nama_pasien').' - '.$this->session->userdata('no_rm');?></td>
                    <td><?= $this->session->userdata('alamat');?></td>
                    <td><?= $this->session->userdata('nama_sub_unit');?></td>
                    <td><?= $this->session->userdata('nama_pegawai');?></td>
                  </tr>
              </table>
          </div>
          <p class="lead">MASUKAN / SCAN NOMOR RUJUKAN</p>
          <div class="col-xs-4"></div>
          <div class="col-xs-4">
            <?php echo form_open('carirujukan');?>
              <div class="form-group">
                <input type="text" class="form-control" name="kode" id="InputID">
                <?php echo form_error('kode'); ?>
                <?php echo $this->session->flashdata('error'); ?>                
              </div>
              <button type="submit" class="btn btn-primary">BUAT SEP</button>
          </div>
          <div class="col-xs-4"></div>
        </div>   
    </div>
</div>    
