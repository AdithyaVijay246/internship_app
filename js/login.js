$(document).ready(function () {
    $("#loginForm").on("submit", function (e) {
        e.preventDefault(); 

        $.ajax({
            type: "POST",
            url: "php/login.php",
            data: {
                username: $("#loginUser").val(),
                password: $("#loginPass").val()
            },
            success: function (response) {
                if (response.status === "success") {

                    localStorage.setItem("authToken", response.token);
                    window.location.href = "profile.html";
                } else {
                    $("#loginResponse").html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            }
        });
    });
});