adminLoad();

function adminLoad() {
    listServers().then(listServersSuccess).catch(showError);
    document.getElementById('addServer').onclick = function (e) {
        addServer().then(addServerSuccess).catch(showError);
    };
}

function addEventListenerForServerList() {
    let deleteServerSpan = document.getElementsByClassName('deleteServer');
    for (let i in deleteServerSpan) {
        deleteServerSpan[i].onclick = function (e) {
            deleteServer(this).then(deleteServerSuccess).catch(showError);
        };
    }
    let renameServerSpan = document.getElementsByClassName('renameServer');
    for (let i in renameServerSpan) {
        renameServerSpan[i].onclick = function (e) {
            renameServer(this).then(renameServerSuccess).catch(showError);
        };
    }
}

function listServers() {
    let data = {
        jwt: jwt,
    }
    return request(apiBaseUrl + '?action=listServers', data);
}

function addServer() {
    let name = prompt('Server name ?');
    if (name === null) {
        return;
    }
    if (name === '') {
        return alert('Please provide a name');
    }
    let ip = prompt('Server IP ? (IPv6 not supported)');
    if (ip === null) {
        return;
    }
    if (ip === '') {
        return alert('Please provide an ip');
    }
    let data = {
        jwt: jwt,
        name: name,
        ip: ip,
    }
    return request(apiBaseUrl + '?action=addServer', data);
}

function listServersSuccess(data) {
    let DOMList = document.getElementById('listServers');
    for (let i in data['list']) {
        DOMList.innerHTML += '<li ' +
            'data-id="' + data['list'][i]['id'] + '" ' +
            'data-ip="' + data['list'][i]['ip'] + '" ' +
            'data-name="' + data['list'][i]['name'] + '" ' +
            '>'
            + data['list'][i]['name'] + ' ' + data['list'][i]['ip']
            + ' <span class="renameServer">Rename</span> <span class="deleteServer">Delete</span> '
            + '</li>';
    }
    addEventListenerForServerList()
}

function addServerSuccess() {
    alert('Server successfully added, refresh the page to see it');
}

function deleteServerSuccess() {
    alert('Server successfully deleted, refresh the page to see it');
}

function renameServerSuccess() {
    alert('Server successfully renamed, refresh the page to see it');
}

function deleteServer(clickedElem) {
    if(!confirm('Are you sure to delete this server?')) {
        return;
    }
    let data = {
        jwt: jwt,
        id: clickedElem.parentElement.getAttribute('data-id'),
    }
    return request(apiBaseUrl + '?action=deleteServer', data);
}

function renameServer(clickedElem) {
    let oldName = clickedElem.parentElement.getAttribute('data-name');
    let newName = prompt('New server name?', oldName);
    if(newName === oldName || newName === null || newName === '') {
        return;
    }
    let data = {
        jwt: jwt,
        id: clickedElem.parentElement.getAttribute('data-id'),
        name: newName,
    }
    return request(apiBaseUrl + '?action=renameServer', data);
}