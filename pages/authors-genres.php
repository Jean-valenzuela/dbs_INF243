<?php
require_once('../classes/database.php');
session_start();

$con = new database();

$bookauthors = $con->viewAuthors();
$listedGenre = $con->viewGenre();

$authorCreateStatus = null; 
$authorCreateMessage = ' ';
$AddgenreCreateStatus = null; 
$AddgenreCreateMessage = ' ';

if(isset($_POST['save_author'])){

  $author_firstname = $_POST['author_firstname'];
  $author_lastname = $_POST['author_lastname'];
  $author_birth_year = $_POST['author_birth_year'];
  $author_nationality = $_POST['author_nationality'];

  try{
    $author_id = $con->insertAuthor($author_firstname, $author_lastname, $author_birth_year, $author_nationality);

    $authorCreateStatus = 'success'; 
    $authorCreateMessage = 'Author Saved Successfully.';

  } catch (Exception $e) {
    $authorCreateStatus = 'error';
    $authorCreateMessage = 'Error saving author.';
  }


}

if(isset($_POST['save_genre'])){

  $genre_name = $_POST['genre_name'];

  try{
    $genre_id = $con->insertGenre($genre_name);

    $AddgenreCreateStatus = 'success'; 
    $AddgenreCreateMessage = 'Added Genre Successfully.';

  } catch (Exception $e) {
    $AddgenreCreateStatus = 'error';
    $AddgenreCreateMessage = 'Error saving genre.';
  }


}

if(isset($_POST['delete_author'])){

  $author_id = $_POST['author_id'];
  $author_name = $_POST['author_name'];
  $_SESSION['author_names'] = $author_name;


  try{
    $con->deleteAuthor($author_id);

    $_SESSION['success_delete_author'] = $_SESSION['author_names'] . ' has been deleted successfully.';
    header('Location: authors-genres.php');
    exit();

  } catch (Exception $e) {
    $error_delete_author = 'Error deleting author.';

  }
}

if(isset($_POST['delete_genre'])){

  $genre_id = $_POST['genre_id'];
  $genre_name = $_POST['genre_name'];
  $_SESSION['genre_names'] = $genre_name;


  try{
    $con->deleteGenre($genre_id);

    $_SESSION['success_delete_genre'] = $_SESSION['genre_names'] . ' has been deleted successfully.';
    header('Location: authors-genres.php');
    exit();

  } catch (Exception $e) {
    $error_delete_genre = 'Error deleting genre.';

  }
}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Authors and Genres - Admin (Teaching Demo)</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">

</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.php">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdminStatic">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navAdminStatic" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="admin-dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="authors-genres.php">Authors &amp; Genres</a></li>
        <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.php">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.php">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.php">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">

<?php if(isset($error_delete_author)){ ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error! </strong> <?php echo $error_delete_author; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
   
  </button>
</div>

<?php } ?>

<?php if(isset($_SESSION['success_delete_author'])){ ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Yipee! </strong> <?php echo $_SESSION['success_delete_author']; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
   
  </button>
</div>
<?php 
  unset($_SESSION['success_delete_author']);

} ?>


<?php if(isset($error_delete_genre)){ ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Error! </strong> <?php echo $error_delete_genre; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
   
  </button>
</div>

<?php } ?>

<?php if(isset($_SESSION['success_delete_genre'])){ ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Yipee! </strong> <?php echo $_SESSION['success_delete_genre']; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
   
  </button>
</div>
<?php 
  unset($_SESSION['success_delete_genre']);

} ?>


  <div class="row g-3">

    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Author</h5>
        <p class="small-muted mb-3">Sample form for the Authors table.</p>

        <form action="#" method="POST" class="row g-2">
          <div class="col-12 col-md-6">
            <label class="form-label">First Name</label>
            <input class="form-control" name="author_firstname" placeholder="e.g., Jose" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Last Name</label>
            <input class="form-control" name="author_lastname" placeholder="e.g., Rizal" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Birth Year</label>
            <input class="form-control" name="author_birth_year" type="number" min="1" max="2100" placeholder="optional" />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Nationality</label>
            <input class="form-control" name="author_nationality" placeholder="optional" />
          </div>
          <div class="col-12">
            <button name="save_author" class="btn btn-primary w-100" type="submit">Save Author</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Genre</h5>
        <p class="small-muted mb-3">Sample form for the Genres table.</p>

        <form action="#" method="POST" class="row g-2">
          <div class="col-12">
            <label class="form-label">Genre Name</label>
            <input class="form-control" name="genre_name" placeholder="e.g., Classic" required />
          </div>
          <div class="col-12">
            <button name = "save_genre" class="btn btn-outline-primary w-100" type="submit">Save Genre</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Authors List</h5>
          <span class="small-muted">Static sample data</span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Author ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birth Year</th>
                <th>Nationality</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bookauthors as $viewAuthors){?>
                
              <tr>
                <td><?php echo $viewAuthors['author_id'] ?></td>
                <td><?php echo $viewAuthors['author_firstname'] ?></td>
                <td><?php echo $viewAuthors['author_lastname'] ?></td>
                <td><?php echo $viewAuthors['author_birth_year'] ?></td>
                <td><?php echo $viewAuthors['author_nationality'] ?></td>
                <td><?php echo '<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAuthorModal"
                
                data-author-id="' . $viewAuthors['author_id'] . '"
                data-author-name="' . $viewAuthors['author_firstname'] . ' ' . $viewAuthors['author_lastname'] . '"
                
                >Delete</button>' ?></td>
              </tr>
 
            <?php } ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Genres List</h5>
          <span class="small-muted">Static sample data</span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Genre ID</th>
                <th>Genre Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              
              <?php foreach ($listedGenre as $viewGenre){?>
                
              <tr>
                <td><?php echo $viewGenre['genre_id'] ?></td>
                <td><?php echo $viewGenre['genre_name'] ?></td>
                <td><?php echo '<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteGenreModal"
                
                data-genre-id="' . $viewGenre['genre_id'] . '"
                data-genre-name="' . $viewGenre['genre_name'] . '"  
                
                >Delete</button>' ?> 
              
              </td>
              </tr>
 
            <?php } ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


<!-- Delete Author Modal -->
  <div class="modal fade" id="deleteAuthorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Author</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        
        <p>Are you sure you want to delete <strong id="delete_author_name"></strong>?</p>
        <p class="text-danger small">This action cannot be undone.</p>
        
        <form action="#" method="POST">
          <input type="hidden" name="author_id" id="delete_author_id" >
          <input type="hidden" name="author_name" id="delete_author_names" >

        <div class="d-flex gap-2 justify-content-end">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

          <button type="submit" class="btn btn-danger" name="delete_author">Delete</button>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Delete Genre Modal -->
  <div class="modal fade" id="deleteGenreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Genre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="delete_genre_name"></strong>?</p>
        <p class="text-danger small">This action cannot be undone.</p>
        
        <form action="#" method="POST">
          <input type="hidden" name="genre_id" id="delete_genre_id" >
          <input type="hidden" name="genre_name" id="delete_genre_names" >

        <div class="d-flex gap-2 justify-content-end">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

          <button type="submit" class="btn btn-danger" name="delete_genre">Delete</button>
        </div>
          
        </form>
      </div>
    </div>
  </div>
</div>

</main>

<script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>


<script>
  const authorCreateStatus = <?php echo json_encode($authorCreateStatus)?>;
  const authorCreateMessage = <?php echo json_encode($authorCreateMessage)?>;
  const AddgenreCreateStatus = <?php echo json_encode($AddgenreCreateStatus)?>;
  const AddgenreCreateMessage = <?php echo json_encode($AddgenreCreateMessage)?>;

if(authorCreateStatus == 'success'){
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: authorCreateMessage,
      confirmButtonText: 'Ok'
    });

  }else if(authorCreateStatus == 'error'){
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: authorCreateMessage,
          confirmButtonText: 'Ok'
        });
  
  }else if(AddgenreCreateStatus == 'success'){
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: AddgenreCreateMessage,
      confirmButtonText: 'Ok'
    });

  }else if(AddgenreCreateStatus == 'error'){
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: AddgenreCreateMessage,
          confirmButtonText: 'Ok'
        });
      }




</script>


<script>

  const deleteAuthorModal = document.getElementById('deleteAuthorModal');
  
  deleteAuthorModal.addEventListener('show.bs.modal', function(event){

  const btn = event.relatedTarget;
  
  if(!btn) return;

  document.getElementById('delete_author_id').value = btn.getAttribute('data-author-id') || '';

  document.getElementById('delete_author_names').value = btn.getAttribute('data-author-name') || '';
  
  document.getElementById('delete_author_name').textContent = btn.getAttribute('data-author-name') || '';


  });
  
</script>

<script>

  const deleteGenreModal = document.getElementById('deleteGenreModal');
  
  deleteGenreModal.addEventListener('show.bs.modal', function(event){

  const btn = event.relatedTarget;
  if(!btn) return;

  document.getElementById('delete_genre_id').value = btn.getAttribute('data-genre-id') || '';
  
  document.getElementById('delete_genre_names').value = btn.getAttribute('data-genre-name') || '';

  document.getElementById('delete_genre_name').textContent = btn.getAttribute('data-genre-name') || '';


  });
  
</script>
</body>
</html>
