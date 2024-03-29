<?php
session_start(); 
// Database connection details
include 'conn.php';
// Retrieve user input (email and PRN)
$email = $_POST['email']; // Assuming email is entered via a form input
$prn = $_POST['prn'];     // Assuming PRN is entered via a form input

// SQL query to check if the user exists in the signup table
$sql = "SELECT * FROM `signup` WHERE `email` = '$email' AND `prn` = '$prn'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['email'] = $email;
    echo "<script>
function checkLocation() {
    // Check if geolocation is supported
    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(position => {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            // Check if user's location falls within the specified latitude and longitude range
            if (userLat >= 17.6485580 && userLat <= 17.6485588 && userLng >= 73.9242132 && userLng <= 73.9242138) {
                // User is within the specified range, redirect to the InOut.php
                window.location.href = 'InOut.php';
            } else {
                // User is not within the specified range, inform the user
                alert('You are not within the allowed location to access this content.');
                window.location.href = 'index.html';
            }
        }, error => {
            // Error in getting user's location
            console.error('Error getting user\'s location:', error);
            alert('Unable to determine your location. Please make sure your device has access to location services and try again later.');
            window.location.href = 'index.html';
        });
    } else {
        // Geolocation is not supported
        alert('Geolocation is not supported by your browser. You cannot access this feature.');
        window.location.href = 'index.html';
    }
}

// Call the function to check the location when the page loads
checkLocation();
</script>"; // Redirect to some page after successful login
    // You can add further actions here if the user exists.
} else {
    echo "<script>
    alert('You are not signed up. Please sign up.');
    window.location.href = 'signUp.html'; // Redirect to signup page
    </script>";
    // You can add further actions here if the user does not exist.
}

$conn->close();
?>
