<html>
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 </head>
 <body>
 <h1>Zmienne serwera</h1>
 <ul>
 <?php
 foreach ($_SERVER as $zmienna => $wartosc) {
 echo "<li><b>".$zmienna."</b> : ".$wartosc."</li>";
 }
 ?>
 </ul>
 </body>
</html>
