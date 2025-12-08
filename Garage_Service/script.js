// Attach handlers safely only when elements exist
document.addEventListener('DOMContentLoaded', function () {
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const email = this.email.value.trim();
      const password = this.password.value.trim();

      if (email === '' || password === '') {
        alert('Please fill in all login fields.');
        return false;
      }

      alert('Login successful (demo)');
    });
  }

  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const name = this.name.value.trim();
      const email = this.email.value.trim();
      const password = this.password.value.trim();

      if (name === '' || email === '' || password === '') {
        alert('Please fill in all registration fields.');
        return false;
      }

      alert('Registration successful (demo)');
    });
  }

  // Booking form (book.html)
  const bookingForm = document.getElementById('bookingForm');
  if (bookingForm) {
    bookingForm.addEventListener('submit', function (e) {
      e.preventDefault();
      // Simple demo validation
      const name = this.name.value.trim();
      const phone = this.phone.value.trim();
      const service = this.service.value;
      if (!name || !phone || !service) {
        alert('Please fill in your name, phone and select a service.');
        return false;
      }
      alert('Booking received (demo). We will contact you to confirm.');
      this.reset();
    });
  }

  // Contact form (contact.html)
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const name = this.name.value.trim();
      const email = this.email.value.trim();
      const message = this.message.value.trim();
      if (!name || !email || !message) {
        alert('Please complete all contact fields.');
        return false;
      }
      alert('Message sent (demo). We will reply shortly.');
      this.reset();
    });
  }
});
