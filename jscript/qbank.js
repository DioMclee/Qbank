const link = document.getElementById('navigate');

link.addEventListener('click', function(event) {
  event.preventDefault(); // Prevent default navigation behavior
  const targetId = this.getAttribute('href').substring(1); // Get target ID
  const targetElement = document.getElementById(targetId);

  // Scroll to the target element smoothly
  window.scroll({
    behavior: 'smooth',
    top: targetElement.offsetTop
  });

  // Update browser history (optional)
  history.pushState(null, null, this.href); // Add the target section to history
});


let listElements = document.querySelectorAll('.faq');

listElements.forEach(listElement => { 
    listElement.addEventListener('click', () => {
     if (listElement.classList.contains('active')){
         listElement.classList.remove('active'); 
        }else{
            listElements.forEach(ListE => {
                ListE.classList.remove('active');
                })
                listElement.classList.toggle('active');
        }
    })
})


// ScrollReveal configuration
document.addEventListener('DOMContentLoaded', () => {
  ScrollReveal().reveal('.page01 .nav', {
      delay: 200,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      // reset: true
  });
  ScrollReveal().reveal('.page01 .info', {
      delay: 150,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      reset: true
  });

  ScrollReveal().reveal('.page02', {
      delay: 250,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      reset: true
  });
  ScrollReveal().reveal('.page02 .boxes', {
      delay: 250,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      reset: true
  });

  ScrollReveal().reveal('.page03 .page-details', {
      delay: 250,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      reset: true
  });

  ScrollReveal().reveal('.page04', {
      delay: 250,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      reset: true 
  });
  ScrollReveal().reveal('.page04 .faqs', {
      delay: 250,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      reset: true 
  });

  ScrollReveal().reveal('.page05 .boxes', {
      delay: 250,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      reset: true
  });
  ScrollReveal().reveal('.page06', {
      delay: 250,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      reset: true
  });
  ScrollReveal().reveal('.page07', {
      delay: 250,
      distance: '50px',
      origin: 'bottom',
      duration: 1000,
      reset: true
  });

});
