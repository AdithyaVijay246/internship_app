$(document).ready(function() {
    // Check if user is logged in 
    const token = localStorage.getItem("authToken");
    if (!token) {
        window.location.href = "login.html";
        return;
    }

    // Load existing profile data from MongoDB 
    $.ajax({
        url: "php/profile.php",
        type: "GET",
        data: { token: token },
        success: function(response) {
            if (response.success) {
                $("#age").val(response.data.age);
                $("#dob").val(response.data.dob);
                $("#contact").val(response.data.contact);
            }
        }
    });

    // Update profile data 
    $("#profileForm").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: "php/profile.php",
            type: "POST",
            data: {
                token: token,
                age: $("#age").val(),
                dob: $("#dob").val(),
                contact: $("#contact").val()
            },
            success: function(res) {
                alert("Profile Updated Successfully!");
            }
        });
    });
    $("#logoutBtn").on("click", function() {
    const token = localStorage.getItem("authToken");
    $.post("php/logout.php", { token: token }, function() {
        // Clear browser storage and redirect
        localStorage.removeItem("authToken");
        window.location.href = "login.html";
    });
    
});
});