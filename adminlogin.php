<?php 
include 'conn.php';

// Copy data to finaloutput table
$sql_copy_finaloutput = "INSERT INTO finaloutput (entry_date, entry_time, STATUS, name, branch, year, reason, quit_time, exit_status)
SELECT e.entry_date, e.entry_time, e.STATUS, e.name, e.branch, e.year, e.reason, q.quit_time, q.STATUS
FROM entry e
JOIN quit q ON e.name = q.name
WHERE e.branch NOT LIKE 'Diploma%';";

$conn->query($sql_copy_finaloutput);

// Copy data to diploma table
$sql_copy_diploma = "INSERT INTO diploma (entry_date, entry_time, STATUS, name, branch, year, reason, quit_time, exit_status)
SELECT e.entry_date, e.entry_time, e.STATUS, e.name, e.branch, e.year, e.reason, q.quit_time, q.STATUS
FROM entry e
JOIN quit q ON e.name = q.name
WHERE e.branch LIKE 'Diploma%'";

// Check if the entry does not already exist in the diploma table
$sql_copy_diploma .= " AND NOT EXISTS (SELECT 1 FROM diploma d WHERE d.name = e.name)";

$conn->query($sql_copy_diploma);

$email = $_POST['email'];
$password = $_POST['password'];

// Sanitize user inputs (example)
$email = $conn->real_escape_string($email);
$password = $conn->real_escape_string($password);

$sql = "SELECT * FROM adminlogin WHERE email = '$email' AND password = '$password'";

$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Login successful, redirect to the next page
    header("Location: adminprofile.html");
    exit(); // Ensure script execution stops after the redirect
} else {
    echo "<script>
    alert('Login Unsuccessful');
    window.location.href = 'adminLogin.html'; 
    </script>";
}

$conn->close();
?>
