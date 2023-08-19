document.addEventListener("DOMContentLoaded", function () {
  const firstNameInput = document.querySelector('input[placeholder="First Name"]');
  const lastNameInput = document.querySelector('input[placeholder="Last Name"]');
  const contactNumberInput = document.querySelector('input[placeholder="Contact Number"]');
  const emailInput = document.querySelector('input[placeholder="Email"]');
  const messageTextarea = document.querySelector('textarea[placeholder="Your Message"]');
  const sendButton = document.querySelector('.btn');

  // Function to check if a string contains only alphabetical characters and spaces
  function isAlphabetical(value) {
    return /^[A-Za-z\s]+$/.test(value);
  }

  // Function to filter the input to allow only alphabetic characters
  function filterAlphabeticInput(inputElement) {
    inputElement.addEventListener("input", function () {
      const inputValue = inputElement.value;
      const filteredValue = inputValue.replace(/[^A-Za-z\s]/g, '');
      if (inputValue !== filteredValue) {
        inputElement.value = filteredValue;
      }
    });
  }

  filterAlphabeticInput(firstNameInput);
  filterAlphabeticInput(lastNameInput);

  const contactForm = document.getElementById('contactForm');

  contactForm.addEventListener('submit', function (event) {
    event.preventDefault();

    // Reset previous error messages
    const errorMessages = [];

    // Validate first name
    const firstNameValue = firstNameInput.value.trim();
    if (!isAlphabetical(firstNameValue) || firstNameValue.length < 2) {
      errorMessages.push("Enter your First name");
    }

    // Validate last name
    const lastNameValue = lastNameInput.value.trim();
    if (!isAlphabetical(lastNameValue) || lastNameValue.length < 3) {
      errorMessages.push("Enter your Last name");
    }

    // Validate contact number
    const contactNumberValue = contactNumberInput.value.trim();
    if (!/^98|97\d{8}$/.test(contactNumberValue) || contactNumberValue.length !== 10) {
      errorMessages.push("Contact number should start with '98' or '97' and be exactly 10 digits long.");
    }

    // Validate email
    const emailValue = emailInput.value.trim();
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
      errorMessages.push("Invalid email address.");
    }

    // Validate message
    const messageValue = messageTextarea.value.trim();
    if (messageValue === "") {
      errorMessages.push("Please enter your message.");
    }

    // Display the first error message if any
    if (errorMessages.length > 0) {
      alert(errorMessages[0]);
    } else {
      // If no errors, proceed with form submission
      const formData = new FormData(contactForm);
      fetch('../php/submit_contact.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        if (data.success) {
          contactForm.reset(); // Reset the form fields after successful submission
        }
      })
      .catch(error => {
        alert("An error occurred while submitting the form.");
        console.error('Error:', error);
      });
    }
  });
});
