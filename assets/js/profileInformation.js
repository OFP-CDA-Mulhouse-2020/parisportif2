

export default function profileInformation()
{
    console.log('test profile Information');

    const formIdentities = Array.from(document.querySelectorAll('.form_identity'));
    const editBtnIdentity = document.querySelectorAll('.edit-btn_identity');
    const cancelBtnIdentity = document.querySelectorAll('.cancel-btn_identity');

    const formAddresses = Array.from(document.querySelectorAll('.form_address'));
    const editBtnAddress = document.querySelectorAll('.edit-btn_address');
    const cancelBtnAddress = document.querySelectorAll('.cancel-btn_address');

    /* Identity Form */
    editBtnIdentity.forEach(button => {
        button.addEventListener('click', (e) => {
            changeStepIdentityForm('next');
        })
    })

    cancelBtnIdentity.forEach(button => {
        button.addEventListener('click', (e) => {
            changeStepIdentityForm('cancel');
        })
    })

    function changeStepIdentityForm(btn)
    {
        let index = 0;
        const active = document.querySelector('.form_identity.active');
        index = formIdentities.indexOf(active);
        formIdentities[index].classList.remove('active');
        if (btn === 'next') {
            index++;
            formAddresses[1].classList.remove('active');
        } else if (btn === 'cancel') {
            index--;
        }
        formIdentities[index].classList.add('active');
        formAddresses[0].classList.add('active');
    }


    /* Address Form */
    editBtnAddress.forEach(button => {
        button.addEventListener('click', (e) => {
            changeStepAddressForm('next');
        })
    })

    cancelBtnAddress.forEach(button => {
        button.addEventListener('click', (e) => {
            changeStepAddressForm('cancel');
        })
    })

    function changeStepAddressForm(btn)
    {
        let index = 0;
        const active = document.querySelector('.form_address.active');
        index = formAddresses.indexOf(active);
        formAddresses[index].classList.remove('active');
        if (btn === 'next') {
            index++;
            formIdentities[1].classList.remove('active');
        } else if (btn === 'cancel') {
            index--;
        }
        formAddresses[index].classList.add('active');
        formIdentities[0].classList.add('active');
    }


}
