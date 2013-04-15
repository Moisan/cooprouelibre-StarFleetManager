<?php
// Author: Sébastien Boisvert and Thierry Moisan
// Client: Coop Roue-Libre de l'Université Laval
// License: GPLv3
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<script type="text/javascript">
    <?php
    $toJSON = array();
    foreach($members as $num => $member){
        $toJSON[$num]['label'] = $member->getName()." ".$member->getAttribute("email");
        $toJSON[$num]['value'] = $member->getId();
    }
    $js_array = json_encode($toJSON);

    echo "var javascript_array = ". $js_array . ";\n";
    ?>

    $(document).ready(function()
    {
        $("#input_member").autocomplete(
            {
                source: javascript_array,
                messages: {
                    noResults: '',
                    results: function() {}
                }
            });
    });
</script>


<?php
if(count($members)==0){
    echo "Aucun membre ne peut louer de vélo présentement.<br />";
}
else{

    $this->startForm("?controller=LoanManagement&action=add_validate");
    ?>

    <tr>
        <td  class="tableContentCell">Numéro de Membre</td>
        <td class="tableContentCell">
            <input id="input_member" class="tableContentCell" name="memberIdentifier">
        </td>

        <?php
        $this->renderHiddenFieldWithValue("placeIdentifier","Point de service",$place->getName(),$place->getId());

        echo "<tr><td  class=\"tableContentCell\">Vélo</td><td class=\"tableContentCell\">";

        echo "<select name=\"bikeIdentifier\" class=\"tableContentCell\">";

        foreach($bikes as $bike){
            $id=$bike->getId();
            $name=$bike->getName();
            echo "<option class=\"tableContentCell\" value=\"$id\" >$name</option>";
        }
        ?>

        </select>

        </td>
    </tr>

    <?php
    $this->endForm();
}
?>
