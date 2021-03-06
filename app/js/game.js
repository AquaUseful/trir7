import { call_api, hide } from './utils.js'

document.addEventListener('DOMContentLoaded', async () => {
    await init_game();
});

/**
 * 
 * @param {HTMLElement} element 
 * @param {HTMLElement} limiter 
 * @param {Promise} drop_cb
 */
async function make_draggable(element, limiter, drop_cb) {
    let element_offset = { x: undefined, y: undefined };
    let new_pos = { x: undefined, y: undefined };

    if (limiter === undefined) {
        limiter = document.body;
    }
    element.addEventListener('mousedown', on_mouse_down);
    /**
     * 
     * @param {Event} ev 
     */
    async function on_mouse_down(ev) {
        ev = ev || window.event;
        ev.preventDefault();
        element_offset = {
            x: ev.layerX,
            y: ev.layerY
        };
        document.addEventListener('mouseup', on_mouse_up);
        document.addEventListener('mousemove', on_mouse_drag);
    }
    /**
     * 
     * @param {Event} ev 
     */
    async function on_mouse_drag(ev) {
        ev = ev || window.event;
        ev.preventDefault();

        let limiter_relative = {
            x: ev.clientX - limiter.offsetLeft,
            y: ev.clientY - limiter.offsetTop
        };

        new_pos = {
            x: limiter_relative.x - element_offset.x,
            y: limiter_relative.y - element_offset.y
        };

        if (new_pos.x < 0) {
            new_pos.x = 0;
        } else if (new_pos.x > (limiter.offsetWidth - element.offsetWidth)) {
            new_pos.x = limiter.offsetWidth - element.offsetWidth;
        }
        if (new_pos.y < 0) {
            new_pos.y = 0;
        } else if (new_pos.y > (limiter.offsetHeight - element.offsetHeight)) {
            new_pos.y = limiter.offsetHeight - element.offsetHeight;
        }

        element.style.left = (new_pos.x * 100 / limiter.offsetWidth) + "%";
        element.style.top = (new_pos.y * 100 / limiter.offsetHeight) + "%";
    }
    /**
     * 
     * @param {Event} ev 
     */
    async function on_mouse_up(ev) {
        document.removeEventListener('mouseup', on_mouse_up);
        document.removeEventListener('mousemove', on_mouse_drag);
        if (drop_cb !== undefined) {
            await drop_cb(element);
        }
    }

}

/**
 * 
 * @param {HTMLElement} element 
 */
async function drop_cb(element) {
    let area = document.getElementById("game_area");
    let container = document.getElementById("game_container");

    let frac_pos = { x: element.offsetLeft / area.offsetWidth, y: element.offsetTop / area.offsetHeight };

    let resp = await call_api('game', 'move', { id: +element.id, pos: [frac_pos.x, frac_pos.y] });

    if (resp['content']['win']) {
        await get_balls();
        await update_score();
        await update_target();
    }
}

async function init_game() {
    let area = document.getElementById("game_area");
    let container = document.getElementById("game_container");
    let secret_ball = document.getElementById('secret_ball');
    let container_desc = {
        'pos': [container.offsetLeft / area.offsetWidth, container.offsetTop / area.offsetHeight],
        'size': [container.offsetWidth / area.offsetWidth, container.offsetHeight / area.offsetHeight]
    };
    let ball_size = [secret_ball.offsetWidth / area.offsetWidth, secret_ball.offsetHeight / area.offsetHeight];
    await hide(secret_ball);

    //document.getElementById('endgame_btn').addEventListener('click', async () => { document.location.replace('endgame.html') });

    let resp = await call_api('game', 'restart', { 'container': container_desc, 'ball_size': ball_size });
    await init_game_timer();
    await get_balls();

    let lives = (await call_api('game', 'getlives', {}))['content']['lives'];
    await update_lives(lives);
    await update_timer();
    await update_target();
}

async function get_balls() {
    let balls = (await call_api('game', 'getballs', {}))['content'];
    let container = document.getElementById('balls_container');
    container.innerHTML = '';
    for (let [i, ball] of Object.entries(balls)) {
        let b_el = document.createElement('div');
        b_el.classList.add('ball');
        b_el.id = i;
        b_el.style.left = (ball['pos'][0] * 100) + '%';
        b_el.style.top = (ball['pos'][1] * 100) + '%';
        b_el.innerHTML = ball['val'].join('+');
        make_draggable(b_el, document.getElementById("game_area"), drop_cb);
        container.appendChild(b_el);
    }
}

async function init_game_timer() {
    setInterval(update_timer, 1000);
}

async function timout() {
    let lives = (await call_api('game', 'getlives', {}))['content']['lives'];
    if (lives <= 0) {
        document.location.replace('endgame.html');
    }
    console.log('Lives', lives);
    await update_lives(lives);
    await get_balls();
    await update_timer();
    await update_target();
}

async function update_timer() {
    let time = (await call_api('game', 'update', {}))['content']['time'];
    document.getElementById('timer_seconds').innerHTML = Math.round(time);
    if (time <= 0) {
        await timout();
    }
}

async function update_score() {
    let score = (await call_api('game', 'getscore', {}))['content']['score'];
    /*let resp = await call_api('game', 'getscore', {});
    console.log(resp);*/
    document.getElementById('score').innerHTML = score;
}

/**
 * 
 * @param {number} lives 
 */
async function update_lives(lives) {
    let container = document.getElementById('lives_container');
    container.innerHTML = '';
    for (let i = 0; i < lives; ++i) {
        let heart = document.createElement('div');
        heart.classList.add('heart');
        if (i === (lives - 1)) {
            heart.classList.add('beating');
        }
        container.appendChild(heart);
    }
}

async function update_target() {
    let container = document.getElementById('game_target');
    let target = (await call_api('game', 'gettarget', {}))['content']['target'];
    container.innerHTML = target;
}