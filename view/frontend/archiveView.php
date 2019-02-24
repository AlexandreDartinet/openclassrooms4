<?php
/**
 * Gère l'affichage de l'index des archives
 */
$title = "Archives - index";
ob_start();
?>
<h2>Index des archives</h2>
<div>
    <ul>
<?php
foreach($dateTable as $year => $yearContent) {
    $yearCount = $dateTable[$year]["count"];
    echo "<li><a href='/archive/$year/'>$year($yearCount)</a><ul>";
    foreach($dateTable[$year] as $month => $monthContent) {
        if($month != "count") {
            $monthCount = $dateTable[$year][$month]["count"];
            $sMonth = (string) (($month < 10)?'0':'').$month;
            echo "<li><a href='/archive/$year/$sMonth/'>$sMonth/$year($monthCount)</a><ul>";
                foreach($dateTable[$year][$month] as $day => $dayContent) {
                    if($day != "count") {
                        $sDay = (string) (($day < 10)?'0':'').$day;
                        $dayCount = $dateTable[$year][$month][$day]["count"];
                        echo "<li><a href='/archive/$year/$sMonth/$sDay/'>$sDay/$sMonth/$year($dayCount)</a></li>";
                    }
                }
            echo "</ul></li>";
        }
    }
    echo "</ul></li>";
}
?>
    </ul>
</div>

<?
$content = ob_get_clean();
require('template.php');