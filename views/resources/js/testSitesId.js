//Toasters
function toasterIdGood() {
    Swal.fire({
        icon: 'success',
        title: 'Id unique validé !'
    })
}

function toasterIdError() {
    Swal.fire({
        icon: 'error',
        title: 'Id unique non-valide !'
    })
}

// Function for test the unique Id

async function testId(i) {
    const idUnique = document.getElementById('idUnique-' + i).value;
    const url = document.getElementById('url-' + i).value;


    if (url.includes("serveur-prive.net")) {
        let obj = await(await fetch("https://serveur-prive.net/api/stats/json/" + idUnique + "/position")).json();
        if (obj.status === 1) { // If api response = 1 it's good
            toasterIdGood();
        } else {
            toasterIdError();
        }

    } else if (url.includes("serveur-minecraft-vote.fr")) {
        let obj = await(await fetch("https://serveur-minecraft-vote.fr/api/v1/servers/" + idUnique).then(function (response) {
            if (response.status === 200) {
                toasterIdGood();
            } else {
                toasterIdError();
            }
        }));

    } else if (url.includes("serveurs-mc.net")) {
        let obj = await(await fetch("https://serveurs-mc.net/api/hasVote/" + idUnique + "/0.0.0.0/10").then(function (response) {
            if (response.status === 200) {
                toasterIdGood();
            } else {
                toasterIdError();
            }
        }));

    } else if (url.includes("top-serveurs.net")) {
        let obj = await(await fetch("https://api.top-serveurs.net/v1/servers/" + idUnique + "/players-ranking")).json();
        if (obj.code === 200) { // If api response = 200 it's good
            toasterIdGood();
        } else {
            toasterIdError();
        }

    } else if (url.includes("serveursminecraft.org")) {
        let obj = await(await fetch("https://www.serveursminecraft.org/serveur/" + idUnique));
        if (obj.code === 200) { // If api response = 200 it's good
            toasterIdGood();
        } else {
            toasterIdError();
        }

    }


    /*else if (url.includes("serveurs-minecraft.org") && !url.includes("liste-serveurs-minecraft.org")){

        let obj = await fetch("https://serveurs-minecraft.org/api/is_online.php?id=" + idUnique + '&format=ajax', {mode: 'no-cors'});
        if (obj === 0){
            toasterIdGood();
        }else {
            toasterIdError();
        }

    }*//*else if (url.includes("serveur-minecraft.com")){

        //let obj = fetch("https://serveur-minecraft.com/"+idUnique, {mode: 'no-cors'});



    }*/


}

