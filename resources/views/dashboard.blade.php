<h1>Dashboard User</h1>

<p>
    Selamat datang,
    {{ auth()->user()->name }}
</p>

<form action="/logout" method="POST">
    @csrf

    <button type="submit">
        Logout
    </button>
</form>