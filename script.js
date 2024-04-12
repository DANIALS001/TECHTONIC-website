document.addEventListener('DOMContentLoaded', function() {

    // Existing code...
    
    // Add event listener to the sign-up form on submission
    var signUpForm = document.getElementById('sign-up-form');
    if(signUpForm) {
      signUpForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting until validation is complete
        
        // Call the validateSignUpForm function when the form is submitted
        validateSignUpForm();
      });
    }
  
    // Attach toggle form functions to buttons
    var signUpButton = document.querySelector('.signup-btn');
    if(signUpButton) {
      signUpButton.onclick = showSignUpForm;
    }
    
    var signInButton = document.querySelector('.forgotbtn');
    if(signInButton) {
      signInButton.onclick = showSignInForm;
    }
  });
  
  function showSignInForm() {
    document.getElementById('sign-in-form').style.display = 'block';
    document.getElementById('sign-up-form').style.display = 'none';
  }
  
  function showSignUpForm() {
    document.getElementById('sign-in-form').style.display = 'none';
    document.getElementById('sign-up-form').style.display = 'block';
  }
  
  function validateSignUpForm() {
    // Get the values from the form
    var username = document.getElementById('signUpUsername').value;
    var confirmUsername = document.getElementById('signUpUsernameConfirm').value;
    var phone = document.getElementById('signUpPhone').value;
    var password = document.getElementById('signUpPassword').value;
    var confirmPassword = document.getElementById('signUpPasswordConfirm').value;
    
    var errorMessage = '';
  
    // Validate Username/Email
    if (username !== confirmUsername) {
      errorMessage += 'Usernames/Emails do not match. ';
    }
  
    // Validate Phone Number
    if (!/^\d+$/.test(phone)) {
      errorMessage += 'Phone number must be a string of numbers. ';
    }
  
    // Validate Password Strength
    if (password.length < 8 || !/[a-zA-Z]/.test(password) || !/\d/.test(password) || !/[^a-zA-Z\d]/.test(password)) {
      errorMessage += 'Password must be at least 8 characters long, contain letters, numbers, and a special character. ';
    }
  
    if (password !== confirmPassword) {
      errorMessage += 'Passwords do not match. ';
    }
    
    // If there are error messages, show them, otherwise, submit the form
    if (errorMessage) {
      document.getElementById('error-message').textContent = errorMessage;
    } else {
      // If validation passes, submit the form
      // Here we would normally submit the form, but since we're doing client-side validation,
      // we would need to do an AJAX call or assign the form's submit event.
      signUpForm.submit(); 
    }
  }
  