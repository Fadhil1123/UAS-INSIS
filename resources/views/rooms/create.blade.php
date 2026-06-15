<h1>Tambah Ruangan</h1>

<form
    method="POST"
    action="{{ route('rooms.store') }}"
>
    @csrf

    <input
        type="text"
        name="room_code"
        placeholder="Kode Ruangan">

    <br><br>

    <input
        type="text"
        name="room_name"
        placeholder="Nama Ruangan">

    <br><br>

    <select name="category">
        <option value="">-- Pilih Kategori --</option>
        <option value="kelas">Kelas</option>
        <option value="laboratorium">Laboratorium</option>
        <option value="aula">Aula</option>
    </select>

    <br><br>

    <input
        type="number"
        name="capacity"
        placeholder="Kapasitas">

    <br><br>

    <button type="submit">
        Simpan
    </button>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

</form>