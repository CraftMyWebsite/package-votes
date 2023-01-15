//Detect reward type & show html

function handleSelectChange(event) {
    let reward_type = event.target.value;

    const btnsave = document.getElementById("reward-type-btn-save");

    switch (reward_type) {
        case "votepoints":
            cleanRewardType();
            createVotePoints();
            break;
        case "votepoints-random":
            cleanRewardType();
            createVotePointsRandom();
            break;
        case "minecraft-commands":
            cleanRewardType();
            createMinecraftCommand();
            break;
        case "none"://If we don't select reward type
            cleanRewardType();
            btnsave.setAttribute("disabled", true);//Change save button status
            break;
        default://Default statement in case of error
            cleanRewardType();
            btnsave.setAttribute("disabled", true);//Change save button status
            break;
    }
}

//For update current reward
function updateReward(e, id) {

    let section = document.getElementById("reward-content-wrapper-update-" + id);

    /* Get action */

    fetch("rewards/get", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: id }),
    })
        .then((response) => response.text())
        .then((response) => {
            let action = JSON.parse(response);

            switch (e.value) {
                case "votepoints":
                    cleanRewardType(section);
                    createVotePoints(action.amount, section);
                    break;
                case "votepoints-random":
                    cleanRewardType(section);
                    createVotePointsRandom(action.amount.min, action.amount.max, section);
                    break;
                case "minecraft-commands":
                    cleanRewardType(section);
                    createMinecraftCommand(action.commands, action.servers, section);
                    break;
                case "none": //If we don't select reward type
                    cleanRewardType(section);
                    break;
            }
        });
}


//Clear old type
function cleanRewardType(parent = null) {

    if (parent === null) {
        parent = document.getElementById("reward-content-wrapper");
    }

    //Clean
    parent.innerHTML = "";

    //Change save button status
    const btn = document.getElementById("reward-type-btn-save");
    btn.removeAttribute("disabled");
}

//Votepoints html
function createVotePoints(amount, parent = null) {

    if (amount === undefined) {
        amount = "";
    }

    if (parent === null) {
        parent = document.getElementById("reward-content-wrapper");
    }


    let div_wrapper = document.createElement("div");
    div_wrapper.setAttribute("class", "input-group mb-3");

    let div_prepend = document.createElement("div");
    div_prepend.setAttribute("class", "input-group-prepend");

    let icon_wrapper = document.createElement("span");
    icon_wrapper.setAttribute("class", "input-group-text");

    let icon = document.createElement("i");
    icon.setAttribute("class", "fas fa-coins");

    let input = document.createElement("input");
    input.setAttribute("value", amount);
    input.setAttribute("placeholder", "Montant")
    input.setAttribute("type", "number")
    input.setAttribute("name", "amount");
    input.setAttribute("class", "form-control");
    input.setAttribute("required", "true");


    parent.append("beforeend", div_wrapper);
    div_wrapper.append("beforeend", div_prepend);
    div_prepend.append("beforeend", icon_wrapper);
    icon_wrapper.append("beforeend", icon);
    div_wrapper.append("beforeend", input);
}

//VotepointsRandom html
function createVotePointsRandom(min, max, parent = null) {

    if (min === undefined || max === undefined) {
        min = "";
        max = "";
    }

    if (parent === null) {
        parent = document.getElementById("reward-content-wrapper");
    }


    let div_wrapper = document.createElement("div");
    div_wrapper.setAttribute("class", "row");

    //Min amount section
    let div_wrapper_min = document.createElement("div");
    div_wrapper_min.setAttribute("class", "col-sm-6");

    let div_form_group_min = document.createElement("div");
    div_form_group_min.setAttribute("class", "form-group");

    let label_min = document.createElement("label");
    label_min.innerText = "Montant minimum";

    let input_min = document.createElement("input");
    input_min.setAttribute("value", min);
    input_min.setAttribute("placeholder", "Montant minimum")
    input_min.setAttribute("type", "number")
    input_min.setAttribute("name", "amount-min");
    input_min.setAttribute("class", "form-control");
    input_min.setAttribute("required", "true");

    //Max amount section
    let div_wrapper_max = document.createElement("div");
    div_wrapper_max.setAttribute("class", "col-sm-6");

    let div_form_group_max = document.createElement("div");
    div_form_group_max.setAttribute("class", "form-group");

    let label_max = document.createElement("label");
    label_max.innerText = "Montant maximum";

    let input_max = document.createElement("input");
    input_max.setAttribute("value", max);
    input_max.setAttribute("placeholder", "Montant maximum")
    input_max.setAttribute("type", "number")
    input_max.setAttribute("name", "amount-max");
    input_max.setAttribute("class", "form-control");
    input_max.setAttribute("required", "true");


    parent.append("beforeend", div_wrapper);
    div_wrapper.append("beforeend", div_wrapper_min);
    div_wrapper_min.append("beforeend", div_form_group_min);
    div_form_group_min.append("beforeend", label_min);
    div_form_group_min.append("beforeend", input_min);

    div_wrapper.append("beforeend", div_wrapper_max);
    div_wrapper_max.append("beforeend", div_form_group_max);
    div_form_group_max.append("beforeend", label_max);
    div_form_group_max.append("beforeend", input_max);

}

function createMinecraftCommand(commands, servers, parent = null) {

    if (commands === undefined || servers === undefined) {
        commands = "";
        servers = [];
    }

    if (parent === null) {
        parent = document.getElementById("reward-content-wrapper");
    }


    let div_wrapper = document.createElement("div");
    div_wrapper.setAttribute("class", "row");

    //Min amount section
    let div_wrapper_commands = document.createElement("div");
    div_wrapper_commands.setAttribute("class", "col-sm-6");

    let div_form_group_commands = document.createElement("div");
    div_form_group_commands.setAttribute("class", "form-group");

    let label_commands = document.createElement("label");
    label_commands.innerText = "Command(s)";

    let input_commands = document.createElement("input");
    input_commands.setAttribute("value", commands);
    input_commands.setAttribute("placeholder", "Command(s), séparez vos commandes avec '|'")
    input_commands.setAttribute("type", "text")
    input_commands.setAttribute("name", "minecraft-commands");
    input_commands.setAttribute("class", "form-control");
    input_commands.setAttribute("required", "true");

    //Max amount section
    let div_wrapper_server = document.createElement("div");
    div_wrapper_server.setAttribute("class", "col-sm-6");

    let div_form_group_server = document.createElement("div");
    div_form_group_server.setAttribute("class", "form-group");

    let label_server = document.createElement("label");
    label_server.innerText = "Serveur";

    let placeholder = document.createElement("p");
    placeholder.innerText = "Vous pouvez utiliser le placeholder {player} pour obtenir le pseudo du joueur qui recevra la récompense."

    let select_server = document.createElement("select");
    select_server.setAttribute("name", "minecraft-servers[]");
    select_server.setAttribute("class", "form-control");
    select_server.setAttribute("required", "true");
    select_server.setAttribute("multiple", "true");


    parent.append("beforeend", div_wrapper);
    div_wrapper.append("beforeend", div_wrapper_commands);
    div_wrapper_commands.append("beforeend", div_form_group_commands);
    div_form_group_commands.append("beforeend", label_commands);
    div_form_group_commands.append("beforeend", input_commands);

    div_wrapper.append("beforeend", div_wrapper_server);
    div_wrapper_server.append("beforeend", div_form_group_server);
    div_form_group_server.append("beforeend", label_server);
    div_form_group_server.append("beforeend", select_server);

    parent.append("beforeend", placeholder);

    getServers(select_server, servers).then(r => r);
}

const getServers = async (select_server, servers) => {
    let url = await fetch(`../minecraft/servers/list/`)
    let jsonData = await url.json();

    for (const [serverId, serverName] of Object.entries(jsonData)) {
        let option = document.createElement("option");
        option.setAttribute("value", `${serverId}`);
        option.innerText = `${serverName}`;

        for (const srvId of servers) {
            srvId === serverId ? option.setAttribute("selected", `true`) : "";
        }

        select_server.append("beforeend", option);
    }
}