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
    <th scope="col">transfer_date</th>
    <th scope="col">sender_name</th>
<th scope="col">Transfer_id	</th>
           <th scope="col">sender_account_id</th>
                <th scope="col">recipient_mobile_number	</th>
                <th scope="col">recipient_name</th>
                <th scope="col">amount</th>
</tr>
</thead>
<tbody>
<?php
include('../connection/connection.php');

$selectquery = "SELECT * FROM `mobilemoneytransfers` ";

$query = mysqli_query($con, $selectquery);

while($result = mysqli_fetch_assoc($query)){
?>
<tr>
<td><?php echo $result['transfer_date']; ?></td>
<td><?php echo $result['sender_name']; ?></td>
<td><?php echo $result['transfer_id']; ?></td>
<td><?php echo $result['sender_account_id']; ?></td>
<td><?php echo $result['recipient_mobile_number']; ?></td>
<td><?php echo $result['recipient_name']; ?></td>
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