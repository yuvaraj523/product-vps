<?php
include('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // You don't need to retrieve or set the id here
    $categoryname = $con->real_escape_string($_POST['categoryname']);
    $categorydescription = $con->real_escape_string($_POST['categorydescription']);
        // Set the target directory for image uploads
        $target_dir = "uploads/";
        $categoryimage = ""; // Placeholder for the image name
    
        // Check if the uploads directory exists, create if not
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
    
        // Handle image upload if a file is uploaded
        if (isset($_FILES["categoryimage"]) && $_FILES["categoryimage"]["error"] == 0) {
            // Define the target file path
            $target_file = $target_dir . basename($_FILES["categoryimage"]["name"]);
            $categoryimage = basename($_FILES["categoryimage"]["name"]);
    
            // Attempt to move the uploaded file to the server
            if (move_uploaded_file($_FILES["categoryimage"]["tmp_name"], $target_file)) {
                // Success message for debugging
                echo "The file " . htmlspecialchars($categoryimage) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "No file was uploaded or there was an error uploading the file.";
        }

    // Adjust the SQL query to exclude the id
    $sql = "INSERT INTO addcategory (categoryname, categorydescription,categoryimage)
            VALUES ('$categoryname', '$categorydescription','$categoryimage')";

    if ($con->query($sql) === TRUE) {
        header("Location: add_categroy.php");
        exit(); 
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

$con->close();
?>

