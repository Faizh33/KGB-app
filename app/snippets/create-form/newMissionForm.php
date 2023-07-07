    <?php
        include_once "../../config/database.php";
        include_once "../classes/Agent.php";
        include_once "../classes/Contact.php";
        include_once "../classes/Target.php";
        include_once "../classes/SafeHouse.php";
        include_once "../classes/MissionType.php";
        include_once "../classes/Speciality.php";
        include_once "../classes/MissionStatus.php";

        use app\classes\Agent;
        use app\classes\Contact;
        use app\classes\Target;
        use app\classes\SafeHouse;
        use app\classes\MissionType;
        use app\classes\Speciality;
        use app\classes\MissionStatus;

        $agentObj = new Agent($pdo);
        $contactObj = new Contact($pdo);
        $targetObj = new Target($pdo);
        $safehouseObj = new SafeHouse($pdo);
        $missionTypeObj = new MissionType($pdo);
        $specialityObj = new Speciality($pdo);
        $missionStatusObj = new MissionStatus($pdo);

    ?>
    <h2>Créer une nouvelle mission</h2>

    <!--formulaire de création d'une mission -->
    <form action="../controllers/insertMissionController.php" method="post" id="newMissionForm">
        <table>
            <tr>
                <td class="labelColumn">
                    <label for="title" class="labelForm">Titre</label>
                </td>
                <td>
                    <input type="text" id="title" class="formInput" name="title" required/>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <label for="description" class="labelForm">Description</label>
                </td>
                <td>
                    <textarea id="description" name="description" required></textarea>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <label for="codeName" class="labelForm">Nom de code</label>
                </td>
                <td>
                    <input type="text" id="codeName" class="formInput" name="codeName" required />
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <label for="country" class="labelForm">Pays</label>
                </td>
                <td>
                    <input type="text" id="country" class="formInput" name="country" required/>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <label for="startDate" class="labelForm">Date de début</label>
                </td>
                <td>
                    <input type="date" id="startDate" class="inputFormDate" name= "startDate" required/>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <label for="endDate" class="labelForm">Date de fin</label>
                </td>
                <td>
                    <input type="date" id="endDate" class="inputFormDate" name= "endDate" required />
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <div class="labelForm">Agent(s)</div>
                </td>
                <td>
                    <div class="formChkBox">
                    <?php
                        $agents = $agentObj::getAllAgents();
                        foreach($agents as $agent) {
                            $agentId = $agent->getId(); ?>
                            <input type="checkbox" name="agents[]" class="agent-checkbox" value="<?php echo $agentId ?>" id="editAgent <?php echo $agentId ?>">
                            <label for="editAgent <?php echo $agentId ?>" class="labelChk"> <?php echo $agent->getLastName() . ' ' . $agent->getFirstName() ?> </label><br>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <div class="labelForm">Contact(s)</div>
                </td>
                <td>
                    <div class="formChkBox">
                        <?php
                        $contacts = $contactObj::getAllContacts();
                        foreach($contacts as $contact) {
                            $contactId = $contact->getId(); ?>
                            <input type="checkbox" name="contacts[]" class="contact-checkbox" value="<?php echo $contactId ?>" id="editContact <?php echo $contactId ?>">
                            <label for="editContact <?php echo $contactId ?>" class="labelChk"> <?php echo $contact->getLastName() . ' ' . $contact->getFirstName() ?> </label><br>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <div class="labelForm">Cible(s)</div>
                </td>
                <td>
                    <div class="formChkBox">
                        <?php
                        $targets = $targetObj::getAllTargets();
                        foreach($targets as $target) {
                            $targetId = $target->getId(); ?>
                            <input type="checkbox" name="targets[]" class="target-checkbox" value="<?php echo $targetId ?>" id="editTarget <?php echo $targetId ?>">
                            <label for="editTarget <?php echo $targetId ?>" class="labelChk"> <?php echo $target->getLastName() . ' ' . $target->getFirstName() ?> </label><br>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <div class="labelForm">Planque(s)</div>
                </td>
                <td>
                    <div class="formChkBox">
                        <?php
                            $safeHouses = $safehouseObj::getAllSafeHouses();
                            foreach($safeHouses as $safeHouse) {
                                $safeHouseId = $safeHouse->getId(); ?>
                                <input type="checkbox" name="safeHouses[]" class="editChk" value="<?php echo $safeHouseId ?>" id="editSafeHouse <?php echo $safeHouseId ?>">
                                <label for="editSafeHouse <?php echo $safeHouseId ?>" class="labelChk"> <?php echo $safeHouse->getAddress() . ', ' . $safeHouse->getCountry() ?> </label><br>
                            <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <label for="missionType" class="labelForm">Type de mission</label>
                </td>
                <td>
                    <select name="missionType" id="missionType">
                        <option value="">--Choisir un type--</option>
                            <?php
                                $missionTypes = $missionTypeObj::getAllMissionTypes();
                                foreach($missionTypes as $missionType) {
                                    $id = $missionType->getId();
                                    $type = $missionType->getType();
                                    echo "<option value=\"$id\">$type</option>";
                                }
                            ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <label for="missionSpeciality" class="labelForm">Spécialité</label>
                </td>
                <td>
                    <select name="missionSpeciality" id="missionSpeciality">
                        <option value="">--Choisir une spécialité--</option>
                        <?php 
                            $missionSpecialitys = $specialityObj::getAllSpecialities();
                            foreach($missionSpecialitys as $missionSpeciality) {
                                $id = $missionSpeciality->getId();
                                $speciality = $missionSpeciality->getSpeciality();
                                echo "<option value=\"$id\">$speciality</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="labelColumn">
                    <label for="missionStatus" class="labelForm">Statut de mission</label>
                </td>
                <td>
                    <select name="missionStatus" id="missionStatus">
                        <option value="">--Choisir un statut--</option>
                        <?php
                            $missionStatuses = $missionStatusObj::getAllMissionStatuses();
                            foreach($missionStatuses as $missionStatus) {
                                $id = $missionStatus->getId();
                                $status = $missionStatus->getStatus();
                                echo "<option value=\"$id\">$status</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table> 
        <div class="formButtonContainer">
            <input type="submit" value="Enregistrer" class="formButton" />
        </div>       
    </form>
    
    <script src="../../../public/js/form-validation.js"></script>