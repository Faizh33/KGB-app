<h2>Créer un nouveau type de mission</h2>

<!--formulaire de création de type de mission -->
<form action="../controllers/insertTypeController.php" method="post" id="form">
    <table>
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