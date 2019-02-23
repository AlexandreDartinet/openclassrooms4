/**
 * Script qui vérifie que les champs password et password_confirm sont identiques avant d'autoriser l'envoi du formulaire
 */

let password = document.getElementById("password");
let confirm_password = document.getElementById("password_confirm");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Les mots de passe ne sont pas identiques.");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;