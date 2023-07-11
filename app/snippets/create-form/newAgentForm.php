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
                <input type="text" name="agentNationality" id="agentNationality" class="formInput" required />
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
    </table>
    <div class="formButtonContainer">
        <input type="submit" value="Enregistrer" class="formButton" />
    </div> 
</form>