document.querySelector('.item button[data-id]').addEventListener('click', () => {
    fetch("get_item.php?id=" + document.querySelector('.item button[data-id]').dataset.id).then(async response => {
        let array = await response.json()
        return array
    }).then(array => {
        document.querySelector('#item-modal h3').innerHTML = array["title"]
        let keys = ["title", "author", "type", "language", "page_count"];
        keys.forEach(key => {
            document.querySelector('#item-modal #' + key).value = array[key];
        })
    })
})
document.querySelectorAll('.items button[data-id]').forEach(btn=>{
    btn.addEventListener('click', () => {
        fetch("get_item.php?id_item=" + btn.dataset.id).then(async response => {
            let array = await response.json()
            return array
        }).then(array => {
            let keys = ["status", "brought_date"];
            keys.forEach(key => {
                document.querySelector('#item-unit-modal #' + key).value = array[key];
            })
            document.querySelector('#item-unit-modal [name="id"]').value = btn.dataset.id
        })
    })

})
