$(document).ready(function () {
    $("#registrationForm").on("submit", function (e) {
        //  Prevent standard form submission
        e.preventDefault();

        // Get form data
        var username = $("#username").val();
        var password = $("#password").val();
        var confirmPassword = $("#confirmPassword").val();

        // Basic client-side validation
        if (password !== confirmPassword) {
            $("#responseMessage").html('<div class="alert alert-danger">Passwords do not match!</div>');
            return;
        }

        // Send data using Jquery AJAX
        $.ajax({
            type: "POST",
            url: "php/register.php",
            data: {
                username: username,
                password: password
            },
            dataType: "json", // Expecting JSON response from PHP
            success: function (response) {
                if (response.status === "success") {
                    $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                    // Clear form or redirect to login
                    $("#registrationForm")[0].reset();
                    setTimeout(function() {
                        window.location.href = "login.html";
                    }, 2000);
                } else {
                    $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function () {
                $("#responseMessage").html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
            }
        });
    });
});