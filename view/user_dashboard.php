<?php
require_once(__DIR__ . '/../model/dblibrary.php'); // Absolute path
?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/book/css/styles.css">
    <title>Library User Dashboard</title>
</head>

<header><h1>User Dashboard</h1></header>

<nav>
<div class="container">
    <!-- Change this to where it will log out of the session and perhaps a modal to view cart--> 
    <a href="/book/index.php?action=library" class="btn btn-primary mx-1">Logout</a>
    <a href="/book/index.php?action=viewUserCart" class="btn btn-primary mx-1">View Cart</a>
    </div> 
</nav> 

 <form action="/book/index.php" method="POST">
    <input type="hidden" name="action" value="userViewTable">
    <button class="btn btn-primary" id="viewBooksBtn">View Books</button>
</form>
    <div id="booksTable" class="mt-4"
     style="display: <?php echo (isset($action) && $action === 'userViewTable' && !empty($books)) ? 'block' : 'none'; ?>;">

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
                

                <?php 
                $userId = $_SESSION['user_id'];
                $isBorrowed = userHasBorrowed($userId, $book['ISBN']);
                ?>

            <td>
                <?php 
                $userId = $_SESSION['user_id'];
                $isBorrowed = userHasBorrowed($userId, $book['ISBN']);
                $isOutOfStock = ($book['availableCopies'] <= 0);
                ?>
                
                <?php if ($isBorrowed): ?>
                    <button class="btn btn-secondary" id="borrowBooksBtn" disabled>Borrowed</button>
                
                <?php elseif ($isOutOfStock): ?>
                    <button class="btn btn-danger" id="borrowBooksBtn" disabled>Out of Stock</button>

                <?php else: ?>
                    <form action="/book/index.php" method="POST">
                        <input type="hidden" name="action" value="borrowBook">
                        <input type="hidden" name="ISBN" value="<?php echo $book['ISBN']; ?>">
                        <button class="btn btn-primary" id="borrowBooksBtn">Borrow</button>
                    </form>
                <?php endif; ?>
            </td>

            <td>
                <?php if ($isBorrowed): ?>
                <!-- Show Return button -->
        <form action="/book/index.php" method="POST">
            <input type="hidden" name="action" value="returnBook">
            <input type="hidden" name="ISBN" value="<?php echo $book['ISBN']; ?>">
            <button class="btn btn-primary" id="returnBooksBtn">Return</button>
        </form>
        <?php else: ?>
                <!-- No return available -->
        <button class="btn btn-primary" id="returnBooksBtn" disabled>Return</button>
        <?php endif; ?>
</td>
            </tr>
                <?php endforeach; ?>
                <?php endif; ?>
        </table>
    </div>

</html>


