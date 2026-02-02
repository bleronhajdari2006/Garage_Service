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

  // File input validation for forms with file fields
  document.querySelectorAll('input[type=file]').forEach(function (inp) {
    inp.addEventListener('change', function () {
      const f = this.files[0];
      if (!f) return;
      const allowed = ['image/jpeg','image/png','image/gif','application/pdf'];
      if (!allowed.includes(f.type)) {
        alert('Invalid file type. Only images and PDFs are allowed.');
        this.value = '';
      } else if (f.size > 5 * 1024 * 1024) {
        alert('File too large. Max 5MB.');
        this.value = '';
      }
    });
  });

  // Simple slider for elements with .slider and .slide children
  const sliders = document.querySelectorAll('.slider');
  sliders.forEach(function (slider) {
    const slides = slider.querySelectorAll('.slide');
    if (slides.length <= 1) return;
    let i = 0;
    slides.forEach((s, idx) => s.style.display = idx === 0 ? 'block' : 'none');
    setInterval(() => {
      slides[i].style.display = 'none';
      i = (i + 1) % slides.length;
      slides[i].style.display = 'block';
    }, 4000);
  });
});
