
document.addEventListener("DOMContentLoaded", function() {
    const checkbox = document.getElementById('showPasswordCheckbox');
    const passwordInput = document.getElementById('id_heslo');

    checkbox.addEventListener('change', function () {
        if (this.checked) {
            passwordInput.type = 'text'; 
        } else {
            passwordInput.type = 'password'; 
        }
    });
});