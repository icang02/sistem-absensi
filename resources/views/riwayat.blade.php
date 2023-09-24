@extends('templates.main')

@section('content')
    @forelse ($riwayat as $item)
        <div class="bg-white shadow mt-2 p-3 d-flex justify-content-between align-items-center">
            <div>
                @php
                    $dateString = $item->tanggal;
                    $carbonDate = Carbon\Carbon::parse($dateString);
                    $formattedDate = $carbonDate->format('l, d M Y');
                @endphp

                <h6>{{ $formattedDate }}</h6>
                <small class="d-block">
                    &diams; Waktu Absen /
                    <span class="fw-medium">{{ $item->waktu_absen }} WITA</span>
                </small>
            </div>

            @if ($item->keterangan == 'Tepat Waktu')
                <button class="btn btn-success badge py-3">{{ $item->keterangan }}</button>
            @elseif ($item->keterangan == 'Terlambat')
                <button class="btn btn-danger badge py-3">{{ $item->keterangan }}</button>
            @else
                <button class="btn btn-dark badge py-3">{{ $item->keterangan }}</button>
            @endif
        </div>
    @empty
        <div class="text-center text-muted mt-3">Belum ada riwayat absen</div>
    @endforelse
@endsection
