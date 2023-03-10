document.querySelectorAll('.item-unit button').forEach(button => {
    button.addEventListener('click', e => {
        fetch("reserve.php?item_id=" + e.target.dataset.id).then(async response => {
            let message = await response.json();
            return message;
        }).then(message => {
            document.querySelector('.modal-body').innerHTML = message
        })
    })
})
document.querySelector('#response-modal button').addEventListener('click',()=>{
    location.reload();
});