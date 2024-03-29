<div>
    <section class="content ">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group input-group-xs">
                        <input type="text" class="form-control" placeholder="Cari Nama, No.RM / No.Rawat"
                            wire:model.defer="cari_nomor">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group input-group-xs">
                        <select class="form-control" wire:model.defer="status_lanjut">
                            <option value="">Semua Status Lanjut</option>
                            <option value="Ranap">Rawat Inap</option>
                            <option value="Ralan">Rawat Jalan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group input-group-xs">
                        <select class="form-control" wire:model.defer="jenis_berkas">
                            <option value="">Semua Jenis Berkas</option>
                            <option value="SCAN">Berkas Scan</option>
                            <option value="INACBG">Berkas Inacbg</option>
                            <option value="RESUMEDLL">Berkas DB Khanza</option>
                            <option value="HASIL">Berkas Gabungan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group input-group-xs">
                        <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}"
                            wire:model.defer="tgl1">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group input-group-xs">
                        <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}"
                            wire:model.defer="tgl2">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="input-group input-group-xs">
                        <div class="input-group-append">
                            <button type="submit" wire:click="render" class="btn btn-md btn-primary">
                                <span>
                                    <span wire:loading.remove wire:target='render'>
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <span wire:loading wire:target='render'>
                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                            aria-hidden="true"></span> Mencari...
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No. Rkm Medis</th>
                <th>No. Rawat</th>
                <th>Nama Pasien
                    {{ $status_lanjut == 'Ralan' ? 'Rawat Jalan' : ($status_lanjut == 'Ranap' ? 'Rawat Inap' : '') }}
                </th>
                <th class="text-center">Jenis Berkas</th>
                <th class="text-center">Act</th>
            </tr>
        </thead>
        <tbody>
            @if ($getBerkasPasien->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">Data Tidak Ada / Silahkan Cari Data</td>
                </tr>
            @else
                @foreach ($getBerkasPasien as $key => $item)
                    @php
                        switch ($item->jenis_berkas) {
                            case 'RESUMEDLL':
                                $folder = 'storage/resume_dll/';
                                $nama_file = 'Berkas Export Khanza';
                                break;
                            case 'INACBG':
                                $folder = 'storage/file_inacbg/';
                                $nama_file = 'Berkas Inacbg';
                                break;
                            case 'SCAN':
                                $folder = 'storage/file_scan/';
                                $nama_file = 'Berkas Scan';
                                break;
                            case 'HASIL':
                                $folder = 'hasil_pdf/';
                                $nama_file = 'Gabungan';
                                break;
                            default:
                                $folder = env();
                                $nama_file = '';
                                break;
                        }
                    @endphp
                    <tr>
                        <td>{{ $item->no_rkm_medis }}</td>
                        <td>{{ $item->no_rawat }}</td>
                        <td>{{ $item->nm_pasien }}</td>
                        <td class="text-center">{{ $nama_file }}</td>
                        <td class="text-center">
                            <a href="{{ url($folder . $item->file) }}" download class="text-success">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
