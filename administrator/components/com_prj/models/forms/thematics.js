'use strict';

let change_thematic = function (elem) {
    let projectID = elem.dataset.project;
    let thematicID = elem.dataset.thematic;
    let url = `index.php?option=com_prj&task=project_thematic.execute&projectID=${projectID}&thematicID=${thematicID}&format=json`;
    jQuery.getJSON(url, function (json) {
        alert((!json.data) ? 'Что-то пошло не так' : 'Изменения сохранены');
    })
};
