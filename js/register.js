$(document).ready(function () {
    $("#registrationForm").on("submit", function (e) {
        e.preventDefault(); // Stop the page from refreshing

        // Basic validation: Check if passwords match
        const password = $("#password").val();
        const confirmPassword = $("#confirmPassword").val();

        if (password !== confirmPassword) {
            $("#responseMessage").html('<div class="alert alert-danger">Passwords do not match!</div>');
            return;
        }

        // Show a loading message
        $("#responseMessage").html('<div class="alert alert-info">Processing...</div>');

        // The AJAX call
        $.ajax({
            type: "POST",
            url: "php/register.php", // Crucial: Points to the subfolder
            data: $(this).serialize(), // Automatically packages form inputs
            success: function (response) {
                // Assuming your PHP returns a success string or JSON
                $("#responseMessage").html('<div class="alert alert-success">' + response + '</div>');
                
                // Optional: Redirect to login after 2 seconds on success
                if (response.includes("successfully")) {
                    setTimeout(() => { window.location.href = "login.html"; }, 2000);
                }
            },
            error: function (xhr, status, error) {
                // Handle server errors (like 500 or 404)
                $("#responseMessage").html('<div class="alert alert-danger">Error: Could not connect to server. Check AWS EC2 firewall.</div>');
                console.error("Status: " + status + ", Error: " + error);
            }
        });
    });
});