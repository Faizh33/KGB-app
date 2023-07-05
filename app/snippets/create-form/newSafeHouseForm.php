<h2>Créer une nouvelle planque</h2>
<form action="" method="post" id="form">
    <table>
        <tr>
            <td>
                <label for="code" class="labelForm">Spécialité</label>
            </td>
            <td>
                <input type="text" name="code" id="code" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td>
                <label for="address" class="labelForm">Adresse</label>
            </td>
            <td>
                <input type="text" name="address" id="address" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td>
                <label for="country" class="labelForm">Pays</label>
            </td>
            <td>
                <input type="text" name="country" id="country" class="formInput" required />
            </td>
        </tr>
        <tr>
            <td>
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