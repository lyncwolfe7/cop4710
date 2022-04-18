<?php
    include_once 'header.php';
?>

<div class="box">
    <?php
        $message = null;
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "empty_input") {
                $message = "<p style='color:red'>You must fill in all fields.<br><br></p>";
            }
            if ($_GET["error"] == "incorrect_login") {
                $message =  "<p style='color:red'>Username or password is incorrect.<br><br></p>";
            }
        }
    ?>

    <form class="form5" action="login_include.php" method="post">
        <div>
            <p class="heading2">Log In</p>
            <div class="error5">
                <?php
                    if (!is_null($message)) {
                    echo $message;
                    }
                ?>
            </div>
            <label>
                <span class="label5">Username</span>
                <input class="input5" type="text" name="username">
            </label>

            <label>
                <span class="label5">Password</span>
                <input class="input5" type="password" name="password">
            </label>

            <button class="button5" type="submit" name="submit">Log In</button>
        </div>
    </form>
</div>


<?php
    include_once 'footer.php';
?>