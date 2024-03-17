class VotesLogic extends VotesStatus{
    constructor(siteId, button) {
        super(siteId, button);
    }

    voteAlreadyVotedLogic(buttonId) {
        let buttonElement = document.getElementById(buttonId);
        buttonElement.innerText = 'Vous avez déjà voté';
        buttonElement.style.backgroundColor = 'red';
        iziToast.show(
            {
                titleSize: '14',
                messageSize: '12',
                icon: 'fa-solid fa-xmark',
                title  : "Votes",
                message: "Vous avez déjà voté",
                color: "#ab1b1b",
                iconColor: '#ffffff',
                titleColor: '#ffffff',
                messageColor: '#ffffff',
                balloon: false,
                close: true,
                pauseOnHover: true,
                position: 'topCenter',
                timeout: 4000,
                animateInside: false,
                progressBar: true,
                transitionIn: 'fadeInDown',
                transitionOut: 'fadeOut',
            });
    }

    startVoteSendLogic(buttonId) {
        let buttonElement = document.getElementById(buttonId);
        buttonElement.innerText = 'Vérification ...';
        buttonElement.style.backgroundColor = 'green';
    }

    voteNotSendLogic() {
        super.voteNotSendLogic();
    }

    voteSendLogic(buttonId) {
        let buttonElement = document.getElementById(buttonId);
        buttonElement.innerText = "Merci <3";
    }

    voteNotFoundLogic(buttonId) {
        let buttonElement = document.getElementById(buttonId);
        buttonElement.innerText = "Temps dépassé.";
        buttonElement.style.backgroundColor = 'orange';
    }
}