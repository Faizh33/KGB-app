<h2>Créer une nouvelle association pays/nationalités</h2>

<!--formulaire de création de statut -->
<form action="../controllers/insertControllers/insertCountryNationalityController.php" method="post" id="form" class="newDatasForm">
    <table class="formTable">
        <tr>
            <td class="labelColumn">
                <label for="country" class="labelForm">Pays</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="country" id="country" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="nationality" class="labelForm">Nationalité</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="nationality" id="nationality" class="formInput" required />
            </td>
        </tr>
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton button" />
    </div> 
</form>