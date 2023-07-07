const toggle = document.getElementById('toggleDark');
const body = document.querySelector('body');

toggle.addEventListener('click', function(){
    this.classList.toggle('bi-moon');
    if(this.classList.toggle('bi-brightness-high-fill')){
        body.style.background = 'white';
        body.style.color = 'black';
        body.style.transition = '2s';
    }else{
        body.style.background = 'black';
        body.style.color = 'white';
        body.style.transition = '2s';
    }
});

// Toggle password visibility
const userPasswordEl = document.querySelector("#password");
const togglePasswordEl = document.querySelector("#togglePassword");

togglePasswordEl.addEventListener("click", function () {
  if (this.checked === true) {
    userPasswordEl.setAttribute("type", "text");
  } else {
    userPasswordEl.setAttribute("type", "password");
  }
});
