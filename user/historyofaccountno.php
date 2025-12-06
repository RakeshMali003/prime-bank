<?php


include('..\user\afteruserlogin.php');

$email = $_SESSION['email'];
$sql = "SELECT * FROM `login`  WHERE email = '$email'";


$query = mysqli_query($con, $sql);

while($result = mysqli_fetch_assoc($query)) {
  

?>


<div class="main_content">
<h4 class="para"> History</h4> <br> <hr>




	


  <table id="example">
  <thead>
    <tr>
    <th scope="col">transaction_id	</th>
    <th scope="col">account_no	</th>
<th scope="col">sender_account_no	</th>
    <th scope="col">transaction_time</th>
                <th scope="col">amount</th>
</tr>
</thead>
<tbody>
<?php
include('../connection/connection.php');

$selectquery = "SELECT * FROM `money_transer_accountno` ";

$query = mysqli_query($con, $selectquery);

while($result = mysqli_fetch_assoc($query)){
?>
<tr>
<td><?php echo $result['transaction_id']; ?></td>
<td><?php echo $result['account_no']; ?></td>
<td><?php echo $result['sender_account_no']; ?></td>
<td><?php echo $result['transaction_time']; ?></td>
<td><?php echo $result['amount']; ?></td>


</tr>

<?php
}
?>
</tbody>
</table>




<?php

}


?>