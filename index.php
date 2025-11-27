<?php
session_start();
require_once('model/dblibrary.php');
require('model/user_db.php');
require('model/book_db.php');

// Gets the action from URL or the form
$action = filter_input(INPUT_POST,'action');
    if($action == NULL){
        $action = filter_input(INPUT_GET, 'action');
        if($action == NULL){
        $action = 'library'; // If no action clicked library is the "homepage"
    }
    }

switch($action){
    //Loads the original library form
    case 'library':
        include('view/library.php');
        break;

    // This fetches the result from viewBooks and stores in into the array
    case 'viewBooksTable':
        $books = viewBooks();
        include('view/library.php');
        break;

    // Same view books table but keeps the view within admin file with extra privileges
    case 'adminViewTable':
        $books = viewBooks();
        include('view/admin_dashboard.php');
        break;
    
    // This will redirect to admin add form
    case 'adminAddTable':
    $count = filter_input(INPUT_POST, 'count', FILTER_VALIDATE_INT); // Takes the count variable to be creating rows 
        include('view/adminAddForm.php');
        break;
    // Case which adds a book to the books table
    case 'adminAddBooks':
        // Form sends arrays
        $ISBNs = $_POST['ISBNS'];
        $titles = $_POST['bookTitles'];
        $authors = $_POST['authorNames'];
        $prices = $_POST['bookPrices'];
        $copies = $_POST['availableCopies'];
        $covers = $_POST['coverImages'];
        $categories = $_POST['categories'];
        $count;

        // For loop makes rows depending on count given by admin and loop through ISBNS
        for ($i = 0; $i < count($ISBNs); $i++) {
        // Skip empty rows
        if (empty($ISBNs[$i])){
            continue;
        } 
        // Inserts a book for that row
        addBooks(
            $ISBNs[$i],
            $titles[$i],
            $authors[$i],
            $prices[$i],
            $copies[$i],
            $covers[$i],
            $categories[$i]
        );
    }

    header("Location: index.php?action=adminViewTable&success=multipleAdded");
    exit;

    // Case which removes books by selected primary id (ISBN)
    case 'adminRemoveBook':
        $ISBN = filter_input(INPUT_POST, 'selectedISBN');
        $bookDeleted = removeBooks($ISBN); // Store the remove books function in this var
        if($bookDeleted){
            header("Location: index.php?action=adminViewTable&success=deletionSuccess");
            exit;
        }
        if(!$bookDeleted){
            header("Location: index.php?action=adminViewTable&success=deletionError");
            exit;
        }
        break;
    
    // Case redirects and grabs the row needing to edit
    case 'showEditForm':
        $ISBN = filter_input(INPUT_POST, 'selectedISBN');
        $rowReturned = []; 
        $rowReturned = getRowISBN($ISBN);
        include('view/adminEditForm.php');
        break;

    // Case which performs the action editing the row
    case 'adminEditBook':
        $ISBN = filter_input(INPUT_POST, 'selectedISBN');
        $bookTitle = filter_input(INPUT_POST, 'bookTitle');
        $authorName = filter_input(INPUT_POST, 'authorName');
        $bookPrice = filter_input(INPUT_POST, 'bookPrice', FILTER_VALIDATE_FLOAT);
        $availableCopies = filter_input(INPUT_POST, 'availableCopies', FILTER_VALIDATE_INT);
        $coverImage = filter_input(INPUT_POST, 'coverImage');
        $category = filter_input(INPUT_POST, 'category');

        // Stores output of the function to the var
        $bookEdited = editBooks($ISBN, $bookTitle, $authorName, $bookPrice, $availableCopies, $coverImage, $category);
        if($bookEdited){
            header("Location: index.php?action=adminViewTable&success=editSuccess");
            exit;
        }
        if(!$bookEdited){
            header("Location: index.php?action=adminViewTable&success=editError");
            exit;
        }
        include('view/adminEditForm.php');
        break;
    
    // Helps return rows page which admins can track users
    case 'adminTrackBooks':
        $ISBN = filter_input(INPUT_POST, 'ISBN');
        $trackedBooks = [];
        $trackedBooks = adminTrackBooks($ISBN);
        include('view/adminTrackBooks.php');
        break;

    // Users able to view books table and borrow books reroute
    case 'userViewTable':
        $books = viewBooks();
        include('view/user_dashboard.php');
        break;
    
    //So it initially loads the form 
    case 'login':
        include('view/login.php');
        break;

    // This handles the form submission and reroutes to profile pages
    case 'validate_login':
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        // If it is a user or an admin using the validate method check the db for the same email and password
        $user = validate_users_table($email, $password);
        $admin = validate_admin_table($email, $password);

        if($user){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user['fname'] . ' ' . $user['lname']; 
            header("Location: index.php?action=userViewTable");
            exit;
        }
        else if($admin){
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin'] = $admin['fname'] . ' ' . $admin['lname'];
            header("Location: index.php?action=adminViewTable");
            exit;
        }
        else{
            header("Location: index.php?action=login&error=invalid");
            exit;
        }
        break;

    // Case loads the register form
    case 'register':
        include ('view/register.php');
        break;

    // Handles registration submission
    case 'registration_submission':
        $fname = filter_input(INPUT_POST, 'fname');
        $lname = filter_input(INPUT_POST, 'lname');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phNum = filter_input(INPUT_POST, 'phNum');
        $password = filter_input(INPUT_POST, 'password');

        // Stores method for registering users into var
        $duplicate_user =  duplicate_registration ($email);
        // Checks for duplicate first
        if($duplicate_user == true){
            header("Location: index.php?action=register&error=exists");
            echo "Account exists";
            exit;
        }
        // Then if not duplicate then it will register
        $registered_user = register_users($fname, $lname, $email, $phNum, $password);
        if($registered_user){
            header("Location: index.php?action=login&success=registered");
            echo "Account Created";
            exit;
        }
        header("Location: index.php?action=register&error=unknown");
        break;
    
    // Case which borrows book
    case 'borrowBook':
        $ISBN = filter_input(INPUT_POST, 'ISBN');
        $userId = $_SESSION['user_id'];   // Session value

        if (!$userId) { 
            header("Location: index.php?action=login&error=notLoggedIn");
            exit;
        }
        // Books which have been borrowed store inside variable
        $book = borrowBooks($ISBN);

        if ($book == false) {
            header("Location: index.php?action=userViewTable&error=errorBorrow");
            exit;
        }
        // Stores borrowed books table insertion to var
        $inserted = insertBorrowedBooksTable($userId, $ISBN);

        if ($inserted == false) {
        header("Location: index.php?action=userViewTable&error=errorInsert");
        exit;
        }
        // Stores decreased inventory for the ISBN to row so changes available copies
        $inventoryUpdated = decreaseBookInventory($ISBN);

        if (!$inventoryUpdated) {
            header("Location: index.php?action=userViewTable&error=errorInventoryUpdate");
            exit;
        }

    header("Location: index.php?action=userViewTable&success=borrowed");
    exit;

    // Case which returns book 
    case 'returnBook':
        $ISBN = filter_input(INPUT_POST, 'ISBN');
        $userId = $_SESSION['user_id'];
        // Stores books returned to var
        $bookReturned = returnBook($userId, $ISBN);

        if($bookReturned){
            header("Location: index.php?action=userViewTable&success=bookReturnSuccess");
            exit;
        }
        if($bookReturned == NULL){
            header("Location: index.php?action=userViewTable&error=bookReturnFail");
            exit;
        }
        break;
    
    // Case which views user cart
    case 'viewUserCart':
        $userId = $_SESSION['user_id'];
        $viewCart = [];
        $viewCart = viewCart($userId); // Stires view cart values output to array
        include('view/viewUserCart.php');
        exit;
    
    // Default case which stays at the main menu
    default:
        include('view/library.php');
        break;

    }
?>
