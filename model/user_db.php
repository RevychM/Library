<?php  

// Function which searches from the users table
function validate_users_table($email, $password){
global $db;
$query = 'SELECT * FROM users WHERE email = :email AND password = :password';
$statement = $db->prepare($query); // prepare and execute the sql statement
$statement->bindValue(':email', $email);
$statement->bindValue(':password', $password);
$statement->execute();
$user = $statement->fetch(); // fetches user record if there is any
$statement->closeCursor();
return $user;
}


// Function which searches from the administrator table
function validate_admin_table($email, $password){
global $db;
$query = 'SELECT * FROM administrators WHERE email = :email AND password = :password';
$statement = $db->prepare($query); // prepare and execute the sql statement
$statement->bindValue(':email', $email);
$statement->bindValue(':password', $password);
$statement->execute();
$admin = $statement->fetch(); // fetches admin record if there is any
$statement->closeCursor();
return $admin;
}

// Function which adds newcomers to users table with required fields
function register_users($fname, $lname, $email, $phNum, $password){
global $db;
$query = 'INSERT INTO users (fname, lname, email, phNum, password)
                    VALUES(:fname, :lname, :email, :phNum, :password)';
$statement = $db->prepare($query); //prepare and execute teh insert query
$statement->bindValue(':fname', $fname);
$statement->bindValue(':lname', $lname);
$statement->bindValue(':email', $email);
$statement->bindValue(':phNum', $phNum);
$statement->bindValue(':password', $password);
$registeredSuccess = $statement->execute(); // becuase this is not returning anything it shows up as null so index will not redirect correctly
$statement->closeCursor();
return $registeredSuccess;
}

// Function which checks for duplicates to users table with required fields

function duplicate_registration ($email){
global $db;
$query = 'SELECT * FROM users WHERE email = :email';
$statement = $db->prepare($query); // prepare and execute the sql statement
$statement->bindValue(':email', $email);
$statement->execute();
$duplicate_user = $statement->fetch(); // fetches user record if there is any
$statement->closeCursor();
 return $duplicate_user ? true : false;   // always boolean
}











?>