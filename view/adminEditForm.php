<?php
require_once(__DIR__ . '/../model/dblibrary.php');
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/book/css/styles.css">
    <title>Library Admin Dashboard</title>
</head>

<body>
<header><h1>Edit Book</h1></header>

<nav>
<div class="container">
    <a href="/book/index.php?action=adminViewTable" class="btn btn-secondary mx-1">Return to Dashboard</a> 
    
    <a href="/book/index.php?action=library" class="btn btn-primary mx-1">Logout</a>
</div> 
</nav>

<div class="container mt-3">

<form action="/book/index.php" method="POST">
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Book Cover</th>
            <th>Category</th>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
            <th>Available Copies</th>
        </tr>
    </thead>
     <tbody>
        <tr>
        <!--Required for no empty entries-->
        <td><input type="text" name="coverImage" class="form-control" required value="<?php echo $rowReturned['coverImage'] ?>"></td>
        <input type="hidden" name="selectedISBN" class="form-control" required value="<?php echo $rowReturned['ISBN'] ?>">
        <td><input type="text" name="category" class="form-control" required value="<?php echo $rowReturned['category'] ?>"></td>
        <td><input type="text" name="bookTitle" class="form-control" required value="<?php echo $rowReturned['bookTitle'] ?>"></td>
        <td><input type="text" name="authorName" class="form-control" required value="<?php echo $rowReturned['authorName'] ?>"></td>
        <td><input type="number" name="bookPrice" class="form-control" step="0.01" min="0" required value="<?php echo $rowReturned['bookPrice'] ?>"></td>
        <td><input type="number" name="availableCopies" class="form-control" min="0" required value="<?php echo $rowReturned['availableCopies'] ?>"></td>
        <td><button type="submit" class="btn btn-primary mx-1" id="editBooksBtn" name="action" value="adminEditBook">Edit</button></td>
        </tr>
    </tbody>
</table>

</form>

    
</body>
</html>
