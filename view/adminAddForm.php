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
<header><h1>Admin Add Book</h1></header>

<nav>
<div class="container">
    <a href="/book/index.php?action=adminViewTable" class="btn btn-secondary mx-1">Return to Dashboard</a> 
    
    <a href="/book/index.php?action=library" class="btn btn-primary mx-1">Logout</a>
</div> 
</nav>

<div class="container mt-3">


    <!--Add book form-->
    <h3>Add Books</h3>

    <form action="/book/index.php" method="POST">


        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Book Cover</th>
                    <th>ISBN</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Available Copies</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < $count; $i++): ?>
                <tr>
                    <!--Required for no empty entries-->
                    <td><input type="text" name="coverImages[]" class="form-control" required></td>
                    <td><input type="text" name="ISBNS[]" class="form-control" required></td>
                    <td><input type="text" name="categories[]" class="form-control" required></td>
                    <td><input type="text" name="bookTitles[]" class="form-control" required></td>
                    <td><input type="text" name="authorNames[]" class="form-control" required></td>
                    <td><input type="number" name="bookPrices[]" class="form-control" step="0.01" min="0" required></td>
                    <td><input type="number" name="availableCopies[]" class="form-control" min="0" required></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary mx-1" id="addBooksBtn" name="action" value="adminAddBooks">
            Add Book
        </button>

    </form>