<!DOCTYPE html>
<html>
<head>
    <title>Kalender Akademik</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #8B1538; color: white; }
        h2 { text-align: center; color: #8B1538; }
        .meta { margin-bottom: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <h2>Kalender Akademik STIH Adhyaksa</h2>
    <div class="meta">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 30%">Kegiatan</th>
                <th style="width: 15%">Tipe</th>
                <th style="width: 25%">Tanggal</th>
                <th style="width: 25%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $index => $event)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $event->title }}</td>
                <td>
                    @switch($event->event_type)
                        @case('perkuliahan') Perkuliahan @break
                        @case('krs') KRS @break
                        @case('krs_perubahan') KRS Perubahan @break
                        @case('uts') UTS @break
                        @case('uas') UAS @break
                        @case('libur_akademik') Libur @break
                        @default Lainnya
                    @endswitch
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}
                    @if($event->end_date && $event->end_date != $event->start_date)
                        s/d {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d M Y') }}
                    @endif
                </td>
                <td>{{ $event->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
