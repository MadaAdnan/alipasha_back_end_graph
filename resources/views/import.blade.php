<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <select name="type" required>
        <option value="">حدد</option>
        <option value="users">Users</option>
        <option value="cities">cities</option>
        <option value="products">products</option>
        <option value="colors">COLORS</option>
        <option value="media">Media</option>
        <option value="seens">Seens</option>
        <option value="service">service</option>
        <option value="phone">Phone</option>
    </select>
    <button type="submit">استيراد</button>
</form>
</body>
</html>

