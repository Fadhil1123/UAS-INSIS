<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login SIMARI</h2>

@if($errors->any())
    <p>{{ $errors->first() }}</p>
@endif

<form method="POST" action="/login">
    @csrf

    <input
        type="text"
        name="nomor_induk"
        placeholder="NIM / NIP">

    <br><br>

    <input
        type="password"
        name="password"
        placeholder="Password">

    <br><br>

    <button type="submit">
        Login
    </button>

</form>

</body>
</html>