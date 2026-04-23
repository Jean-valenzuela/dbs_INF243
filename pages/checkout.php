<?php
require_once('../classes/database.php');

$con = new database();

$viewBorrowers = $con->viewBorrowers();

$loanItemCreateStatus=null;
$loanItemCreateMessage= ' '; 

if(isset($_POST['create_loan'])){
  // 1. collect and validate inputs from user
    $copy_ids = $_POST['copy_id'];
    $li_duedate  = $_POST['li_duedate'];
    $condition_out = $_POST['condition_out'];
    
  
  try{
    $loan_item_id = $con->insertLoanItem($copy_ids, $li_duedate, $condition_out);
    
    $addressCreateStatus = 'success';
    $addressCreateMessage = 'Loan created successfully.';
    
  
  } catch(Exception $e){
    $addressCreateStatus = 'error';
    $addressCreateMessage = 'Error creating loan.';

  }
  
  }





?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout — Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.php">Library Admin</a>
    <div class="ms-auto d-flex gap-2">
      <a class="btn btn-sm btn-outline-secondary" href="admin-dashboard.php">Back</a>
      <a class="btn btn-sm btn-outline-secondary" href="login.html">Logout</a>
    </div>
  </div>
</nav>

<main class="container py-4">
  <div class="row g-3">
    <div class="col-12 col-lg-7">
      <div class="card p-4">
        <h5 class="mb-1">Process Checkout</h5>
        <p class="small-muted mb-4">Create a Loan + LoanItems. Processor is required.</p>

        <!-- Later in PHP: action="../php/loans/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Borrower</label>
              <select class="form-select" name="borrower_id" required>
                <option value="">Select borrower</option>
                <?php
                foreach($viewBorrowers as $borrowers){
                  echo '<option value="'.$borrowers['borrower_id'].'"> '.'['.$borrowers['borrower_id'].'] '.$borrowers['borrower_firstname']. ' '.$borrowers['borrower_lastname']. '</option>';
                }
              ?>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Processed By (User ID)</label>
              <input class="form-control" name="processed_by_user_id" type="number" value="1" required>
              <div class="small-muted mt-1">Should be the logged-in ADMIN user_id.</div>
            </div>

            <div class="col-12">
              <label class="form-label">Copy IDs to Borrow (comma-separated)</label>
              <input class="form-control" name="copy_ids" placeholder="e.g., 102, 401" required>
              <div class="small-muted mt-1">In PHP: validate copy status is AVAILABLE and not currently on loan.</div>
              
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Due Date</label>
              <input class="form-control" name="li_duedate" type="date" required>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label">Condition Out</label>
              <select class="form-select" name="condition_out" required>
                <option value="GOOD">GOOD</option>
                <option value="DAMAGED">DAMAGED</option>
              </select>
            </div>
          </div>

          <hr class="my-4">
          <button name="create_loan" class="btn btn-primary" type="submit">Create Loan</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-5">
      <div class="card p-4">
        <h6 class="mb-2">Checkout Rules Reminder</h6>
        <ul class="small-muted mb-0">
          <li>Loan must have a borrower_id.</li>
          <li>Loan must have processed_by_user_id (ADMIN).</li>
          <li>Each copy can only be actively on loan once.</li>
          <li>Loan requires at least one LoanItem.</li>
        </ul>
      </div>
    </div>
  </div>
</main>

<script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>

<script>
  const loanItemCreateStatus = <?php echo json_encode($loanItemCreateStatus)?>;
  const loanItemCreateMessage = <?php echo json_encode($loanItemCreateMessage)?>;

  if(loanItemCreateStatus == 'success'){
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: loanItemCreateMessage,
      confirmButtonText: 'Ok'
    });

  }else(loanItemCreateStatus == 'error'){
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: loanItemCreateMessage,
          confirmButtonText: 'Ok'
        });
      }

</script>
 
</body>
</html>