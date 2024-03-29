document.querySelectorAll('.btn-checkbox').forEach(button => {
    button.addEventListener('click', evt => {
        document.querySelectorAll('.btn-checkbox').forEach(button => button.classList.remove('active'))
        evt.target.classList.toggle('active');
        document.querySelectorAll('.btn-checkbox input').forEach(input => input.removeAttribute('checked'))
        evt.target.lastElementChild.setAttribute('checked', '');
    })
})
window.addEventListener('keypress', ev => {
    if (ev.key === "Enter") {
        document.querySelector('form').submit()
    }
});
document.querySelectorAll('button[data-id]').forEach(button => {
    button.addEventListener('click', evt => {
        fetch("borrow.php?item_id=" + evt.target.dataset.id).then(response => {
            location.reload()
        })
    })
})