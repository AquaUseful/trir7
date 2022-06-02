import { call_api } from "./utils.js";

async function bind_buttons(btnMap) {
    for (let [id, func] of Object.entries(btnMap)) {
        let btn = document.getElementById(id);
        btn.addEventListener("click", func, false);
    }
}

async function display_form_errors(validationResult) {
    for (let [field, result] of Object.entries(validationResult)) {
        let el = document.getElementById(field + "_err");
        el.innerHTML = result.displayError;
    }
}

export async function display_general_error(errorText) {
    let el = document.getElementById("general_error");
    el.innerHTML = errorText;
}

export async function read_form_fields(fields) {
    let result = new Object();
    console.log(fields);
    for (let field of fields) {
        let el = document.getElementById(field + "_field");
        result[field] = el.value;
    }
    return result;
}

export function clear_form(fields) {
    for (let field of fields) {
        document.getElementById(field + '_field').value = '';
        document.getElementById(field + '_err').innerHTML = '';
    }
}

export async function initialize_form(containerId, formName, buttonMap) {
    let container = document.getElementById(containerId);
    let formText = (await call_api('page', 'get', { 'name': formName }))['content']['html'];

    container.innerHTML = formText;

    //let formInfo = await call_api('forminfo', { 'name': formName });
    await bind_buttons(buttonMap);
}

export async function create_submit_cb(formName, successCb) {
    let formInfo = await call_api('form', 'getinfo', { 'name': formName });
    let cb = async function () {
        //let formInfo = await call_api('forminfo', { 'name': formName }).content;
        // console.log(formInfo)
        let fields = await read_form_fields(formInfo.content.fields);
        // console.log(fields);
        let validationResult = await call_api('form', 'validate', { 'name': formName, 'fields': fields });
        if (validationResult.content.valid) {
            await successCb(fields);
        } else {
            await display_form_errors(validationResult.content.fields);
        }
    };

    return cb;
}