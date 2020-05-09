function showPicture() {

    let file = document.getElementById("profilePicture");
    console.log(file['files'][0]);

    if (file && file['files'] && file['files'][0]) {
        let img = document.getElementById('adminPic');
        img.src = URL.createObjectURL(file.files[0]);
    }

}