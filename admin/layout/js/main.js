$(function () {
    'use strict';

    $('[placeholder]').focus(function () {

        $(this).attr('data-text', $(this).attr('placeholder'))

        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    })
})

let IALIP = document.getElementById('IALIP');
let BLISP = document.getElementById('BLISP');
let iconEye = document.getElementById('icon-eye');

BLISP.addEventListener('click', function () {
    let type = IALIP.getAttribute('type') === 'password' ? 'text' : 'password';
    IALIP.setAttribute('type', type);

    iconEye.classList.toggle('fa-eye');
    iconEye.classList.toggle('fa-eye-slash');
})

function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}