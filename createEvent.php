<center><section class='createEvent-form'>
    <h2>Create Event</h2>
    <form method='post'>
        Event Name: <input type='text' name='name' placeholder='Event name...'> <br><br>
        Event Category: <input type='text' name='category' placeholder='Event category...'> <br><br>
        Event Description: <input type='text' name='description' placeholder='Description...'> <br><br>
        Event Date: <input type='date' name='date' placeholder='mm/dd/yyyy'> <br><br>
        Event Start Time: <input type='time' name='start' placeholder='00:00'> <br><br>
        Event Start End: <input type='time' name='end' placeholder='00:00'> <br><br>
        Event Location: <input type='text' name='location' placeholder='Location...'> <br><br>
        Contact Phone: <input type='int' name='location' placeholder='##########'> <br><br>
        Contact Email: <input type='email' name='email' placeholder='Email...'> <br><br>

        <?php
            if (isset($_POST['location'])) {
                $location = $_POST['location'];
                ?>
                <center>
                    <iframe width="500" height="500" src="https://maps.google.com/maps?q=<?php echo $location; ?>&output=embed"></iframe>
                </center>
                <?php
            }
        ?>

        <button type='submit' name='submit'>Create</button> <br><br>
    </form>
</section></center>