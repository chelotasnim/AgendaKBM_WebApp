const auth_fields = document.querySelectorAll('.auth-form input');
if (auth_fields[0] != undefined) {
    auth_fields.forEach(field => {
        field.addEventListener(
            'change', function () {
                if (field.value.length > 0) {
                    if (field.nextElementSibling != undefined) {
                        field.nextElementSibling.classList.add('active');
                    };
                } else {
                    if (field.nextElementSibling != undefined) {
                        field.nextElementSibling.classList.remove('active');
                    };
                };
            }
        );
    });
};

const alert_boxes = document.querySelectorAll('.alert-box');
if (alert_boxes[0] != undefined) {
    alert_boxes.forEach(alert => {
        alert.addEventListener(
            'click', function () {
                alert.style.transform = 'translateX(150%)';

                function removeAlert() {
                    alert.remove();
                };
                setTimeout(removeAlert, 1000);
            }
        );
    });
};

const box_options = document.querySelectorAll('.box-option');
if (box_options[0] != undefined) {
    box_options.forEach(opt => {
        const opt_input = opt.querySelector('input');
        opt_input.addEventListener(
            'click', function () {
                if (opt_input.checked) {
                    box_options.forEach(other_box => {
                        other_box.classList.remove('selected');
                    });
                    opt.classList.add('selected');
                } else {
                    opt.classList.remove('selected');
                };
            }
        );
    });
};

const select_modal_trigger = document.querySelector('#select-modal');
const select_modal = document.querySelector('.select-kelas-modal');
if (select_modal_trigger != undefined) {

    select_modal_trigger.addEventListener(
        'click', function () {
            select_modal.style.display = 'flex';
            function addClass() {
                select_modal.classList.add('active');
            };
            setTimeout(addClass, 100);
        }
    );
};

const row_search = document.querySelector('.row-search');
if (row_search != undefined) {
    const select_value = row_search.parentElement.parentElement.parentElement.querySelectorAll('.select-val');
    row_search.addEventListener(
        'keyup', function () {
            select_value.forEach(select => {
                if (select.getAttribute('data-nama-kelas').toLowerCase().indexOf(row_search.value.toLowerCase()) > -1) {
                    select.style.display = 'block';
                } else {
                    select.style.display = 'none';
                };
            });
        }
    )

    select_value.forEach(select => {
        select.addEventListener(
            'click', function () {
                select_modal.classList.remove('active');
                function removeDisplay() {
                    select_modal.style.display = 'none';
                };
                setTimeout(removeDisplay, 600);

                document.querySelector('#id-kelas').value = select.getAttribute('data-id');
                select_modal_trigger.value = select.getAttribute('data-nama-kelas');
                select_modal_trigger.classList.add('active');
            }
        )
    });
};

const next_btn = document.querySelector('#next-btn');
const roles = document.querySelectorAll('[name="role"]');
const form_body = document.querySelectorAll('.form-body');
if (next_btn != undefined) {
    next_btn.addEventListener(
        'click', function () {
            roles.forEach(role => {
                if (role.checked) {
                    form_body.forEach(form => {
                        form.style.display = 'none';
                        if (form.getAttribute('data-entity') == role.value) {
                            form.style.display = 'block';
                            next_btn.style.display = 'none';
                        };
                    });
                };
            });
        }
    );
};

const back_btn = document.querySelectorAll('button[data-slide="select_role"]');
if (back_btn != undefined) {
    back_btn.forEach(btn => {
        btn.addEventListener(
            'click', function () {
                form_body.forEach(form => {
                    form.style.display = 'none';
                    if (form.getAttribute('data-slide') == btn.getAttribute('data-slide')) {
                        form.style.display = 'block';
                    } else {
                        form.reset();
                        form.querySelectorAll('input').forEach(input => {
                            input.nextElementSibling.classList.remove('active');
                        });
                    };

                    next_btn.style.display = 'block';
                });
            }
        )
    });
};