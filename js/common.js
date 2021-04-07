async function request(url, params = null) {
    return new Promise((resolve, reject) => {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        if (params !== null) {
            xhr.send(JSON.stringify(params))
        } else {
            xhr.send()
        }
        xhr.onload = function () {
            if (xhr.status === 200) {
                // return JSON.parse(xhr.response);
                resolve(JSON.parse(xhr.response))
            }
            resolve(false)
        }
    });
}