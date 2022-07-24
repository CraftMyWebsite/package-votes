

//Detect reward type & show html
let reward_type;
$(document).on('change','#reward_type',function(){
    reward_type = this.value;

    const btnsave = document.getElementById("reward-type-btn-save");

    switch (reward_type){
        case "votepoints":
            cleanRewardType();
            createVotePoints();
            break;
        case "votepoints-random":
            cleanRewardType();
            createVotePointsRandom();
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

});

//For update current reward
function updateReward(e, id){

    let section = document.getElementById("reward-content-wrapper-update-" + id);

    /* Get action */

    $.ajax({
        type: "POST",
        url: "rewards/get",
        async: true,
        dataType: "html",
        data: {
            id: id
        },
        success: function (response){
            let action = JSON.parse(response);

            switch (e.value){
                case "votepoints":
                    cleanRewardType(section);
                    createVotePoints(action.amount, section);
                    break;
                case "votepoints-random":
                    cleanRewardType(section);
                    createVotePointsRandom(action.amount.min, action.amount.max, section);
                    break;
                case "none"://If we don't select reward type
                    cleanRewardType(section);
                    break;
            }

        }
    })

}


//Clear old type
function cleanRewardType(parent = null) {

    if (parent === null){
        parent = document.getElementById("reward-content-wrapper");
    }

    //Clean
    parent.innerHTML = "";

    //Change save button status
    const btn = document.getElementById("reward-type-btn-save");
    btn.removeAttribute("disabled");
}

//Votepoints html
function createVotePoints(amount, parent = null){

    if (amount === undefined){
        amount = "";
    }

    if (parent === null){
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
    input.setAttribute("required", true);


    parent.append(div_wrapper);
    div_wrapper.append(div_prepend);
    div_prepend.append(icon_wrapper);
    icon_wrapper.append(icon);
    div_wrapper.append(input);
}

//VotepointsRandom html
function createVotePointsRandom(min, max, parent = null){

    if (min === undefined || max === undefined){
        min = "";
        max = "";
    }

    if (parent === null){
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
    input_min.setAttribute("required", true);

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
    input_max.setAttribute("required", true);


    parent.append(div_wrapper);
    div_wrapper.append(div_wrapper_min);
    div_wrapper_min.append(div_form_group_min);
    div_form_group_min.append(label_min);
    div_form_group_min.append(input_min);

    div_wrapper.append(div_wrapper_max);
    div_wrapper_max.append(div_form_group_max);
    div_form_group_max.append(label_max);
    div_form_group_max.append(input_max);

}