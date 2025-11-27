<?php
    function viewBooks(){
        global $db;
        $query = 'SELECT * FROM books ORDER BY category ASC, bookTitle ASC';
        $statement = $db->prepare($query); // prepare and execute the sql statement
        $statement->execute();
        $bookTable = $statement->fetchAll(); // fetches user record if there is any
        $statement->closeCursor();
    return $bookTable;
    }
    
    function addBooks($ISBN, $bookTitle, $authorName, $bookPrice, $availableCopies, $coverImage, $category){
        global $db;
        $query = 'INSERT INTO books (ISBN, bookTitle, authorName, bookPrice, availableCopies, coverImage, category)
                    VALUES (:ISBN, :bookTitle, :authorName, :bookPrice, :availableCopies, :coverImage, :category)';
        $statement = $db->prepare($query);
        $statement -> bindValue(':ISBN',$ISBN);
        $statement -> bindValue(':bookTitle',$bookTitle);
        $statement -> bindValue(':authorName',$authorName);
        $statement -> bindValue(':bookPrice',$bookPrice);
        $statement -> bindValue(':availableCopies',$availableCopies);
        $statement -> bindValue(':coverImage',$coverImage);
        $statement -> bindValue(':category',$category);
        $addBookSuccess = $statement -> execute();
        $statement->closeCursor();
        return $addBookSuccess;
    }

    function removeBooks($ISBN){
        global $db;
        $query = 'DELETE FROM books WHERE ISBN = :ISBN';
        $statement = $db->prepare($query);
        $statement->bindValue(':ISBN', $ISBN);
        $deleteBookSuccess = $statement->execute();
        $statement->closeCursor();
        return $deleteBookSuccess;
    }

    function getRowISBN($ISBN){
        global $db;
        $query = 'SELECT * FROM books WHERE ISBN = :ISBN';
        $statement = $db->prepare($query);
        $statement->bindValue(':ISBN', $ISBN);
        $statement -> execute();
        $bookRow = $statement ->fetch();
        $statement->closeCursor();
        return $bookRow;
    }
    function editBooks($ISBN, $bookTitle, $authorName, $bookPrice, $availableCopies, $coverImage, $category){
        global $db;
        $query = 'UPDATE books SET  bookTitle= :bookTitle,
                                    authorName= :authorName,
                                    bookPrice= :bookPrice,
                                    availableCopies= :availableCopies,
                                    coverImage =:coverImage,
                                    category=:category
                                WHERE ISBN = :ISBN';
        $statement = $db->prepare($query);
        $statement->bindValue(':ISBN', $ISBN);
        $statement->bindValue(':bookTitle', $bookTitle);
        $statement->bindValue(':authorName', $authorName);
        $statement->bindValue(':bookPrice', $bookPrice);
        $statement->bindValue(':availableCopies', $availableCopies);
        $statement->bindValue(':coverImage', $coverImage);
           $statement->bindValue(':category', $category);
        $editSuccess = $statement ->execute();
        $statement->closeCursor();
        return $editSuccess;
    }

    function borrowBooks($ISBN){
        global $db;
        $query = 'SELECT * FROM books WHERE ISBN = :ISBN';
        $statement = $db->prepare($query);
        $statement->bindValue(':ISBN', $ISBN);
        $statement -> execute();
        $bookRow = $statement ->fetch();
        $statement->closeCursor();
        if($bookRow == null){
            return false;
        }
        if($bookRow['availableCopies']<=0){
            return false;   
        }
        return($bookRow);
    }

    function insertBorrowedBooksTable($userId, $ISBN) {
    global $db;
    $query = 'INSERT INTO borrowedBooks (ISBN, user_id, dateBorrowed, returnDate)
              VALUES (:ISBN, :user_id, NOW(), NULL)';
    $statement = $db->prepare($query);
    $statement->bindValue(':ISBN', $ISBN);
    $statement->bindValue(':user_id', $userId);
    $success = $statement->execute();
    $statement->closeCursor();
    return $success;
}

    function decreaseBookInventory($ISBN){
        global $db;
        $query = 'UPDATE books set availableCopies = availableCopies -1
                  WHERE ISBN = :ISBN AND availableCopies > 0';
        $statement = $db->prepare($query);
        $statement->bindValue(':ISBN', $ISBN);
        $statement->execute();
        $success = $statement->rowCount() > 0;
        $statement->closeCursor();
        return $success;
    }
    // Function for returning books
    function returnBook($userId, $ISBN) {
    global $db;
    // Checks if the user actually borrowed this book
    $query = 'SELECT * FROM borrowedBooks 
              WHERE user_id = :user_id 
              AND ISBN = :ISBN 
              AND returnDate IS NULL';

    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userId);
    $statement->bindValue(':ISBN', $ISBN);
    $statement->execute();
    $borrowRecord = $statement->fetch();
    $statement->closeCursor();

    if (!$borrowRecord) {
        echo "This book is not currently borrowed by this user.";
        return false;
    }

    // Updates borrowedBooks table and mark it as returned
    $query = 'UPDATE borrowedBooks
              SET returnDate = NOW()
              WHERE borrow_id = :borrow_id';

    $statement = $db->prepare($query);
    $statement->bindValue(':borrow_id', $borrowRecord['borrow_id']);
    $statement->execute();
    $statement->closeCursor();

    // 3. Increase book inventory
    $query = 'UPDATE books
              SET availableCopies = availableCopies + 1
              WHERE ISBN = :ISBN';

    $statement = $db->prepare($query);
    $statement->bindValue(':ISBN', $ISBN);
    $statement->execute();
    $statement->closeCursor();

    return true;
}
    // Checks if user has borrowed the book 
    function userHasBorrowed($userId, $ISBN) {
    global $db;
    $query = "SELECT * FROM borrowedBooks 
              WHERE user_id = :user_id 
              AND ISBN = :ISBN 
              AND returnDate IS NULL"; // Checks if they have not returned (null)
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userId);
    $statement->bindValue(':ISBN', $ISBN);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result ? true : false;
}
    // Function returns the row of which user borrowed using a join of the tables
    function viewCart($userId){
    global $db;
    $query = "SELECT  books.ISBN, books.bookTitle, books.authorName,
                      books.bookPrice, books.availableCopies, 
                      books.coverImage, books.category
                FROM books
                JOIN borrowedBooks
                ON books.ISBN = borrowedBooks.ISBN WHERE borrowedBooks.user_id = :user_id
                AND borrowedBooks.returnDate IS NULL";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $userId);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
    }

    // Admin Function keeps track of users who have borrowed books
    function adminTrackBooks(){
    global $db;
    $query = "SELECT users.fname, 
                users.lname, users.email, books.ISBN, 
                books.bookTitle, books.bookPrice, books.coverImage
              FROM borrowedBooks
              JOIN users ON users.id = borrowedBooks.user_id
              JOIN books ON books.ISBN = borrowedBooks.ISBN
              AND borrowedBooks.returnDate IS NULL";
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
    }
?>