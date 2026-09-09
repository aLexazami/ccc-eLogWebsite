// assets/js/login-toggle.js

document.addEventListener('DOMContentLoaded', () => {
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (togglePasswordBtn && passwordInput && toggleIcon) {
        togglePasswordBtn.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            
            // Toggle input attribute type
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            // Toggle Bootstrap Icon classes
            toggleIcon.classList.toggle('bi-eye-slash-fill', !isPassword);
            toggleIcon.classList.toggle('bi-eye-fill', isPassword);
            
            // Toggle accessibility attribute state
            togglePasswordBtn.setAttribute(
                'aria-label', 
                isPassword ? 'Hide password' : 'Show password'
            );
        });
    }
});