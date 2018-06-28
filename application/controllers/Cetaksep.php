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
			$cek_rujukan=$this->rest_model->CekRujukan($no_rujukan);		
			if($cek_rujukan->metaData->code=='200'){			
				if(!empty($this->session->userdata('no_sep'))){
					$no_sep=$this->session->userdata('no_sep');
					$result_sep=$this->rest_model->CariSep($no_sep);	
					if($result_sep->metaData->code=='200'){
						$this->cetaksep_ulang($result_sep);
					}else{
						$this->insertsep($cek_rujukan,$no_rujukan);
					}
				}else{
					$this->insertsep($cek_rujukan,$no_rujukan);
				}			
			}else{			
				$pesan="<p class='ex'>Nomor ".$cek_rujukan->metaData->message."</p>";
				$this->session->set_flashdata('error',$pesan);
				redirect('step2');
				
			}
		}	
		
	}

	public function insertsep($cek_rujukan,$no_rujukan)
	{			
		$datasep['t_sep']=array(
			'noKartu'=>$cek_rujukan->response->rujukan->peserta->noKartu,
			'tglSep'=>date('Y-m-d'),
			'ppkPelayanan'=>'1105R001',
			'jnsPelayanan'=>'2',
			'klsRawat'=>'3',
			'noMR'=>$this->session->userdata('no_rm'),				
			'catatan'=>'test',
			'diagAwal'=>$cek_rujukan->response->rujukan->diagnosa->kode,
			'noTelp'=>$this->session->userdata('no_telp'),
			'user'=>'admin',
		);
		$datasep['t_sep']['rujukan']=array(
			'asalRujukan'=>'1',
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
		$datasep['t_sep']['jaminan']=array(
			'lakaLantas'=>'0',
			'penjamin'=>'0',
			'lokasiLaka'=>'0',
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
			print_r($result_sep);
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
			'asal_faskes'=>'RSUD KRATON',
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
			'kls_tanggungan'=>'Kelas -',
			'catatan'=>$datasep->catatan,
			'penjamin'=>''
		);
		$this->load->view('cetak_sep',$data);
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
			'asal_faskes'=>'RSUD KRATON',
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
			'kls_tanggungan'=>'Kelas -',
			'catatan'=>$datasep->catatan,
			'penjamin'=>''
		);
		$this->load->view('cetak_sep',$data);
	}

	
}