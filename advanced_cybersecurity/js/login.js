let viewPasswordBtn = document.querySelector("#view-password");


// function to toggle password visibility
let togglePassword = () => {
    let password = document.querySelector('#password');
    let type = password.getAttribute('type');
    if (type == 'password') {
        password.setAttribute('type', 'text');
        viewPasswordBtn.style.color = 'rgb(2, 157, 204)';
    }
    else if(type=='text'){
        password.setAttribute('type', 'password');
        viewPasswordBtn.style.color = 'rgb(78, 78, 78)';
    }
}

viewPasswordBtn.addEventListener('click', ()=>{
    togglePassword();
});

