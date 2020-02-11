<?php
include 'Habitica_PHP.php';

$SessionWithHabitica = new Habitica('f860ec13-1ded-4d04-8d2b-3de25b4b58bf','7c57125c-2568-462f-8638-8d75735955fb');
$Abfrage  = $SessionWithHabitica->userTasks('todos');

//Kompletten Array Inhalt Hässlich ausgeben
//print_r ($Abfrage);

//Array Inhalt Hübsch?
//print("<pre>".print_r($Abfrage,true)."</pre>");

//Einzelnes Object Abfragen
//echo $Abfrage["habitRPGData"]["data"][0]["text"];

//Abfragen und Anzahl der Aufgaben Zählen
$AnzahlArrayObjecte = count ($Abfrage["habitRPGData"]["data"]);

$aufgabenarray = ["taskId" =>  "bb47959b-562c-4427-a3c4-53af977dcb0c", "direction" => "up"];
$SessionWithHabitica->taskScoring($aufgabenarray);

?>

<html><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Habitica ToDoList</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript"></script>
</head>
<body>
<div class="row d-flex justify-content-center container">
    <div class="col-md-8">
        <div class="card-hover-shadow-2x mb-3 card">
            <div class="card-header-tab card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="fa fa-tasks"></i>&nbsp;Task Lists</div>
            </div>
            <div class="scroll-area-sm">
                <perfect-scrollbar class="ps-show-limits">
                    <div style="position: static;" class="ps ps--active-y">
                        <div class="ps-content">
                            <ul class=" list-group list-group-flush">
                              <?php
                              for ($i=0; $i < $AnzahlArrayObjecte; $i++) {
                              echo "                                <li class=\"list-group-item\">\n";
                              echo "                                    <div class=\"todo-indicator bg-warning\"></div>\n";
                              echo "                                    <div class=\"widget-content p-0\">\n";
                              echo "                                        <div class=\"widget-content-wrapper\">\n";
                              echo "                                            <div class=\"widget-content-left mr-2\">\n";
                              echo "                                                <div class=\"custom-checkbox custom-control\"> <input class=\"custom-control-input\" id=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\" type=\"checkbox\"><label class=\"custom-control-label\" for=\"".$Abfrage["habitRPGData"]["data"][$i]["_id"]."\"> </label> </div>\n";
                              echo "                                            </div>\n";
                              echo "                                            <div class=\"widget-content-left\">\n";
                              echo "                                                <div class=\"widget-heading\">".$Abfrage["habitRPGData"]["data"][$i]["text"];
                              echo "                                                </div>\n";
                              echo "                                                <div class=\"widget-subheading\"><i>".$Abfrage["habitRPGData"]["data"][$i]["date"]."</i></div>\n";
                              echo "                                            </div>\n";
                              echo "                                            <div class=\"widget-content-right\"> <button class=\"border-0 btn-transition btn btn-outline-success\"> <i class=\"fa fa-check\"></i></button> <button class=\"border-0 btn-transition btn btn-outline-danger\"> <i class=\"fa fa-trash\"></i> </button> </div>\n";
                              echo "                                        </div>\n";
                              echo "                                    </div>\n";
                              echo "                                </li>\n";
                              }
                               ?>
                                <li class="list-group-item">
                                    <div class="todo-indicator bg-warning"></div>
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-2">
                                                <div class="custom-checkbox custom-control"> <input class="custom-control-input" id="exampleCustomCheckbox12" type="checkbox"><label class="custom-control-label" for="exampleCustomCheckbox12">&nbsp;</label> </div>
                                            </div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">Call Sam For payments <div class="badge badge-danger ml-2">Rejected</div>
                                                </div>
                                                <div class="widget-subheading"><i>By Bob</i></div>
                                            </div>
                                            <div class="widget-content-right"> <button class="border-0 btn-transition btn btn-outline-success"> <i class="fa fa-check"></i></button> <button class="border-0 btn-transition btn btn-outline-danger"> <i class="fa fa-trash"></i> </button> </div>
                                        </div>
                                    </div>
                                </li>
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
            <div class="d-block text-right card-footer"><button class="mr-2 btn btn-link btn-sm">Cancel</button><button class="btn btn-primary">Add Task</button></div>
        </div>
    </div>
</div>

</body></html>
