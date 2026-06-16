<h2>Pengajuan Peminjaman Ruangan</h2>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form
    action="{{ route('bookings.store') }}"
    method="POST">

    @csrf

    <label>Ruangan</label>
    <select name="room_id">

        @foreach($rooms as $room)

            <option value="{{ $room->id }}">
                {{ $room->room_name }}
            </option>

        @endforeach

    </select>

    <br><br>

    <label>Jenis Peminjaman</label>

    <select name="booking_type">

        <option value="perkuliahan">
            Perkuliahan
        </option>

        <option value="organisasi">
            Organisasi
        </option>

    </select>

    <br><br>

    <label>Tanggal</label>

    <input
        type="date"
        name="booking_date">

    <br><br>

    <label>Jam Mulai</label>

    <input
        type="time"
        name="start_time">

    <br><br>

    <label>Jam Selesai</label>

    <input
        type="time"
        name="end_time">

    <br><br>

    <label>Keperluan</label>

    <textarea
        name="purpose"></textarea>

    <br><br>

    <button type="submit">
        Ajukan Booking
    </button>

</form>