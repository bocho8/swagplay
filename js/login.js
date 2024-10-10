// Login Modal Logic
var login_modal = document.getElementById('login_modal');
var open_login = document.getElementById('open_login');
var close_login = document.getElementById('close_login');

// Register Modal Logic
var register_modal = document.getElementById('register_modal');
var open_register = document.getElementById('open_register');
var close_register = document.getElementById('close_register');

// Open login modal
open_login.onclick = function () {
    login_modal.style.display = "block";
};

// Open register modal
open_register.onclick = function () {
    register_modal.style.display = "block";
};

// Close login modal
close_login.onclick = function () {
    login_modal.style.display = "none";
};

// Close register modal
close_register.onclick = function () {
    register_modal.style.display = "none";
};

// Close modals if clicked outside
window.onclick = function (event) {
    if (event.target == login_modal) {
        login_modal.style.display = "none";
    }
    if (event.target == register_modal) {
        register_modal.style.display = "none";
    }
};