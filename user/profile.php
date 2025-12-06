<?php


include('..\user\afteruserlogin.php');

$email = $_SESSION['email'];

$sql = "SELECT * FROM `login`  WHERE email = '$email'";


$query = mysqli_query($con, $sql);

while($result = mysqli_fetch_assoc($query)) {
  
    if(isset($_POST['user_info'])){

        $lastname = $_POST['lname'];
        $address = $_POST['address'];
        $state = $_POST['state'];
        $city = $_POST['city']; 
        $zip = $_POST['zip'];

        // Handle image upload
        $image = ''; // Initialize the variable
        if(isset($_FILES['image'])) {
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));

            $extensions= array("jpeg","jpg","png");

            if(in_array($file_ext,$extensions) === false){
                echo "Extension not allowed, please choose a JPEG or PNG file.";
            } else {
                move_uploaded_file($file_tmp,"uploads/".$file_name);
                $image = "uploads/".$file_name; // Store the path in the database
            }
        }

        $updatequery =  "UPDATE `login` SET `lname`='$lastname',`image`='$image',`address`='$address',`state`='$state',`zip`='$zip',`city`='$city' WHERE email = '$email'";

        $res = mysqli_query($con ,$updatequery);

        if($res == true){
            ?>
            <script>
                alert("successfully profile update");
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("sorry unable to update profile");
            </script>
            <?php
        }
    }


?>

<div class="main_content">
<h4 class="para"> Profile</h4> <br> <hr>

<div class="card">
<div class="alert alert-warning" role="alert">
  <a href="#" class="alert-link">You need to complete your profile to get access to your dashboard</a>
</div>

<form class="m-5" method="POST">
<div class="form-row">
    <div class="col-md-6 mb-3">
      <label for="validationDefault01">Account Number</label>
      <input type="text" class="form-control" id="validationDefault01" name="account_no" value="<?php echo $result['account_no']; ?>" required readonly>
    </div>
    <div class="col-md-6 mb-3">
      <label for="validationDefault02">Email</label>
      <input type="text" class="form-control" id="validationDefault02" name="email" value="<?php echo $result['email']; ?>" required readonly>
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label for="validationDefault01">First name</label>
      <input type="text" class="form-control" id="validationDefault01" name="name" value="<?php echo $result['name']; ?>" readonly required>
    </div>
    <div class="col-md-6 mb-3">
      <label for="validationDefault02">Last name</label>
      <input type="text" class="form-control" id="validationDefault02" name="lname" value="" required>
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label for="validationDefault01">Address</label>
      <input type="text" class="form-control" id="validationDefault01" name="address" value="" required>
    </div>
    <div class="col-md-6 mb-3">
      <label for="validationDefault02">Image</label>
      <input type="file" class="form-control" id="validationDefault02" name="image" value="" required>
    </div>
  </div>
  <div class="form-row">
  <div class="col-md-3 mb-3">
      <label for="validationDefault03">Mobile No</label>
      <input type="text" class="form-control" id="validationDefault03" name="mobile" required readonly value="<?php echo $result['mobile_no']; ?>">
    </div>
    <div class="col-md-3 mb-3">
      <label for="validationDefault03">City</label>
      <input type="text" class="form-control" id="validationDefault03" name="city" required>
    </div>
    <div class="col-md-3 mb-3">
      <label for="validationDefault04">State</label>
      <select class="form-control" id="state" name="state" required>
    <option value="">Select State</option>
    <option value="Andhra Pradesh">Andhra Pradesh</option>
    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
    <option value="Assam">Assam</option>
    <option value="Bihar">Bihar</option>
    <option value="Chhattisgarh">Chhattisgarh</option>
    <option value="Goa">Goa</option>
    <option value="Gujarat">Gujarat</option>
    <option value="Haryana">Haryana</option>
    <option value="Himachal Pradesh">Himachal Pradesh</option>
    <option value="Jharkhand">Jharkhand</option>
    <option value="Karnataka">Karnataka</option>
    <option value="Kerala">Kerala</option>
    <option value="Madhya Pradesh">Madhya Pradesh</option>
    <option value="Maharashtra">Maharashtra</option>
    <option value="Manipur">Manipur</option>
    <option value="Meghalaya">Meghalaya</option>
    <option value="Mizoram">Mizoram</option>
    <option value="Nagaland">Nagaland</option>
    <option value="Odisha">Odisha</option>
    <option value="Punjab">Punjab</option>
    <option value="Rajasthan">Rajasthan</option>
    <option value="Sikkim">Sikkim</option>
    <option value="Tamil Nadu">Tamil Nadu</option>
    <option value="Telangana">Telangana</option>
    <option value="Tripura">Tripura</option>
    <option value="Uttar Pradesh">Uttar Pradesh</option>
    <option value="Uttarakhand">Uttarakhand</option>
    <option value="West Bengal">West Bengal</option>
</select>

    </div>
    <div class="col-md-3 mb-3">
      <label for="validationDefault05">Zip</label>
      <input type="text" class="form-control" id="validationDefault05" name="zip" required>
    </div>
  </div>
  <div class="form-group">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
      <label class="form-check-label" for="invalidCheck2">
        Agree to information is correct
      </label>
    </div>
  </div>
  <button class="btn btn-primary" type="submit" name="user_info">Submit </button>
</form>
</div>


<?php

}


?>