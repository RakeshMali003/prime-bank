<?php
// Start session
session_start();

// Include database connection
include('../connection/connection.php');
?>

<div class="main_content">
    <h4 class="para"> Total Credit List</h4> <br> <hr>
    <!--------------------------------------------------end---------------------------------------->
    <div class="table-responsive">
        <table class="table table-bordered" id="example">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Deposit Id</th>
                    <th scope="col">Account Id</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Deposit method</th>
                    <th scope="col">Deposit reference </th>
                    <th scope="col">Deposit date </th>
                    <th scope="col">Cheque no </th>
                    <th scope="col">Cheque name </th>
                    <th scope="col">Deposit date </th>
                    <th scope="col">Bank name </th>
                    <th scope="col">Cheque_deposit_ac_no </th>
                    <th scope="col" colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include('../connection/connection.php');

                $selectquery = "SELECT * FROM `deposit` ";

                $query = mysqli_query($con, $selectquery);

                while ($result = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?php echo $result['deposit_id']; ?></td>
                        <td><?php echo $result['account_id']; ?></td>
                        <td><?php echo $result['amount']; ?></td>
                        <td><?php echo $result['deposit_method']; ?></td>
                        <td><?php echo $result['deposit_reference']; ?></td>
                        <td><?php echo $result['deposit_date']; ?></td>
                        <td><?php echo $result['cheque_no']; ?></td>
                        <td><?php echo $result['cheque_name']; ?></td>
                        <td><?php echo $result['deposit_date']; ?></td>
                        <td><?php echo $result['bank_name']; ?></td>
                        <td><?php echo $result['cheque_deposit_ac_no']; ?></td>
                        <td><a class="btn btn-danger" href="#">Delete</a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Button trigger modal -->
</div>

<?php
include('..\admin\adminfotter.php');
?>