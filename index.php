<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registration | PHP</title>
    <style>
        .error {color: #FF0000;}
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php

        include 'connection.php';
        // define variables and set to empty values
        $genderErr = $firstnameErr = $lastnameErr = $countrycodeErr = $mobilenumberErr = $emailErr = $stateErr = $cityErr = $pincodeErr = $passwordErr = $confirmpasswordErr = $dobErr = $highesteducationErr = $programminglanguageErr = $percentageErr = $passingyearErr = $bioErr = $documentErr = "";

        $gender = $firstname = $lastname = $countrycode = $mobilenumber = $email = $state = $city = $pincode = $password = $confirmpassword = $dob = $highesteducation = $programminglanguage = $percentage = $passingyear = $bio = $document = "";

        $flag = true;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (empty($_POST['gender'])) {
                $genderErr = "Gender is required";
                $flag = false;
            }
            else {
                $gender = test_input($_POST["gender"]);
            }
             
            if (empty($_POST['firstname'])) {
                $firstnameErr = "First name is required";
                $flag = false;
            }
            else {
                $firstname = test_input($_POST["firstname"]);
                if (!preg_match("/^[a-zA-Z]+$/", $_POST["firstname"])) {
                  $firstnameErr = "Only letters and no white space allowed";
                  $flag = false;
                }
            }

            if (empty($_POST['lastname'])) {
                $lastnameErr = "Last name is required";
                $flag = false;
            }
            else {
                $lastname = test_input($_POST["lastname"]);
                if (!preg_match("/^[a-zA-Z]+$/", $_POST["lastname"])) {
                  $lastnameErr = "Only letters and no white space allowed";
                  $flag = false;
                }
            }

            if (empty($_POST['countrycode'])) {
                $countrycodeErr = "Country code is required";
                $flag = false;
            }
            else {
                $countrycode = test_input($_POST["countrycode"]);
            }

            if (empty($_POST['mobilenumber'])) {
                $mobilenumberErr = "Mobile number is required";
                $flag = false;
            }
            else {
                $mobilenumber = test_input($_POST["mobilenumber"]);
                if (!preg_match("/^[0-9]{10}$/", $_POST["mobilenumber"])) {
                  $mobilenumberErr = "Enter valid 10 digits mobile number";
                  $flag = false;
                }
            }

            if (empty($_POST['email'])) {
                $emailErr = "Email is required";
                $flag = false;
            }
            else {
                $email = test_input($_POST["email"]);
                if (!preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $_POST["email"])) {
                  $emailErr = "Enter valid email";
                  $flag = false;
                }
            }

            if (empty($_POST['password'])) {
                $passwordErr = "Password is required";
                $flag = false;
            }
            else {
                $password = test_input(password_hash($_POST['password'], PASSWORD_DEFAULT));
                if (!preg_match("/^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9])(?=.*[a-z]).{8,}$/", $_POST["password"])) {
                  $passwordErr = "Enter valid password";
                  $flag = false;
                }
            }

            if (empty($_POST['confirmpassword'])) {
                $confirmpasswordErr = "Password Confirmation is required";
                $flag = false;
            }
            else {
                $confirmpassword = test_input(password_hash($_POST['confirmpassword'], PASSWORD_DEFAULT));
                if ($_POST["password"] != $_POST["confirmpassword"]) {
                    $confirmpasswordErr = "No, password doesn't match";
                    $flag = false;
                }
                elseif ($_POST["password"] == $_POST["confirmpassword"]){
                    $confirmpasswordErr = "Yes, password matches";
                }
            }

            if (empty($_POST['dob'])) {
                $dobErr = "Date of birth is required";
                $flag = false;
            }
            else {
                $dob = test_input($_POST["dob"]);
                if (time() < strtotime('+18 years', strtotime($_POST["dob"]))) {
                    $dobErr = "Age must be 18 or above. Please enter a valid date of birth, otherwise you will not be able to submit the form";
                    $flag = false;
                }
            }

            if (empty($_POST['highesteducation'])) {
                $highesteducationErr = "Highest ducation is required";
                $flag = false;
            }
            else {
                $highesteducation = test_input($_POST["highesteducation"]);
            }

            if (empty($_POST['programminglanguage'])) {
                $programminglanguageErr = "Programming language is required";
                $flag = false;
            }
            else {
                $programminglanguage = test_input(implode(",",$_POST["programminglanguage"]));
                if(count($_POST["programminglanguage"]) < 2) {
                    $programminglanguageErr = "Please select at least 2 programming languages";
                    $flag = false;
                }
            }

            if (empty($_POST['percentage'])) {
                $percentageErr = "Percentage is required";
                $flag = false;
            }
            else {
                $percentage = test_input($_POST["percentage"]);
                if ($_POST["percentage"] < 60) {
                    $percentageErr = "Percentage must be 60 or above 60";
                    $flag = false;
                }
            }

            if (empty($_POST['passingyear'])) {
                $passingyearErr = "Passing year is required";
                $flag = false;
            }
            else {
                $passingyear = test_input($_POST["passingyear"]);
                if ($_POST["passingyear"] <= 2015 || $_POST["passingyear"] >= 2021) {
                    $passingyearErr = "Passing year must be between 2015 and 2021";
                    $flag = false;
                }
            }

            if (empty($_POST['document'])) {
                $documentErr = "Document is required";
                $flag = false;
            }
            else {
                $document = test_input($_POST["document"]);
                if (!preg_match("/\.(pdf)$/", $_POST["document"])) {
                    $documentErr = "Upload only pdf";
                    $flag = false;
                }
            }
            
            $state = $_POST['state'];
            
            $pincode = $_POST['pincode'];
            $bio = $_POST['bio'];
            
            if ($flag) {   
                $stmt = $conn->prepare("INSERT INTO users (gender, firstname, lastname, mobilenumber, countrycode, email, state, city, pincode, password, dob, highesteducation, programminglanguage, percentage, passingyear, bio, document) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssssssssss", $gender, $firstname, $lastname, $mobilenumber, $countrycode, $email, $state, $city, $pincode, $password, $dob, $highesteducation, $programminglanguage, $percentage, $passingyear, $bio, $document); 
                
                if($stmt->execute()){
                    
                    $to = "recipient@example.com";
                    $subject = "Registration Form Submission";
                    $message = "Thank you for submitting your registration form. We will get back to you soon.";
                    $headers = "From: no-reply@example.com" . "\r\n" .
                    "CC: cc@example.com";

                    ini_set("SMTP", "smtp.example.com");
                    ini_set("smtp_port", 587);

                    if(mail($to, $subject, $message, $headers)){
                        echo '<script>alert("Email sent successfully")</script>';
                    }else{
                        echo '<script>alert("Error: Email not sent")</script>';
                    }

                    echo '<script>alert("New record created successfully")</script>';
                } 
                else {
                    echo "<script>alert('Error: ' . $sql . '<br>' . $conn->error)</script>";
                }
                
                $stmt->close();
                
                $conn->close();
            }
        }

        function test_input($data) {
            if (is_string($data)) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
            } else if (is_array($data)) {
                // loop through the array and call test_input on each element
                foreach ($data as &$value) {
                    $value = test_input($value);
                }
            }
            return $data;
        }
    ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h2>Registration form</h2>
        <p>
            <span class="error">* Required field</span>
        </p>
        <label>Gender: </label>
        <input style="color: white;" type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female"><span style="color: white;">Female</span>
        <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male"><span style="color: white;">Male</span>
        <input type="radio" name="gender" <?php if (isset($gender) && $gender=="other") echo "checked";?> value="other"><span style="color: white;">Other</span>
        <span class="error">*</span>
        <div class="error"><?php echo $genderErr;?></div>
        <br><br>

        <label>First Name: </label>
        <input type="text" name="firstname" value="<?php echo $firstname;?>">
        <span class="error">*</span>
        <div class="error"><?php echo $firstnameErr;?></div>
        <br><br>

        <label>Last Name: </label>
        <input type="text" name="lastname" value="<?php echo $lastname;?>">
        <span class="error">*</span>
        <div class="error"><?php echo $lastnameErr;?></div>
        <br><br>

        <label>Country Code: </label>
        <select name='countrycode' value="<?php echo $countrycode;?>">
            <option value="" selected></option>
            <option value='+91'>(+91) India</option>
        </select>
        <span class="error">*</span>
        <div class="error"><?php echo $countrycodeErr;?></div>
        <br><br>

        <label>Mobile Number: </label>
        <input type="number" name="mobilenumber" value="<?php echo $mobilenumber;?>">
        <span class="error">*</span>
        <div class="error"><?php echo $mobilenumberErr;?></div>
        <br><br>
        
        <label>Email: </label>
        <input type="text" name="email" value="<?php echo $email;?>">
        <span class="error">*</span>
        <div class="error"><?php echo $emailErr;?></div>
        <br><br>

        <label for="state">State: (optional)</label>
        <select name='state' id="state">
            <option value='UP'>Uttar Pradesh</option>
            <option value='HR'>Haryana</option>
        </select>
        <br><br>

        <label for="city">City: (optional)</label>
        <select name='city' id="city">
            <option value="Noida">Noida</option>
            <option value="Gurugram">Gurugram</option>
        </select>
        <br><br>

        <label for="pincode">City Pin Code: (optional)</label>
        <select name='pincode' id="pincode">
            <option value="201301">Noida - 201301</option>
            <option value="201302">Noida - 201302</option>
        </select>
        <br><br>
        
        <label>Create your password: </label>
        <input type='password' name='password' value="<?php echo $password;?>">
        <span class="error">*</span>
        <div class="error"><?php echo $passwordErr;?></div>
        <br><br>

        <label>Confirm password: </label>
        <input type='password' name='confirmpassword' value="<?php echo $confirmpassword;?>">
        <span class="error">*</span>
        <div class="error"><?php echo $confirmpasswordErr;?></div>
        <br><br>

        <label>Date of Birth: </label>
        <input type='date' name='dob'>
        <span class="error">*</span>
        <div class="error"><?php echo $dobErr;?></div>
        <br><br>

        <label>Highest Education: </label>
        <select name='highesteducation' value="<?php echo $highesteducation;?>">
            <option value="" selected></option>
            <option value='graduation'>Graduation</option>
            <option value='post-graduation'>Post-Graduation</option>
        </select>
        <span class="error">*</span>
        <div class="error"><?php echo $highesteducationErr;?></div>
        <br><br>

        <label>Programming Language: </label>
        <input type='checkbox' name='programminglanguage[]' <?php if (isset($programminglanguage) && $programminglanguage=="Java") echo "checked";?> value='Java'> <span style="color: white;">Java</span>
        <input type='checkbox' name='programminglanguage[]' <?php if (isset($programminglanguage) && $programminglanguage=="Python") echo "checked";?> value='Python'> <span style="color: white;">Python</span>
        <input type='checkbox' name='programminglanguage[]' <?php if (isset($programminglanguage) && $programminglanguage=="C++") echo "checked";?> value='C++'> <span style="color: white;">C++</span>
        <span class="error">*</span>
        <div class="error"><?php echo $programminglanguageErr;?></div>
        <br><br>

        <label>Percentage: </label>
        <input type='number' name='percentage' value="<?php echo $percentage;?>">
        <span class="error">*</span>
        <div class="error"><?php echo $percentageErr;?></div>
        <br><br>

        <label>Passing Year: </label>
        <input type='number' name='passingyear' value="<?php echo $passingyear;?>">
        <span class="error">*</span>
        <div class="error"><?php echo $passingyearErr;?></div>
        <br><br>

        <label>Bio: </label>
        <textarea name='bio' maxlength='50'></textarea>
        <br><br>

        <label>Upload Document: </label>
        <input type='file' name='document' value="<?php echo $document;?>">
        <span class="error">*</span>
        <div class="error"><?php echo $documentErr;?></div>
        <br><br>

        <input type="submit" name="submit" value="Submit">  
    </form>

</body>
</html>