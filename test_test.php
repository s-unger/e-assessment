<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
       $q1 = $_POST['ans1'][0];
       $q2 = $_POST['ans2'];
       $q3 = $_POST['ans3'];
       $correct = 0;

       if ($q1 == "" || $q2 == "" || $q3 == "") {
           echo '<h2>Please answer all questions!</h2>';
       } else {
           if($q1 == 5) {
               $correct++;
           } else {
               echo '<p>Wrong answer!</p>';
           }

           if($q2 == 1) {
               $correct++;
           } else {
            echo '<p>Wrong answer!</p>';
           }

           if($q3 == true) {
               $correct++;
           }  else {
            echo '<p>Wrong answer!</p>';
           }

       }
    ?>
</head>
<body>
    <form action="test_test.php" method="post"> 

        <div class="q-group">
           <p class="question"> Question 1</p>
           <input type="number" name="ans1">
        </div>

        <div class="q-group">
           <p class="question"> Question 2</p>
           <input type="radio" name="ans2" value="1">
           <input type="radio" name="ans2" value="2">
           <input type="radio" name="ans2" value="3">
        </div>

        <div class="q-group">
           <p class="question"> Question 3</p>
           <input type="radio" name="ans3" value="true">
           <input type="radio" name="ans3" value="false">
        </div>



        <input type="submit">
    </form>
</body>
</html>