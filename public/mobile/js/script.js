function getTime() {
    const date = new Date();
    let hour = date.getHours();
    let minute = date.getMinutes();

    if (hour < 10) {
        hour = '0' + hour;
    };

    if (minute < 10) {
        minute = '0' + minute;
    };

    document.querySelector('.hours').textContent = hour;
    document.querySelector('.minutes').textContent = minute;

    setInterval(getTime, 60000);
};
getTime();

const mintues = document.querySelector('.minutes');
if (mintues != undefined) {
    function display() {
        mintues.style.opacity = '1';
    };
    setInterval(display, 500);

    function undisplay() {
        mintues.style.opacity = '0';
    };
    setInterval(undisplay, 1000);
};

const nav_btn = document.querySelectorAll('.nav-icon');
if (nav_btn[0] != undefined) {
    nav_btn.forEach(btn => {
        btn.addEventListener(
            'click', function () {
                const active_btn = document.querySelector('.nav-icon.active');
                active_btn.classList.remove('active');
                btn.classList.add('active');
            }
        );
    });
};