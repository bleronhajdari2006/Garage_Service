// Login form validation
document.getElementById('loginForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const email = this.email.value.trim();
  const password = this.password.value.trim();

  if(email === '' || password === '') {
    alert('Please fill in all login fields.');
    return false;
  }

  alert('Login successful (demo)');
});

// Register form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const name = this.name.value.trim();
  const email = this.email.value.trim();
  const password = this.password.value.trim();

  if(name === '' || email === '' || password === '') {
    alert('Please fill in all registration fields.');
    return false;
  }

  alert('Registration successful (demo)');
});
