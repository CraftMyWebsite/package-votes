class VotesLogic extends VotesStatus{
    constructor(siteId, button) {
        super(siteId, button);
    }

    voteAlreadyVotedLogic() {
        //this.button.innerText = "Vous avez déjà voté"

        iziToast.show(
            {
                titleSize: '16',
                messageSize: '14',
                icon: 'fa-solid fa-check',
                title  : "Votes",
                message: "Vous avez déjà voté",
                color: "#41435F",
                iconColor: '#22E445',
                titleColor: '#22E445',
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

    startVoteSendLogic() {
        this.button.innerText = 'Vérification en cours'
    }

    voteNotSendLogic() {
        super.voteNotSendLogic();
    }

    voteSendLogic() {
        this.button.innerText = "Ez t'as voté"
    }
}