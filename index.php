<!DOCTYPE html>
<html lang="ru">
<head>

<meta charset="UTF-8">

<title>FreePBX Queue Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<h1>FreePBX Queue Dashboard</h1>

<div id="summary"></div>

<h2>Операторы</h2>

<table id="operators">
    <thead>
        <tr>
           <th>Оператор</th>
           <th>Статус</th>
           <th>Ответил</th>
           <th>Не ответил</th>
        </tr>
    </thead>

    <tbody></tbody>

</table>

<h2>Последние вызовы</h2>

<table id="calls">

    <thead>
        <tr>
            <th>Время</th>
            <th>Номер</th>
            <th>Оператор</th>
            <th>Длительность</th>
            <th>Статус</th>
        </tr>
    </thead>

    <tbody></tbody>

</table>

<script src="script.js"></script>

</body>
</html>