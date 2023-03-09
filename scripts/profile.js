document.querySelector('#authentication-modal .btn-primary').addEventListener('click',()=>{
    let password = document.querySelector('#authentication-modal #password').value;
    if (password === ""){
        document.querySelector('#authentication-modal #password').classList.add('red');
    }else  {
        fetch("password_check.php?password="+password)
            .then(async (Response)=>{
                let response = await Response.json() ;
                return response;
            }).then(response=>{
            if (response){
                document.querySelector('#email').removeAttribute('disabled');
                document.querySelector('#phone_number').removeAttribute('disabled');
                document.querySelector('form').submit();
            }else {
                document.querySelector('.response').classList.remove('hidden');
            }
        })
    }

})
document.querySelector('#password-modal form').addEventListener('submit',(e)=>{
    e.preventDefault();
    document.querySelector('#password-modal .response').classList.remove('hidden');
    document.querySelector('.error').classList.remove('hidden');
    document.querySelector('.error').classList.add('hidden');
    document.querySelector('#password-modal .response').classList.add('hidden');
    if (document.querySelector('#new_password').value === document.querySelector('#password_confirmation').value){
        let password = document.querySelector('#password-modal #old_password').value;
        if (password === ""){
            document.querySelector('#password-modal #old_password').classList.add('red');
        }else  {
            fetch("password_check.php?password="+password)
                .then(async (Response)=>{
                    let response = await Response.json() ;
                    return response;
                }).then(response=>{
                if (response){
                    document.querySelector('#password-modal form').submit()
                }else {
                    document.querySelector('#password-modal .response').classList.remove('hidden');
                    e.preventDefault();
                }
            })
        }
    }else  {
        document.querySelector('.error').classList.remove('hidden');
        e.preventDefault();
    }
})