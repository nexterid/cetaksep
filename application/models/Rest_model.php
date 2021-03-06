<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_model extends CI_Model {

    var $API ="";

    public function __construct() {
        parent::__construct();
        $this->API="http://10.10.11.15";
    }   
    
    public function CekRegister($no_rm)
    {
        $result=json_decode($this->curl->simple_get($this->API.'/data/registrasi/'.$no_rm));
        return $result;
    }

    public function CekRujukan($no_rujukan)
    {
        $result=json_decode($this->curl->simple_get($this->API.'/rujukan/'.$no_rujukan));
        return $result;
    }

    public function CekRujukanRs($no_rujukan)
    {
        $result=json_decode($this->curl->simple_get($this->API.'/rujukan/rs/'.$no_rujukan));
        return $result;
    }

    public function CekRujukanBpjs($no_rujukan)
    {
        $result=json_decode($this->curl->simple_get($this->API.'/rujukan/bpjs/'.$no_rujukan));
        return $result;
    }

    public function CekRujukanInternal($data)
    {
        $result=json_decode($this->curl->simple_post($this->API.'/rujukaninternal',$data,array(CURLOPT_BUFFERSIZE => 10)));
        return $result;
    }

    public function InsertSep($dataJson)
    {
        $this->curl->create($this->API.'/sep/insert');       
        $this->curl->option(CURLOPT_HTTPHEADER, array('Content-type: application/json; Charset=UTF-8'));        
        $this->curl->post($dataJson);
        $result= json_decode($this->curl->execute());	
        return $result;
    }

    public function CariSep($no_sep)
    {
        $result=json_decode($this->curl->simple_get($this->API.'/sep/'.$no_sep));
        return $result;
    }

    public function UpdateRegister($data)
    {
        $result=json_decode($this->curl->simple_post($this->API.'/updateregister',$data,array(CURLOPT_BUFFERSIZE => 10)));
        return $result;
       
    }

    public function faskes($kode_faskes,$no)
    {
        $result=json_decode($this->curl->simple_get($this->API.'/referensi/faskes/'.$kode_faskes.'/'.$no));
        return $result;
    }

}