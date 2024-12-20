let signUp = document.querySelector(".sign-up-form")
let getStarted = document.querySelector(".sign_up")
let signin = document.querySelector(".login-trigger")
let signinSection = document.querySelector(".sign-in-form")

let Articleform = document.querySelector(".blog-creation");
let manage = document.querySelector(".management");

let hambutton = document.querySelector(".hamburger-icon");
let authenS = document.querySelector(".auth");

function test() {
    const menu = document.querySelector(".hamburger-menu");
    menu.classList.toggle("active");
}


getStarted.onclick = () => {
    signinSection.style.display = "none"
    signUp.style.display = "flex";
    setTimeout(() => {
        signUp.style.opacity = "1";
    }, 0);
}
signin.onclick = () => {
    signUp.style.display = "none";
    signinSection.style.display = "flex";
    setTimeout(() => {
        signinSection.style.opacity = "1"
    }, 0);
}


document.addEventListener("DOMContentLoaded", () => {
    const formVisibility = document.getElementById("formVisibility").value;

    if (formVisibility === "signUp") {
        signinSection.style.display = "none";
        signUp.style.display = "flex";
        signUp.style.opacity = "1";
    }
    else if (formVisibility === "sign-in") {
        signUp.style.display = "none";
        signinSection.style.display = "flex";
        signinSection.style.opacity = "1";
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const profileCheck = document.querySelector("#test").value;
    if (profileCheck === "exist") {
        authenS.style.display = "none";
    }
})

function addArticle() {
    manage.style.display = "none"
    Articleform.style.display = "block";
}
function manageArticle() {
    Articleform.style.display = "none";
    manage.style.display = "block"
}

