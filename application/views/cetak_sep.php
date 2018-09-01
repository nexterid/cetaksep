<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak SEP</title>
    <style>                  
        table {
            width: 100%;
            border-collapse: collapse;
        }       
        th {
            height: 15px;
            padding: 8px;
        }
        td {            
            font-family: "Courier, monospace";
            font-size:13px;
            height: 8px;
            padding: 2px;
        }
      
        .double_underline  {
            border-bottom: 1px black solid;

        }
    </style>
</head>
<script>
    function onload(){           
        window.print();
        window.location.href = "<?php echo site_url('step'); ?>";    
        window.close();        
    }   
</script>

<body onLoad="onload()">
<!-- <body> -->
<section class='content' id="content">
    <div class='row'>
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">  
                    <table class="table table-borderless">
                        <tr>
                            <td rowspan="2" style="width:10%;vertical-align: top"><img class="img-responsive avatar-view"  src="<?php echo base_url('/assets/images/logo-bpjs.png'); ?>" width="180px" height="25px"></td>
                            <td style="padding-left:2.8em;width:90%;font-size: 15px">
                                SURAT ELEGIBILITAS PESERTA 
                            </td> 
                        </tr> 
                        <tr>
                            <td style="padding-left:4em;width:90%;font-size: 15px">RSUD KRATON Pekalongan</td>
                        </tr> 
                        <tr style="width:50px">
                            <td colspan="2"></td>                            
                        </tr>                       
                    </table>                    
                </div>
                <div class="box-body">
                    <div class="col-md-12">                        
                        <table class="table">                            
                            <tr>
                                <td>No. SEP</td>
                                <td>:</td>
                                <td><?= $sep['no_sep'];?></td>
                                <td>Nama Peserta</td>
                                <td>:</td>
                                <td colspan="4"><?= $sep['nama_peserta'];?></td>
                            </tr> 
                            <tr>
                                <td style="width:15%">Tanggal SEP</td>
                                <td>:</td>
                                <td style="width:30%"><?= tgl_balik($sep['tgl_sep']);?></td>
                                <td style="width:15%">Tanggal Lahir</td>
                                <td>:</td>
                                <td style="width:20%"><?= tgl_balik($sep['tgl_lahir']);?></td>
                                <td style="width:10%">JnsKelamin</td>
                                <td>:</td>
                                <td style="width:10%"><?= $sep['jns_kelamin']?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top">No RM</td>
                                <td style="vertical-align: top">:</td>
                                <td style="vertical-align: top"><?= $sep['no_rm'];?></td>
                                <td rowspan="3" style="vertical-align: top">Alamat Pasien</td>
                                <td rowspan="3" style="vertical-align: top">:</td>
                                <td rowspan="3" colspan="4" style="vertical-align: top"><?= $sep['alamat'];?></td>
                            </tr>
                            <tr>
                                <td>No Registrasi</td>
                                <td>:</td>
                                <td><?= $sep['no_reg'];?></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td>:</td>
                                <td><?= $sep['no_kartu'];?></td>
                            </tr>
                            <tr>
                                <td>Poli Tujuan</td>
                                <td>:</td>
                                <td><?= $sep['poli_tujuan'];?></td>
                                <td>Peserta</td>
                                <td>:</td>
                                <td colspan="4"><?= $sep['peserta'];?></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top">Asal Faskes Tk. I</td>
                                <td style="vertical-align: top">:</td>
                                <td style="vertical-align: top"><?= $sep['asal_faskes'];?></td>
                                <td>COB</td>
                                <td>:</td>
                                <td colspan="4"><?= $sep['cob'];?></td>
                            </tr>
                            <tr>
                                <td>Antrian</td>
                                <td>:</td>
                                <td><?= $sep['antrian'];?></td>
                                <td>Jenis Rawat</td>
                                <td>:</td>
                                <td colspan="4"><?= $sep['jns_rawat'];?></td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="vertical-align: top">Diagnosa Awal</td>
                                <td rowspan="2" style="vertical-align: top">:</td>
                                <td rowspan="2" style="vertical-align: top"><?= $sep['diagnosa_awal'];?></td>
                                <td>Kls Tanggungan</td>
                                <td>:</td>
                                <td colspan="4"><?= $sep['kls_tanggungan'];?></td>
                            </tr>
                            <tr>
                                <td>Catatan</td>
                                <td>:</td>
                                <td colspan="4"><?= $sep['catatan'];?></td>
                            </tr>
                            <tr>
                                <td>Diagnosa Utama</td>
                                <td>:</td>
                                <td><?= $sep['diagnosa_utama'];?></td>
                                <td>Penjamin</td>
                                <td>:</td>
                                <td colspan="4"><?= $sep['penjamin'];?></td>
                            </tr>                            
                        </table>
                        <table class="table">
                            <tr>
                                <td style="height:60px" colspan="7"></td>
                            </tr>
                            <tr>
                                <td style="width:15%">Tindakan/ Operasi</td>
                                <td>:</td>
                                <td colspan="2" style="width:35%"></td>
                                <td width="20%">Pasien/ <br>Keluarga Pasien</td>
                                <td colspan="2" width="30%">Dokter <br>DPJP</td>                               
                          </tr>
                            <tr>
                              <td style="height:57px" colspan="7"></td>
                            </tr>
                            <tr>
                              <td width="60%"colspan="4">&nbsp;</td>
                              <td width="20%"><hr class="double_underline" align="center"></td>
                              <td width="30%"><hr class="double_underline" align="center"></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td colspan="5"> <i>*Saya Menyetujui BPJS Kesehatan menggunakan Informasi Medis Pasien jika diperlukan <br>*SEP bukan sebagai bukti penjamin peserta</i></td>
                              <td colspan="2">Dicetak Oleh : Admin</td>
                            </tr>
                        </table>
                  </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>    
</body>
</html>