/**
 * Shows a success message, then hides it
 * @param {string} message 
 */
function showSuccess(message) {
    $('body').append($(document.createElement('section'))
        .attr('id', 'success')
        .addClass('notification is-success')
        .text(message)
    );
    setTimeout(() => {
        $('#success').fadeOut(1000, () => {
            $('#success').remove();
            console.log("Removed success.");
        });
    }, 
    5000);
}
/**
 * Shows an error message then deletes it
 * 
 * @param {string} message 
 */
function showError(message) {
    $('body').append($(document.createElement('section'))
        .attr('id', 'retry')
        .addClass('notification is-danger')
        .text(message)
    );
    setTimeout(() => {
        $('#retry').fadeOut(1000, () => {
            $('#retry').remove();
        });
    }, 
    5000);
}