<?php
    include_once 'header.php';
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