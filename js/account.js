// FETCH INFORMATION ACCOUNT
function getInfosUser(){
    let myHeaders = new Headers();
    myHeaders.append("X-AUTH-TOKEN", getToken());

    let requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow',
        mode: 'cors',
        credentials: 'include',
    };

    fetch(apiURL + "accountInfo", requestOptions)
        .then (response => {
            if (response.ok){
                return response.json();
            } else {
                throw new Error('Invalid user');
            }
        })
        .then(result => {
            console.log(result);
            return result;
        }) 
        .catch(error => console.log('error', error));
}

// UPDATE PSEUDO


// UPDATE EMAIL


// UPDATE PASSWORD


// DELETE ACCOUNT


// GET INFORMATION FROM USER

