
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reason Input Form</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    // User is logged in, fetch user data from the database
    // Database connection details

    // In your PHP code after successful insertion into the 'entry' table
// Update the check-in status in the database

// Now, when the user clicks the "Out" button and checks out, update the check-in status accordingly

    include 'conn.php';
    $email = $_SESSION['email'];

    // Fetch user data from the database
    $query = "SELECT * FROM signup WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profileName = $row['name'];
        $profileEmail = $row['email'];
        $profilePrn = $row['prn'];
        $profileBranch = $row['branch'];
        $profileYear = $row['year'];
        $STATUS = 'IN successfully';

        // Get the current UTC timestamp
        $utcTimestamp = gmdate('Y-m-d H:i:s');

        // Convert UTC to IST
        $utcDateTime = new DateTime($utcTimestamp, new DateTimeZone('UTC'));
        $utcDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $istTimestamp = $utcDateTime->format('Y-m-d H:i:s');

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate reason field
            $reason = trim($_POST["reason"]);
            if (empty($reason)) {
                $reasonErr = "Reason is required";
            } else {
                // Insert data into the 'entry' table with IST timestamp
               // Convert IST timestamp to separate date and time components
$istDate = $utcDateTime->format('Y-m-d');
$istTime = $utcDateTime->format('H:i:s');

// Insert data into the 'entry' table with IST date and time components
$inQuery = "INSERT INTO entry (user_id, name, email, prn, branch, year, entry_date, entry_time, STATUS, reason) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($inQuery);
$stmt->bind_param("isssssssss", $user_id, $profileName, $profileEmail, $profilePrn, $profileBranch, $profileYear, $istDate, $istTime, $STATUS, $reason);

                if ($stmt->execute()) {
                    echo "<script>
                        alert('In Successfully');
                        window.location.href = 'InOut.php'; 
                    </script>";
                } else {
                    echo "<script>
                        alert('Something Went Wrong');
                        window.location.href = 'InOut.php'; 
                    </script>";
                }
            }
        }
    } else {
        echo "<script>
            alert('User not found in the database');
            window.location.href = 'InOut.php'; 
        </script>";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    echo "<script>
        alert('You are not logged in.');
        window.location.href = 'InOut.php'; 
    </script>";
}
?>

<!-- Modal for Reason Selection -->
<div class="modal fade" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reasonModalLabel">Select Reason</h5>
       
      </div>
      <div class="modal-body">
        <form id="reasonForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="form-group">
            <label for="reason">Reason:</label>
            <select class="form-control" id="reason" name="reason" required>
              <option value="">Select</option>
              <option value="Study">Study</option>
              <option value="Book Issue">Book Issue</option>
              <option value="Book Issue">Book Return</option>
              <option value="News Paper Reading">News Paper Reading</option>
              <option value="Digital Section">Digital Section</option>
              <option value="Reprography">Reprography(printing)</option>
            </select>
            <span class="error"><?php echo isset($reasonErr) ? $reasonErr : "";?></span>
          </div>
      </div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
// JavaScript function to open the reason modal when the page loads
$(document).ready(function() {
    $('#reasonModal').modal('show');
});

// JavaScript function to handle form submission
function submitReason() {
  var reason = document.getElementById('reason').value;
  if (reason.trim() !== '') {
    // Here you can perform further processing such as submitting the form or AJAX request
    console.log('Selected Reason:', reason);
    // Close the modal
    $('#reasonModal').modal('hide');
  } else {
    alert('Please select a reason.');
  }
}
</script>

</body>
</html>