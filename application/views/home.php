<style>
  p.ex {
  color: red;
}
</style>
<div class="container">
  <div class="row">
    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
      <h1 class="display-4"></h1>
      <p class="lead">MASUKAN / SCAN NOMOR RM</p>
      <div class="col-xs-4">
      </div>
      <div class="col-xs-4">
        <?php echo form_open('cekregister');?>
        <div class="form-group">
          <input type="text" class="form-control" name="kode" id="InputID" autofocus>
          <?php echo form_error('kode'); ?>
          <?php echo $this->session->flashdata('error'); ?>
        </div>
        
        <button type="submit" class="btn btn-primary">CARI</button>
      </div>
      <div class="col-xs-4">
      </div>
    </div>   
  </div>
</div>    
