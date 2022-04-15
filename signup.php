<?php
    include_once 'header.php';
?>

<center><section class='signup-form'>
    <h2>Sign Up</h2>
    <form action='signup_include.php' method='post'>
        <input type='text' name='name' placeholder='Full name...'> <br><br>
        <input type='text' name='university' placeholder='University...'> <br><br>
        <input type='text' name='email' placeholder='Email...'> <br><br>
        <input type='text' name='username' placeholder='Username...'> <br><br>
        <input type='password' name='password' placeholder='Password...'> <br><br>
        <button type='submit' name='submit'>Sign Up</button> <br><br>
    </form>
</section></center>



<?php
    include_once 'footer.php';
?>