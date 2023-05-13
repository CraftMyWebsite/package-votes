//Toasters
function sendToaster(title, content, type) {
    iziToast.show(
        {
            title: title,
            message: content,
            color: type === "1" ? "green" : "red"
        });
}

// Function for test the unique Id
async function testId(editId = null) {
    let site_id = document.getElementById('idUnique').value;
    let url = document.getElementById('url').value;

    if (editId !== null) {
        site_id = document.getElementById('idUniqueEdit-' + editId).value;
        url = document.getElementById('urlEdit-' + editId).value;
    }

    let formData = {
        'url': url,
        'site_id': site_id
    }


    let request = await fetch('test/id', {
        method: 'POST',
        headers: {"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"},
        body: Object.entries(formData).map(([k, v]) => {
            return k + '=' + v
        }).join('&')
    })

    const response = await request.json();

    sendToaster(response['toaster']['title'], response['toaster']['content'], response['status']);
}

