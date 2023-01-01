let viewPasswordBtn = document.querySelector("#view-password");
let viewConfirmPasswordBtn = document.querySelector("#view-confirm-password");

// function to toggle password visibility
let togglePassword = () => {
    let password = document.querySelector('#password');
    let type = password.getAttribute('type');
    if (type == 'password') {
        password.setAttribute('type', 'text');
        viewPasswordBtn.style.color = 'rgb(2, 157, 204)';
    }
    else if (type == 'text') {
        password.setAttribute('type', 'password');
        viewPasswordBtn.style.color = 'rgb(78, 78, 78)';
    }
}

let toggleConfirmPassword = () => {
    let confirmPassword = document.querySelector('#confirm-password');
    let type = confirmPassword.getAttribute('type');
    if (type == 'password') {
        confirmPassword.setAttribute('type', 'text');
        viewConfirmPasswordBtn.style.color = 'rgb(2, 157, 204)';
    }
    else if (type == 'text') {
        confirmPassword.setAttribute('type', 'password');
        viewConfirmPasswordBtn.style.color = 'rgb(78, 78, 78)';
    }
}

//invoking the functions
viewPasswordBtn.addEventListener('click', () => {
    togglePassword();
});

viewConfirmPasswordBtn.addEventListener('click', () => {
    toggleConfirmPassword();
});


//showing password requirements
let password = document.querySelector('#password');
let passwordRequirements = document.querySelector('.password-requirements')
let passwordStrength = document.querySelector('.password-strength');

password.addEventListener('focus', () => {
    passwordRequirements.style.display = 'flex';
});

password.addEventListener('blur', () => {
    passwordRequirements.style.display = 'none';
});

//determinig password strength
let criteria = {
    count: false,
    letters: false,
    numbers: false,
    special: false
}


let passwordStrengthCheck = () => {
    password = document.querySelector('#password').value;
    let strengthBar = document.querySelector('.strength-bar');
    let strengthMessage = document.querySelector('.strength-message');
    criteria.count = (password.length > 7) ? true : false;
    criteria.letters = (/([a-z].*[A-Z])|([A-Z].*[a-z])+/.test(password)) ? true : false;
    criteria.numbers = (/[0-9]+/.test(password)) ? true : false;
    criteria.special = (/[!\"$%&/()=?@~`\\.\';:+=^*_-]+/.test(password)) ? true : false;


    console.log(criteria.count);
    if (password.length != 0) {
        strengthBar.style.display = 'block'
        strengthMessage.style.display = 'block';
        let strengthBarLength = Object.values(criteria).filter(value => value);
        strengthBar.innerHTML = '';
        for (let i in strengthBarLength) {
            let span = document.createElement('span');
            span.classList.add('strength');
            strengthBar.appendChild(span);
        }
        console.log(Object.values(criteria), strengthBarLength);
        let spanReference = document.getElementsByClassName('strength');
        for (let i = 0; i < spanReference.length; i++) {
            switch (spanReference.length - 1) {
                case 0:
                    spanReference[i].style.background = '#ff3e36';
                    strengthMessage.textContent = "Your password is very weak"
                    break;

                case 1:
                    spanReference[i].style.background = '#ff691f';
                    strengthMessage.textContent = 'Your password is weak'
                    break;

                case 2:
                    spanReference[i].style.background = '#ffda36';
                    strengthMessage.textContent = 'Your password is good';
                    break;

                case 3:
                    spanReference[i].style.background = '#0be881';
                    strengthMessage.textContent = 'Your password is strong';
                    break;
            }

        }
        let checkCharacterCount = () => {
            let state = criteria.count;
            if (state) {
                document.querySelector('#character-count').style.color = 'green';
            }
            else {
                document.querySelector('#character-count').style.color = '#b6b5b5';
            }
        }
        checkCharacterCount();


        let checkCharacterCase = () => {
            let state = criteria.letters;
            if (state) {
                document.querySelector('#character-case').style.color = 'green';
            }
            else {
                document.querySelector('#character-case').style.color = '#b6b5b5';
            }
        }
        checkCharacterCase();


        //checking if password contains number
        let checkCharacterNumber = () => {
            let state = criteria.numbers;
            if (state) {
                document.querySelector('#character-number').style.color = 'green';
            }
            else {
                document.querySelector('#character-number').style.color = '#b6b5b5';
            }
        }
        checkCharacterNumber();

        //checking if password contains special character
        let checkSpecialCharacter = () => {
            let state = criteria.special;
            if (state) {
                document.querySelector('#character-special').style.color = 'green';
            }
            else {
                document.querySelector('#character-special').style.color = '#b6b5b5';
            }
        }
        checkSpecialCharacter();
    }

    else {
        strengthBar.style.display = 'none';
        strengthMessage.style.display = 'none';
    }

}

password.addEventListener('input', () => {
    passwordStrengthCheck();
});


//refreshing captcha
let captchaRefresh = () => {
    var img = document.images['captcha_image'];
    img.src = img.src.substring(
        0, img.src.lastIndexOf("?")
    ) + "?rand=" + Math.random() * 1000;
}

//calling function to refresh captcha on click event
let refreshButton = document.querySelector('#refresh_btn');
refreshButton.addEventListener('click', () => {
    captchaRefresh();
});