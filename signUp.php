<?php
session_start(); 
include 'conn.php';

// Retrieve user input from the form (make sure to sanitize and validate)
$branch = $_POST['branch'];
$year = $_POST['year'];
$prn = $_POST['prn'];
$email = $_POST['email'];
$name = $_POST['name'];

// SQL query to check if email already exists
$check_query = "SELECT * FROM `signup` WHERE `email` = '$email'";
$result = $conn->query($check_query);

if ($result->num_rows > 0) {
    echo "<script>
alert('You are already signed up. Please go and sign in');
window.location.href = 'signIn.html'; 
</script>";
    
    
} else {
    // Email does not exist, proceed with insertion
    // SQL query to insert data into the signup table
    $sql = "INSERT INTO `signup`(`branch`, `year`, `prn`, `email`, `name`) VALUES ('$branch','$year','$prn','$email','$name')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['email'] = $email;
        
        // Include the location check JavaScript code before redirection
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
</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
