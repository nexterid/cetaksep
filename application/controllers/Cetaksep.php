<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cetaksep extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
    }	

	public function index()
	{		
		$this->load->view('header');
		$this->load->view('home');
		$this->load->view('footer');
	}
	public function home2()
	{
		if($this->session->userdata('register')=='true'){
			$this->load->view('header');
			$this->load->view('home2');
			$this->load->view('footer');
		}else{
			redirect('step');
		}
		
	}

	public function home3()
	{
		if($this->session->userdata('register')=='true'){
			$this->load->view('header');
			$this->load->view('home3');
		}else{
			redirect('step');
		}
		
	}
	
	public function cekregister()
	{
		$this->form_validation->set_message('required', '%s Tidak Boleh Kosong');
		$this->form_validation->set_rules('kode', 'No RM', 'trim|required|max_length[9]');
		$this->form_validation->set_error_delimiters('<p class="ex">', '</p>');
		if($this->form_validation->run() == FALSE){			
			$this->index();
		}else{
			$no_rm=$this->input->post('kode');
			$cek_register=$this->rest_model->CekRegister($no_rm);
			if($cek_register->ok=='true'){
				$session=array(
					'no_reg'=>$cek_register->hasil->no_reg,
					'tgl_reg'=>$cek_register->hasil->tgl_reg,
					'no_sep'=>$cek_register->hasil->no_SJP,
					'no_rm'=>$cek_register->hasil->no_RM,
					'no_telp'=>$cek_register->hasil->no_telp,
					'nama_pasien'=>$cek_register->hasil->nama_pasien,
					'alamat'=>$cek_register->hasil->alamat,
					'alamat_lengkap'=>$cek_register->hasil->alamat_lengkap,
					'nama_sub_unit'=>$cek_register->hasil->nama_sub_unit,
					'nama_pegawai'=>$cek_register->hasil->nama_pegawai,
					'antrian'=>$cek_register->hasil->antrian,
					'no_kartu'=>$cek_register->hasil->no_kartu,
					'register'=>true
				);
				$this->session->set_userdata($session);	
				// $data['hasil']=$cek_register->hasil;
				$this->home2();
			}else{
				$pesan="<p class='ex'>".$cek_register->pesan."</p>";
				$this->session->set_flashdata('error',$pesan);
				redirect('step');
			}
		}
				
	}
	
	public function carirujukan()
	{
		$no_rujukan=$this->input->post('kode');	
		$this->form_validation->set_message('required', '%s Tidak Boleh Kosong');
		$this->form_validation->set_message('min_length', '%s Minimal 19 digit');
		$this->form_validation->set_rules('kode', 'No Rujukan', 'trim|required|min_length[19]');
		$this->form_validation->set_error_delimiters('<p class="ex">', '</p>');
		if($this->form_validation->run() == FALSE){			
			$this->home2();
		}else{
			if(strpos($no_rujukan,'/')!=null){
				$data['no_rujukan']=$no_rujukan;
				$cek_rujukan=$this->rest_model->CekRujukanInternal($data);		
				if($cek_rujukan->metaData->code=='200'){	
					if($cek_rujukan->response->rujukan->peserta->noKartu==$this->session->userdata('no_kartu')){
						$norujukanbpjs=$cek_rujukan->response->rujukan->noRujukanBpjs;
						if($this->rest_model->CekRujukan($norujukanbpjs)){
							if($cek_rujukan->metaData->code=='200'){
								$this->session->set_userdata('asal_faskes',$cek_rujukan->response->rujukan->provPerujuk->nama);	
								$no_rujukan = substr($no_rujukan,6);	
								$catatan='SKDP TERLAMPIR DARI RSUD KRATON';						
								if(!empty($this->session->userdata('no_sep'))){
									$no_sep=$this->session->userdata('no_sep');
									$result_sep=$this->rest_model->CariSep($no_sep);							
									if($result_sep->metaData->code=='200'){
										$this->cetaksep_ulang($result_sep);
									}else{
										$this->insertsep_skdp($cek_rujukan,$no_rujukan,$catatan);
									}
								}else{						
									$this->insertsep_skdp($cek_rujukan,$no_rujukan,$catatan);
								}
							}else{
								$pesan="<p class='ex'>Nomor Rujukan BPJS Awal Tidak Ditemukan <br>Silahkan Hubungi Petugas Untuk Pembuatan SEP</p>";
								$this->session->set_flashdata('error',$pesan);
								redirect('step2');
							}
						}
							
					}else{
						$pesan="<p class='ex'>Nomor kartu rujukan tidak sesaui dengan nomor kartu Pasien</p>";
						$this->session->set_flashdata('error',$pesan);
						redirect('step2');
					}
				}else{			
					$pesan="<p class='ex'>Nomor ".$cek_rujukan->metaData->message."</p>";
					$this->session->set_flashdata('error',$pesan);
					redirect('step2');					
				}
					
			}else{
				$cek_rujukan=$this->rest_model->CekRujukan($no_rujukan);		
				if($cek_rujukan->metaData->code=='200'){
					$cek_rujukanbpjs=$this->rest_model->CekRujukanBpjs($no_rujukan);
					if($cek_rujukanbpjs->metaData->code=='200'){
						$pesan="<p class='ex'>".$cek_rujukanbpjs->metaData->message."</p>";
						$this->session->set_flashdata('error',$pesan);
						redirect('step2');
					}else{
						if($cek_rujukan->response->rujukan->peserta->noKartu==$this->session->userdata('no_kartu')){
							$this->session->set_userdata('asal_faskes',$cek_rujukan->response->rujukan->provPerujuk->nama);	
							$catatan='';		
							if(!empty($this->session->userdata('no_sep'))){
								$no_sep=$this->session->userdata('no_sep');
								$result_sep=$this->rest_model->CariSep($no_sep);
								if($result_sep->metaData->code=='200'){
									$this->cetaksep_ulang($result_sep);
								}else{
									$this->insertsep($cek_rujukan,$no_rujukan,$catatan);
								}
							}else{
								$this->insertsep($cek_rujukan,$no_rujukan,$catatan);
							}	
						}else{
							$pesan="<p class='ex'>Nomor kartu rujukan tidak sesaui dengan nomor kartu Pasien</p>";
							$this->session->set_flashdata('error',$pesan);
							redirect('step2');
						}
					}					
							
				}else{			
					$pesan="<p class='ex'>Nomor ".$cek_rujukan->metaData->message."</p>";
					$this->session->set_flashdata('error',$pesan);
					redirect('step2');
					
				}
			}			
		}	
		
	}

	public function insertsep($cek_rujukan,$no_rujukan,$catatan)
	{			
		$kode_faskes=$cek_rujukan->response->rujukan->provPerujuk->kode;
		$cek_faskes = $this->rest_model->faskes($kode_faskes,1);
		if($cek_faskes->metaData->code=='200'){
			$asal_rujukan='1';
		}else{
			$asal_rujukan='2';
		}
		$datasep['t_sep']=array(
			'noKartu'=>$cek_rujukan->response->rujukan->peserta->noKartu,
			'tglSep'=>date('Y-m-d'),
			'ppkPelayanan'=>'1105R001',
			'jnsPelayanan'=>'2',
			'klsRawat'=>'3',
			'noMR'=>$this->session->userdata('no_rm'),				
			'catatan'=>$catatan,
			'diagAwal'=>$cek_rujukan->response->rujukan->diagnosa->kode,
			'noTelp'=>$this->session->userdata('no_telp'),
			'user'=>'pasien',
		);
		$datasep['t_sep']['rujukan']=array(
			'asalRujukan'=>$asal_rujukan,
			'tglRujukan'=>$cek_rujukan->response->rujukan->tglKunjungan,
			'noRujukan'=>$no_rujukan,
			'ppkRujukan'=>$cek_rujukan->response->rujukan->provPerujuk->kode,
		);	
		$datasep['t_sep']['poli']=array(
			'tujuan'=>$cek_rujukan->response->rujukan->poliRujukan->kode,
			'eksekutif'=>'0',
		);	
		$datasep['t_sep']['cob']=array(
			'cob'=>'0',
		);
		$datasep['t_sep']['katarak']=array(
			"katarak"=>'0'
		);
		$datasep['t_sep']['jaminan']=array(
			'lakaLantas'=>'0'			
		);
		$datasep['t_sep']['jaminan']['penjamin']=array(
			'penjamin'=>'0',
			"tglKejadian"=>"000-00-00",
            "keterangan"=> "0"
		);
		$datasep['t_sep']['jaminan']['penjamin']['suplesi']=array(
			"suplesi"=> "0",
            "noSepSuplesi"=> "0"
		);
		$datasep['t_sep']['jaminan']['penjamin']['suplesi']['lokasiLaka']=array(
			"kdPropinsi"=> "0",
            "kdKabupaten"=> "0000",
            "kdKecamatan"=> "0000"
		);
		$datasep['t_sep']['skdp']=array(
			"noSurat"=> "000000",
			"kodeDPJP"=>"00000"
		);
		
		$data['request']=$datasep;
		$dataJson=json_encode($data);			
		$result_sep=$this->rest_model->InsertSep($dataJson);
		if($result_sep->metaData->code=='200'){
			$data=array(
				'no_RM'=>$this->session->userdata('no_rm'),
				'no_reg'=>$this->session->userdata('no_reg'),
				'no_sep'=>$result_sep->response->sep->noSep
			);	
			$this->rest_model->UpdateRegister($data);				
			$this->cetaksep_baru($result_sep);	
		
		}else{
			$pesan="<p class='ex'>Nomor ".$result_sep->metaData->message."</p>";
			$this->session->set_flashdata('error',$pesan);
			redirect('step2');
			// print_r($result_sep);
		}
			// echo $dataJson;
	}

	public function insertsep_skdp($cek_rujukan,$no_rujukan,$catatan)
	{			
		$kode_faskes=$cek_rujukan->response->rujukan->provPerujuk->kode;
		$cek_faskes = $this->rest_model->faskes($kode_faskes,1);
		$no_surat='0'.substr($no_rujukan, 0, 5);
		if($cek_faskes->metaData->code=='200'){
			$asal_rujukan='1';
		}else{
			$asal_rujukan='2';
		}
		$datasep['t_sep']=array(
			'noKartu'=>$cek_rujukan->response->rujukan->peserta->noKartu,
			'tglSep'=>date('Y-m-d'),
			'ppkPelayanan'=>'1105R001',
			'jnsPelayanan'=>'2',
			'klsRawat'=>'3',
			'noMR'=>$this->session->userdata('no_rm'),				
			'catatan'=>$catatan,
			'diagAwal'=>$cek_rujukan->response->rujukan->diagnosa->kode,
			'noTelp'=>$this->session->userdata('no_telp'),
			'user'=>'pasien',
		);
		$datasep['t_sep']['rujukan']=array(
			'asalRujukan'=>$asal_rujukan,
			'tglRujukan'=>$cek_rujukan->response->rujukan->tglKunjungan,
			'noRujukan'=>$cek_rujukan->response->rujukan->noRujukanBpjs,
			'ppkRujukan'=>$cek_rujukan->response->rujukan->provPerujuk->kode,
		);	
		$datasep['t_sep']['poli']=array(
			'tujuan'=>$cek_rujukan->response->rujukan->poliRujukan->kode,
			'eksekutif'=>'0',
		);	
		$datasep['t_sep']['cob']=array(
			'cob'=>'0',
		);
		$datasep['t_sep']['katarak']=array(
			"katarak"=>'0'
		);
		$datasep['t_sep']['jaminan']=array(
			'lakaLantas'=>'0'			
		);
		$datasep['t_sep']['jaminan']['penjamin']=array(
			'penjamin'=>'0',
			"tglKejadian"=>"000-00-00",
            "keterangan"=> "0"
		);
		$datasep['t_sep']['jaminan']['penjamin']['suplesi']=array(
			"suplesi"=> "0",
            "noSepSuplesi"=> "0"
		);
		$datasep['t_sep']['jaminan']['penjamin']['suplesi']['lokasiLaka']=array(
			"kdPropinsi"=> "0",
            "kdKabupaten"=> "0000",
            "kdKecamatan"=> "0000"
		);
		$datasep['t_sep']['skdp']=array(
			"noSurat"=> $no_surat,
			"kodeDPJP"=>$cek_rujukan->response->rujukan->kodeDPJP
		);
		
		$data['request']=$datasep;
		$dataJson=json_encode($data);			
		$result_sep=$this->rest_model->InsertSep($dataJson);
		if($result_sep->metaData->code=='200'){
			$data=array(
				'no_RM'=>$this->session->userdata('no_rm'),
				'no_reg'=>$this->session->userdata('no_reg'),
				'no_sep'=>$result_sep->response->sep->noSep
			);	
			$this->rest_model->UpdateRegister($data);				
			$this->cetaksep_baru($result_sep);	
		
		}else{
			$pesan="<p class='ex'>Nomor ".$result_sep->metaData->message."</p>";
			$this->session->set_flashdata('error',$pesan);
			redirect('step2');
			// print_r($result_sep);
		}
		
	}


	public function cetaksep_ulang($result_sep)
	{		
		$datasep=$result_sep->response;			
		$data['sep']=array(
			'no_sep'=>$datasep->noSep,
			'tgl_sep'=>$datasep->tglSep,
			'no_rm'=>$this->session->userdata('no_rm'),
			'no_reg'=>$this->session->userdata('no_reg'),
			'no_kartu'=>$datasep->peserta->noKartu,
			'poli_tujuan'=>$datasep->poli,
			'asal_faskes'=>$this->session->userdata('asal_faskes'),
			'antrian'=>$this->session->userdata('antrian'),
			'diagnosa_awal'=>$datasep->diagnosa,
			'diagnosa_utama'=>'',
			'nama_peserta'=>$datasep->peserta->nama,
			'tgl_lahir'=>$datasep->peserta->tglLahir,
			'jns_kelamin'=>$datasep->peserta->kelamin,
			'alamat'=>$this->session->userdata('alamat_lengkap'),
			'peserta'=>$datasep->peserta->jnsPeserta,
			'cob'=>'',
			'jns_rawat'=>$datasep->jnsPelayanan,
			'kls_tanggungan'=>'Kelas - 3',
			'catatan'=>$datasep->catatan,
			'penjamin'=>''
		);
		$this->load->view('cetak_sep',$data);
		// $this->session->set_userdata($data);
	}

	public function cetaksep_baru($result_sep)
	{		
		$datasep=$result_sep->response->sep;			
		$data['sep']=array(
			'no_sep'=>$datasep->noSep,
			'tgl_sep'=>$datasep->tglSep,
			'no_rm'=>$this->session->userdata('no_rm'),
			'no_reg'=>$this->session->userdata('no_reg'),
			'no_kartu'=>$datasep->peserta->noKartu,
			'poli_tujuan'=>$datasep->poli,
			'asal_faskes'=>$this->session->userdata('asal_faskes'),
			'antrian'=>$this->session->userdata('antrian'),
			'diagnosa_awal'=>$datasep->diagnosa,
			'diagnosa_utama'=>'',
			'nama_peserta'=>$datasep->peserta->nama,
			'tgl_lahir'=>$datasep->peserta->tglLahir,
			'jns_kelamin'=>$datasep->peserta->kelamin,
			'alamat'=>$this->session->userdata('alamat_lengkap'),
			'peserta'=>$datasep->peserta->jnsPeserta,
			'cob'=>'',
			'jns_rawat'=>$datasep->jnsPelayanan,
			'kls_tanggungan'=>'Kelas - 3',
			'catatan'=>$datasep->catatan,
			'penjamin'=>''
		);
		$this->load->view('cetak_sep',$data);
		
	}

	function testprinter(){
		$this->load->library("EscPos.php");
		try {
				// Enter the device file for your USB printer here
			$connector = new Escpos\PrintConnectors\FilePrintConnector("/dev/usb/lp0");
				
				/* Print a "Hello world" receipt" */
				$printer = new Escpos\Printer($connector);
				$printer -> text("Hello World!\n");
				$printer -> cut();

				/* Close printer */
				$printer -> close();
		} catch (Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}
	}

	function testkode()
	{		
		$kode="20180807764/SKU/OBG/0818";
		// $kode="0167U0060216Y000109";
		// if (preg_match("/^([0-9])*./([a-z])(\/[a-z])(0-9)$/i", $kode, $match)){
		// 	echo $kode;
		// }else{
		// 	print "tidak sesuai";
		// }
		// if(strpos($kode,'/')!=null){
		// 	echo "kode cocok ".$kode;
		// }else{
		// 	print "kode tidak sesuai " .$kode;
		// }
		$hasil='0'.substr($kode, 6, 5);
		echo $kode.'<br>'.$hasil;
			
	}


	function cari_faskes()
	{
		$kode_faskes= "1105R001";
		$cek_faskes = $this->rest_model->faskes($kode_faskes,1);
		if($cek_faskes->metaData->code=='200'){
			echo '1';
		}else{
			echo '2';
		}
	}
	
}
