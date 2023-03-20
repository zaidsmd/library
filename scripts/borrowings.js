document.querySelectorAll('.card button').forEach(btn=>{
    btn.addEventListener('click',ev=>{
        btn.classList.toggle('active');
        btn.parentElement.parentElement.lastElementChild.classList.toggle('hidden');

    })
});
document.querySelectorAll('.btn-checkbox').forEach(button=>{
    button.addEventListener('click',evt => {
        document.querySelectorAll('.btn-checkbox').forEach(button=> button.classList.remove('active'))
        evt.target.classList.toggle('active');
        document.querySelectorAll('.btn-checkbox input').forEach(input=>input.removeAttribute('checked'))
        evt.target.lastElementChild.setAttribute('checked','');
    })
})
window.addEventListener('keypress', ev => {
    if (ev.key === "Enter"){
        document.querySelector('form').submit()
    }
});