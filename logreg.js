// index.js

function toggleForms() {
    const signInForm = document.getElementById('signIn');
    const signUpForm = document.getElementById('signUp');
    const formTitle = document.getElementById('formTitle');

    if (signInForm.style.display === 'none') {
        signInForm.style.display = 'block';
        signUpForm.style.display = 'none';
        formTitle.textContent = "Sign In";
    } else {
        signInForm.style.display = 'none';
        signUpForm.style.display = 'block';
        formTitle.textContent = "Sign Up";
    }
}
