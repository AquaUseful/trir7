document.addEventListener('DOMContentLoaded', async () => {
    console.log('Lodaded game');
    let ball = document.getElementById("ball");
    let area = document.getElementById("game_area");
    await make_draggable(ball, area, cb);
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
async function cb(element) {
    let area = document.getElementById("game_area");
    let container = document.getElementById("game_container");

    let frac_pos = { x: element.offsetLeft / area.offsetWidth, y: element.offsetTop / area.offsetHeight };

    console.log('Moved to:', frac_pos);

}