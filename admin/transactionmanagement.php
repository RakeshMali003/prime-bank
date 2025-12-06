<?php
include('..\admin\adminnavbar.php');
$email = $_SESSION['email'];
?>


<div class="main_content">
    <h4 class="para"> Transaction Management</h4> <br> <hr>
    
    <!-- Buttons to trigger modals -->
    <button type="button" class="btn btn-primary mr-5" id="withdrawalsBtn">
        Withdrawals
    </button>
    <button type="button" class="btn btn-primary mr-5" id="depositbtn">
        Deposit
    </button>
    <button type="button" class="btn btn-primary mr-5" id="creditbtn">
        Credit
    </button>
    <button type="button" class="btn btn-primary mr-5" id="debitbtn">
        Debit
    </button>

    <!-- Modal containers -->
    <div id="modalContainer"></div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
$(document).ready(function() {
    // Function to load modal content
    function loadModalContent(url) {
        $('#modalContainer').load(url);
    }

    // Button click event handlers
    $('#withdrawalsBtn').click(function() {
        // Close previously opened modals
        $('#modalContainer').empty();
        // Load withdrawals form
        loadModalContent('../admin/withdrawal_section.php');
    });

    $('#depositbtn').click(function() {
        // Close previously opened modals
        $('#modalContainer').empty();
        // Load deposit form
        loadModalContent('../admin/deposit_section.php');
    });

    $('#creditbtn').click(function() {
        // Close previously opened modals
        $('#modalContainer').empty();
        // Load credit form
        loadModalContent('../admin/credit_section.php');
    });

    $('#debitbtn').click(function() {
        // Close previously opened modals
        $('#modalContainer').empty();
        // Load debit form
        loadModalContent('../admin/debit_section.php');
    });
});
</script>

