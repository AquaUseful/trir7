import { clear_form, create_submit_cb, display_general_error, initialize_form, read_form_fields } from "./form.js";
import { call_api } from "./utils.js";

async function login_cb(fields) {
    let result = await call_api('session', 'login', fields);
    if (result.ok) {
        document.location.replace('rules.html');
    } else {
        clear_form(Object.keys(fields));
        await display_general_error(result.content.displayError);
    }
}

document.addEventListener("DOMContentLoaded", async () => {
    console.log('called init');
    let btn_map = { "submit": await create_submit_cb('login', login_cb) };
    await initialize_form("form_container", "login", btn_map);
}, false);
