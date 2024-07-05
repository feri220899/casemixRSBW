<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class MapingAsuransi extends Component
{
    public $carinomor;
    public  function mount()  {
        $this->getnamaAsuransi();
    }
    public function render()
    {
        $this->getnamaAsuransi();
        return view('livewire.laporan.maping-asuransi');
    }

    public $getAsuransi;
    public function getnamaAsuransi(){
        $cariKode = $this->carinomor;
        $this->getAsuransi = DB::table('penjab')
        ->select('penjab.png_jawab','penjab.kd_pj', 'bw_maping_asuransi.nama_perusahaan', 'bw_maping_asuransi.alamat_asuransi')
        ->leftJoin('bw_maping_asuransi','penjab.kd_pj','=','bw_maping_asuransi.kd_pj')
        ->where(function ($query) use ($cariKode) {
            $query->orwhere('penjab.png_jawab', 'LIKE', "%$cariKode%")
                ->orwhere('penjab.kd_pj', 'LIKE', "%$cariKode%");
        })
        ->get();
    }

    public $nama_perusahaan;
    public $alamat_asuransi;
    function updateInsertNomor($key, $kd_pj)  {
        DB::table('bw_maping_asuransi')->updateOrInsert(
            ['kd_pj' => $kd_pj],
            ['nama_perusahaan' => $this->getAsuransi[$key]['nama_perusahaan'], 'alamat_asuransi' => $this->getAsuransi[$key]['alamat_asuransi']]
        );
    }
}