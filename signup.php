<?php
    include_once 'header.php';
?>

<?php
    // Error handling
    if(isset($_GET["error"])) {
        if ($_GET["error"] == "empty_input") {
            echo "<p style='color:#500'>You must fill in all fields.<br><br></p>";
        }
        if ($_GET["error"] == "password_mismatch") {
            echo "<p style='color:#500'>Passwords do not match.<br><br></p>";
        }
        if ($_GET["error"] == "nonunique_email") {
            echo "<p style='color:#500'>Email already exists.<br><br></p>";
        }
        if ($_GET["error"] == "nonunique_username") {
            echo "<p style='color:#500'>Username already exists.<br><br></p>";
        }
        if ($_GET["error"] == "bad_stmt") {
            echo "<p style='color:#500'>Database error. Please try again.<br><br></p>";
        }
        
        if ($_GET["error"] == "none") {
            echo "<p style='color:#500'>Sign up successful.<br><br></p>";
        }
    }
?>


<center><section class='signup-form'>
    <h2>Sign Up</h2>
    <form action='signup_include.php' method='post'>
        <input type='text' name='name' placeholder='Full name...'> <br><br>
        <input type='text' name='email' placeholder='Email...'> <br><br>
        <input type='text' name='username' placeholder='Username...'> <br><br>
        <input type='password' name='password' placeholder='Password...'> <br><br>
        <input type='password' name='passwordConfirm' placeholder='Confirm Password...'> <br><br>
        <input type='text' name='university' placeholder='University...'> <br><br>
        <button type='submit' name='submit'>Sign Up</button> <br><br>
    </form>
</section></center>



<?php
    include_once 'footer.php';
?>