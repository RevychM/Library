<?php
require_once(__DIR__ . '/../model/dblibrary.php'); // Absolute path
?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/book/css/styles.css">
    <title>Admin Track Books</title>
</head>

<header><h1>Admin Track Books</h1></header>

<nav>
<div class="container">
    <!-- Change this to where it will log out of the session and perhaps a modal to view cart--> 
    <a href="/book/index.php?action=library" class="btn btn-primary mx-1">Logout</a>
    <a href="view/admin_dashboard.php" class="btn btn-primary mx-1">Return to Dashboard</a>
    </div> 
</nav>

<a href="/book/index.php?action=adminTrackBooks" class="btn btn-primary mx-1" id="adminTrackBooksBtn">Track Books</a>
<div class="container mt-3">

<div id="booksTable" class="mt-4"
     style="display: <?php echo (isset($action) && $action === 'adminTrackBooks' && !empty($trackedBooks)) ? 'block' : 'none'; ?>;">

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Book Cover</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>ISBN</th>
            <th>Book Title</th>
            <th>Book Price</th>
            <th>Remove</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($trackedBooks)): ?>
            <?php foreach ($trackedBooks as $track) :?>
            <?php 
            // This takes the path if it is in the image folder 
            $bookCoverPath = $track['coverImage']; 

            // Check if the file exists or upload the default picture
            if (!file_exists(__DIR__ . '/../' . $track['coverImage'])) {
                $bookCoverPath = '../images/default_cover.jpg';
            }
            ?>
            <tr>
                <td><img src="<?php echo $bookCoverPath ?>"alt= "Book Cover" width="150" height="150"></td>
                <td><?php echo $track['fname']?></td>
                <td><?php echo $track['lname']?></td>
                <td><?php echo $track['email']?></td>
                <td><?php echo $track['ISBN']?></td>
                <td><?php echo $track['bookTitle']?></td>
                <td><?php echo "$" . $track['bookPrice']?></td>

                <!-- This has the same Remove book feature while viewing in cart--> 
                <td>
                    <form action="/book/index.php" method="POST" style="display:inline;">
                    <input type="hidden" name="selectedISBN" value="<?php echo $track['ISBN']; ?>">
                    <button type="submit" class="btn btn-primary mx-1" id="removeBooksBtn" name="action" value="adminRemoveBook">Remove</button>
                    </form>
                </td>
            </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
        </table>
</div>
        </div>
</html>

