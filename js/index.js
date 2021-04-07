document.addEventListener('DOMContentLoaded', load, false);
let jwt = null;

function load() {
    document.getElementById('loginForm').onsubmit = function (e) {
        e.preventDefault();
        login().then(loginResult).catch(showError);
    };
}

async function login() {
    let name = document.getElementById('name').value;
    let password = document.getElementById('password').value;
    if (name === '' || password === '') {
        return alert('Please provide a password and a name');
    }
    let data = {
        name: name,
        password: password,
    }
    return request(apiBaseUrl + '?action=login', data);
}

function loginResult(data) {
    if (typeof data.jwt !== 'undefined') {
        jwt = data.jwt;
        document.getElementsByTagName('body')[0].innerHTML = data.body;
        let jsRef = document.createElement("script");
        jsRef.setAttribute("src", data.jsScript);
        document.getElementsByTagName("head")[0].appendChild(jsRef);
    }
}

function showError() {
    alert('Error');
}