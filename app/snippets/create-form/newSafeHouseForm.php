<h2>Créer une nouvelle planque</h2>

<!--formulaire de création de planque -->
<form action="../controllers/insertControllers/insertSafeHouseController.php" method="post" id="form" class="newDatasForm">
    <table class="formTable">
        <tr>
            <td class="labelColumn">
                <label for="code" class="labelForm">Code</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="code" id="code" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="address" class="labelForm">Adresse</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="address" id="address" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="country" class="labelForm">Pays</label>
            </td>
            <td class="inputColumn">
                <select name="safeHouseCountry" id="safeHouseCountry" class="formInput" required>
                    <?php
                    $countriesNationalities = $countryNationalityObj::getAllCountriesNationalities();
                    foreach ($countriesNationalities as $countryNationality) {
                        $country = $countryNationality->getCountry();
                        $countryId = $countryNationality->getId();
                        echo "<option value=\"$countryId\">$country</option>";                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="type" class="labelForm">Type</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="type" id="type" class="formInput" required />
            </td>
        </tr>
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton button" />
    </div> 
</form>