let emailReg = /^\w+@\w+.\w+$/i;
let telReg = /^((\+212)|(0212)|(0))([678])[0-9]{8}$/i;
let nameReg = /^[a-z]{1,50}$/i;
let cinReg = /^[a-z0-9]{1,8}$/i;
document.querySelector('#add-modal form').addEventListener('submit', e => {
    let key = true;
    if (!nameReg.test(document.querySelector('#name').value)) {
        key = false;
        document.querySelector('#name').parentElement.lastElementChild.classList.add('show');
    } else {
        document.querySelector('#name').parentElement.lastElementChild.classList.remove('show');
    }
    if (!nameReg.test(document.querySelector('#last-name').value)) {
        key = false;
        document.querySelector('#last-name').parentElement.lastElementChild.classList.add('show');
    } else {
        document.querySelector('#last-name').parentElement.lastElementChild.classList.remove('show');
    }
    if (!telReg.test(document.querySelector('#phone-number').value)) {
        key = false;
        document.querySelector('#phone-number').parentElement.lastElementChild.classList.add('show');
    } else {
        document.querySelector('#phone-number').parentElement.lastElementChild.classList.remove('show');
    }
    if (!emailReg.test(document.querySelector('#email').value)) {
        key = false;
        document.querySelector('#email').parentElement.lastElementChild.classList.add('show');
    } else {
        document.querySelector('#email').parentElement.lastElementChild.classList.remove('show');
    }
    if (!cinReg.test(document.querySelector('#cin').value)) {
        key = false;
        document.querySelector('#cin').parentElement.lastElementChild.classList.add('show');
    } else {
        document.querySelector('#cin').parentElement.lastElementChild.classList.remove('show');
    }
    if (!key) {
        e.preventDefault()
    }
})

function fill() {

}