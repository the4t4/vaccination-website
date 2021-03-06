const previous = document.querySelector("#prev");
const next = document.querySelector("#next");
const calendar = document.querySelector("#calendar");
const monthYear = document.querySelector("#monthYear");

const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
let day = 0;
let month = 0;
let year = 0;

previous.addEventListener("click", ()=>{
    if(month == 1){
        year--;
        month = 12;
    }
    else month--;
    renderCalendar(day, month, year);
});

next.addEventListener("click", ()=>{
    if(month == 12){
        year++;
        month = 1;
    }
    else month++;
    renderCalendar(day, month, year);
});

function renderCalendar(day, month, year){
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = this.responseText;
            if(response.indexOf("ERROR") == -1){
                monthYear.innerHTML = months[month-1] + ", " + year; 
                calendar.innerHTML = response;
            }
        }
    };
    xhr.open("GET", "calendar.php?day="+ day + "&month="+ month +"&year="+year, true);
    xhr.send();
}

window.addEventListener("load", ()=>{
    const today = new Date();
    day = today.getDate();
    month = today.getMonth()+1; //getMonth() returns month indexed from 0..11 
    year = today.getFullYear();
    renderCalendar(day, month, year);
});