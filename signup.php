<?php
    include_once 'header.php';
?>

<div class="box">
    <?php
        $message = null;
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "empty_input") {
                $message =  "<p style='color:red'>You must fill in all fields.<br><br></p>";
            }
            if ($_GET["error"] == "invalid_username") {
                $message =  "<p style='color:red'>Invalid username Please try again.<br><br></p>";
            }
            if ($_GET["error"] == "invalid_email") {
                $message =  "<p style='color:red'>Invalid Email. Please try again.<br><br></p>";
            }  
            if ($_GET["error"] == "password_mismatch") {
                $message =  "<p style='color:red'>Passwords do not match.<br><br></p>";
            }
            if ($_GET["error"] == "nonunique_username") {
                $message =  "<p style='color:red'>Username already exists.<br><br></p>";
            }
            if ($_GET["error"] == "email_exists") {
                $message =  "<p style='color:red'>Email already exists.<br><br></p>";
            }
            if ($_GET["error"] == "bad_stmt") {
                $message =  "<p style='color:red'>Database error. Please try again.<br><br></p>";
            }        
            if ($_GET["error"] == "none") {
                $message =  "<p style='color:blue'>Sign up successful.<br><br></p>";
            }
        }
    ?>

    <form class="form5" action="include/superAdmin-signup.php" method="post">
        <div>
            <p class="heading2">Sign Up</p>
            <div class="error5">
                <?php

                    if (!is_null($message)) {
                        echo $message;
                    }
                ?>
            </div>
            
            <label>
            <span class="label5">Full Name</span><input class="input5" type="text" name="name">
            </label>

            <label>
            <span class="label5">Email</span><input class="input5" type="text" name="email">
            </label>

            <label>
            <span class="label5">Username</span><input class="input5" type="text" name="username">
            </label>

            <label>
            <span class="label5">Password</span><input class="input5" type="password" name="password">
            </label>
            
            <label>
            <span class="label5">Confirm Password</span><input class="input5" type="password" name="passwordConfirm">
            </label>

            <label>
            <span class="label5">School Name</span><input class="input5" type="text" name="schoolName">
            </label>

            <button class="button5" type="submit" name="submit">Sign Up</button>
        </div>
    </form>

</div>

<?php
    include_once 'footer.php';
?>