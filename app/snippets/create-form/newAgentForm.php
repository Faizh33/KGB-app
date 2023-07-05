<h2>Créer un nouvel agent</h2>
    <form action="" method="post" id="form">
        <table>
            <tr>
                <td>
                    <label for="agentLastName" class="labelForm">Nom de famille</label>
                </td>
                <td>
                    <input type="text" name="agentLastName" id="agentLastName" class="formInput" required />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="agentFirstName" class="labelForm">Prénom</label>
                </td>
                <td>
                    <input type="text" name="agentFirstName" id="agentFirstName" class="formInput" required />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="agentBirthDate" class="labelForm">Date de naissance</label>
                </td>
                <td>
                    <input type="date" name="agentBirthDate" id="agentBirthDate" class="inputFormDate" class="formInput" required />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="agentNationality" class="labelForm">Nationalité</label>
                </td>
                <td>
                    <input type="text" name="agentNationality" id="agentNationality" class="formInput" required />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="agentIdCode" class="labelForm">Code d'identification</label>
                </td>
                <td>
                    <input type="text" name="agentIdCode" id="agentIdCode" class="formInput" required />
                </td>
            </tr>
        </table>
        <div class="formButtonContainer">
            <input type="submit" value="Enregistrer" class="formButton" />
        </div> 
    </form>