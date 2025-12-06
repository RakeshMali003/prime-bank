<?php
// Start session
session_start();

// Include database connection
include('../connection/connection.php');
?>

<div class="main_content">
    <h4 class="para"> Total Debit List</h4> <br> <hr>
    
    <!-- Make the table responsive -->
    <div class="table-responsive">
        <table class="table table-bordered" id="example">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Withdrawal ID</th>
                    <th scope="col">Account No</th>
                    <th scope="col">Name</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                    <th scope="col">Method</th>
                    <th scope="col">Reference</th>
                    <th scope="col" colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include('../connection/connection.php');

                $selectquery = "SELECT * FROM `withdrawals` ";

                $query = mysqli_query($con, $selectquery);

                while ($result = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?php echo $result['withdrawal_id']; ?></td>
                        <td><?php echo $result['account_id']; ?></td>
                        <td><?php echo $result['name']; ?></td>
                        <td><?php echo $result['amount']; ?></td>
                        <td><?php echo $result['withdrawal_date']; ?></td>
                        <td><?php echo $result['withdrawal_method']; ?></td>
                        <td><?php echo $result['withdrawal_reference']; ?></td>
                        <td><a class="btn btn-danger" href="#">Delete</a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- End of responsive table -->
</div>

<?php
include('..\admin\adminfotter.php');
?>