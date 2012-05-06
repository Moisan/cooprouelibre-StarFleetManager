<?php
// Author: Sébastien Boisvert
// Client: Coop Roue-Libre de l'Université Laval
// License: GPLv3

$this->printRowAsTable($item->getAttributes(),$columnNames);

$id=$item->getAttributeValue("id");

?>

<br />

<?php

$core->makeButton("?controller=LoanManagement&action=list&bikeIdentifier=$id","Voir les prêts");

echo "<br />";

$core->makeButton("?controller=RepairManagement&action=list&bikeIdentifier=$id","Voir les réparations");

?>

<br />

<h2>Historique de l'endroit du vélo</h2>

<?php

foreach($places as $item2){

	$id=$item2->getId();
	$name=$item2->getName();

	echo $name."<br />";
}
?>

<br />

<?php

$id=$item->getAttributeValue("id");

if($item->canBeMoved()){
	$core->makeButton("?controller=BikePlaceManagement&action=add&bikeIdentifier=$id","déplacer un vélo");
}else{

?>

Le vélo est présentement emprunté.

<?php

}


?>
