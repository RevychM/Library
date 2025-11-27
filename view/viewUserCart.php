<?php
require_once(__DIR__ . '/../model/dblibrary.php'); // Absolute path
?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/book/css/styles.css">
    <title>View Cart</title>
</head>

<header><h1>View Cart</h1></header>

<nav>
<div class="container">
    <a href="/book/index.php?action=userViewTable" class="btn btn-secondary mx-1">Return to Dashboard</a> 
    
    <a href="/book/index.php?action=library" class="btn btn-primary mx-1">Logout</a>
</div> 
</nav>

<a href="/book/index.php?action=viewUserCart" class="btn btn-primary mx-1" id="viewCartBtn">View Cart</a>

<div id="booksTable" class="mt-4"
     style="display: <?php echo (isset($action) && $action === 'viewUserCart' && !empty($viewCart)) ? 'block' : 'none'; ?>;">

<table class="table table-striped table-hover">

    <thead>
        <tr>
            <th>Book Cover</th>
            <th>ISBN</th>
            <th>Category</th>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
            <th>Return</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($viewCart)): ?>
            <?php foreach ($viewCart as $view) :?>
            <?php 
            // This takes the path if it is in the image folder 
            $bookCoverPath = $view['coverImage']; 

            // Check if the file exists or upload the default picture
            if (!file_exists(__DIR__ . '/../' . $view['coverImage'])) {
                $bookCoverPath = '../images/default_cover.jpg';
            }
            ?>
            <tr>
                <td><img src="<?php echo $bookCoverPath ?>"alt= "Book Cover" width="150" height="150"></td>
                <td><?php echo $view['ISBN']?></td>
                <td><?php echo $view['category']?></td>
                <td><?php echo $view['bookTitle']?></td>
                <td><?php echo $view['authorName']?></td>
                <td><?php echo "$" . $view['bookPrice']?></td>

                <!-- This has the same return book feature while viewing in cart--> 
                <td>
                    <form action="/book/index.php" method="POST">
                        <input type="hidden" name="action" value="returnBook">
                        <input type="hidden" name="ISBN" value="<?php echo $view['ISBN']; ?>">
                        <button class="btn btn-primary" id="returnBooksBtn">Return</button>
                    </form>
                </td>
            
            </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
        </table>
</div>



