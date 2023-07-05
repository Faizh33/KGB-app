<h2>Créer une nouvelle cible</h2>
<form action="" method="post" id="form">
    <table>
        <tr>
            <td>
                <label for="targetLastName" class="labelForm">Nom de famille</label>
            </td>
            <td>
                <input type="text" name="targetLastName" id="targetLastName" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td>
                <label for="targetFirstName" class="labelForm">Prénom</label>
            </td>
            <td>
                <input type="text" name="targetFirstName" id="targetFirstName" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td>
                <label for="targetBirthDate" class="labelForm">Date de naissance</label>
            </td>
            <td>
                <input type="date" name="targetBirthDate" id="targetBirthDate" class="inputFormDate" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td>
                <label for="targetNationality" class="labelForm">Nationalité</label>
            </td>
            <td>
                <input type="text" name="targetNationality" id="targetNationality" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td>
                <label for="targetIdCode" class="labelForm">Code d'identification</label>
            </td>
            <td>
                <input type="text" name="targetIdCode" id="targetIdCode" class="formInput" required />
            </td>
        </tr>
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton" />
    </div> 
</form>