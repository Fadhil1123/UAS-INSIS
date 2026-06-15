<h1>Edit Ruangan</h1>

<form
    method="POST"
    action="{{ route('rooms.update', $room->id) }}"
>
    @csrf
    @method('PUT')

    <input
        type="text"
        name="room_code"
        value="{{ $room->room_code }}">

    <br><br>

    <input
        type="text"
        name="room_name"
        value="{{ $room->room_name }}">

    <br><br>

    <select name="category">

        <option value="kelas"
            {{ $room->category == 'kelas' ? 'selected' : '' }}>
            Kelas
        </option>

        <option value="laboratorium"
            {{ $room->category == 'laboratorium' ? 'selected' : '' }}>
            Laboratorium
        </option>

        <option value="aula"
            {{ $room->category == 'aula' ? 'selected' : '' }}>
            Aula
        </option>

    </select>

    <br><br>

    <br><br>

    <input
        type="number"
        name="capacity"
        value="{{ $room->capacity }}">

    <br><br>

    <button type="submit">
        Update
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