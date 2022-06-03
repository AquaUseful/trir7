/**
 * 
 * @param {string} api 
 * @param {string} action 
 * @param {Object} content 
 */
export async function call_api(api, action, content) {
    let response = await fetch(
        'php/api/api.php',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({ 'api': api, 'action': action, 'content': content })
        }
    );
    return response.json();
}

/**
 * 
 * @param {HTMLElement} el 
 */
export async function hide(el) {
    el.classList.add('hidden');
}
/**
 * 
 * @param {HTMLElement} el 
 */
export async function show(el) {
    el.classList.remove('hidden');
}