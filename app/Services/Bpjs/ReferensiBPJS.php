<?php

namespace App\Services\Bpjs;

use App\Services\Bpjs\cUrl;
use Bpjs\Bridging\Icare\BridgeIcare;
use Bpjs\Bridging\Antrol\BridgeAntrol;
use Bpjs\Bridging\Vclaim\BridgeVclaim;

class ReferensiBPJS
{
    protected $bridging;
    protected $antrol;
    protected $icare;

    public function __construct()
	{
		$this->bridging = new BridgeVclaim();
        $this->antrol = new BridgeAntrol();
        $this->icare = new cUrl();
	}

    // 1 REFERENSI ======================================================
    public function getDiagnosa($kode)
	{
        try {
            $endpoint = 'referensi/diagnosa/'. $kode;
            return $this->bridging->getRequest($endpoint);
        } catch (\Throwable $th) {
            return [];
        }
	}

    public function getPoli($kode)
	{
        try {
            $endpoint = 'referensi/poli/'. $kode;
            return $this->bridging->getRequest($endpoint);
        } catch (\Throwable $th) {
            return [];
        }
	}

    public function getFasilitasKesehatan($parameter1, $parameter2)
	{
        try {
            $endpoint = 'referensi/faskes/'.$parameter1.'/'.$parameter2;
            return $this->bridging->getRequest($endpoint);
        } catch (\Throwable $th) {
            return [];
        }
	}

    // 2 ANTROL ======================================================
    public function cekinBPJS($data)
	{
            $endpoint = 'antrean/updatewaktu';
            return $this->antrol->postRequest($endpoint, $data, "POST");
	}

    public function dashboardTanggal($tanggal)
	{
        $endpoint = "antrean/pendaftaran/tanggal/{$tanggal}";
		return $this->antrol->getRequest($endpoint);
	}

    public function cekantrianTaskID($kodebooking)
    {
            $endpoint = "antrean/pendaftaran/kodebooking/{$kodebooking}";
            return $this->antrol->getRequest($endpoint);
    }
    public function cekTaskID($data)
    {
        try {
            $endpoint = 'antrean/getlisttask';
            return $this->antrol->postRequest($endpoint, $data, "POST");
        } catch (\Throwable $th) {
            return [] ;
        }
    }
    // Batal Antran MJKN
    public function batalAntranMJKN($data)
    {
        try {
            $endpoint = 'antrean/batal';
            return $this->antrol->postRequest($endpoint, $data, "POST");
        } catch (\Throwable $th) {
            return [] ;
        }
    }

    public function updateJadwalHfisDokter($data)
    {
        try {
            $endpoint = 'jadwaldokter/updatejadwaldokter';
            return $this->antrol->postRequest($endpoint, $data, "POST");
        } catch (\Throwable $th) {
            return [] ;
        }
    }
    public function getJadwalHfisDokter($kdpoli, $tanggal)
    {
        try {
            $endpoint = "jadwaldokter/kodepoli/{$kdpoli}/tanggal/{$tanggal}";
            return $this->antrol->getRequest($endpoint);
        } catch (\Throwable $th) {
            //throw $th;
        }
}

    // 3 SEP ======================================================
    public function CariSepVclaim1($nomorsep) {
        try {
            $endpoint = 'SEP/'. $nomorsep;
            return $this->bridging->getRequest($endpoint);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function CariSepVclaim2($nomorsep) {
        try {
            $endpoint = 'RencanaKontrol/nosep/'. $nomorsep;
            return $this->bridging->getRequest($endpoint);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function CariSuplesi($nokartuPeserta, $tglSep ) {
        try {
            $endpoint = 'sep/JasaRaharja/Suplesi/'.$nokartuPeserta.'/tglPelayanan/'.$tglSep;
            return $this->bridging->getRequest($endpoint);
        } catch (\Throwable $th) {
            return [];
        }
    }

    // ICARE

    public function validateICARE($data)
    {
            $endpoint = 'api/rs/validate';
            return $this->icare->postRequest($endpoint, $data);
    }

}
