import { call_api } from './utils.js';

document.addEventListener('DOMContentLoaded', async () => {
    await update_table();
});

async function update_table() {
    let text = (await call_api('page', 'get', { 'name': 'records' }))['content']['html'];
    document.getElementById('table_container').innerHTML = text;
}