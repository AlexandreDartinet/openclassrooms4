/**
 * Script qui vérifie que les champs email et email_confirm sont identiques avant d'autoriser l'envoi du formulaire
 */

let email = document.getElementById("email");
let confirm_email = document.getElementById("email_confirm");

function validateEmail(){
  if(email.value != confirm_email.value) {
    confirm_email.setCustomValidity("Les emails ne sont pas identiques.");
  } else {
    confirm_email.setCustomValidity('');
  }
}

email.onchange = validateEmail;
confirm_email.onkeyup = validateEmail;