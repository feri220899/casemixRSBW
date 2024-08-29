<div>
    <div class="card-header">
        <form wire:submit.prevent="getDataKhanza">
            <div class="row">
                <div class="col-lg-3">
                    <div class="input-group">
                        <input class="form-control form-control-sidebar form-control-sm" type="text" aria-label="Search"
                            placeholder="Cari Sep / Rm / No.Rawat" wire:model.defer="carinomor">
                    </div>
                </div>
                <div class="col-lg-2">
                    <input type="date" class="form-control form-control-sidebar form-control-sm"
                        wire:model.defer="tanggal1">
                </div>
                <div class="col-lg-2">
                    <input type="date" class="form-control form-control-sidebar form-control-sm"
                        wire:model.defer="tanggal2">
                </div>
                <div class="col-lg-2">
                    <div class="input-group">
                        <select class="form-control form-control-sidebar form-control-sm"
                            wire:model.lazy="status_lanjut">
                            <option value="Ranap">Ranap</option>
                            <option value="Ralan">Ralan</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-sidebar btn-primary btn-sm" wire:click="render()">
                                <i class="fas fa-search fa-fw"></i>
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"
                                    wire:loading wire:target="getDataKhanza"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 text-right">
                    @if (session()->has('response200'))
                        <span class="text-success"><i class="icon fas fa-check"> </i>
                            {{ session('response200') }} </span>
                    @endif
                    @if (session()->has('response500'))
                        <span class="text-danger"><i class="icon fas fa-ban"> </i> {{ session('response500') }}
                        </span>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <div class="card-body table-responsive p-0" style="height: 450px;">
        <table class="table text-nowrap table-sm table-bordered table-hover table-head-fixed p-3 text-sm"
            style="white-space: nowrap;">
            <thead>
                <tr>
                    <th>No. Order</th>
                    <th>No. Rawat</th>
                    <th>Pasien</th>
                    <th>Tanggal Lahir</th>
                    <th>NIP</th>
                    <th>Penjab</th>
                    <th>Dokter</th>
                    <th>dr_perujuk</th>
                    <th>Poli</th>
                    <th>Tgl Permintaan</th>
                    <th>Act</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($getDatakhanza as $key => $data)
                    <tr>
                        <td>
                            <div class="d-flex justify-content-between">
                                {{ $data->noorder }} &nbsp;
                                <div class="badge-group-sm float-right">
                                    <a data-toggle="dropdown" href="#"><i class="fas fa-eye"></i></a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                        @foreach ($data->Permintaan as $item)
                                            <div class="dropdown-item">
                                                {{ $item->nm_perawatan }} - ( {{ $item->kd_jenis_prw }})
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $data->no_rawat }}</td>
                        <td>{{ $data->nm_pasien }} - {{ $data->no_rkm_medis }} - ({{ $data->jk }})</td>
                        <td>{{ $data->tgl_lahir }}</td>
                        <td>{{ $data->nip }}</td>
                        <td>{{ $data->png_jawab }}</td>
                        <td>{{ $data->nm_dokter }}</td>
                        <td>{{ $data->dr_perujuk }}</td>
                        <td>{{ $data->nm_poli }}</td>
                        <td>{{ $data->tgl_permintaan }}</td>
                        <td>
                            <button id="dropdownSubMenu1{{ $key }}" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"
                                class="btn btn-default btn-sm dropdown-toggle dropdown dropdown-hover py-0"></button>
                            <div>
                                <ul aria-labelledby="dropdownSubMenu1{{ $key }}"
                                    class="dropdown-menu border-0 shadow">
                                    <li><button class="dropdown-item" data-toggle="modal"
                                            data-target="#KirimDataLIS{{ $key }}">Kirim ke SOFTMEDIX</a>
                                    </li>
                                    <li><button class="dropdown-item" wire:click="getDataLIS('{{ $data->noorder }}')"
                                            data-toggle="modal" data-target="#DetailDataLIS{{ $key }}">Tarik
                                            Data Sotfmedix</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @foreach ($getDatakhanza as $key => $item)
            {{-- MODAL KIRIM --}}
            <div class="modal fade" id="KirimDataLIS{{ $key }}" tabindex="-1" role="dialog"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Order Data SOFTMEDIX LIS
                                <b>{{ $data->nm_pasien }}</b>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Action
                                        </label>
                                        <select class="form-control"
                                            wire:model.defer="getDatakhanza.{{ $key }}.order_control">
                                            <option value="D">Delete</option>
                                            <option value="U">Update</option>
                                            <option value="N">Baru</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Cito
                                        </label>
                                        <select class="form-control"
                                            wire:model.defer="getDatakhanza.{{ $key }}.cito">
                                            <option value="Y">Cito</option>
                                            <option value="N">Tidak Cito</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Sembunyikan Nama
                                        </label>
                                        <select class="form-control"
                                            wire:model.defer="getDatakhanza.{{ $key }}.med_legal">
                                            <option value="Y">Ya</option>
                                            <option value="N">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Catan 1
                                        </label>
                                        <textarea type="text" class="form-control" wire:model.defer="getDatakhanza.{{ $key }}.reserve1"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Catan 2
                                        </label>
                                        <textarea type="text" class="form-control" wire:model.defer="getDatakhanza.{{ $key }}.reserve2"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Catan 3
                                        </label>
                                        <textarea type="text" class="form-control" wire:model.defer="getDatakhanza.{{ $key }}.reserve3"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Catan 4
                                        </label>
                                        <textarea type="text" class="form-control" wire:model.defer="getDatakhanza.{{ $key }}.reserve4"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-primary"
                                wire:click="sendDataToLIS('{{ $key }}')" data-dismiss="modal">Kirim</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- MODAL DETAIL --}}
            <div class="modal fade" id="DetailDataLIS{{ $key }}" tabindex="-1" role="dialog"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Detail Data LIS
                            </h6>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="card py-3  d-flex justify-content-center align-items-center">
                                    @if ($detailDataLis)
                                        @if ($detailDataLis['response']['code'] == '200')
                                            <table border="0px" width="1000px">
                                                <tr>
                                                    <td rowspan="4"> <img
                                                            src="data:image/png;base64,{{ base64_encode($Setting['logo']) }}"
                                                            alt="Girl in a jacket" width="80" height="80">
                                                    </td>
                                                    <td class="text-center">
                                                        <h4>{{ $Setting['nama_instansi'] }} </h4>
                                                    </td>
                                                    <td rowspan="4" class="px-4">
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>{{ $Setting['alamat_instansi'] }} ,
                                                        {{ $Setting['kabupaten'] }},
                                                        {{ $Setting['propinsi'] }}
                                                        {{ $Setting['kontak'] }}</td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td> E-mail : {{ $Setting['email'] }}</td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td colspan="">
                                                        <h5 class="mt-2">HASIL PEMERIKSAAN LABORATORIUM </h5>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table border="0px" width="1000px">
                                                <tr style="vertical-align: top;">
                                                    <td width="130px">No.RM</td>
                                                    <td width="300px">:
                                                        {{ $detailDataLis['response']['sampel']['pid'] }}</td>
                                                    <td width="130px">No.Rawat </td>
                                                    <td width="200px">: </td>
                                                </tr>
                                                <tr style="vertical-align: top;">
                                                    <td width="130px">Nama Pasien</td>
                                                    <td width="300px">:
                                                        {{ $detailDataLis['response']['sampel']['pname'] }}</td>

                                                    <td width="130px">Tgl. Periksa </td>
                                                    <td width="200px">:
                                                        {{ date('d-m-Y', strtotime($detailDataLis['response']['sampel']['order_lab'])) }}
                                                    </td>
                                                </tr>
                                                <tr style="vertical-align: top;">
                                                    <td width="130px">JK/Umur </td>
                                                    <td width="300px">:
                                                        {{ $detailDataLis['response']['sampel']['sex'] }} /
                                                        {{ $detailDataLis['response']['sampel']['birth_dt'] }}
                                                    </td>

                                                    <td width="130px">Jam Periksa </td>
                                                    <td width="200px">: </td>
                                                    </td>
                                                </tr>

                                                <tr style="vertical-align: top;">
                                                    <td width="130px">Alamat </td>
                                                    <td width="300px">:</td>
                                                    <td width="130px">Kamar/Poli </td>
                                                    <td width="200px">:
                                                        {{ $detailDataLis['response']['sampel']['bangsal_name'] }}</td>
                                                </tr>
                                                <tr style="vertical-align: top;">
                                                    <td width="130px"> Dokter Pengirim </td>
                                                    <td width="300px">:
                                                        {{ $detailDataLis['response']['sampel']['clinician_name'] }}
                                                    </td>
                                                    <td width="130px"> </td>
                                                    <td width="200px"></td>
                                                </tr>
                                            </table>
                                            <table border="1px" width="1000px" class="mt-2">
                                                <tr>
                                                    <th>test_id</th>
                                                    <th>nama_test</th>
                                                    <th>id_template</th>
                                                    <th>jenis_hasil</th>
                                                    <th>hasil</th>
                                                    <th>satuan</th>
                                                    <th>nilai_normal</th>
                                                    <th>flag</th>
                                                    <th>kode_paket</th>
                                                    <th>reserve4</th>
                                                </tr>
                                                @php
                                                    $uniqueTests = [];
                                                @endphp

                                                @foreach ($detailDataLis['response']['sampel']['result_test'] as $item)
                                                    @if (!in_array($item['nama_test'], $uniqueTests) && $item['test_id'] == $item['id_template'])
                                                    <tr>
                                                            <td>{{ $item['test_id'] }}</td>
                                                            <td>{{ $item['nama_test'] }}</td>
                                                            <td>{{ $item['id_template'] }}</td>
                                                            <td>{{ $item['jenis_hasil'] }}</td>
                                                            <td>{{ $item['hasil'] }}</td>
                                                            <td>{{ $item['satuan'] }}</td>
                                                            <td>{{ $item['nilai_normal'] }}</td>
                                                            <td>{{ $item['flag'] }}</td>
                                                            <td>{{ $item['kode_paket'] }}</td>
                                                            <td>{{ $item['reserve4'] }}</td>
                                                        </tr>
                                                        @php
                                                            $uniqueTests[] = $item['nama_test'];
                                                        @endphp
                                                    @endif
                                                @endforeach

                                            </table>

                                            <button type="button" name="" id="" class="btn btn-primary" btn-lg btn-block" wire:click='getTestLAB("{{$key}}")'>getTestLab</button>

                                            <table border="0px" width="1000px" class="mt-2">
                                                <tr>
                                                    <td class="text-xs"><b>Catatan :</b> Jika ada keragu-raguan
                                                        pemeriksaan,
                                                        diharapkan
                                                        segera menghubungi laboratorium</td>
                                                </tr>
                                            </table>
                                            <table border="0px" width="1000px">
                                                <tr>
                                                    <td width="250px" class="text-center">
                                                        Penanggung Jawab
                                                        <div class="barcode mt-1">
                                                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG('Dikeluarkan di ' . $Setting['nama_instansi'] . ', Kabupaten/Kota ' . $Setting['kabupaten'] . ' Ditandatangani secara elektronik oleh ' . $detailDataLis['response']['sampel']['clinician_name'] . ' ID ' . 'Kode Dokter' . ' ' . $detailDataLis['response']['sampel']['order_lab'], 'QRCODE') }}"
                                                                alt="barcode" width="80px" height="75px" />
                                                        </div>
                                                        {{-- {{ $periksa->nm_dokter }} --}}
                                                    </td>
                                                    <td width="150px"></td>
                                                    <td width="250px" class="text-center">
                                                        Hasil :
                                                        {{ date('d-m-Y', strtotime($detailDataLis['response']['sampel']['order_lab'])) }}
                                                        <br>
                                                        Petugas Laboratorium
                                                        <div class="barcode mt-1">
                                                            <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG('Dikeluarkan di ' . $Setting['nama_instansi'] . ', Kabupaten/Kota ' . $Setting['kabupaten'] . ' Ditandatangani secara elektronik oleh ' . $detailDataLis['response']['sampel']['acc_by'] . ' ID ' . 'PETUGAS' . ' ' . $detailDataLis['response']['sampel']['order_lab'], 'QRCODE') }}"
                                                                alt="barcode" width="80px" height="75px" />
                                                        </div>
                                                        {{ $detailDataLis['response']['sampel']['acc_by'] }}
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif
                                    @endif
                                    {{-- @if ($detailDataLis)
                                        {{$Setting['nama_instansi']}}
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>test_id</th>
                                                        <th>nama_test</th>
                                                        <th>jenis_hasil</th>
                                                        <th>hasil</th>
                                                        <th>satuan</th>
                                                        <th>nilai_normal</th>
                                                        <th>flag</th>
                                                        <th>kode_paket</th>
                                                        <th>reserve4</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($detailDataLis['response']['code'])
                                                        {{ $detailDataLis['response']['sampel']['pid'] }}
                                                        {{ $detailDataLis['response']['sampel']['pname'] }}
                                                        {{ $detailDataLis['response']['sampel']['sex'] }}
                                                        {{ $detailDataLis['response']['sampel']['birth_dt'] }}
                                                        {{ $detailDataLis['response']['sampel']['clinician_name'] }}
                                                        {{ $detailDataLis['response']['sampel']['bangsal_name'] }}
                                                        {{ $detailDataLis['response']['sampel']['order_lab'] }}
                                                        {{ $detailDataLis['response']['sampel']['reg_no'] }}
                                                        {{ $detailDataLis['response']['sampel']['lis_sampel'] }}
                                                        {{ $detailDataLis['response']['sampel']['acc_by'] }}
                                                        {{ $detailDataLis['response']['sampel']['acc_date'] }}
                                                        {{ $detailDataLis['response']['sampel']['reserve1'] }}
                                                        {{ $detailDataLis['response']['sampel']['reserve2'] }}
                                                        {{ $detailDataLis['response']['sampel']['reserve3'] }}
                                                        @foreach ($detailDataLis['response']['sampel']['result_test'] as $item)
                                                            <tr>
                                                                <td>{{ $item['test_id'] }}</td>
                                                                <td>{{ $item['nama_test'] }}</td>
                                                                <td>{{ $item['jenis_hasil'] }}</td>
                                                                <td>{{ $item['hasil'] }}</td>
                                                                <td>{{ $item['satuan'] }}</td>
                                                                <td>{{ $item['nilai_normal'] }}</td>
                                                                <td>{{ $item['flag'] }}</td>
                                                                <td>{{ $item['kode_paket'] }}</td>
                                                                <td>{{ $item['reserve4'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>