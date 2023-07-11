<h2>Créer une nouvelle spécialité</h2>

<!--formulaire de création de spécialité -->
<form action="../controllers/insertControllers/insertSpecialityController.php" method="post" id="form" class="newDatasForm">
    <table class="formTable">
        <tr>
            <td class="labelColumn">
                <label for="speciality" class="labelForm">Spécialité</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="speciality" id="speciality" class="formInput" required />
            </td>
        </tr>
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton" />
    </div> 
</form>