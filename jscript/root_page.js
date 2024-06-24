document.addEventListener('DOMContentLoaded', () => {
    let choiceElements = document.querySelectorAll('.choices .choice');
  
    choiceElements.forEach(choiceElement => {
      choiceElement.addEventListener('click', () => {
        if (choiceElement.classList.contains('active')) {
          choiceElement.classList.remove('active');
        } else {
          choiceElements.forEach(element => {
            element.classList.remove('active');
          });
          choiceElement.classList.add('active');
        }
      });
    });
  });
  