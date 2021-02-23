export default function login()
{
    console.log('test login');
    /* 1er Form */
    const editForm = document.querySelector('.edit_email');
    const editBtnEmail = document.querySelector('.button_edit_email');
    console.log(editForm);
    console.log(editBtnEmail);
    /* 2eme Form */
    const editEmailDisable = document.querySelector('.edit_email_disable');
    const btnEditEmailDisable = document.querySelector('.button_edit_email_disable');
    console.log(editEmailDisable);
    console.log(btnEditEmailDisable);
    /* 3eme FORM */
    const passwordForm = document.querySelector('.edit_password');
    const btn_form_password = document.querySelector('.btn_form_password');
    console.log(passwordForm);
    console.log(btn_form_password);
    /* 4eme FORM */
    const edit_password_disable = document.querySelector('.edit_password_disable');
    const btn_edit_password_disable = document.querySelector('.btn_edit_password_disable');
    console.log(edit_password_disable);
    console.log(btn_edit_password_disable);
    /* function active */

    /* 1er Form */
    editForm.classList.add('d-none');
    editBtnEmail.addEventListener('click', (e) => {
            console.log('click')
            editForm.classList.add("d-none");
            editEmailDisable.classList.remove('d-none');
        })
    /* 2eme Form */
    btnEditEmailDisable.addEventListener('click', (e) => {
        console.log('click')
        editEmailDisable.classList.add('d-none');
        editForm.classList.remove('d-none')
    })

    /* 3eme Form */
    passwordForm.classList.add('d-none');
    btn_form_password.addEventListener('click', (e) => {
        console.log('click')
        passwordForm.classList.add("d-none");
        edit_password_disable.classList.remove('d-none');
    })
    /* 4eme FORM */
    btn_edit_password_disable.addEventListener('click', (e) => {
        console.log('click')
        edit_password_disable.classList.add('d-none');
        passwordForm.classList.remove('d-none')
    })
}
