<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Informasi Kamar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-[#f9fafb] py-6 font-sans">
    <!-- Wrapper agar konten tidak tertutup header -->
    <div class="w-full px-2 sm:px-4 md:px-6 pt-28">
        <!-- Header -->
        <div class="w-full bg-white shadow-md p-4 flex justify-between items-center border-b fixed top-0 left-0 z-10">
            <div class="flex items-center">
                <img alt="Logo Rumah Sakit" class="h-20" src="{{ asset('img/bw2.png') }}" />
            </div>
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-800">INFORMASI KAMAR INAP</h1>
            </div>
            <div class="flex items-center">
                <img alt="Logo BPJS" class="h-10" src="{{ asset('img/bpjs.png') }}" />
            </div>
        </div>

        <!-- Konten Bangsal -->
        <div class="max-h-[calc(100vh-10rem)] overflow-y-auto">
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 px-1">
                @foreach ($results as $kd_bangsal => $items)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden">
                        @php
                            $total = count($items);
                            $terisi = collect($items)->where('status', 'Terisi')->count();
                            $kosong = $total - $terisi; // atau: collect($items)->where('status', 'Kosong')->count();
                            $groupedByKelas = collect($items)->groupBy('kelas');
                        @endphp

                        <!-- Header Bangsal -->
                        <div
                            class="bg-gradient-to-r from-indigo-500 to-blue-600 text-white text-sm font-semibold py-2 px-3 flex justify-between items-center">
                            <span>{{ $items[0]->nm_bangsal ?? 'Bangsal' }}</span>
                            <span class="text-white text-xs font-normal text-right leading-tight">
                                Total: {{ $total }} kamar<br>
                                Terisi: {{ $terisi }} | Kosong: {{ $kosong }}
                            </span>
                        </div>

                        <!-- Daftar kamar -->
                        <div class="p-3 space-y-4 max-h-[500px] overflow-y-auto">
                            @foreach ($groupedByKelas as $kelas => $kamars)
                                <div>
                                    <!-- Nama Kelas -->
                                    <div class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-300 pb-1">
                                        {{ $kelas }}
                                    </div>

                                    <!-- Grid kamar per kelas -->
                                    <div class="grid gap-2 grid-cols-2">
                                        @foreach ($kamars as $item)
                                            @php
                                                $status = strtolower($item->status ?? '');
                                                $jk = strtoupper($item->jk ?? '');
                                                $bgColor = match (true) {
                                                    $status === 'kosong'
                                                        => 'bg-green-100 text-green-800 border-green-300',
                                                    $status === 'terisi' && $jk === 'L'
                                                        => 'bg-blue-100 text-blue-800 border-blue-300', // ← biru muda
                                                    $status === 'terisi' && $jk === 'P'
                                                        => 'bg-pink-100 text-pink-800 border-pink-300',
                                                    default => 'bg-gray-100 text-gray-800 border-gray-300',
                                                };
                                            @endphp

                                            <div
                                                class="border rounded-md {{ $bgColor }} text-xs font-medium p-2 flex flex-col text-center h-full">
                                                <!-- Kode kamar -->
                                                <div
                                                    class="text-sm font-bold break-words whitespace-normal w-full px-1">
                                                    {{ $item->kd_kamar }}
                                                </div>

                                                <!-- Status kamar -->
                                                <div
                                                    class="text-[10px] font-semibold uppercase tracking-wide mt-1 {{ $item->status === 'Kosong' ? 'text-green-700' : 'text-red-700' }}">
                                                    {{ $item->status }}
                                                </div>

                                                <!-- Info pasien jika terisi -->
                                                @if ($item->status === 'Terisi')
                                                    <div class="text-[10px] text-left leading-tight space-y-[2px] mt-1">
                                                        <div class="font-semibold text-gray-900 truncate">
                                                            👤 {{ $item->nm_pasien ?? '-' }}
                                                        </div>
                                                        <div class="text-gray-700 truncate">
                                                            🧍 {{ $item->umurdaftar ?? '?' }}
                                                            {{ $item->sttsumur ?? '' }}, {{ $item->jk ?? '?' }}
                                                        </div>
                                                        <div class="text-gray-500 italic text-[9px] line-clamp-3">
                                                            💉 {{ $item->diagnosa_awal ?? '-' }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>
