import { call_api } from './utils.js'

document.addEventListener('DOMContentLoaded', async () => {
    await update_score();
    document.getElementById('btn_records').addEventListener('click', async () => {
        document.location.replace('records.html');
    });
    document.getElementById('btn_again').addEventListener('click', async () => {
        document.location.replace('game.html');
    });
});

async function update_score() {
    let score = (await call_api('game', 'getscore', {}))['content']['score'];
    document.getElementById('score_value').innerHTML = score;
}