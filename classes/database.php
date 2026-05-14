<?php

class database{

    function opencon() : PDO{
        return new PDO(
    dsn: 'mysql:host=localhost;
        dbname=library_valenzuela',
        username:'root', //default
        password:''); //default
    }

    function insertUser($email, $password_hash, $is_active){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Users (username, password_hash, is_active) VALUES (?,?,?)');
            $stmt->execute([$email, $password_hash, $is_active]);
            $user_id = $con->lastInsertId();
            $con->commit();
            return $user_id;


        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;

        }
    }

    function insertBorrowers($firstname, $lastname, $email, $phone, $member_since, $is_active){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Borrowers (borrower_firstname, borrower_lastname, borrower_email, borrower_phone_number, borrower_member_since, is_active ) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$firstname, $lastname, $email, $phone, $member_since, $is_active]);
            $borrowers_id = $con->lastInsertId();
            $con->commit();
            return $borrowers_id;


        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;

        }
    }

    function insertBorrowerUser($user_id, $borrower_id){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO BorrowerUser (user_id, borrower_id) VALUES (?,?)');
            $stmt->execute([$user_id, $borrower_id]);
            $bu_id = $con->lastInsertId();
            $con->commit();
            return $bu_id;


        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;

        }
    }

    function viewBorrowerUser(){
        $con = $this->opencon();
        return $con->query("SELECT * from Borrowers")->fetchAll();
     }


     function insertBorroweraddress($borrower_id, $house_number, $street, $barangay, $city, $province, $postal_code, $is_primary){
        
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borroweraddress (borrower_id, ba_house_number, ba_street, ba_barangay, ba_city, ba_province, ba_postal_code, is_primary) VALUES (?,?,?,?,?,?,?,?)');
            $stmt->execute([$borrower_id, $house_number, $street, $barangay, $city, $province, $postal_code, $is_primary]);
            $ba_id = $con->lastInsertId();
            $con->commit();
            return $ba_id;
        
     }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;

    }
}

    function insertBook($title, $ISBN, $publication_year, $edition, $publisher){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Books (book_title, book_isbn, book_publication_year, book_edition, book_publisher) VALUES (?,?,?,?,?)');
            $stmt->execute([$title, $ISBN, $publication_year, $edition, $publisher]);
            $book_id = $con->lastInsertId();
            $con->commit();
            return $book_id;

    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }

    }

    function viewBooks(){
         $con = $this->opencon();
        return $con->query("SELECT * from Books")->fetchAll();

    }

    function insertBookCopy($book_id, $status){

     $con = $this->opencon();
        
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO bookcopy(book_id, bc_status) VALUES(?,?)');
            $stmt->execute([$book_id, $status]);
            $copy_id = $con->lastInsertId();
            $con->commit();
            return $copy_id;
        
     }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;

    }

    }

    function viewAuthors(){
        $con = $this->opencon();
        return $con->query("SELECT * from Author")->fetchAll();

    }

    function insertBookAuthor($book_id, $author_id){

        $con = $this->opencon();
           
           try{
               $con->beginTransaction();
               $stmt = $con->prepare('INSERT INTO bookauthors(book_id, author_id) VALUES(?,?)');
               $stmt->execute([$book_id, $author_id]);
               $baba_id = $con->lastInsertId();
               $con->commit();
               return $baba_id;
           
        }catch(PDOException $e){
           if($con->inTransaction()){
               $con->rollBack();
           }
           throw $e;
   
       }
   
       }

       function viewGenre(){
        $con = $this->opencon();
        return $con->query("SELECT *

        FROM genre

        order BY genre_id ASC

        ")->fetchAll();

    }

    function insertBookGenre($genre_id, $book_id){

        $con = $this->opencon();
           
           try{
               $con->beginTransaction();
               $stmt = $con->prepare('INSERT INTO bookgenre(genre_id, book_id) VALUES(?,?)');
               $stmt->execute([$genre_id, $book_id]);
               $gb_id = $con->lastInsertId();
               $con->commit();
               return $gb_id;
           
        }catch(PDOException $e){
           if($con->inTransaction()){
               $con->rollBack();
           }
           throw $e;
   
       }
   
       }

       function viewCopies(){
        $con = $this->opencon();
        return $con->query("SELECT
    books.book_id,
    books.book_title,
    books.book_isbn,
    books.book_publication_year,
    books.book_publisher,
    COUNT(bookcopy.copy_id) AS Copies,
    SUM(bookcopy.bc_status = 'AVAILABLE') AS Available_copies
    
    FROM books
    
    LEFT JOIN bookcopy ON books.book_id = bookcopy.book_id
    
    GROUP BY 1

    ")->fetchAll();

       }


       function recentLoans(){
        $con = $this->opencon();
        return $con->query("SELECT 
	loan.loan_id,
    CONCAT(borrowers.borrower_firstname, ' ', borrowers.borrower_lastname) AS borrower,
    loan.loan_status,
    loan.loan_date,
    users.username AS processed_by_user
    
    FROM loan
    
    JOIN borrowers ON loan.borrower_id=borrowers.borrower_id
    JOIN users ON loan.processed_by_user_id = users.user_id
    
    GROUP BY 1
    
        ")->fetchAll();
       }


       function borrowerList(){
        $con = $this->opencon();
        return $con->query("SELECT

	borrowers.borrower_id,
    CONCAT(borrowers.borrower_firstname, ' ', borrowers.borrower_lastname) AS borrower_name,
    borrowers.borrower_email,
    
    CASE 
     WHEN borrowers.is_active = 1 THEN 'YES'
     ELSE 'NO'
     END AS borrower_active, 
     
    CASE
    WHEN users.is_active = 1 THEN 'YES'
    ELSE 'NO'
    END AS user_active
    
    FROM borrowers
    
    JOIN borroweruser ON borrowers.borrower_id = borroweruser.borrower_id
    JOIN users ON borroweruser.user_id = users.user_id
    
    
    GROUP BY 1
    
    ")->fetchAll();
    }

    function totalBooks(){
        $con = $this->opencon();
        return $con->query("SELECT

	COUNT(books.book_id) AS total_books
    
    FROM books

    
    ")->fetchAll();
    }

    function totalCopies(){
        $con = $this->opencon();
        return $con->query("SELECT

	COUNT(bookcopy.copy_id) AS total_copies
    
    FROM bookcopy

    
    ")->fetchAll();
    }

    function openLoans(){
        $con = $this->opencon();
        return $con->query("SELECT

	COUNT(loan.loan_id) AS open_loans
    
    FROM loan
    
    WHERE loan.loan_status = 'OPEN'

    
    ")->fetchAll();
    }

    function overDue(){
        $con = $this->opencon();
        return $con->query("SELECT

	COUNT(CASE 
              WHEN loanitem.li_returned_at IS NOT NULL AND DATEDIFF(loanitem.li_returned_at, loanitem.li_duedate) > 0 THEN 1 
              WHEN loanitem.li_returned_at IS NULL AND loanitem.li_duedate < CURRENT_DATE THEN 1
         END) AS overdue_count
	
    FROM loan

	JOIN loanitem ON loan.loan_id = loanitem.loan_id
	
	

    
    ")->fetchAll();
    }

    function viewBorrowers(){
        $con = $this->opencon();
        return $con->query("SELECT * from Borrowers")->fetchAll();

    }


    function updateBook($book_id, $title, $isbn, $year, $publisher)
{
    $con = $this->opencon();
 
    try {
        $con->beginTransaction();
 
        $stmt = $con->prepare("
            UPDATE Books
            SET book_title = ?,
                book_isbn = ?,
                book_publication_year = ?,
                book_publisher = ?
            WHERE book_id = ?
        ");
 
        $stmt->execute([$title, $isbn, $year, $publisher, $book_id]);
 
        $con->commit();
        return true; // Successfully updated
 
    } catch (PDOException $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        throw $e;
    }
}

function insertAuthor($author_firstname, $author_lastname, $author_birth_year, $author_nationality){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Author (author_firstname, author_lastname, author_birth_year, author_nationality) VALUES (?,?,?,?)');
            $stmt->execute([$author_firstname, $author_lastname, $author_birth_year, $author_nationality]);
            $author_id = $con->lastInsertId();
            $con->commit();
            return $author_id;

    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }

    }

function insertGenre($genre_name){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Genre (genre_name) VALUES (?)');
            $stmt->execute([$genre_name]);
            $genre_id = $con->lastInsertId();
            $con->commit();
            return $genre_id;

    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }

    }

    function deletebooks($book_id){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();

            $stmtCopies = $con->prepare("DELETE FROM BookCopy WHERE book_id = ? ");
            $stmtCopies->execute([$book_id]);

            $stmtBG = $con->prepare("DELETE FROM BookGenre WHERE book_id = ? ");
            $stmtBG->execute([$book_id]);

            $stmtBA = $con->prepare("DELETE FROM BookAuthors WHERE book_id = ? ");
            $stmtBA->execute([$book_id]);

            $stmtBook = $con->prepare("DELETE FROM Books WHERE book_id = ? ");
            $stmtBook->execute([$book_id]);

            $con->commit();
            return true;

    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }

    }

    function updateAuthor($author_id, $author_firstname, $author_lastname, $author_birth_year, $author_nationality)
    {
    $con = $this->opencon();
 
    try {
        $con->beginTransaction();
 
        $stmt = $con->prepare("
            UPDATE Author
            SET author_firstname = ?,
                author_lastname = ?,
                author_birth_year = ?,
                author_nationality = ?
            WHERE author_id = ?
        ");
 
        $stmt->execute([$author_firstname, $author_lastname, $author_birth_year, $author_nationality, $author_id]);
 
        $con->commit();
        return true; 
 
    } catch (PDOException $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        throw $e;
    }
    }

    function deleteAuthor($author_id){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();

            $stmtBookAuthor = $con->prepare("DELETE FROM bookauthors WHERE author_id = ? ");
            $stmtBookAuthor->execute([$author_id]);

            $stmtAuthor = $con->prepare("DELETE FROM Author WHERE author_id = ? ");
            $stmtAuthor -> execute([$author_id]);

            $con->commit();
            return true;

    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e; 
    }

    }

    function updateGenre($genre_id, $genre_name)
    {
    $con = $this->opencon();
 
    try {
        $con->beginTransaction();
 
        $stmt = $con->prepare("
            UPDATE Genre
            SET genre_name = ?
            WHERE genre_id = ?
        ");
 
        $stmt->execute([$genre_name, $genre_id]);
 
        $con->commit();
        return true; 
 
    } catch (PDOException $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        throw $e;
    }
    }

    function deleteGenre($genre_id){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();

            $stmtBookGenre = $con->prepare("DELETE FROM bookgenre WHERE genre_id = ? ");
            $stmtBookGenre->execute([$genre_id]);

            $stmtGenre = $con->prepare("DELETE FROM Genre WHERE genre_id = ? ");
            $stmtGenre -> execute([$genre_id]);

            $con->commit();
            return true;

    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }

    }





function getActiveBorrowers(){
    $con = $this->opencon();
    return $con->query("SELECT
    borrowers.borrower_id,
    CONCAT(borrowers.borrower_firstname, ' ', borrowers.borrower_lastname) AS borrower_name
FROM borrowers
WHERE is_active = 1")->fetchAll();
}

function getAvailableCopies(){
    $con = $this->opencon();
    return $con->query("SELECT 
	bookcopy.copy_id,
    books.book_id,
    books.book_title

FROM books
JOIN bookcopy ON books.book_id = bookcopy.book_id
WHERE bookcopy.bc_status = 'AVAILABLE'
ORDER BY 3")->fetchAll();
}


function createLoanWithItems($borrower_id, $processed_by_user_id, $copy_ids, $li_duedate, $condition_out){
    $con = $this->opencon();
    try{
        $con->beginTransaction();

        $insertLoanStmt = $con->prepare("INSERT INTO Loan (borrower_id, processed_by_user_id, loan_status, loan_date)
    VALUES (?, ?, 'OPEN', NOW())
");
        $insertLoanStmt->execute([$borrower_id, $processed_by_user_id]);
        
        $loan_id = $con->lastInsertId();

        $checkCopyStmt = $con->prepare("SELECT bc_status FROM BookCopy WHERE copy_id = ?");


        $activeLoanStmt = $con->prepare("SELECT COUNT(*) as active_count FROM LoanItem
        JOIN Loan ON LoanItem.loan_id = Loan.loan_id
        WHERE LoanItem.copy_id = ? AND LoanItem.li_returned_at IS NULL AND Loan.loan_status = 'OPEN'");

        $insertLoanItemStmt = $con->prepare("INSERT INTO LoanItem(loan_id, copy_id, li_duedate, condition_out) VALUES(?,?,?,?) ");

        $updateCopyStmt = $con->prepare("UPDATE BookCopy SET bc_status ='ON_LOAN' WHERE copy_id = ? ");

        foreach ($copy_ids as $copy_id) {

            $checkCopyStmt->execute([$copy_id]);
            $copyStatus = $checkCopyStmt->fetch();
        
            if (!$copyStatus) {
                throw new Exception("Copy ID $copy_id does not exist.");
            }
        
            if ($copyStatus['bc_status'] !== 'AVAILABLE') {
                throw new Exception("Copy ID $copy_id is not available.");
            }
        
            $activeLoanStmt->execute([$copy_id]);
            $activeLoan = $activeLoanStmt->fetch();
        
            if ($activeLoan['active_count'] > 0) {
                throw new Exception("Copy already on active loan.");
            }
        
            $insertLoanItemStmt->execute([$loan_id, $copy_id, $li_duedate, $condition_out]);
            $updateCopyStmt->execute([$copy_id]);
        }

        $con->commit();
        return $loan_id;

        } catch (Exception $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
             }
                throw $e;
        }

        
    }
    
}


//opencon - open connection
//dbs_app - name ng database sa xampp

?>