'use strict';

let change_thematic = function (elem) {
    let projectID = elem.dataset.project;
    let thematicID = elem.dataset.thematic;
    let url = `index.php?option=com_prj&task=project_thematic.execute&projectID=${projectID}&thematicID=${thematicID}&format=json`;
    jQuery.getJSON(url, function (json) {
        if (!json.data) alert('Что-то пошло не так'); else sendNotification('Успешно', {
            body: 'Изменения сохранены',
        });
    })
};

function sendNotification(title, options) {
    if (!("Notification" in window)) {
        alert('Ваш браузер не поддерживает HTML Notifications, его необходимо обновить.');
    }
    else if (Notification.permission === "granted") {
        let notification = new Notification(title, options);
        function clickFunc() {
            alert('Кликать сюда абсолютно необязательно :)');
        }
        notification.onclick = clickFunc;
    }

    else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function (permission) {
            if (permission === "granted") {
                let notification = new Notification(title, options);
            } else {
                alert('Вы запретили показывать уведомления');
            }
        });
    } else {
        // Пользователь ранее отклонил наш запрос на показ уведомлений
        // В этом месте мы можем, но не будем его беспокоить. Уважайте решения своих пользователей.
    }
}