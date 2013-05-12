<?php

// Author: Sébastien Boisvert
// Client: Coop Roue-Libre de l'Université Laval
// License: GPLv3



if($lang=='en'){
    $core->makeButton("?controller=NewMember&action=register&lang=fr", "Français");
    include("app/views/Template/contrat-anglais.php");
    ?>
        <form action="?controller=NewMember&amp;action=add_save" method="post">
        <table>
            <tbody>
                <tr>
                    <td class="tableContentCell">First name</td>

                    <td><input class="tableContentCell" name="firstName" type=
                    "text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Last name</td>

                    <td><input class="tableContentCell" name="lastName" type=
                    "text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Birth Date
                    (aaaa-mm-jj)</td>

                    <td><input class="tableContentCell" name="dateOfBirth"
                    type="text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Sex</td>

                    <td class="tableContentCell"><select class=
                    "tableContentCell" name="sex">
                        <option class="tableContentCell" value="F">
                            Femme
                        </option>

                        <option class="tableContentCell" value="M">
                            Homme
                        </option>
                    </select></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Permanent Adress</td>

                    <td><input class="tableContentCell" name="physicalAddress"
                    type="text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Phone number</td>

                    <td><input class="tableContentCell" name="phoneNumber"
                    type="text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Email</td>

                    <td><input class="tableContentCell" name="email" type=
                    "text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Creator</td>

                    <td class="tableContentCellFilled"><input name=
                    "userIdentifier" type="hidden" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Creation date</td>

                    <td class="tableContentCellFilled">2013-05-05
                    13:34:33<input name="creationTime" type="hidden" value=
                    "2013-05-05 13:34:33"></td>
                </tr>

                <tr>
                    <td></td>

                    <td>
                        <div class="button">
                            <a class="buttonLink" href="#" onclick=
                            "document.forms[0].submit();">Submit</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
<?php
}
else{
    $core->makeButton("?controller=NewMember&action=register&lang=en", "Anglais");
    include("app/views/Template/contrat-francais.php");
    ?>
        <form action="?controller=NewMember&amp;action=add_save" method="post">
        <table>
            <tbody>
                <tr>
                    <td class="tableContentCell">Prénom</td>

                    <td><input class="tableContentCell" name="firstName" type=
                    "text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Nom de famille</td>

                    <td><input class="tableContentCell" name="lastName" type=
                    "text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Date de naissance
                    (aaaa-mm-jj)</td>

                    <td><input class="tableContentCell" name="dateOfBirth"
                    type="text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Sexe</td>

                    <td class="tableContentCell"><select class=
                    "tableContentCell" name="sex">
                        <option class="tableContentCell" value="F">
                            Femme
                        </option>

                        <option class="tableContentCell" value="M">
                            Homme
                        </option>
                    </select></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Adresse permanente</td>

                    <td><input class="tableContentCell" name="physicalAddress"
                    type="text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Téléphone</td>

                    <td><input class="tableContentCell" name="phoneNumber"
                    type="text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Courriel</td>

                    <td><input class="tableContentCell" name="email" type=
                    "text" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Créateur</td>

                    <td class="tableContentCellFilled"><input name=
                    "userIdentifier" type="hidden" value=""></td>
                </tr>

                <tr>
                    <td class="tableContentCell">Date de création</td>

                    <td class="tableContentCellFilled">2013-05-05
                    13:34:33<input name="creationTime" type="hidden" value=
                    "2013-05-05 13:34:33"></td>
                </tr>

                <tr>
                    <td></td>

                    <td>
                        <div class="button">
                            <a class="buttonLink" href="#" onclick=
                            "document.forms[0].submit();">Soumettre</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
<?php
}
?>




