<?php
require_once(__DIR__ . '/../model/dblibrary.php'); // Absolute path
?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/book/css/styles.css">
    <title> Library </title>
</head>
<header><h1>KPU Library</h1></header>

<nav>
<div class="container">
    <a href="/book/index.php?action=login" class="btn btn-primary mx-1">Login</a>
    <a href="/book/index.php?action=register" class="btn btn-primary mx-1">Register</a>
    </div> 
</nav> 

<body>
     
    <form action="/book/index.php" method="POST">
    <input type="hidden" name="action" value="viewBooksTable">
    <button class="btn btn-primary" id="viewBooksBtn">View Books</button>

    <div class="container mt-3">

    <div id="booksTable" class="mt-4"
     style="display: <?php echo (isset($action) && $action === 'viewBooksTable' && !empty($books)) ? 'block' : 'none'; ?>;">

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

            <!-- This will check if books array is empty if not then for each loop will print values within row by row --> 
            <?php if (!empty($books)): ?>
            <?php foreach ($books as $book) :?>
            <?php 
            // This takes the path if it is in the image folder 
            $bookCoverPath = $book['coverImage']; 

            // Check if the file exists or upload the default picture
            if (!file_exists(__DIR__ . '/../' . $book['coverImage'])) {
                $bookCoverPath = '../images/default_cover.jpg';
            }
            ?>
                <tr>
                <td><img src="<?php echo $bookCoverPath ?>"alt= "Book Cover" width="150" height="150"></td>
                <td><?php echo $book['ISBN']?></td>
                <td><?php echo $book['category']?></td>
                <td><?php echo $book['bookTitle']?></td>
                <td><?php echo $book['authorName']?></td>
                <td><?php echo "$" . $book['bookPrice']?></td>
                <td><?php echo $book['availableCopies']?></td>
                <td><a href="/book/index.php?action=login" button class="btn btn-primary" id="checkOutBtn" a href="/book/index.php?action=login">Check out</button></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
        </table>
    </div>
        </div>
</form>
    
</body>

</html>