let lastWebsiteOpened;

const sendVote = async (siteId, button, attemptCount = 0) => {
    const voteLogic = new VotesLogic(siteId, button);
    let response = await fetch(`vote/send/${siteId}`);
    let jsonData = await response.json();

    if (lastWebsiteOpened !== siteId) {
        voteLogic.startVoteSendLogic(siteId);

        if (jsonData === "not_send" && lastWebsiteOpened !== siteId) {
            await openWebsite(siteId);
        }
        lastWebsiteOpened = siteId;
    }

    if (jsonData === "not_send") {
        voteLogic.voteNotSendLogic();
        console.log(`waiting vote ${siteId}...`);
        if (attemptCount < 10) {
            setTimeout(() => {
                sendVote(siteId, button, attemptCount + 1);
            }, 1000);
        } else {
            voteLogic.voteNotFoundLogic(siteId);
            console.log("Max attempts reached. Stopping.");
        }

    } else if (jsonData === "already_vote") {
        voteLogic.voteAlreadyVotedLogic(siteId);
        console.log("You have already voted");
    } else if (jsonData === "send") {
        voteLogic.voteSendLogic(siteId);
        console.log("Vote sent");
    }

    return jsonData;
};

const openWebsite = async (siteId) => {
    let popup = window.open('about:blank', '_blank');
    if (!popup) {
        alert("Veuillez autoriser les popups pour ce site.");
        return;
    }
    try {
        let response = await fetch(`vote/geturl/${siteId}`);
        popup.location = await response.text();
    } catch (err) {
        popup.close();
        alert("Impossible d'ouvrir le site de vote.");
    }
};
