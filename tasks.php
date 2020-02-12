      <div class="scroll-area-sm multi-collapse">
          <perfect-scrollbar class="ps-show-limits">
              <div style="position: static;" class="ps ps--active-y">
                  <div class="ps-content">
                      <ul class=" list-group list-group-flush">

                        <?php
                        for ($i=0; $i < $AnzahlArrayObjecte; $i++) {

                          //Checken ob es ein Dailys ist
                          if ($Abfrage["habitRPGData"]["data"][$i]["type"] == 'daily') {
                            //Nächstes ToDo Datum Ausrechnen
                            $NextDueDate = $Abfrage["habitRPGData"]["data"][$i]["nextDue"][0];
                            $NextDueDate = date("d.m.Y", strtotime($NextDueDate));
                            //Wenn es eine schon Abgeschlossene Daily ist, Array Löschen und zum Ende Springen
                            if ($Abfrage["habitRPGData"]["data"][$i]["completed"] == 1) {
                              goto End;
                            }
                          }


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


                        //Wenn es ein Daily ist Anders verfahren
                        if ($Abfrage["habitRPGData"]["data"][$i]["type"] == 'daily') {
                          //Nächste Aufgaben Ausführung Auslesen
                          $NextDueDateTime = $Abfrage["habitRPGData"]["data"][$i]["nextDue"][0];
                          //Convertieren nach Time Format
                          $NextDueDateTime = date("d-m-Y", strtotime($NextDueDateTime));
                          $datetime2 = DateTime::createFromFormat('d-m-Y', $NextDueDateTime);
                          $datetime1 = new DateTime("now");
                          $interval = $datetime1->diff($datetime2);
                          $Tage = $interval->format('%R%a');
                          //echo $Tage;

                          //Farben Entsprechend dem Datum Anpassen
                          if ($Tage < 3) {
                              echo "<div class=\"todo-indicator bg-danger\"></div>\n";
                          }else if ($Tage >= 3 && $Tage < 5) {
                              echo "<div class=\"todo-indicator bg-warning\"></div>\n";
                          }elseif ($Tage >= 5 && $Tage < 7) {
                              echo "<div class=\"todo-indicator bg-primary\"></div>\n";
                          }elseif ($Tage >= 7) {
                              echo "<div class=\"todo-indicator bg-success\"></div>\n";
                          }

                        }else {
                          //Wenn es kein Daily ist denn Stadarrt Verfahren
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

                          //Farben Entsprechend dem Datum Anpassen
                          if ($Tage < 3) {
                                echo "<div class=\"todo-indicator bg-success\"></div>\n";
                          }else if ($Tage >= 3 && $Tage < 5) {
                            echo "<div class=\"todo-indicator bg-primary\"></div>\n";
                          }elseif ($Tage >= 5 && $Tage < 7) {
                            echo "<div class=\"todo-indicator bg-warning\"></div>\n";
                          }elseif ($Tage >= 7) {
                            echo "<div class=\"todo-indicator bg-danger\"></div>\n";
                          }
                        }


                        echo "                                    <div class=\"widget-content p-0\">\n";
                        echo "                                        <div class=\"widget-content-wrapper\">\n";
                        echo "                                            <div class=\"widget-content-left mr-2\">\n";
                        echo "                                                <div class=\"custom-checkbox custom-control\"> <input class=\"custom-control-input\" id=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\" type=\"checkbox\" name=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\" value=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\"><label class=\"custom-control-label\" for=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\"> </label> </div>\n";
                        echo "                                            </div>\n";
                        echo "                                            <div class=\"widget-content-left\">\n";
                        echo "                                                <div class=\"widget-heading\">".$Abfrage["habitRPGData"]["data"][$i]["text"];

                        //Checken ob Aufgabe Überfällig ist
                        if ($Overdue) {
                        echo "<div class=\"badge badge-danger ml-2\">Overdue</div>";
                        }

                        echo "                                                </div>\n";

                        //wenn es ein Daily ist
                        if ($Abfrage["habitRPGData"]["data"][$i]["type"] == 'daily') {
                          echo "                                                <div class=\"widget-subheading\"><i>".$NextDueDate."</i></div>\n";
                        }else {
                          echo "                                                <div class=\"widget-subheading\"><i>".$newDate."</i></div>\n";
                        }

                        echo "                                            </div>\n";
                        echo"<div class=\"widget-content-right\">";
                        //Checken ob Text in der Aufgabe Hinterlegt und wenn ja als Popover Anzeigen
                        if ($Abfrage["habitRPGData"]["data"][$i]["notes"]) {
                          echo "<button type=\"button\" class=\"mr-2 border-0 btn-transition btn btn-outline-info\"  data-container=\"body\" data-toggle=\"popover\" data-placement=\"left\" data-content=\"".$Abfrage["habitRPGData"]["data"][$i]["notes"]."\"> <i class=\"fa fa-file\"></i></button>";
                          //echo "<button class=\"border-0 btn-transition btn btn-outline-danger\"> <i class=\"fa fa-trash\"></i> </button>\n";
                        }
                        //Wenn das ein Habit ist, denn Plus Minus Boxen Anzeigen
                        if ($Abfrage["habitRPGData"]["data"][$i]["type"] == 'habit') {
                          echo "<button type=\"submit\" name=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\" value=\"up\" class=\"mr-2 btn-transition btn btn-outline-success\">+".$Abfrage["habitRPGData"]["data"][$i]["counterUp"]."</button>";
                          echo "<button type=\"submit\" name=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\" value=\"down\" class=\"mr-2 btn-transition btn btn-outline-danger\">- ".$Abfrage["habitRPGData"]["data"][$i]["counterDown"]."</button>";
                        }
                        echo "                                            </div>";
                        echo "                                        </div>\n";
                        echo "                                    </div>\n";
                        echo "                                </li>\n";
                        End:
                        }
                         ?>

                      </ul>
                  </div>
              </div>
          </perfect-scrollbar>
      </div>
      <input type="hidden" name="formsend" value="true">
      <div class="d-block text-right card-footer">
        <button type="submit" name="logout" value="true" class="mr-2 btn btn-danger float-left" >Logout</button>
        <button type="submit" class="mr-2 btn btn-success">Send</button>
        <a class="btn btn-primary" href="index.php?Page=addtask" role="button">Add Task</a>

   </form>
