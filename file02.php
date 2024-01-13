<html>
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 </head>
 <body>
 <form action="file02.php" method="GET">
 Podaj wartość x: <input type="text" name="x"/>
 Podaj wartość y: <input type="text" name="y"/>
 <input type="submit" value="wyślij"/>
 <input type="reset" value="wyczyść"/>
 </form>
 <?php
 $int_options = array("options" =>
 array("min_range" => 1,"max_range" => 1000000));
 $x = filter_var($_GET['x'], FILTER_VALIDATE_INT, $int_options);
 $y = filter_var($_GET['y'], FILTER_VALIDATE_INT, $int_options);
 if ($x && $y) {
 while ($x != $y) {
 if ($x > $y)
 $x -= $y;
 else
 $y -= $x;
 }
 echo "największy wspólny dzielnik tych liczb to ".$x;
 }
 else
 echo "<div style=\"color: red\">podaj poprawne liczby</div>";
 ?>
 </body>
</html>
