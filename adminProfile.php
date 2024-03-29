<?php

// Database connection details
include 'conn.php';

// Function to export data from multiple tables to separate CSV files
function exportMultipleTablesToCSV($sqlArray, $filenameArray, $conn) {
    // Loop through each SQL query and filename pair
    for ($i = 0; $i < count($sqlArray); $i++) {
        $sql = $sqlArray[$i];
        $filename = $filenameArray[$i];

        // Set appropriate headers for the CSV file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Open a writable stream to the output buffer
        $output = fopen('php://output', 'w');

        // Add a header row to the CSV file
        $header = array("Entry Date","Time","Status","Name","Branch","Year","Reason","Time","Status");
        fputcsv($output, $header);

        // Execute the SQL query
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Loop through the result set and add data to the CSV file
            while ($row = $result->fetch_assoc()) {
                $data = array($row['entry_date'], $row['entry_time'],$row['STATUS'],$row['name'],$row['branch'],$row['year'],$row['reason'],$row['quit_time'],$row['exit_status']);
                fputcsv($output, $data);
            }
        }

        // Close the output stream
        fclose($output);
    }
}

// Define SQL queries for each table
$sqlArray = array(
    "SELECT * FROM finaloutput",
    "SELECT * FROM diploma"
);

// Define filenames for each CSV file
$filenameArray = array(
    "finaloutput_data.csv",
    "diploma_data.csv"
);

// Export data from multiple tables to separate CSV files
exportMultipleTablesToCSV($sqlArray, $filenameArray, $conn);

// Close the database connection
$conn->close();

?>
