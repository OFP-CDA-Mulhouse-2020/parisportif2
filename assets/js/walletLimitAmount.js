export default function walletLimitAmount()
{

    console.log('test Limit Amount for Wallet');

    //setup
    let typingTimer;                //timer identifier
    let doneTypingInterval = 700;  //time in ms (5 seconds)
    const rangeValue = document.getElementById('rangeValue');
    const walletLimitValue = document.getElementById('wallet_limitAmountPerWeek');

    if (rangeValue) {
        window.onload = function () {
            console.log('input number value : ' + rangeValue.value);

            console.log('input range value : ' + walletLimitValue.value);

            rangeValue.onchange = function () {
                console.log(rangeValue.value)
                walletLimitValue.value = rangeValue.value;
            }

            //lancement du compte à rebours sur 'keyup'
            rangeValue.addEventListener('keyup', () => {
                clearTimeout(typingTimer);
                if (rangeValue.value) {
                    typingTimer = setTimeout(doneTyping, doneTypingInterval);
                }
            });

            //quand l'utilisateur à finit d'écrire
            function doneTyping()
            {
                // console.log(rangeValue().value);
                walletLimitValue.value = rangeValue.value;
            }
        }
    }

}