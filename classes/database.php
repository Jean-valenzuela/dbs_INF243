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
        return $con->query("SELECT * from Genre")->fetchAll();

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
    
    JOIN bookcopy ON books.book_id = bookcopy.book_id
    
    GROUP BY 1

    ")->fetchAll();

       }
}

//opencon - open connection
//dbs_app - name ng database sa xampp

?>