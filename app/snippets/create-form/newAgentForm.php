<h2>Créer un nouvel agent</h2>

<!--formulaire de création d'agent -->
<form action="../controllers/insertControllers/insertAgentController.php" method="post" id="form" class="newDatasForm">
    <table class="formTable">
        <tr>
            <td class="labelColumn">
                <label for="agentLastName" class="labelForm">Nom de famille</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="agentLastName" id="agentLastName" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="agentFirstName" class="labelForm">Prénom</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="agentFirstName" id="agentFirstName" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="agentBirthDate" class="labelForm">Date de naissance</label>
            </td>
            <td class="inputColumn">
                <input type="date" name="agentBirthDate" id="agentBirthDate" class="inputFormDate" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="agentNationality" class="labelForm">Nationalité</label>
            </td>
            <td class="inputColumn">
                <select name="agentNationality" id="agentNationality" class="formInput" required>
                    <?php
                    $countriesNationalities = \app\classes\CountryNationality::getAllCountriesNationalities();
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
                <label for="agentIdCode" class="labelForm">Code d'identification</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="agentIdCode" id="agentIdCode" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="agentSpeciality" class="labelForm">Spécialité</label>
            </td>
            <td class="inputColumn">
                <div class="formChkBox">
                    <?php
                        $specialities = app\classes\Speciality::getAllSpecialities();
                        foreach($specialities as $speciality) {
                            $specialityId = $speciality->getId(); ?>
                            <input type="checkbox" name="specialities[]" class="editChk" value="<?php echo $specialityId ?>" id="editSpeciality <?php echo $specialityId ?>">
                            <label for="editSpeciality <?php echo $specialityId ?>" class="labelChk"> <?php echo $speciality->getSpeciality() ?> </label><br>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton button" />
    </div> 
</form>