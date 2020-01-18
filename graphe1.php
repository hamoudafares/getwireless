<?php
require_once ('includes/jpgraph-4.2.8/src/jpgraph.php');
require_once ('includes/jpgraph-4.2.8/src/jpgraph_pie.php');
require_once ('includes/jpgraph-4.2.8/src/jpgraph_pie3d.php');
require_once 'core/init.php';
// Some data and the labels
$date1= Session::get('date_debut') ;
$date2= Session::get('date_fin') ;
$link=mysqli_connect( Config::get('mysql/host'),Config::get('mysql/username'),Config::get('mysql/password'),Config::get('mysql/db'));
$query="SELECT projet , COUNT(projet) from mission_files where `date` >= '{$date1}' AND `date` <= '{$date2}' GROUP by projet";
$results=mysqli_query($link,$query);
$data=array();
$labels=array();
foreach ($results->fetch_all() as $result){
    array_push($data,intval($result[1]));
    array_push($labels,"{$result[0]}(%.1f%%)");
}
// Create the Pie Graph.
$graph = new PieGraph(800,800);
$graph->SetShadow();

// Set A title for the plot
$graph->title->Set('Projects');
$graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
$graph->title->SetColor('black');

// Create pie plot
$p1 = new PiePlot($data);
$p1->SetCenter(0.5,0.5);
$p1->SetSize(0.3);

// Setup the labels to be displayed
$p1->SetLabels($labels);

// This method adjust the position of the labels. This is given as fractions
// of the radius of the Pie. A value < 1 will put the center of the label
// inside the Pie and a value >= 1 will pout the center of the label outside the
// Pie. By default the label is positioned at 0.5, in the middle of each slice.
$p1->SetLabelPos(1);

// Setup the label formats and what value we want to be shown (The absolute)
// or the percentage.
$p1->SetLabelType(PIE_VALUE_PER);
$p1->value->Show();
$p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);
$p1->value->SetColor('darkgray');

// Add and stroke
$graph->Add($p1);
$graph->Stroke();

?>