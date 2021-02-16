export default function profileInformation()
{
    console.log('test profile Information');
    const formIdentities = Array.from(document.querySelectorAll('.form_identity'));
    const editBtn = document.querySelectorAll('.edit-btn');
    const cancelBtn = document.querySelectorAll('.cancel-btn');
    console.log(formIdentities)

    editBtn.forEach(button => {
        button.addEventListener('click', (e) => {
            changeStep('next');
        })
    })

    cancelBtn.forEach(button => {
        button.addEventListener('click', (e) => {
            changeStep('cancel');
        })
    })

    function changeStep(btn)
    {
        let index = 0;
        const active = document.querySelector('.form_identity.active');
        index = formIdentities.indexOf(active);
        formIdentities[index].classList.remove('active');
        if (btn === 'next') {
            index++;
        } else if (btn === 'cancel') {
            index--;
        }
        formIdentities[index].classList.add('active');
    }

}
