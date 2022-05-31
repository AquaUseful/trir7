import { call_api } from "./utils.js";

document.addEventListener('DOMContentLoaded', async () => {
    await update_login();
});

async function update_login() {
    let resp = await call_api('session', 'getlogin', {});
    if (resp['ok']) {
        document.getElementById('header_login').innerHTML = resp['content']['login']
    } else if (resp['err'] === 'nologin') {
        document.location.replace('index.html');
    }
}