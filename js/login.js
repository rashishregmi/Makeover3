 // Function to validate input fields in a form box
function validateInputFields(formBoxClass) {
    const inputFields = document.querySelectorAll(`${formBoxClass} input`);
    let isValid = true;

    inputFields.forEach(input => {
        const label = input.parentElement.querySelector('label');

        if (input.value.trim() === '') {
            isValid = false;
            input.parentElement.classList.remove('input-filled');
            if (label) {
                label.style.top = '50%';
            }
        } else {
            input.parentElement.classList.add('input-filled');
            if (label) {
                label.style.top = '-5px';
            }
        }
    });

    return isValid;
}

document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.querySelector('.wrapper');
    const loginLink = document.querySelector('.login-link');
    const registerLink = document.querySelector('.register-link');

    registerLink.addEventListener('click', () => {
        if (!wrapper.classList.contains('active')) {
            wrapper.classList.add('active');
            validateInputFields('.form-box.login');
        }
    });

    loginLink.addEventListener('click', () => {
        if (wrapper.classList.contains('active')) {
            wrapper.classList.remove('active');
            validateInputFields('.form-box.register');
        }
    });

    const inputBoxes = document.querySelectorAll('.input-box');

    inputBoxes.forEach(inputBox => {
        const input = inputBox.querySelector('input');
        const label = inputBox.querySelector('label');

        input.addEventListener('input', () => {
            if (input.value.trim() !== '') {
                inputBox.classList.add('input-filled');
            } else {
                inputBox.classList.remove('input-filled');
            }
        });

        input.addEventListener('focus', () => {
            if (label) {
                label.style.top = '-5px';
            }
        });

        input.addEventListener('blur', () => {
            if (input.value.trim() === '') {
                if (label) {
                    label.style.top = '50%';
                }
            }
        });

        // Check for filled inputs on page load
        if (input.value.trim() !== '') {
            inputBox.classList.add('input-filled');
            label.style.top = '-5px';
        }
    });

    const registerForm = document.getElementById('register-form');
    if (!registerForm) {
        console.error("register-form not found in the DOM.");
        return;
    }

    registerForm.addEventListener('submit', async (event) => {
        event.preventDefault();

         
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const termsCheckbox = document.getElementById('terms');

        

        const emailValue = emailInput.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailValue) || emailValue.length < 5) {
            alert('Please enter a valid email address with at least 5 characters.');
            return;
        }

        const passwordValue = passwordInput.value.trim();
        const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordPattern.test(passwordValue)) {
            alert('Password should have at least 8 characters, 1 capital letter, and 1 special character.');
            return;
        }

        if (!termsCheckbox.checked) {
            alert('Please agree to the terms & conditions.'); 
            return;
        }

// Check for duplicate entries with a timestamp to prevent caching
const duplicateCheckUrl = `../php/check_user.php?timestamp=${Date.now()}`;
        const formData = new FormData();
         
        formData.append('email', emailValue);

        try {
            const response = await fetch(duplicateCheckUrl, {
                method: 'POST',
                body: formData
            });

            const data = await response.text();
            if (data === "true") {
                // Display error message for duplicate user
                const errorMessageElement = document.getElementById('error-message2');
                if (errorMessageElement) {
                    errorMessageElement.textContent = 'Error: Email already exists.';
                    errorMessageElement.style.color = 'red';
                }
                return;
            }

            // If all validations pass and the user is not registered, manually submit the form
            registerForm.submit();
        } catch (error) {
            console.error("Error checking for duplicate user:", error);
            alert("An error occurred while processing the registration. Please try again later.");
        }
    });

    const loginForm = document.querySelector('.form-box.login form');
    if (!loginForm) {
        console.error("Login form not found in the DOM.");
        return;
    }

    loginForm.addEventListener('submit', async(event) => {
        event.preventDefault();

        const emailInputLogin = document.getElementById('email1');
        const passwordInputLogin = document.getElementById('password1');

        const emailValue = emailInputLogin.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailValue) || emailValue.length < 5) {
            alert('Please enter a valid email address with at least 5 characters.');
            return;
        }

        const passwordValue = passwordInputLogin.value.trim();
        const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordPattern.test(passwordValue)) {
            alert('Password should have at least 8 characters, 1 capital letter, and 1 special character.');
            return;
        }
    // Check for duplicate entries with a timestamp to prevent caching
    const duplicateCheckUrl = `../php/check_user2.php?timestamp=${Date.now()}`;
     const formData = new FormData();
    formData.append('email', emailValue);
    formData.append('password', passwordValue);

     try {
     const response = await fetch(duplicateCheckUrl, {
         method: 'POST',
         body: formData
     });

     const data = await response.text();
     if (data === "false") {
         // Display error message "Please register first" in red below the login title
         const errorMessageElement = document.getElementById('error-message');
         errorMessageElement.textContent = 'Please register first.';
         errorMessageElement.style.color = 'red';
         
         return;
         }
         else if (data === "incorrect") {
            // Display error message for incorrect password
            const errorMessageElement = document.getElementById('error-message');
            errorMessageElement.textContent = 'Incorrect password. Please try again.';
            errorMessageElement.style.color = 'red';
            return;
        }
        // If login data is matched and exists in the database, submit the form
      loginForm.submit();
         } catch (error) {
         console.error("Error checking for duplicate user:", error);
         alert("An error occurred while processing the login. Please try again later.");
        }
    });
});