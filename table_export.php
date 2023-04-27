<?php

include('configuration/db.php');
include('configuration/mcode.php');
include('configuration/key.php');

$roles = rtrim(trim($_POST["roles"], ","));
$hasAdminRole = in_array("admin", explode(",", strtolower($roles)));

if ($hasAdminRole) {
    // get the encryption data
    $encryption_key = hex2bin($key);
    $iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
    $iv = $iv_query['riv'];
    mysqli_close($connect);

    // set up the query to run
    $table = htmlentities($_POST['table']);
    $queries = array(
        "patient" => "
            INSERT INTO `Patient_tracking`(
                `id`,
                `birth`,
                `gender`,
                `race`,
                `zip`,
                `institution`,
                `study`,
                `family`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?),UNHEX(?),UNHEX(?),UNHEX(?),UNHEX(?),UNHEX(?),?,UNHEX(?),?, ?)
        ",
        "biospecimen" => "
            INSERT INTO `Biospecimens_tracking` (
                `id`,
                `date`,
                `type`,
                `cellularity`,
                `collection`,
                `storage`,
                `bankingid`,
                `paired`,
                `imaging`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ",
        "cancer" => "
            INSERT INTO `Diseases_tracking`(
                `id`,
                `date`,
                `type`,
                `histology`,
                `status`,
                `code`,
                `side`,
                `oncotree`,
                `clinicalsg`,
                `clinicalss`,
                `pathologicsg`,
                `pathologicss`,
                `comments`,
                `tracking`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ",
        "cbc" => "
            INSERT INTO `CBC_tracking`(
                `id`,
                `date`,
                `type`,
                `count`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?)
        ",
        "cmp" => "
            INSERT INTO `CMP_tracking`(
                `id`,
                `date`,
                `type`,
                `count`,
                `comment`,
                `tracking`
            ) VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?)
        ",
        "comorbid" => "
            INSERT INTO `Comorbid_tracking`(
                `id`,
                `date`,
                `code`,
                `status`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?)
        ",
        "death" => "
            INSERT INTO `Death_tracking`(
                `id`,
                `date`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?)
        ",
        "diagnostic" => "
            INSERT INTO `DiagnosisNF1_tracking`(
                `id`,
                `date`,
                `diagnosis`,
                `mode`,
                `criteria`,
                `severity`,
                `visibility`,
                `age`,
                `circumference`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ",
        "labs" => "
            INSERT INTO `Lab_tracking`(
                `id`,
                `date`,
                `location`,
                `height`,
                `weight`,
                `diastolic`,
                `systolic`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ",
        "lesion" => "
            INSERT INTO `LesionsNF1_tracking`(
                `id`,
                `date`,
                `type`,
                `evaluation`,
                `number`,
                `location`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?, ?, ?)
        ",
        "manifestation" => "
            INSERT INTO `ManifestationsNF1_tracking`(
                `id`,
                `date`,
                `type`,
                `evaluation`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?, ?)
        ",
        "medication" => "
            INSERT INTO `Medication_tracking`(
                `id`,
                `medication`,
                `start`,
                `stop`,
                `reason`,
                `intent`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?),?,?,?,?,?,?,?)
        ",
        "mutation" => "
            INSERT INTO `Mutation_tracking`(
                `id`,
                `date`,
                `test`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?)
        ",
        "outcome" => "
            INSERT INTO `Outcome_tracking`(
                `id`,
                `date`,
                `status`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?), ?, ?, ?, ?, ?)
        ",
        "procedure" => "
            INSERT INTO `ProcedureNf1_tracking`(
                `id`,
                `date`,
                `type`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?),?,?,?,?,?)
        ",
        "radiation" => "
            INSERT INTO `Radiation_tracking`(
                `id`,
                `date`,
                `location`,
                `type`,
                `site`,
                `intent`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?),?,?,?,?,?,?,?,?)
        ",
        "status" => "
            INSERT INTO `ClinicalCondition_tracking`(
                `id`,
                `date`,
                `ecog`,
                `karnofsky`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?),?,?,?,?,?,?)
        ",
        "surgery" => "
            INSERT INTO `Surgery_tracking`(
                `id`,
                `date`,
                `location`,
                `type`,
                `site`,
                `intent`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?),?,?,?,?,?,?,?,?)
        ",
        "tumor" => "
            INSERT INTO `Tumor_tracking`(
                `id`,
                `date`,
                `test`,
                `result`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?),?,?,?,?,?,?)
        ",
        "variant" => "
            INSERT INTO `Variant_tracking`(
                `id`,
                `date`,
                `test`,
                `gene`,
                `cdna`,
                `protein`,
                `variantid`,
                `varianthgvs`,
                `interpretation`,
                `source`,
                `comment`,
                `tracking`,
                `event`
            )
            VALUES (UNHEX(?),?,?,?,?,?,?,?,?,?,?,?,?)
        "
    );
    $stmt = $clinical_data_pdo->prepare($queries[$table]);

    // set up the values for the prepared statement
    // set the values common to all queries
    $id = htmlentities($_POST['id']);
    $enc_id = bin2hex(openssl_encrypt($id, $cipher, $encryption_key, 0, $iv));
    $tracking = htmlentities($_POST['tracking']);
    $event = "Export " . htmlentities($_POST['format']);

    // set up the query specific values and bind all values to the query
    switch ($table) {
        case 'patient':
            $birth = htmlentities($_POST['birth']);
            $gender = htmlentities($_POST['gender']);
            $race = htmlentities($_POST['race']);
            $zip = htmlentities($_POST['zip']);
            $institution = htmlentities($_POST['institution']);
            $study = htmlentities($_POST['study']);
            $family = htmlentities($_POST['family']);

            $enc_birth = bin2hex(openssl_encrypt($birth, $cipher, $encryption_key, 0, $iv));
            $enc_gender = bin2hex(openssl_encrypt($gender, $cipher, $encryption_key, 0, $iv));
            $enc_race = bin2hex(openssl_encrypt($race, $cipher, $encryption_key, 0, $iv));
            $enc_zip = bin2hex(openssl_encrypt($zip, $cipher, $encryption_key, 0, $iv));
            $enc_institution = bin2hex(openssl_encrypt($institution, $cipher, $encryption_key, 0, $iv));
            $enc_family = bin2hex(openssl_encrypt($family, $cipher, $encryption_key, 0, $iv));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $enc_birth, PDO::PARAM_STR);
            $stmt->bindParam(3, $enc_gender, PDO::PARAM_STR);
            $stmt->bindParam(4, $enc_race, PDO::PARAM_STR);
            $stmt->bindParam(5, $enc_zip, PDO::PARAM_STR);
            $stmt->bindParam(6, $enc_institution, PDO::PARAM_STR);
            $stmt->bindParam(7, $study);
            $stmt->bindParam(8, $enc_family, PDO::PARAM_STR);
            $stmt->bindParam(9, $tracking);
            $stmt->bindParam(10, $event);
            break;
        case "biospecimen":
            $date = htmlentities($_POST['date']);
            $type = htmlentities($_POST['type']);
            $cellularity = htmlentities($_POST['cellularity']);
            $collection = htmlentities($_POST['collection']);
            $storage = htmlentities($_POST['storage']);
            $bankingid = htmlentities($_POST['bankingid']);
            $paired = htmlentities($_POST['paired']);
            $imaging = htmlentities($_POST['imaging']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $type);
            $stmt->bindParam(4, $cellularity);
            $stmt->bindParam(5, $collection);
            $stmt->bindParam(6, $storage);
            $stmt->bindParam(7, $bankingid);
            $stmt->bindParam(8, $paired);
            $stmt->bindParam(9, $imaging);
            $stmt->bindParam(10, $comment);
            $stmt->bindParam(11, $tracking);
            $stmt->bindParam(12, $event);
            break;
        case 'cancer':
            $date = htmlentities($_POST['date']);
            $type = htmlentities($_POST['type']);
            $histology = htmlentities($_POST['histology']);
            $status = htmlentities($_POST['status']);
            $location = htmlentities($_POST['location']);
            $side = htmlentities($_POST['side']);
            $oncotree = htmlentities($_POST['oncotree']);
            $clinicalsg = htmlentities($_POST['clinicalsg']);
            $clinicalss = htmlentities($_POST['clinicalss']);
            $pathologicsg = htmlentities($_POST['pathologicsg']);
            $pathologicss = htmlentities($_POST['pathologicss']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $type);
            $stmt->bindParam(4, $histology);
            $stmt->bindParam(5, $status);
            $stmt->bindParam(6, $location);
            $stmt->bindParam(7, $side);
            $stmt->bindParam(8, $oncotree);
            $stmt->bindParam(9, $clinicalsg);
            $stmt->bindParam(10, $clinicalss);
            $stmt->bindParam(11, $pathologicsg);
            $stmt->bindParam(12, $pathologicss);
            $stmt->bindParam(13, $comment);
            $stmt->bindParam(14, $tracking);
            $stmt->bindParam(15, $event);
            break;
        case 'cbc':
        case 'cmp':
            $date = htmlentities($_POST['date']);
            $type = htmlentities($_POST['type']);
            $count = htmlentities($_POST['count']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));
            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $type);
            $stmt->bindParam(4, $count);
            $stmt->bindParam(5, $comment);
            $stmt->bindParam(6, $tracking);
            $stmt->bindParam(7, $event);
            break;
        case 'comorbid':
            $date = htmlentities($_POST['date']);
            $code = htmlentities($_POST['code']);
            $status = htmlentities($_POST['status']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $code);
            $stmt->bindParam(4, $status);
            $stmt->bindParam(5, $comment);
            $stmt->bindParam(6, $tracking);
            $stmt->bindParam(7, $event);
            break;
        case 'death':
            $date = htmlentities($_POST['date']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $comment);
            $stmt->bindParam(4, $tracking);
            $stmt->bindParam(5, $event);
            break;
        case 'diagnostic':
            $date = htmlentities($_POST['date']);
            $diagnosis = htmlentities($_POST['diagnosis']);
            $mode = htmlentities($_POST['mode']);
            $criteria = htmlentities($_POST['criteria']);
            $severity = htmlentities($_POST['severity']);
            $visibility = htmlentities($_POST['visibility']);
            $age = htmlentities($_POST['age']);
            $head = htmlentities($_POST['head']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $diagnosis);
            $stmt->bindParam(4, $mode);
            $stmt->bindParam(5, $criteria);
            $stmt->bindParam(6, $severity);
            $stmt->bindParam(7, $visibility);
            $stmt->bindParam(8, $age);
            $stmt->bindParam(9, $head);
            $stmt->bindParam(10, $comment);
            $stmt->bindParam(11, $tracking);
            $stmt->bindParam(12, $event);
            break;
        case 'labs':
            $date = htmlentities($_POST['date']);
            $location = htmlentities($_POST['location']);
            $height = htmlentities($_POST['height']);
            $weight = htmlentities($_POST['weight']);
            $diastolic = htmlentities($_POST['diastolic']);
            $systolic = htmlentities($_POST['systolic']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $location);
            $stmt->bindParam(4, $height);
            $stmt->bindParam(5, $weight);
            $stmt->bindParam(6, $diastolic);
            $stmt->bindParam(7, $systolic);
            $stmt->bindParam(8, $comment);
            $stmt->bindParam(9, $tracking);
            $stmt->bindParam(10, $event);
            break;
        case 'lesion':
            $date = htmlentities($_POST['date']);
            $type = htmlentities($_POST['type']);
            $evaluation = htmlentities($_POST['evaluation']);
            $number = htmlentities($_POST['number']);
            $location = htmlentities($_POST['location']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $type);
            $stmt->bindParam(4, $evaluation);
            $stmt->bindParam(5, $number);
            $stmt->bindParam(6, $location);
            $stmt->bindParam(7, $comment);
            $stmt->bindParam(8, $tracking);
            $stmt->bindParam(9, $event);
            break;
        case 'manifestation':
            $date = htmlentities($_POST['date']);
            $type = htmlentities($_POST['type']);
            $evaluation = htmlentities($_POST['evaluation']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $type);
            $stmt->bindParam(4, $evaluation);
            $stmt->bindParam(5, $comment);
            $stmt->bindParam(6, $tracking);
            $stmt->bindParam(7, $event);
            break;
        case 'medication':
            $medication = htmlentities($_POST['medication']);
            $start = htmlentities($_POST['start']);
            $stop = htmlentities($_POST['stop']);
            $reason = htmlentities($_POST['reason']);
            $intent = htmlentities($_POST['intent']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $medication);
            $stmt->bindParam(3, $start);
            $stmt->bindParam(4, $stop);
            $stmt->bindParam(5, $reason);
            $stmt->bindParam(6, $intent);
            $stmt->bindParam(7, $comment);
            $stmt->bindParam(8, $tracking);
            break;
        case 'mutation':
            $date = htmlentities($_POST['date']);
            $test = htmlentities($_POST['test']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $test);
            $stmt->bindParam(4, $comment);
            $stmt->bindParam(5, $tracking);
            $stmt->bindParam(6, $event);
            break;
        case 'outcome':
            $date = htmlentities($_POST['date']);
            $status = htmlentities($_POST['status']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $status);
            $stmt->bindParam(4, $comment);
            $stmt->bindParam(5, $tracking);
            $stmt->bindParam(6, $event);
            break;
        case 'procedure':
            $date = htmlentities($_POST['date']);
            $type = htmlentities($_POST['type']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $type);
            $stmt->bindParam(4, $comment);
            $stmt->bindParam(5, $tracking);
            $stmt->bindParam(6, $event);
            break;
        case 'radiation':
        case 'surgery':
            $date = htmlentities($_POST['date']);
            $location = str_replace("'", "\'", htmlentities($_POST['location']));
            $type = htmlentities($_POST['type']);
            $site = htmlentities($_POST['site']);
            $intent = htmlentities($_POST['intent']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $location);
            $stmt->bindParam(4, $type);
            $stmt->bindParam(5, $site);
            $stmt->bindParam(6, $intent);
            $stmt->bindParam(7, $comment);
            $stmt->bindParam(8, $tracking);
            $stmt->bindParam(9, $event);
            break;
        case 'status':
            $date = htmlentities($_POST['date']);
            $ecog = htmlentities($_POST['ecog']);
            $karnofsky = htmlentities($_POST['karnofsky']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $ecog);
            $stmt->bindParam(4, $karnofsky);
            $stmt->bindParam(5, $comment);
            $stmt->bindParam(6, $tracking);
            $stmt->bindParam(7, $event);
            break;


            break;
        case 'tumor':
            $date = $_POST['date'];
            $test = $_POST['test'];
            $result = $_POST['result'];
            $comment = str_replace("'", "\'", $_POST['comment']);

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $test);
            $stmt->bindParam(4, $result);
            $stmt->bindParam(5, $comment);
            $stmt->bindParam(6, $tracking);
            $stmt->bindParam(7, $event);
            break;
        case 'variant':
            $date = htmlentities($_POST['date']);
            $test = htmlentities($_POST['test']);
            $gene = htmlentities($_POST['gene']);
            $cdna = htmlentities($_POST['cdna']);
            $protein = htmlentities($_POST['protein']);
            $mutationid = htmlentities($_POST['mutationid']);
            $mutationhgvs = htmlentities($_POST['mutationhgvs']);
            $interpretation = htmlentities($_POST['interpretation']);
            $source = htmlentities($_POST['source']);
            $comment = str_replace("'", "\'", htmlentities($_POST['comment']));

            $stmt->bindParam(1, $enc_id, PDO::PARAM_STR);
            $stmt->bindParam(2, $date);
            $stmt->bindParam(3, $test);
            $stmt->bindParam(4, $gene);
            $stmt->bindParam(5, $cdna);
            $stmt->bindParam(6, $protein);
            $stmt->bindParam(7, $mutationid);
            $stmt->bindParam(8, $mutationhgvs);
            $stmt->bindParam(9, $interpretation);
            $stmt->bindParam(10, $source);
            $stmt->bindParam(11, $comment);
            $stmt->bindParam(12, $tracking);
            $stmt->bindParam(13, $event);
            break;
        default:
            break;
    }
    $stmt->execute();
    $clinical_data_pdo = null;
} else {
    http_response_code(401);
    echo "You are not authorised to do this operation!";
}
