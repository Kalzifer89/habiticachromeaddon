<?php

session_start();

//Habtica PHP API Includieren
include 'Habitica_PHP.php';


//Login Vorgang und API Daten in Coockie Speichern
if (!isset($_COOKIE['LoggedIn'])) {
      if (!empty($_POST['APIUser']) && !empty($_POST['APIToken'])) {
        setcookie("LoggedIn", "True", 0);
        setcookie("APIUser", $_POST['APIUser'], 0);
        setcookie("APIToken", $_POST['APIToken'], 0);
        echo "<meta http-equiv=\"refresh\" content=\"1; URL=index.php\">";
      }
}

//Ausloggen wenn Logout Button gedrückt wird
if (isset($_POST['logout'])) {
  setcookie("LoggedIn", "", time() -3600);
  setcookie("APIUser", "",time() -3600);
  setcookie("APIToken", "",time() -3600);
  session_destroy();
  echo "<meta http-equiv=\"refresh\" content=\"1; URL=index.php\">";
}

//Wenn der Nutzer Eingelogt ist Aufgaben Abfragen
if (isset($_COOKIE['LoggedIn'])) {
  //Checken ob ein bestimmter Aufgaben Typ ausgewählt ist
  if (isset($_GET['Type'])) {
    $SessionWithHabitica = new Habitica($_COOKIE['APIUser'],$_COOKIE['APIToken']);
    $Abfrage  = $SessionWithHabitica->userTasks($_GET['Type']);
  }else {
    // Wenn nicht Standart Aufgaben Anzeigen
    $SessionWithHabitica = new Habitica($_COOKIE['APIUser'],$_COOKIE['APIToken']);
    $Abfrage  = $SessionWithHabitica->userTasks('todos');
  }
  //Abfragen und Anzahl der Aufgaben Zählen
  $AnzahlArrayObjecte = count ($Abfrage["habitRPGData"]["data"]);
}
//Datenmüll und Anleitungen

//Kompletten Array Inhalt Hässlich ausgeben
//print_r ($Abfrage);

//Array Inhalt Hübsch?
//print("<pre>".print_r($Abfrage,true)."</pre>");

//Einzelnes Object Abfragen
//echo $Abfrage["habitRPGData"]["data"][0]["text"];

//Array Mit Aufgabe und Status anlegen up = Erledigt
//$aufgabenarray = ["taskId" =>  "bb47959b-562c-4427-a3c4-53af977dcb0c", "direction" => "up"];
//Aufgabe Durchführen
//$SessionWithHabitica->taskScoring($aufgabenarray);

// Übunrgen für die Datums Berechnung
// $FakeDatum = "2020-02-09T17:49:49.954Z";
// $datetime2 = date("Y-m-d", strtotime($FakeDatum));
// $datetime2 = DateTime::createFromFormat('Y-m-d', $datetime2);
//
//
// $datetime1 = new DateTime('2020-02-15');
// $interval = $datetime1->diff($datetime2);
// echo $interval->format('%R%a days');


//Nur Ausführen wenn das Formular Abgesendet wurde
if (isset($_POST["formsend"])) {
  //Anzahl der Möglichen Abharkfelder Überprüfen
  for ($i=0; $i < $AnzahlArrayObjecte; $i++) {
    //Wenn Feld Angeharkt ist, Aufgaber in Habitica Abharken
    if (isset($_POST[$Abfrage["habitRPGData"]["data"][$i]["_id"]])){
      if ($_POST[$Abfrage["habitRPGData"]["data"][$i]["_id"]] == "up") {
        $aufgabenarray = ["taskId" =>  $Abfrage["habitRPGData"]["data"][$i]["_id"], "direction" => "up"];
        $SessionWithHabitica->taskScoring($aufgabenarray);
      }elseif ($_POST[$Abfrage["habitRPGData"]["data"][$i]["_id"]] == "down") {
        $aufgabenarray = ["taskId" =>  $Abfrage["habitRPGData"]["data"][$i]["_id"], "direction" => "down"];
        $SessionWithHabitica->taskScoring($aufgabenarray);
      }else {
        $aufgabenarray = ["taskId" =>  $Abfrage["habitRPGData"]["data"][$i]["_id"], "direction" => "up"];
        $SessionWithHabitica->taskScoring($aufgabenarray);
      }

    }
  }
  //Array Daten nach Durchlauf der Aufgaben Aktualisieren
  if (isset($_GET["Type"])) {
    $Abfrage  = $SessionWithHabitica->userTasks($_GET['Type']);
  }else {
      $Abfrage  = $SessionWithHabitica->userTasks('todos');
  }

  $AnzahlArrayObjecte = count ($Abfrage["habitRPGData"]["data"]);
  $Notification = true;
  $NotificationText = "Your Data was transmitted successfully.";
  //Ausführen wenn der New Task Button gedrückt wurde
}elseif (isset($_POST["Title"]) && isset($_POST["Notes"])) {
  $aufgabenarray = ["type" =>  "todo", "text" => $_POST["Title"], "notes" => $_POST["Notes"]];
  $SessionWithHabitica->newTask($aufgabenarray);
  //Array Daten nach Durchlauf der Aufgaben Aktualisieren
  $Abfrage  = $SessionWithHabitica->userTasks('todos');
  $AnzahlArrayObjecte = count ($Abfrage["habitRPGData"]["data"]);
  //Bestätigung Anzeigen
  $Notification = true;
  $NotificationText = "Your ToDo was created successfully.";
}elseif (isset($_POST["Title"])) {
  $aufgabenarray = ["type" =>  "todo", "text" => $_POST["Title"]];
  $SessionWithHabitica->newTask($aufgabenarray);
  //Array Daten nach Durchlauf der Aufgaben Aktualisieren
  $Abfrage  = $SessionWithHabitica->userTasks('todos');
  $AnzahlArrayObjecte = count ($Abfrage["habitRPGData"]["data"]);
  //Bestätigung
  $Notification = true;
  $NotificationText = "Your ToDo was created successfully.";
  }
else {
  $Notification = false;
}

$AktuellesDatum  = date("d.m.Y");
$Overdue = false;


?>

<html><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Habitica ToDoList</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
  <?php if ($Notification) {
    echo "    $(window).on('load',function(){\n";
    echo "        $('#exampleModal').modal('show');\n";
    echo "    });";} ?>
  </script>
</head>

<body>

<script>
//Javaskript für Popover Laden
  $(function () {
  $('[data-toggle="popover"]').popover()
  })
  //Javaskript für Modal
  $('#myModal').on('shown.bs.modal', function () {
  $('#myInput').trigger('focus')
})
</script>

<div class="row d-flex justify-content-left container">
    <div class="col-md-8">
      <form action="<?php if (isset($_GET['Type'])){ echo "index.php?Type=".$_GET['Type'];} else { echo "index.php";} ?>" method="post">
        <div class="card-hover-shadow-2x mb-3 card">
            <div class="card-header-tab card-header">
                <ul class="nav nav-tabs card-header-tabs">
                  <li class="nav-item">
                    <a class="nav-link <?php if (isset($_GET['Type']) && $_GET['Type'] == "todos") {echo "active";} ?>" href="index.php?Type=todos"><i class="fa fa-tasks"></i>&nbsp;ToDos</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?php if (isset($_GET['Type']) && $_GET['Type'] == "dailys") {echo "active";} ?>" href="index.php?Type=dailys"><i class="fa fa-calendar-check-o"></i>&nbsp;Dailys</a>

                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?php if (isset($_GET['Type']) && $_GET['Type'] == "habits") {echo "active";} ?>" href="index.php?Type=habits"><i class="fa fa-bolt"></i>&nbsp;Habits</a>
                  </li>
                </ul>
            </div>
                <!-- Modal Popup -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Your Changes</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <?php echo $NotificationText; ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

            <?php
              //Check ob Benutzer Angemeldet ist, wenn nicht Login laden
              if (isset($_GET['Page']) && $_GET['Page'] == "addtask") {
                include 'addtask.php';
              }elseif (isset($_COOKIE['LoggedIn'])) {
                include 'tasks.php';
              }
              else {
                include 'login.php';
              }
    ?>

        </div>
    </div>
</div>

</body></html>
