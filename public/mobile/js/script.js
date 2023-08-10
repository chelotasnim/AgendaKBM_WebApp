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