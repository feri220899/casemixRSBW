<?php

namespace App\Http\Livewire\Lab;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Lab\ServiceSoftmedik;

class BridgingalatlatLis extends Component
{
    public $carinomor;
    public $status_lanjut;
    public $tanggal1;
    public $tanggal2;
    public $cito;
    public function mount(Request $request)
    {
        $this->carinomor =  ($request->no_rawat) ? $request->no_rawat : '';
        $this->status_lanjut = ($request->no_rawat) ? $request->status_lanjut : '';
        $this->tanggal1 = date('Y-m-d');
        $this->cito = 'Y';
        $this->tanggal2 = date('Y-m-d');
        $this->getDataKhanza();
    }
    public function render()
    {
        $this->getDataKhanza();
        return view('livewire.lab.bridgingalatlat-lis');
    }

    public $getDatakhanza;
    public function getDataKhanza()
    {
        $carinomor = $this->carinomor;
        $tanggal1 = $this->tanggal1;
        $tanggal2 = $this->tanggal2;
        $this->getDatakhanza = DB::table('permintaan_lab')
            ->select(
                'reg_periksa.status_lanjut',
                'reg_periksa.no_rawat',
                'reg_periksa.kd_pj',
                'reg_periksa.kd_dokter',
                'reg_periksa.kd_poli',
                'pasien.no_rkm_medis',
                'pasien.nm_pasien',
                'pasien.jk',
                'pasien.tgl_lahir',
                'pasien.alamat',
                'pasien.no_tlp',
                'pasien.email',
                'pasien.nip',
                'perujuk.kd_dokter as kd_dr_perujuk',
                'perujuk.nm_dokter as dr_perujuk',
                'dokter.nm_dokter',
                'penjab.png_jawab',
                'poliklinik.nm_poli',
                'permintaan_lab.noorder',
                'permintaan_lab.tgl_permintaan',
                'permintaan_lab.jam_permintaan',
                'kamar_inap.kd_kamar',
                'kamar.kelas',
                'bangsal.nm_bangsal',
                'bangsal.kd_bangsal',
                DB::raw('"N" as cito'),
                DB::raw('"N" as med_legal'),
                DB::raw('"" as reserve1'),
                DB::raw('"" as reserve2'),
                DB::raw('"" as reserve3'),
                DB::raw('"" as reserve4')
            )
            ->join('reg_periksa', 'reg_periksa.no_rawat', '=', 'permintaan_lab.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('dokter as perujuk', 'permintaan_lab.dokter_perujuk', '=', 'perujuk.kd_dokter')
            ->leftJoin('kamar_inap', 'kamar_inap.no_rawat', '=', 'reg_periksa.no_rawat')
            ->leftJoin('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->leftJoin('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->where('reg_periksa.status_lanjut', $this->status_lanjut)
            ->where(function ($query) use ($carinomor, $tanggal1, $tanggal2) {
                if ($carinomor) {
                    $query->orwhere('reg_periksa.no_rkm_medis', 'LIKE', "%$carinomor%")
                        ->orwhere('pasien.nm_pasien', 'LIKE', "%$carinomor%")
                        ->orwhere('reg_periksa.no_rawat', 'LIKE', "%$carinomor%")
                        ->orwhere('permintaan_lab.noorder', 'LIKE', "%$carinomor%")
                        ->whereBetween('permintaan_lab.tgl_permintaan', [$tanggal1, $tanggal2]);
                } else {
                    $query->whereBetween('permintaan_lab.tgl_permintaan', [$tanggal1, $tanggal2]);
                }
            })
            ->get();
        $this->getDatakhanza->map(function ($item) {
            $item->Permintaan = DB::table('permintaan_pemeriksaan_lab')
                ->select('permintaan_pemeriksaan_lab.kd_jenis_prw', 'jns_perawatan_lab.nm_perawatan')
                ->where('permintaan_pemeriksaan_lab.noorder', $item->noorder)
                ->join('jns_perawatan_lab', 'permintaan_pemeriksaan_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
                ->get();
        });
    }


    public function sendDataToLIS($key)
    {
        $Service = new  ServiceSoftmedik();
        $data = $this->getDatakhanza;
        $order_test = [];
        foreach ($data[$key]['Permintaan'] as $permintaan) {
            $order_test[] = $permintaan['kd_jenis_prw'];
        }
        $sendToLis = [
            'order' => [
                'msh' => [
                    'product' => 'SOFTMEDIX LIS',
                    'version' => $Service->version(),
                    'user_id' => $Service->user_id(),
                    'key' => $Service->key(),
                ],
                'pid' => [
                    'pmrn' => $data[$key]['no_rkm_medis'],
                    'pname' => $data[$key]['nm_pasien'],
                    'sex' => $data[$key]['jk'],
                    'birth_dt' => Carbon::parse($data[$key]['tgl_lahir'])->format('d.m.Y'),
                    'address' => $data[$key]['alamat'],
                    'no_tlp' => $data[$key]['no_tlp'],
                    'no_hp' => $data[$key]['no_tlp'],
                    'email' => ($data[$key]['email']) ? $data[$key]['email'] : '-',
                    'nik' => ($data[$key]['nip']) ? $data[$key]['nip'] : '-',
                ],
                'obr' => [
                    'order_control' => 'U',
                    'ptype' => ($data[$key]['status_lanjut'] === 'Ralan') ? 'OP' : 'IP',
                    'reg_no' => $data[$key]['noorder'],
                    'order_lab' => $data[$key]['noorder'],
                    'provider_id' => $data[$key]['kd_pj'],
                    'provider_name' => $data[$key]['png_jawab'],
                    'order_date' => Carbon::parse($data[$key]['tgl_permintaan'])->format('d.m.Y') . ' ' . Carbon::parse($data[$key]['jam_permintaan'])->format('h:m:s'),
                    'clinician_id' => $data[$key]['kd_dr_perujuk'],
                    'clinician_name' => $data[$key]['dr_perujuk'],
                    'bangsal_id' => ($data[$key]['status_lanjut'] === 'Ralan') ? $data[$key]['kd_poli'] : $data[$key]['kd_bangsal'],
                    'bangsal_name' => ($data[$key]['status_lanjut'] === 'Ralan') ? $data[$key]['nm_poli'] : $data[$key]['nm_bangsal'],
                    'bed_id' => ($data[$key]['status_lanjut'] === 'Ralan') ? '0000' : $data[$key]['kd_kamar'],
                    'bed_name' => ($data[$key]['status_lanjut'] === 'Ralan') ?'0000' : $data[$key]['nm_bangsal'],
                    'class_id' => ($data[$key]['status_lanjut'] === 'Ralan') ?'0' : substr($data[$key]['kelas'], 6),
                    'class_name' => ($data[$key]['status_lanjut'] === 'Ralan') ?'0' : $data[$key]['kelas'],
                    'cito' => $data[$key]['cito'],
                    'med_legal' => $data[$key]['med_legal'],
                    'user_id' => session('auth')['id_user'],
                    'reserve1' => $data[$key]['reserve1'],
                    'reserve2' => $data[$key]['reserve2'],
                    'reserve3' => $data[$key]['reserve3'],
                    'reserve4' => $data[$key]['reserve4'],
                    'order_test' => $order_test,
                ],
            ],
        ];
        return $Service->ServiceSoftmedixPOST($sendToLis);
    }
}
