/**
 * Login functionality
 */
$("#loginForm").submit(function (e) {

    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    /* Prevent the form from submitting natively */
    e.preventDefault();

    /* If the the username or password is empty, show an alert */
    if (password === "" || username === "") {
        toastr.info("Por favor, rellena todos los campos");
        return;
    }
    /* Make a request to the index.php and serialize the username and password inputs from the form */
    $.post("index.php?action=login_check", $(e.target).serialize())
        /* The user is valid, log in and redirect to home */
        .done(function (data) {
            document.location = "?action=login";
        })
        /* The user is invalid, show an alert */
        .fail(function (data) {
            var response = JSON.parse(data.responseText);
            toastr.error(response.message);
        });
});