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
                titleSize: '16',
                messageSize: '14',
                icon: 'fa-solid fa-xmark',
                title  : "Votes",
                message: "Vous avez déjà voté",
                color: "#41435F",
                iconColor: '#e42222',
                titleColor: '#e42222',
                messageColor: '#fff',
                balloon: false,
                close: false,
                position: 'bottomRight',
                timeout: 5000,
                animateInside: false,
                progressBar: false,
                transitionIn: 'fadeInLeft',
                transitionOut: 'fadeOutRight',
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