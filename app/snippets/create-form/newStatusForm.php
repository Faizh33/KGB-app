<h2>Créer un nouveau statut</h2>

<!--formulaire de création de statut -->
<form action="../controllers/insertStatusController.php" method="post" id="form">
    <table class="formTable">
        <tr>
            <td class="labelColumn">
                <label for="status" class="labelForm">Statut</label>
            </td>
            <td class="inputColumn">
                <input type="text" name="status" id="status" class="formInput" required />
            </td>
        </tr>
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton" />
    </div> 
</form>