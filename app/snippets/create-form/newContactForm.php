<h2>Créer un nouveau contact</h2>

<!--formulaire de création de contact -->
<form action="../controllers/insertContactController.php" method="post" id="form">
    <table>
        <tr>
            <td class="labelColumn">
                <label for="contactLastName" class="labelForm">Nom de famille</label>
            </td>
            <td>
                <input type="text" name="contactLastName" id="contactLastName" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="contactFirstName" class="labelForm">Prénom</label>
            </td>
            <td>
                <input type="text" name="contactFirstName" id="contactFirstName" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="contactBirthDate" class="labelForm">Date de naissance</label>
            </td>
            <td>
                <input type="date" name="contactBirthDate" id="contactBirthDate" class="inputFormDate" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="contactNationality" class="labelForm">Nationalité</label>
            </td>
            <td>
                <input type="text" name="contactNationality" id="contactNationality" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td class="labelColumn">
                <label for="contactIdCode" class="labelForm">Nom de code</label>
            </td>
            <td>
                <input type="text" name="contactIdCode" id="contactIdCode" class="formInput" required />
            </td>
        </tr>
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton" />
    </div> 
</form>