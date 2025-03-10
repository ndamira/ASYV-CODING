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

function searchCourse(){
    let input = document.getElementById("search");
    let upperCase = input.value.toUpperCase();
    let allCourses = document.getElementById("allCourses");
    let course = document.getElementsByClassName("course");
    for( let i = 0; i < course.length; i++){
        let courseTitle = course[i].getElementsByTagName("h3")[0];
        let title = courseTitle.textContent || courseTitle.innerText;
        if(title.toUpperCase().indexOf(upperCase) > -1){
            course[i].style.display = "";
        }
        else{
            course[i].style.display = "none";
        }
    }
}