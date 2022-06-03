import { call_api } from './utils.js';

document.addEventListener('DOMContentLoaded', async () => {
    document.getElementById('btn_login').addEventListener('click', async () => {
        document.location.replace('login.html');
    });
    document.getElementById('btn_register').addEventListener('click', async () => {
        document.location.replace('register.html');
    });
});
