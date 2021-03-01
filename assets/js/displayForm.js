

export default function displayForm()
{

    const form1Enabled = document.querySelector('.form1.enabled');
    const form1Disabled = document.querySelector('.form1.disabled');
    const editBtn1 = document.querySelector('.edit-btn1');
    const cancelBtn1 = document.querySelector('.cancel-btn1');

    const form2Enabled = document.querySelector('.form2.enabled');
    const form2Disabled = document.querySelector('.form2.disabled');
    const editBtn2 = document.querySelector('.edit-btn2');
    const cancelBtn2 = document.querySelector('.cancel-btn2');

    if(form1Enabled && form2Enabled){
        /* Form 1 */
        editBtn1.addEventListener('click', (e) => {
            form1Enabled.classList.toggle('d-none');
            form1Disabled.classList.toggle('d-none');
            form2Enabled.classList.add('d-none');
            form2Disabled.classList.remove('d-none');

        });

        cancelBtn1.addEventListener('click', (e) => {
            form1Enabled.classList.toggle('d-none');
            form1Disabled.classList.toggle('d-none');
        });


        /* Form 2 */
        editBtn2.addEventListener('click', (e) => {
            form2Enabled.classList.toggle('d-none');
            form2Disabled.classList.toggle('d-none');
            form1Enabled.classList.add('d-none');
            form1Disabled.classList.remove('d-none');

        });

        cancelBtn2.addEventListener('click', (e) => {
            form2Enabled.classList.toggle('d-none');
            form2Disabled.classList.toggle('d-none');
        });

    }else if(form1Enabled){
        /* Form 1 */
        editBtn1.addEventListener('click', (e) => {
            form1Enabled.classList.toggle('d-none');
            form1Disabled.classList.toggle('d-none');

        });

        cancelBtn1.addEventListener('click', (e) => {
            form1Enabled.classList.toggle('d-none');
            form1Disabled.classList.toggle('d-none');
        });

    }

}
