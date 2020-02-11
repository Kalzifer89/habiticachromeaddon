<?php
include 'Habitica_PHP.php';

$SessionWithHabitica = new Habitica('f860ec13-1ded-4d04-8d2b-3de25b4b58bf','7c57125c-2568-462f-8638-8d75735955fb');
$Abfrage  = $SessionWithHabitica->userTasks('todos');

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

//Abfragen und Anzahl der Aufgaben Zählen
$AnzahlArrayObjecte = count ($Abfrage["habitRPGData"]["data"]);


//Nur Ausführen wenn das Formular Abgesendet wurde
if (isset($_POST["formsend"])) {
  //Anzahl der Möglichen Abharkfelder Überprüfen
  for ($i=0; $i < $AnzahlArrayObjecte; $i++) {
    //Wenn Feld Angeharkt ist, Aufgaber in Habitica Abharken
    if (isset($_POST[$Abfrage["habitRPGData"]["data"][$i]["_id"]])){
      $aufgabenarray = ["taskId" =>  $Abfrage["habitRPGData"]["data"][$i]["_id"], "direction" => "up"];
      $SessionWithHabitica->taskScoring($aufgabenarray);
    }
  }
  //Array Daten nach Durchlauf der Aufgaben Aktualisieren
  $Abfrage  = $SessionWithHabitica->userTasks('todos');
  $AnzahlArrayObjecte = count ($Abfrage["habitRPGData"]["data"]);
  $Notification = true;
  $NotificationText = "Ihre Daten wurden erfolgreich übertragen";
  //Ausführen wenn der New Task Button gedrückt wurde
}elseif (isset($_POST["Title"]) && isset($_POST["Notes"])) {
  $aufgabenarray = ["type" =>  "todo", "text" => $_POST["Title"], "notes" => $_POST["Notes"]];
  $SessionWithHabitica->newTask($aufgabenarray);
  //Array Daten nach Durchlauf der Aufgaben Aktualisieren
  $Abfrage  = $SessionWithHabitica->userTasks('todos');
  $AnzahlArrayObjecte = count ($Abfrage["habitRPGData"]["data"]);
  //Bestätigung Anzeigen
  $Notification = true;
  $NotificationText = "Ihre Aufgabe wurden erfolgreich angelegt.";
}elseif (isset($_POST["Title"])) {
  $aufgabenarray = ["type" =>  "todo", "text" => $_POST["Title"]];
  $SessionWithHabitica->newTask($aufgabenarray);
  //Array Daten nach Durchlauf der Aufgaben Aktualisieren
  $Abfrage  = $SessionWithHabitica->userTasks('todos');
  $AnzahlArrayObjecte = count ($Abfrage["habitRPGData"]["data"]);
  //Bestätigung
  $Notification = true;
  $NotificationText = "Ihre Aufgabe wurden erfolgreich angelegt.";
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
    <script type="text/javascript"></script>
</head>

<body>
<script>
//Javaskript für Popover Laden
  $(function () {
  $('[data-toggle="popover"]').popover()
  })
</script>
<div class="row d-flex justify-content-center container">
    <div class="col-md-8">
      <form action="/index.php" method="post">
        <div class="card-hover-shadow-2x mb-3 card">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="fa fa-tasks"></i>&nbsp;Task Lists</div>
            </div>
            <?php
            if ($Notification) {
              echo " <div class=\"alert alert-success\" role=\"alert\">\n";
              echo $NotificationText;
              echo " </div>";
            }
             ?>

            <div class="scroll-area-sm">
                <perfect-scrollbar class="ps-show-limits">
                    <div style="position: static;" class="ps ps--active-y">
                        <div class="ps-content">
                            <ul class=" list-group list-group-flush">

                              <?php
                              for ($i=0; $i < $AnzahlArrayObjecte; $i++) {

                                //Checken ob das Key im Array Existert
                                if (isset($Abfrage["habitRPGData"]["data"][$i]["date"])){
                                  //Denn Schauen ob etwas drinnen steht
                                  if ($Abfrage["habitRPGData"]["data"][$i]["date"]){
                                    //Dateum Umwandeln
                                    $originalDate = $Abfrage["habitRPGData"]["data"][$i]["date"];
                                    $newDate = date("d.m.Y", strtotime($originalDate));
                                    if ($AktuellesDatum > $newDate) {
                                      $Overdue = true;
                                    }
                                  }else {
                                    $newDate = "";
                                    $Overdue = false;
                                  }
                                }else {
                                  $newDate = "";
                                  $Overdue = false;
                                }

                              echo "                                <li class=\"list-group-item\">\n";


                              //Berechnung wann die Aufgabe erstellt wurde und wieviele Tage sie schon existiert
                              $Erstelldatum = $Abfrage["habitRPGData"]["data"][$i]["createdAt"];
                              //Convertieren das Erstellungdatum aus Array nach DateTime PHP
                              $Erstelldatum = date("d-m-Y", strtotime($Erstelldatum));
                              $datetime1 = new DateTime("now");
                              $datetime2 = DateTime::createFromFormat('d-m-Y', $Erstelldatum);
                              //Daten Vergleichen und Anzahl der Tage herausfinden
                              $interval = $datetime2->diff($datetime1);
                              //Tage Speichern
                              $Tage = $interval->format('%R%a');
                              if ($Tage < 3) {
                                    echo "<div class=\"todo-indicator bg-success\"></div>\n";
                              }else if ($Tage >= 3 && $Tage < 5) {
                                echo "<div class=\"todo-indicator bg-primary\"></div>\n";
                              }elseif ($Tage >= 5 && $Tage < 7) {
                                echo "<div class=\"todo-indicator bg-warning\"></div>\n";
                              }elseif ($Tage >= 7) {
                                echo "<div class=\"todo-indicator bg-danger\"></div>\n";
                              }


                              echo "                                    <div class=\"widget-content p-0\">\n";
                              echo "                                        <div class=\"widget-content-wrapper\">\n";
                              echo "                                            <div class=\"widget-content-left mr-2\">\n";
                              echo "                                                <div class=\"custom-checkbox custom-control\"> <input class=\"custom-control-input\" id=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\" type=\"checkbox\" name=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\" value=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\"><label class=\"custom-control-label\" for=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\"> </label> </div>\n";
                              echo "                                            </div>\n";
                              echo "                                            <div class=\"widget-content-left\">\n";
                              echo "                                                <div class=\"widget-heading\">".$Abfrage["habitRPGData"]["data"][$i]["text"];
                              if ($Overdue) {
                              echo "<div class=\"badge badge-danger ml-2\">Overdue</div>";
                              }

                              echo "                                                </div>\n";
                              echo "                                                <div class=\"widget-subheading\"><i>".$newDate."</i></div>\n";
                              echo "                                            </div>\n";
                              //Checken ob Text in der Aufgabe Hinterlegt und wenn ja als Popover Anzeigen
                              if ($Abfrage["habitRPGData"]["data"][$i]["notes"]) {
                                echo "<div class=\"widget-content-right\"> <button type=\"button\" class=\"border-0 btn-transition btn btn-outline-success\"  data-container=\"body\" data-toggle=\"popover\" data-placement=\"left\" data-content=\"".$Abfrage["habitRPGData"]["data"][$i]["notes"]."\"> <i class=\"fa fa-file\"></i></button> <button class=\"border-0 btn-transition btn btn-outline-danger\"> <i class=\"fa fa-trash\"></i> </button> </div>\n";
                              }else {
                                echo "<div class=\"widget-content-right\"><button class=\"border-0 btn-transition btn btn-outline-danger\"> <i class=\"fa fa-trash\"></i> </button> </div>\n";
                              }
                              echo "                                        </div>\n";
                              echo "                                    </div>\n";
                              echo "                                </li>\n";
                              }
                               ?>
                                <li class="list-group-item">
                                    <div class="todo-indicator bg-focus"></div>
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-2">
                                                <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox1" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox1">&nbsp;</label></div>
                                            </div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Make payment to Bluedart</div>
                                                <div class="widget-subheading">
                                                    <div>By Johnny <div class="badge badge-pill badge-info ml-2">NEW</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-content-right"> <button class="border-0 btn-transition btn btn-outline-success"> <i class="fa fa-check"></i></button> <button class="border-0 btn-transition btn btn-outline-danger"> <i class="fa fa-trash"></i> </button> </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="todo-indicator bg-primary"></div>
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-2">
                                                <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox4" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox4">&nbsp;</label></div>
                                            </div>
                                            <div class="widget-content-left flex2">
                                                <div class="widget-heading">Office rent </div>
                                                <div class="widget-subheading">By Samino!</div>
                                            </div>
                                            <div class="widget-content-right"> <button class="border-0 btn-transition btn btn-outline-success"> <i class="fa fa-check"></i></button> <button class="border-0 btn-transition btn btn-outline-danger"> <i class="fa fa-trash"></i> </button> </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="todo-indicator bg-info"></div>
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-2">
                                                <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox2" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox2">&nbsp;</label></div>
                                            </div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Office grocery shopping</div>
                                                <div class="widget-subheading">By Tida</div>
                                            </div>
                                            <div class="widget-content-right"> <button class="border-0 btn-transition btn btn-outline-success"> <i class="fa fa-check"></i></button> <button class="border-0 btn-transition btn btn-outline-danger"> <i class="fa fa-trash"></i> </button> </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="todo-indicator bg-success"></div>
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-2">
                                                <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox3" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox3">&nbsp;</label></div>
                                            </div>
                                            <div class="widget-content-left flex2">
                                                <div class="widget-heading">Ask for Lunch to Clients</div>
                                                <div class="widget-subheading">By Office Admin</div>
                                            </div>
                                            <div class="widget-content-right"> <button class="border-0 btn-transition btn btn-outline-success"> <i class="fa fa-check"></i></button> <button class="border-0 btn-transition btn btn-outline-danger"> <i class="fa fa-trash"></i> </button> </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="todo-indicator bg-success"></div>
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-2">
                                                <div class="custom-checkbox custom-control"><input class="custom-control-input" id="exampleCustomCheckbox10" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox10">&nbsp;</label></div>
                                            </div>
                                            <div class="widget-content-left flex2">
                                                <div class="widget-heading">Client Meeting at 11 AM</div>
                                                <div class="widget-subheading">By CEO</div>
                                            </div>
                                            <div class="widget-content-right"> <button class="border-0 btn-transition btn btn-outline-success"> <i class="fa fa-check"></i></button> <button class="border-0 btn-transition btn btn-outline-danger"> <i class="fa fa-trash"></i> </button> </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </perfect-scrollbar>
            </div>
            <input type="hidden" name="formsend" value="true">
            <div class="d-block text-right card-footer"><button type="submit" class="mr-2 btn btn-success" >Send</button><button type="button" class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Add Task</button></div>
         </form>
            <div class="collapse" id="collapseExample">
              <div class="card card-body">
                <form action="/index.php" method="post">
                  <div class="form-group">
                    <label for="Title">ToDo Title</label>
                    <input type="text" name="Title" class="form-control" id="Title">
                  </div>
                  <div class="form-group">
                    <label for="Notes">ToDo Notes</label>
                    <textarea class="form-control" id="Notes" rows="3" name="Notes"></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary btn-block">Create New Task</button>
                </form>
              </div>
            </div>
        </div>
    </div>
</div>

</body></html>
