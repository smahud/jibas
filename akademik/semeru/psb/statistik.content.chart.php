<?php 
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../library/common.func.php');
require_once("../library/class/jpgraph.php");
require_once("../library/class/jpgraph_bar.php");
require_once("../library/class/jpgraph_line.php");

// $data = " WyJiYXJjaGFydCIsIlN0YXRpc3RpayBTaXN3YSBBa3RpZiBCZXJkYXNhcmthbiBBZ2FtYSIsIkFnYW1hIiwiSnVtbGFoIFNpc3dhIixbIjciLCI5IiwiMSJdLFtudWxsLCJJc2xhbSIsIkthdG9saWsiXV0=";
// $chartData = json_decode(base64_decode($data), true);

$data = $_REQUEST['data'];
$chartData = json_decode(base64_decode($data), true);

$chartType = $chartData[0];
$title    = $chartData[1];
$xaxis    = $chartData[2];
$yaxis    = $chartData[3];
$values   = $chartData[4];
$labels   = $chartData[5];

// FIX: Convert string array ["7", "9", "1"] into true integers [7, 9, 1]
$values = array_map('intval', $values);

// 3. Create the graph framework (Width, Height)
$graph = new Graph(600, 400, 'auto');

// 💡 ADD THIS LINE TO FIX IT:
$graph->ClearTheme();

// 4. Define the scales.
$graph->SetScale('textlin');

// 5. Setup margins around the plot area
$graph->SetShadow();
$graph->img->SetMargin(60, 30, 50, 50);

// 6. Add titles and labels
$graph->title->Set($title);
$graph->xaxis->title->Set($xaxis);
$graph->yaxis->title->Set($yaxis);

// 7. Bind the descriptive text labels to the X-axis
$graph->xaxis->SetTickLabels($labels);

// 8. Construct the Bar Plot using your newly converted numeric Y-data
$bplot = new BarPlot($values);

// 2. Enable the values to show on the bars
$bplot->SetShadow('darkgray@0.5');
$bplot->value->Show();

// 3. Format the display
$bplot->value->SetFormat('%d');
$bplot->value->SetColor('darkred');       // Text color
$bplot->value->SetFont(FF_FONT1, FS_BOLD); // Font style
$bplot->value->SetAngle(0);

// 9. Style the appearance of the bars
// 9. Style the appearance of the bars
// Pass an array of colors instead of a single color string
$chartColors = array(
    'orange',       // Orange
    'cadetblue',    // Blue
    'forestgreen',  // Green
    'purple',       // Purple
    'gold',         // Yellow/Gold
    'darkred',      // Red (Dark)
    'dodgerblue',   // Blue (Bright)
    'sandybrown',   // Brown/Orange
    'seagreen',     // Green (Dark)
    'orchid',       // Purple/Pink
    'yellow',       // Yellow
    'tomato',       // Red/Orange
    'navy',         // Blue (Dark)
    'lawngreen',    // Green (Bright)
    'plum',         // Purple (Light)
    'sienna',       // Brown
    'coral',        // Red/Orange (Light)
    'steelblue',    // Blue/Gray
    'chartreuse',   // Green/Yellow
    'deeppink',     // Pink/Red
    'blueviolet',   // Purple/Blue
    'chocolate',    // Brown (Dark)
    'aquamarine'    // Green/Blue
);
$bplot->SetFillColor($chartColors);

$bplot->SetColor('navy');       
$bplot->SetWidth(0.6);          

// 10. Inject the completed plot into the graph canvas
$graph->Add($bplot);

// 11. Render and output the resulting graphic
$graph->Stroke();
?>
