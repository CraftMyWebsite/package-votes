let lastWebsiteOpened;
const sendVote = async (siteId, button) => {

    let voteLogic = new VotesLogic(siteId, button)

    if (lastWebsiteOpened !== siteId) {
        voteLogic.startVoteSendLogic()

        openWebsite(siteId)
        lastWebsiteOpened = siteId
    }

    let url = await fetch(`vote/send/${siteId}`)
    let jsonData = await url.json()

    if (jsonData === "not_send") {
        voteLogic.voteNotSendLogic()
        console.log(`waiting vote ${siteId}...`)
        setTimeout(function () {
            sendVote(siteId);
        }, 3000);

    } else if (jsonData === "already_vote") {
        voteLogic.voteAlreadyVotedLogic()
        console.log("You have already vote")
    } else if (jsonData === "send") {
        voteLogic.voteSendLogic()
        console.log("Vote send")
    }

    return jsonData;
}

const openWebsite = (siteId) => {
    fetch(`vote/geturl/${siteId}`)
        .then(x => x.text())
        .then(url => window.open(url));
}