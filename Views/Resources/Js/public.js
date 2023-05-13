const sendVote = async (siteId) => {

    openWebsite(siteId)

    let url = await fetch(`vote/send/${siteId}`)
    let jsonData = await url.json()

    if (jsonData === "not_send") {
        console.log(`waiting vote ${siteId}...`)
        setTimeout(function () {
            sendVote(siteId);
        }, 3000);

    } else if (jsonData === "already_vote") {
        console.log("You have already vote")
    } else if (jsonData === "send") {
        console.log("Vote send")
    }

    return jsonData;
}

const openWebsite = (siteId) => {
  fetch(`vote/geturl/${siteId}`)
      .then(x => x.text())
      .then(url => window.open(url));
}