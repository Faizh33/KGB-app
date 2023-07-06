<h2>Créer une nouvelle planque</h2>

<!--formulaire de création de planque -->
<form action="../controllers/insertTargetController.php" method="post" id="form">
    <table>
        <tr>
            <td class="labelColumn">
                <label for="code" class="labelForm">Spécialité</label>
            </td>
            <td>
                <input type="text" name="code" id="code" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="address" class="labelForm">Adresse</label>
            </td>
            <td>
                <input type="text" name="address" id="address" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="country" class="labelForm">Pays</label>
            </td>
            <td>
                <input type="text" name="country" id="country" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="type" class="labelForm">Type</label>
            </td>
            <td>
                <input type="text" name="type" id="type" class="formInput" required />
            </td>
        </tr>
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton" />
    </div> 
</form>