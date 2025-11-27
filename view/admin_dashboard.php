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
<header><h1>Admin Dashboard</h1></header>

<nav>
<div class="container">
    <a href="/book/index.php?action=library" class="btn btn-primary mx-1">Logout</a>
    <a href="/book/index.php?action=adminTrackBooks" class="btn btn-primary mx-1" id="adminTrackBooksBtn">Track Books</a>
</div> 
</nav>

<div class="container mt-3">


    <!--View add book form-->
    <h3>Add Books</h3>

    <form action="/book/index.php" method="POST">
        <input type="number" name="count" min="1" max="50" required>
    <button class="btn btn-primary" name="action" id="chooseCount" value="adminAddTable">Make Listings</button>

    </form>

    <br><hr>


    <!-- View books form -->
    <h3>Book Inventory</h3>
    <form action="/book/index.php" method="POST">
        <button type="submit" class="btn btn-primary mx-1" id="viewBooksBtn" name="action" value="adminViewTable">
            View Books
        </button>
    </form>

    <br>

    <!-- Books table -->
    <div id="booksTable"
        style="display: <?php echo (isset($action) && $action === 'adminViewTable' && !empty($books)) ? 'block' : 'none'; ?>;">

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Book Cover</th>
                    <th>ISBN</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Copies</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                    <?php $bookCoverPath = $book['coverImage'];
                        if (!file_exists(__DIR__ . '/../' . $book['coverImage'])) {
                            $bookCoverPath = '../images/default_cover.jpg';
                        }
                        ?>

                        <tr>
                            <td><img src="<?php echo $bookCoverPath; ?>" width="150" height="150"></td>
                            <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
                            <td><?php echo htmlspecialchars($book['category']); ?></td>
                            <td><?php echo htmlspecialchars($book['bookTitle']); ?></td>
                            <td><?php echo htmlspecialchars($book['authorName']); ?></td>
                            <td><?php echo "$" . number_format($book['bookPrice'], 2); ?></td>
                            <td><?php echo htmlspecialchars($book['availableCopies']); ?></td>

                            <td>
                                <!--Edit form which uses the ISBN to find specific row-->
                                <form action="/book/index.php" method="POST" style="display:inline;">
                                <input type="hidden" name="selectedISBN" value="<?php echo $book['ISBN']; ?>">
                                <button type="submit" class="btn btn-primary mx-1" id="editBooksBtn" name="action" value="showEditForm">Edit</button>
                                </form>

                                <!-- Remove book form with confirmation dialog -->
                            <form action="/book/index.php" 
                                method="POST" 
                                style="display:inline;"
                                onsubmit="return confirm('WARNING: Are you sure you want to permanently delete the book titled \'<?php echo htmlspecialchars($book['bookTitle']); ?>\' (ISBN: <?php echo htmlspecialchars($book['ISBN']); ?>)? This action cannot be undone.');">

                                <input type="hidden" name="selectedISBN" value="<?php echo htmlspecialchars($book['ISBN']); ?>">
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

</body>
</html>
