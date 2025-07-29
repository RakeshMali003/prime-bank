<?php 
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--------------------------title logo--------------------->
<link rel="icon" href="\BANK ONLINE PROJECT\weblogo.png" type="image/icon type">
    <!---------------------------------css file --------------------------->
    <link rel="stylesheet" href="..\BANK ONLINE PROJECT\style.css">

    <!------------------------------------------bootstrap ------------------------>
    <link rel="stylesheet" href="..\BANK ONLINE PROJECT\bootstrap\bootstrap-4.6.2-dist\css\bootstrap.min.css">
    
    <script src="..\BANK ONLINE PROJECT\bootstrap\bootstrap-4.6.2-dist\js\bootstrap.min.js"></script>

    <!----------------------------------------other link------------------------->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<!-------------------------------------------------------------font-awesome--------------------------------------------->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Document</title>
    <style>
       @media (max-width: 768px) {
        /* Adjust navbar width for mobile devices */
        .navbar {
            width: 100%;
            margin-left: 0;
            border-radius: 0;
        }

        /* Adjust image size for smaller screens */
        .navbar-brand img {
            height: 20px; /* Set the height of the image */
            width: auto; /* Let the width adjust automatically */
            margin-right: 200px; /* Add some margin between the image and the navbar */
            margin-left: 0;
            margin-bottom: -80px;
            margin-top:-80px
        }

        /* Center align the navbar items */
        .navbar-nav {
            width: 100%;
        }

        /* Adjust margin for sign-in and sign-up buttons */
        .navbar-nav .nav-item {
            margin: 5px;
        }
    }
    nav{
   background-color: #0047ab; 
    }
    .modal-content{
        background-color: rgba(52, 9, 143, 0.51);
        color:white;
    }
    .text-bold{
    font-weight: bold;
}
  /* Custom CSS for bank theme */
  .navbar {
      background-color: #0047ab; /* Bank's primary color */
    }

    .navbar-brand, .navbar-nav .nav-link {
      color: white;
    }

    .navbar-brand:hover, .navbar-nav .nav-link:hover {
      color: #ffcc00; /* Bank's secondary color */
    }

      /* Hide Google Translate banner */
      .goog-te-banner-frame.skiptranslate {
        display: none!important;
    }

    /* Hide Google Translate button */
    .goog-te-gadget-icon {
        display: none!important;
    }



    /* Style Google Translate button */
    .goog-te-gadget-icon {
        margin-right: 10px; /* Adjust the right margin */
        display: inline-block;
        background-color: #0047ab; /* Bank's primary color */
        color: white;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Style Google Translate dropdown menu */
    .goog-te-menu-value {
        color: black; /* Adjust text color */
    }
   ul:hover> :not(:hover){
    filter: blur(4px);
    opacity: 0.5;
}

</style>
</head>
<body>
<!------------------------------------------start nabar ---------------------------->

<nav class="navbar navbar-expand-lg navbar-dark fixed-top " style=" width:auto; justify-content:center; margin:50px; border-radius:50px; border-top:2px solid white; height:60px ">
  <a class="navbar-brand" href="..\BANK ONLINE PROJECT\index.php"><img src="..\BANK ONLINE PROJECT\weblogo.png" style="height:90px; width:140px;"></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="..\BANK ONLINE PROJECT\index.php">Home</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="#aboutus">About Us</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="#services">Services</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="#contact">Contact Us</a>
      </li>
    </ul>
    <!-- Google Translate Widget -->

    <ul class="navbar-nav">
    <li class="nav-item active">
        <a class="btn  my-2 my-sm-0" href="#" data-toggle="modal" data-target="#signin" id="sign-in" style="text-decoration:none; border-radius:20px">Sign In</a>
      </li>
      <li class="nav-item active">
        <a href="..\BANK ONLINE PROJECT\common user interface\userregistration.php" class="btn  my-2 mx-5 my-sm-0" id="sign-up" style="text-decoration:none" >Sign Up</a>
      </li>


    </ul>
  </div>
</nav>
<!------------------------------------------end nabar ---------------------------->



<!---------------------------------------------------------------------------user login page--------------------------------> 
<?php
include('..\BANK ONLINE PROJECT\common user interface\userlogin.php')
?>
<!-------------------------------------------------------------------end of user login--------------------------------------------->
