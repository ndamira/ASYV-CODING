let notice = document.getElementById("notice");
let logout = document.getElementById("logout");

function openNotification(){
    logout.classList.remove("openLogout");
    notice.classList.toggle("openNotification");
}

function closeNotification(){
    notice.classList.remove("openNotification");
}

function openLogout(){
    notice.classList.remove("openNotification");
    logout.classList.toggle("openLogout");
}

function closeLogout(){
    logout.classList.remove("openLogout");
}