<?php
include 'Habitica_PHP.php';

$SessionWithHabitica = new Habitica('f860ec13-1ded-4d04-8d2b-3de25b4b58bf','7c57125c-2568-462f-8638-8d75735955fb');
$Abfrage  = $SessionWithHabitica->userTasks('todos');

//Kompletten Array Inhalt Hässlich ausgeben
//print_r ($Abfrage);
//Array Inhalt Hübsch?
print("<pre>".print_r($Abfrage,true)."</pre>");
//Einzelnes Object Abfragen
//echo $Abfrage["habitRPGData"]["data"][0]["text"];
?>
