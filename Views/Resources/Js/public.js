let lastWebsiteOpened;
let attemptCount = 0;
const sendVote = async (siteId, button) => {

    let voteLogic = new VotesLogic(siteId, button)
    let url = await fetch(`vote/send/${siteId}`)
    let jsonData = await url.json()

    if (lastWebsiteOpened !== siteId) {
        voteLogic.startVoteSendLogic(siteId)

        if (jsonData === "not_send" && lastWebsiteOpened !== siteId) {
            openWebsite(siteId)
        }
        lastWebsiteOpened = siteId
    }

    if (jsonData === "not_send") {
        voteLogic.voteNotSendLogic()
        console.log(`waiting vote ${siteId}...`)
        if (attemptCount < 10) {
            setTimeout(function () {
                sendVote(siteId, button);
            }, 1000);
            attemptCount++;
        } else {
            voteLogic.voteNotFoundLogic(siteId);
            console.log("Max attempts reached. Stopping.");
        }

    } else if (jsonData === "already_vote") {
        voteLogic.voteAlreadyVotedLogic(siteId)
        console.log("You have already vote")
    } else if (jsonData === "send") {
        voteLogic.voteSendLogic(siteId)
        console.log("Vote send")
    }

    return jsonData;
}

const openWebsite = (siteId) => {
    fetch(`vote/geturl/${siteId}`)
        .then(x => x.text())
        .then(url => window.open(url));
}