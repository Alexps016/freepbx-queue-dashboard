function formatDuration(seconds) {

    seconds = parseInt(seconds);

    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;

    if (h > 0) {
        return `${h}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }

    return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
}

async function loadDashboard() {

    const response = await fetch('api.php');
    const data = await response.json();

    document.getElementById("lastUpdate").textContent =
    "Последнее обновление: " + new Date().toLocaleTimeString("ru-RU");



    // Общая статистика
 document.getElementById('summary').innerHTML = `

<div class="cards">

    <div class="card total">
        <div class="title">📞 Всего вызовов</div>
        <div class="value">${data.summary.total}</div>
    </div>

    <div class="card answered">
        <div class="title">🟢 Ответили</div>
        <div class="value">${data.summary.answered}</div>
    </div>

    <div class="card missed">
        <div class="title">🔴 Не ответили</div>
        <div class="value">${data.summary.missed}</div>
    </div>

</div>

`;

// Операторы
let operators = "";

for (const ext in data.operators) {

    let rowClass = "";

    switch (data.operators[ext].status.color) {

        case "green":
            rowClass = "operator-idle";
            break;

        case "red":
            rowClass = "operator-busy";
            break;

        case "gray":
            rowClass = "operator-offline";
            break;
    }

    operators += `
    <tr class="${rowClass}">
        <td><b>${ext}</b></td>
        <td>${data.operators[ext].status.text}</td>
        <td>${data.operators[ext].answered}</td>
        <td>${data.operators[ext].missed}</td>
    </tr>`;
}

document.querySelector("#operators tbody").innerHTML = operators;

let activeCalls = "";

if (data.active_calls.length === 0) {

    activeCalls = `
    <tr>
        <td colspan="3" style="text-align:center;">
            Нет активных разговоров
        </td>
    </tr>`;

} else {

    data.active_calls.forEach(call => {

        activeCalls += `
        <tr>
            <td><b>${call.operator}</b></td>
            <td>${call.caller}</td>
            <td>${formatDuration(call.duration)}</td>
        </tr>`;

    });

}

document.querySelector("#activeCalls tbody").innerHTML = activeCalls;



    // Последние вызовы
    let calls = "";

function getCallStatus(status) {

    switch (status) {

        case 'ANSWERED':
            return '🟢 Ответ';

        case 'ABANDON':
            return '🔴 Потерян';

        case 'EXITWITHKEY':
            return '🟡 Отменён';

        case 'TIMEOUT':
            return '⚫ Таймаут';

        default:
            return status;
    }

}

    data.calls.forEach(call => {

        calls += `
        <tr>
            <td>${call.time}</td>
            <td>${call.caller}</td>
            <td>${call.operator}</td>
            <td>${formatDuration(call.duration)}</td>
            <td>${getCallStatus(call.status)}</td>
        </tr>`;

    });

    document.querySelector("#calls tbody").innerHTML = calls;

}

loadDashboard();

// Обновление каждые 5 секунд
setInterval(loadDashboard, 5000);