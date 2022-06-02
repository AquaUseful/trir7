import { call_api } from "./utils.js";

document.addEventListener('DOMContentLoaded', async () => {
    await update_login();
    await set_logout_btn();
});

async function update_login() {
    let resp = await call_api('session', 'getlogin', {});
    if (resp['ok']) {
        document.getElementById('header_login').innerHTML = resp['content']['login']
    } else if (resp['err'] === 'nologin') {
        document.location.replace('index.html');
    }
}

async function set_logout_btn() {
    document.getElementById('header_logout_btn').addEventListener('click', async () => {
        await call_api('session', 'logout', {});
        document.location.replace('index.html');
    });
}
