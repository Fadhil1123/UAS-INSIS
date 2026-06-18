<h1>Daftar Ruangan</h1>

<a href="{{ route('admin.rooms.create') }}">
    Tambah Ruangan
</a>

<br><br>

<table border="1">

    <tr>
        <th>Kode</th>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Kapasitas</th>
        <th>Aksi</th>
    </tr>

    @foreach($rooms as $room)

    <tr>
        <td>{{ $room->room_code }}</td>
        <td>{{ $room->room_name }}</td>
        <td>{{ $room->category }}</td>
        <td>{{ $room->capacity }}</td>

        <td>

            <a href="{{ route('admin.rooms.edit', $room->id) }}">
                Edit
            </a>

            <form
                method="POST"
                action="{{ route('admin.rooms.destroy', $room->id) }}"
            >
                @csrf
                @method('DELETE')

                <button type="submit">
                    Hapus
                </button>
            </form>

        </td>
    </tr>

    @endforeach

</table>