<?php

namespace App\Http\Controllers\Farmasi;

use PDF;
use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ViewSepResepController extends Controller
{
    function ViewBerkasSepResep(Request $request) {
        $noRawat = $request->cariNoRawat;
        $noSep = $request->cariNoSep;
        $cekNorawat = DB::table('reg_periksa')
        ->select('status_lanjut')
        ->where('no_rawat', '=', $noRawat);
        $jumlahData = $cekNorawat->count();

        if ($jumlahData > 0) {
            $pasien = DB::table('reg_periksa')
                ->join('pasien', 'pasien.no_rkm_medis', '=', 'reg_periksa.no_rkm_medis')
                ->join('penjab', 'penjab.kd_pj', '=', 'reg_periksa.kd_pj')
                ->leftJoin('bridging_sep','bridging_sep.no_rawat','=','reg_periksa.no_rawat')
                ->where('bridging_sep.no_sep', '=', $noSep)
                ->where('reg_periksa.no_rawat', '=', $noRawat)
                ->select('pasien.no_rkm_medis', 'pasien.nm_pasien', 'bridging_sep.no_sep', 'reg_periksa.no_rawat', 'penjab.png_jawab');
            $getPasien = $pasien->first();

            $getSEP = DB::table('bridging_sep')
                ->select('bridging_sep.no_sep',
                    'reg_periksa.no_reg',
                    'reg_periksa.status_lanjut',
                    'reg_periksa.kd_pj',
                    'bridging_sep.no_rawat',
                    'bridging_sep.tglsep',
                    'bridging_sep.tglrujukan',
                    'bridging_sep.no_rujukan',
                    'bridging_sep.kdppkrujukan',
                    'bridging_sep.nmppkrujukan',
                    'bridging_sep.kdppkpelayanan',
                    'bridging_sep.nmppkpelayanan',
                    'bridging_sep.jnspelayanan',
                    'bridging_sep.catatan',
                    'bridging_sep.diagawal',
                    'bridging_sep.nmdiagnosaawal',
                    'bridging_sep.kdpolitujuan',
                    'bridging_sep.nmpolitujuan',
                    'bridging_sep.klsrawat',
                    'bridging_sep.klsnaik',
                    'bridging_sep.pembiayaan',
                    'bridging_sep.pjnaikkelas',
                    'bridging_sep.lakalantas',
                    'bridging_sep.user',
                    'bridging_sep.nomr',
                    'bridging_sep.nama_pasien',
                    'bridging_sep.tanggal_lahir',
                    'bridging_sep.peserta',
                    'bridging_sep.jkel',
                    'bridging_sep.no_kartu',
                    'bridging_sep.tglpulang',
                    'bridging_sep.asal_rujukan',
                    'bridging_sep.eksekutif',
                    'bridging_sep.cob',
                    'bridging_sep.notelep',
                    'bridging_sep.katarak',
                    'bridging_sep.tglkkl',
                    'bridging_sep.keterangankkl',
                    'bridging_sep.suplesi',
                    'bridging_sep.no_sep_suplesi',
                    'bridging_sep.kdprop',
                    'bridging_sep.nmprop',
                    'bridging_sep.kdkab',
                    'bridging_sep.nmkab',
                    'bridging_sep.kdkec',
                    'bridging_sep.nmkec',
                    'bridging_sep.noskdp',
                    'bridging_sep.kddpjp',
                    'bridging_sep.nmdpdjp',
                    'bridging_sep.tujuankunjungan',
                    'bridging_sep.flagprosedur',
                    'bridging_sep.penunjang',
                    'bridging_sep.asesmenpelayanan',
                    'bridging_sep.kddpjplayanan',
                    'bridging_sep.nmdpjplayanan')
                ->join('reg_periksa', 'reg_periksa.no_rawat', '=', 'bridging_sep.no_rawat')
                ->where('bridging_sep.no_rawat', '=', $noRawat)
                ->where('bridging_sep.no_sep', '=', $noSep)
                ->first();

            $berkasResep = DB::table('resep_obat')
                ->select('resep_obat.no_resep',
                    'resep_obat.tgl_perawatan',
                    'resep_obat.jam',
                    'resep_obat.no_rawat',
                    'resep_obat.kd_dokter',
                    'penjab.png_jawab',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'dokter.nm_dokter')
                ->join('reg_periksa','resep_obat.no_rawat','=','reg_periksa.no_rawat')
                ->join('pasien','reg_periksa.no_rkm_medis','=','pasien.no_rkm_medis')
                ->join('dokter','resep_obat.kd_dokter','=','dokter.kd_dokter')
                ->join('penjab','reg_periksa.kd_pj','=','penjab.kd_pj')
                ->where('resep_obat.no_rawat','=', $noRawat)
                ->orderBy('resep_obat.tgl_perawatan','asc')
                ->orderBy('resep_obat.jam','asc')
                ->get();
                foreach( $berkasResep as $itemresep){
                    $detailberkasResep = DB::table('detail_pemberian_obat')
                        ->select('databarang.nama_brng',
                            'detail_pemberian_obat.jml',
                            'databarang.kode_sat',
                            'detail_pemberian_obat.biaya_obat',
                            'detail_pemberian_obat.total')
                        ->join('databarang','detail_pemberian_obat.kode_brng','=','databarang.kode_brng')
                        ->leftJoin('detail_obat_racikan',function($join) {
                            $join->on('detail_pemberian_obat.kode_brng','=','detail_obat_racikan.kode_brng')
                            ->on('detail_pemberian_obat.tgl_perawatan','=','detail_obat_racikan.tgl_perawatan')
                            ->on('detail_pemberian_obat.jam','=','detail_obat_racikan.jam')
                            ->on('detail_pemberian_obat.no_rawat','=','detail_obat_racikan.no_rawat');
                        })
                        ->where('detail_pemberian_obat.tgl_perawatan','=', $itemresep->tgl_perawatan)
                        ->where('detail_pemberian_obat.jam','=', $itemresep->jam)
                        ->where('detail_pemberian_obat.no_rawat','=', $itemresep->no_rawat)
                        ->orderBy('databarang.kode_brng','asc')
                        ->get();
                    $itemresep->detailberkasResep = $detailberkasResep;
                }
        }
        $cekBerkas = DB::connection('db_con2')->table('file_farmasi')
            ->select('jenis_berkas')
            ->where('no_rawat', $noRawat)
            ->get();

        return view('farmasi.view-berkas-sep-resep', [
            'noRawat'=>$noRawat,
            'cekBerkas'=>$cekBerkas,
            'noSep'=>$noSep,
            'jumlahData'=>$jumlahData,
            'getPasien'=>$getPasien,
            'getSEP'=>$getSEP,
            'berkasResep'=>$berkasResep,
        ]);
    }

    // UPLOAD BERKAS
    function UploadBerkasFarmasi(Request $request) {
        if ($request->hasFile('file_scan_farmasi')) {
            $file = $request->file('file_scan_farmasi');
            $no_rawatSTR = str_replace('/', '', $request->no_rawat);
            $path_file = 'FILE-SCAN-FARMASI' . '-' . $no_rawatSTR. '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put('file_scan_farmasi/' . $path_file, file_get_contents($file));
            $cekBerkas = DB::connection('db_con2')->table('file_farmasi')->where('no_rawat', $request->no_rawat)
                ->where('jenis_berkas', 'FILE-SCAN-FARMASI')
                ->exists();
            if (!$cekBerkas){
                DB::connection('db_con2')->table('file_farmasi')->insert([
                    'no_rkm_medis' => $request->no_rkm_medis,
                    'no_rawat' => $request->no_rawat,
                    'nama_pasein' => $request->nama_pasein,
                    'jenis_berkas' => 'FILE-SCAN-FARMASI',
                    'file' => $path_file,
                ]);
            }
           return back()->with('successupload', 'Berhasil Menyimpan Data & Mengunggah File');
        }
    }

    // DOWNLOAD BERKAS RESEP
    function DonwloadSEPResep(Request $request){
        $cekBerkas = DB::connection('db_con2')->table('file_farmasi')
            ->select('file')
            ->where('jenis_berkas', 'SEP-RESEP')
            ->where('no_rawat', $request->no_rawat)
            ->first();
        $filePath = public_path('storage/file_sepresep_farmasi/' . $cekBerkas->file);
        return Response::download($filePath, $cekBerkas->file);
    }
    // DOWNLOAD HASIL GABUNG
    function DonwloadHasilGabung(Request $request){
        $cekBerkas = DB::connection('db_con2')->table('file_farmasi')
            ->select('file')
            ->where('jenis_berkas', 'HASIL-FARMASI')
            ->where('no_rawat', $request->no_rawat)
            ->first();
        $filePath = public_path('hasil_farmasi_pdf/' . $cekBerkas->file);
        return Response::download($filePath, $cekBerkas->file);
    }



}