<!DOCTYPE html>
<html lang="ru">
<head>

<meta charset="UTF-8">

<title>Airport Call-Centre</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<h1>Airport Call-Centre</h1>

<div id="summary"></div>

<div id="lastUpdate"></div>

<h2>Операторы</h2>

<table id="operators">
    <thead>
        <tr>
           <th>Оператор</th>
           <th>Статус</th>
           <th>Ответил</th>
           <th>Не поднял трубку</th>
        </tr>
    </thead>

    <tbody></tbody>

</table>

<h2>Активные разговоры</h2>

<table id="activeCalls">

    <thead>
        <tr>
            <th>Оператор</th>
            <th>Абонент</th>
            <th>Длительность</th>
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