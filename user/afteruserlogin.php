<?php
// Start session
session_start();

include('../connection/connection.php');

if (!isset($_SESSION['email'])) {
    // Redirect to login page if session email is not set
    header('Location: ../index.php');
    exit(); // Stop further execution
}

// Retrieve email from session
$email = $_SESSION['email'];

// Retrieve user data from the database based on the email
$sql = "SELECT * FROM `login` WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // Display user's email
   
} else {
    // User not found
    echo "Error: User not found.";
}

$stmt->close();

?>


        <!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script>document.getElementsByTagName("html")[0].className += " js";</script>
  <link rel="stylesheet" href="../sidebar.css">

  
  <link rel="stylesheet" href="..\bootstrap\bootstrap-4.6.2-dist\css\bootstrap.min.css">
    <script src="..\bootstrap\bootstrap-4.6.2-dist\js\bootstrap.min.jss"></script>

    <!-----------------------------------end bostrap file ---------------------------------------------->


    <!--------------------------------------fontawesome----------------------------->
    <link rel="stylesheet" href="..\fontawesome-free-6.1.1-web\css\fontawesome.min.css">
    <script src="..\fontawesome-free-6.1.1-web\js\fontawesome.min.js"></script>

    <!-- font ausome-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!---------------------------------------end fontawesome----------------------->

<style>
  .para{
    float:left;
    color:#4e22a1ba;
  }
  th{
    width:200px;
    font-weight:bold;
margin:20px;
  }
  .card-box {
      margin-bottom: 20px;
    }
</style>

  
    <!-----------------------------------------javascript table link ---------------------------------------->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
    

</head>
<body>
  <header class="cd-main-header js-cd-main-header" style="   background-color: rgba(51, 9, 143, 0.943);">
    <div class="cd-logo-wrapper">
    <a class="navbar-brand" href=""><img src="..\weblogo.png"  height="50px" width="70px" alt=""></a>
    </div>
    
    <div class=" js-cd-search">
      <form>

      </form>
    </div>
  
    <button class="reset cd-nav-trigger js-cd-nav-trigger" aria-label="Toggle menu"><span></span></button>
  

    <ul class="cd-nav__list js-cd-nav__list">

      <li class="cd-nav__item cd-nav__item--has-children  js-cd-item--has-children">
        
      <a href="#0">
      <p style="font-size:20px; margin:10px">Account No: <?php echo $row['account_no']; ?></p>

        </a>
    
     
      </li>
    </ul>
  </header> <!-- .cd-main-header -->
  
  <main class="cd-main-content">
    <nav class="cd-side-nav js-cd-side-nav" style="background-color: rgb(44, 44, 236);font-size:19px">
      <ul class="cd-side__list js-cd-side__list">


        <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
        <a class="nav-link active" href="..\user\dashboard.php" aria-disabled="page">Dashboard</a>
        </li>

        <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
        <a class="nav-link active" href="..\user\profile.php" aria-disabled="page">Profie</a>
        </li>

        <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
        <a class="nav-link active" href="..\user\historyofmobile.php" aria-disabled="page"> History OF Mobile Trasaction</a>
        </li>

        <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
        <a class="nav-link active" href="..\user\historyofaccountno.php" aria-disabled="page"> History OF Account No Trasaction</a>
        </li>
        
        <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
        <a class="nav-link active" href="..\user\transferwithaccountno.php" aria-disabled="page">Transfer With Account Number</a>
        </li>

            
        <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
        <a class="nav-link active" href="..\user\transferwithmobile.php" aria-disabled="page">Transfer With Mobile Number</a>
        </li>

            
        <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
        <a class="nav-link active" href="..\user\userlogout.php" aria-disabled="page">Logout</a>
        </li>




    </ul>       
          

    

       



    </nav>
  
    <div class="cd-content-wrapper">
      <div class="text-component">
      
<?php

      
      ?>
