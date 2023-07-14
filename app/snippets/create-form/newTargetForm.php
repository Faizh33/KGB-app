<h2>Créer une nouvelle cible</h2>

<!--formulaire de création de cible -->
<form action="../controllers/insertControllers/insertTargetController.php" method="post" id="form" class="newDatasForm">
    <table class="formTable">
        <tr>
            <td class="labelColumn">
                <label for="targetLastName" class="labelForm">Nom de famille</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="targetLastName" id="targetLastName" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="targetFirstName" class="labelForm">Prénom</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="targetFirstName" id="targetFirstName" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="targetBirthDate" class="labelForm">Date de naissance</label>
            </td>
            <td class="inputColumn">
                <input type="date" name="targetBirthDate" id="targetBirthDate" class="inputFormDate" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="targetNationality" class="labelForm">Nationalité</label>
            </td>
            <td class="inputColumn">
                <select name="targetNationality" id="targetNationality" class="formInput" required>
                    <?php
                    $countriesNationalities = $countryNationalityObj::getAllCountriesNationalities();
                    foreach ($countriesNationalities as $countryNationality) {
                        $nationality = $countryNationality->getNationality();
                        echo "<option value=\"$nationality\">$nationality</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="targetIdCode" class="labelForm">Code d'identification</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="targetIdCode" id="targetIdCode" class="formInput" required />
            </td>
        </tr>
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton button" />
    </div> 
</form>