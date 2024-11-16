<?php
// Include your database connection file
include('db.php');

// Fetch data from the 'addcategory' table
$sql = "SELECT * FROM addcategory";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category List</title>
    
    <!-- Include DataTables CSS and jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .action-buttons a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }

        .action-buttons a:hover {
            text-decoration: underline;
        }

        .edit-btn, .delete-btn {
            cursor: pointer;
        }

        .category-image {
            width: 100px;
            height: auto;
        }

        table.dataTable {
            width: 100% !important;
        }
    </style>
</head>
<body>



<table id="categoryTable" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>Category Image</th>
            <th>Category Name</th>
            <th>Category Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td><img src='uploads/" . $row['categoryimage'] . "' class='category-image' alt='Category Image'></td>";
                echo "<td>" . $row['categoryname'] . "</td>";
                echo "<td>" . $row['categorydescription'] . "</td>";
                echo "<td class='action-buttons'>
                        <a href='edit_category.php?id=" . $row['id'] . "' class='edit-btn'><i class='fas fa-edit'></i> Edit</a>
                        <a href='delete_category.php?id=" . $row['id'] . "' class='delete-btn'><i class='fas fa-trash'></i> Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No categories found</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#categoryTable').DataTable({
            "pageLength": 10, // Number of rows to display per page
            "lengthChange": false, // Hide the option to change the number of rows displayed
            "searching": true, // Enable the search box
            "ordering": true, // Enable column sorting
            "info": true, // Show table information
            "paging": true // Enable pagination
        });
    });
</script>

<?php
// Close the database connection
$con->close();
?>

</body>
</html>
