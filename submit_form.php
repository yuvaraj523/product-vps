<?php
include('db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $productName = $con->real_escape_string($_POST['productName']);
    $productDescription = $con->real_escape_string($_POST['productDescription']);
    $productCategory = $con->real_escape_string($_POST['productCategory']);
    $keyword = $con->real_escape_string($_POST['keyword']);
    $priceAmount = $con->real_escape_string($_POST['priceAmount']);

    $target_dir = "uploads/";
    $productImage = ""; 

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] == 0) {
        $target_file = $target_dir . basename($_FILES["productImage"]["name"]);
        $productImage = basename($_FILES["productImage"]["name"]);  

        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars($productImage) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "No file was uploaded or there was an error uploading the file.";
    }

    $product_pdf = "";
    if (isset($_FILES['productPDF']) && $_FILES['productPDF']['error'] === UPLOAD_ERR_OK) {
        $pdfTmpPath = $_FILES['productPDF']['tmp_name'];
        $pdfName = $_FILES['productPDF']['name'];
        $pdfSize = $_FILES['productPDF']['size'];
        $pdfNameCmps = explode(".", $pdfName);
        $pdfExtension = strtolower(end($pdfNameCmps));

        if ($pdfExtension === 'pdf' && $pdfSize < 5000000) { 
            $uploadPdfDir = 'uploads/';
            $pdfDestPath = $uploadPdfDir . $pdfName;

            if (move_uploaded_file($pdfTmpPath, $pdfDestPath)) {
                echo "The PDF file has been successfully uploaded.";
                $product_pdf = $pdfName; 
            } else {
                echo "There was an error moving the uploaded PDF file.";
            }
        } else {
            echo "Upload failed. Only PDF files under 5MB are allowed.";
        }
    } else {
        echo "No PDF file uploaded or there was an upload error.";
    }

    $sql = "INSERT INTO list (product_image, product_name, product_description, product_category, keyword, price_amount, product_pdf)
            VALUES ('$productImage', '$productName', '$productDescription', '$productCategory', '$keyword', '$priceAmount', '$product_pdf')";

    if ($con->query($sql) === TRUE) {
        header("Location: list.php");
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

$con->close();
?>
