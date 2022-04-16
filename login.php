<?php
    include_once 'header.php';
?>

<?php
    if(isset($_GET["error"])) {
        if ($_GET["error"] == "empty_input") {
            echo "<p style='color:#500'>You must fill in all fields.<br><br></p>";
        }
        if ($_GET["error"] == "incorrect_login") {
            echo "<p style='color:#500'>Username or password is incorrect.<br><br></p>";
        }
    }
?>

<center><section class='login-form'>
    <h2>Login</h2>
    <form action='login_include.php' method='post'>
        <input type='text' name='username' placeholder='Username...'> <br><br>
        <input type='password' name='password' placeholder='Password...'> <br><br>
        <button type='submit' name='submit'>Login</button> <br><br>
    </form>
</section></center>



<?php
    include_once 'footer.php';
?>