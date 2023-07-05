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
    ?>
    <h2>Créer une nouvelle mission</h2>
    <form action="" method="post" id="newMissionForm">
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
                        $agents = Agent::getAllAgents($pdo);
                        foreach($agents as $agent) {
                            $agentId = $agent->getId(); ?>
                            <input type="checkbox" name="agents[]" class="agent-checkbox" value='"<?php $agentId ?>"' id="editAgent <?php $agentId ?>">
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
                        $contacts = Contact::getAllContacts($pdo);
                        foreach($contacts as $contact) {
                            $contactId = $contact->getId(); ?>
                            <input type="checkbox" name="contacts[]" class="contact-checkbox" value='"<?php $contactId ?>"' id="editContact <?php $contactId ?>">
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
                        $targets = Target::getAllTargets($pdo);
                        foreach($targets as $target) {
                            $targetId = $target->getId(); ?>
                            <input type="checkbox" name="targets[]" class="target-checkbox" value='"<?php $targetId ?>"' id="editTarget <?php $targetId ?>">
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
                            $safeHouses = SafeHouse::getAllSafeHouses($pdo);
                            foreach($safeHouses as $safeHouse) {
                                $safeHouseId = $safeHouse->getId(); ?>
                                <input type="checkbox" name="safeHouses[]" class="editChk" value='"<?php $safeHouseId ?>"' id="editSafeHouse <?php $safeHouseId ?>">
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
                                $missionTypes = MissionType::getAllMissionTypes($pdo);
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
                            $missionSpecialitys = Speciality::getAllSpecialities($pdo);
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
                    <label for="missionStatut" class="labelForm">Statut de mission</label>
                </td>
                <td>
                    <select name="missionStatut" id="missionStatut">
                        <option value="">--Choisir un statut--</option>
                        <?php
                            $missionStatuses = MissionStatus::getAllMissionStatuses($pdo);
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