<?php

include('configuration/db.php');
include('configuration/mcode.php');
include('configuration/key.php');

session_start();
$encryption_key = hex2bin($key);
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];

mysqli_close($connect);

$roles = rtrim(trim($_POST["roles"], ","));
$hasAdminRole = in_array("admin", explode(",", strtolower($roles)));
const DELIMITER = ',';

if ($hasAdminRole) {
    $unencryptedID = $_POST["id"];
    $patientID = "0x" . bin2hex(openssl_encrypt($unencryptedID, $cipher, $encryption_key, 0, $iv));
    // indexes of the fields to decrypt for each table
    $decrypt = array(
        "patient" => [0, 1, 2, 3, 4, 5, 7],
        "biospecimens" => [0],
        "cancers" => [0],
        "cbc" => [0],
        "cmp" => [0],
        "comorbid" => [0],
        "death" => [0],
        "labs" => [0],
        "medication" => [0],
        "nf1diagnosis" => [0],
        "nf1manifestations" => [0],
        "nf1procedures" => [0],
        "nf1skinlesions" => [0],
        "outcome" => [0],
        "radiation" => [0],
        "status" => [0],
        "surgery" => [0],
        "tumor" => [0],
        "variant" => [0],
    );
    // header names for the tables
    $fields = array(
        "patient" => ["ID", "Birth date", "Gender", "Race", "Postal code", "Institution", "Study", "Family ID"],
        "biospecimens" => [
            "ID",
            "Date of collection",
            "Specimen type",
            "Specimen cellularity %",
            "Location of collection",
            "Location of storage",
            "Banking ID",
            "Paired with blood sample",
            "Imaging available",
            "Comments"
        ],
        "cancers" => [
            "ID",
            "Date of diagnosis",
            "Tumor type",
            "Tumor histology",
            "Clinical status",
            "Body location code",
            "Body location side",
            "OncoTree code",
            "Clinical stage group",
            "Clinical stage system",
            "Pathologic stage group",
            "Pathologic stage system",
            "Comments"
        ],
        "cbc" => ["ID", "Date", "CBC type", "CBC count", "Comments"],
        "cmp" => ["ID", "Date", "CMP type", "CMP count", "Comments"],
        "comorbid" => [
            "ID",
            "Date of evaluation",
            "Comorbid condition code",
            "Condition clinical status",
            "Comments"
        ],
        "death" => ["ID", "Date", "Comments"],
        "labs" => [
            "ID",
            "Date of evaluation",
            "Location",
            "Height (cm)",
            "Weight (kg)",
            "Blood pressure diastolic (mmHg)",
            "Blood pressure systolic (mmHg)",
            "Comments"
        ],
        "medication" => [
            "ID",
            "Medication",
            "Start",
            "Stop",
            "Termination reason",
            "Treatment intent",
            "Comments"
        ],
        "nf1diagnosis" => [
            "ID",
            "Date of diagnosis",
            "Clinical diagnosis",
            "Mode of transmission",
            "Diagnostic criteria",
            "Severity (Riccardi scale)",
            "Visibility (Ablon scale)",
            "Age of puberty/menarche",
            "Head circumference",
            "Comments"
        ],
        "nf1manifestations" => ["ID", "Date of diagnosis", "Type", "Evaluation", "Comments"],
        "nf1procedures" => ["ID", "Date", "Procedure", "Findings"],
        "nf1skinlesions" => [
            "ID",
            "Date of diagnosis",
            "Type",
            "Evaluation",
            "Number",
            "Location",
            "Comments"
        ],
        "outcome" => ["ID", "Date of evaluation", "Disease status", "Comments"],
        "radiation" => [
            "ID",
            "Date",
            "Location",
            "Procedure",
            "Body site",
            "Treatment intent",
            "Comments"
        ],
        "status" => [
            "ID",
            "Date of evaluation",
            "ECOG performance status",
            "Karnofsky performance status",
            "Comments"
        ],
        "surgery" => [
            "ID",
            "Date",
            "Location",
            "Procedure",
            "Body site",
            "Treatment intent",
            "Comments"
        ],
        "tumor" => [
            "ID",
            "Date",
            "Tumor test code",
            "Tumor test result",
            "Comments"
        ],
        "variant" => [
            "ID",
            "Date",
            "Test name",
            "Gene",
            "cDNA",
            "Protein",
            "Variant found ID",
            "Variant found NM number",
            "Variant found interpretation",
            "Genomic source class",
            "Comments"
        ],
    );
    // list of queries to execute
    $queries = array(
        "patient" => "
            SELECT 
                HEX(id),
                HEX(birth),
                HEX(gender),
                HEX(race),
                HEX(zip),
                HEX(institution), 
                study, 
                HEX(family)
            FROM Patient
            WHERE id LIKE {$patientID}",
        "biospecimens" => "
            SELECT DISTINCT 
                HEX(Biospecimens.id), 
                Biospecimens.date, 
                Biospecimens.type, 
                Biospecimens.cellularity, 
                Biospecimens.collection, 
                Biospecimens.storage, 
                Biospecimens.bankingid, 
                Biospecimens.paired,
                Biospecimens.imaging,
                Biospecimens.comment
            FROM Biospecimens
            JOIN Patient ON Biospecimens.id = Patient.id
            WHERE Biospecimens.id = {$patientID}",
        "cancers" => "
            SELECT 
                HEX(Diseases.id),
                Diseases.date,
                Diseases.type,
                Diseases.histology,
                Diseases.status,
                Diseases.code,
                Diseases.side,
                Diseases.oncotree,
                Diseases.clinicalsg,
                Diseases.clinicalss,
                Diseases.pathologicsg,
                Diseases.pathologicss,
                Diseases.comments 
            FROM Diseases
            JOIN Patient ON Diseases.id = Patient.id
            WHERE Diseases.id = {$patientID}",
        "cbc" => "
            SELECT 
                DISTINCT HEX(CBC.id), 
                CBC.date, 
                CBC.type, 
                CBC.count, 
                CBC.comment 
            FROM CBC
            JOIN Patient ON CBC.id = Patient.id
            WHERE CBC.id = {$patientID}",
        "cmp" => "
            SELECT 
                DISTINCT HEX(CMP.id),
                CMP.date,
                CMP.type,
                CMP.count,
                CMP.comment
            FROM CMP
            JOIN Patient on CMP.id = Patient.id
            WHERE CMP.id = {$patientID}",
        "comorbid" => "
            SELECT 
                HEX(Comorbid.id),
                Comorbid.date,
                Comorbid.code,
                Comorbid.status,
                Comorbid.comment
            FROM Comorbid
            JOIN Patient on Comorbid.id = Patient.id
            WHERE Comorbid.id LIKE {$patientID}",
        "death" => "
            SELECT
                HEX(Death.id),
                Death.date,
                Death.comment
            FROM Death
            JOIN Patient ON Death.id = Patient.id
            WHERE Death.id = {$patientID}",
        "labs" => "
            SELECT
                DISTINCT HEX(Lab.id),
                Lab.date,
                Lab.location,
                Lab.height,
                Lab.weight,
                Lab.diastolic,
                Lab.systolic,
                Lab.comment
            FROM Lab
            JOIN Patient on Lab.id = Patient.id
            WHERE Lab.id = {$patientID}",
        "medication" => "
            SELECT 
                DISTINCT HEX(Medication.id), 
                Medication.medication, 
                Medication.start, 
                Medication.stop, 
                Medication.reason, 
                Medication.intent, 
                Medication.comment 
            FROM Medication
            JOIN Patient ON Medication.id = Patient.id
            WHERE Medication.id = {$patientID}",
        "nf1diagnosis" => "
            SELECT 
                DISTINCT HEX(DiagnosisNF1.id),
                DiagnosisNF1.date,
                DiagnosisNF1.diagnosis,
                DiagnosisNF1.mode,
                DiagnosisNF1.criteria,
                DiagnosisNF1.severity,
                DiagnosisNF1.visibility,
                DiagnosisNF1.age,
                DiagnosisNF1.circumference,
                DiagnosisNF1.comment 
            FROM DiagnosisNF1
            JOIN Patient ON DiagnosisNF1.id = Patient.id
            WHERE DiagnosisNF1.id = {$patientID}",
        "nf1manifestations" => "
            SELECT
                DISTINCT HEX(ManifestationsNF1.id),
                ManifestationsNF1.date,
                ManifestationsNF1.type,
                ManifestationsNF1.evaluation,
                ManifestationsNF1.comment
                FROM ManifestationsNF1
            JOIN Patient ON ManifestationsNF1.id = Patient.id
            WHERE ManifestationsNF1.id = {$patientID}",
        "nf1procedures" => "
            SELECT
                DISTINCT HEX(ProcedureNf1.id),
                ProcedureNf1.date,
                ProcedureNf1.type,
                ProcedureNf1.comment
                FROM ProcedureNf1
            JOIN Patient ON ProcedureNf1.id = Patient.id
            WHERE ProcedureNf1.id = {$patientID}",
        "nf1skinlesions" => "
            SELECT
                DISTINCT HEX(LesionsNF1.id),
                LesionsNF1.date,
                LesionsNF1.type,
                LesionsNF1.evaluation,
                LesionsNF1.number,
                LesionsNF1.location,
                LesionsNF1.comment
            FROM LesionsNF1
            JOIN Patient ON LesionsNF1.id = Patient.id
            WHERE LesionsNF1.id = {$patientID}",
        "outcome" => "
            SELECT
            DISTINCT HEX(Outcome.id),
            Outcome.date,
            Outcome.status,
            Outcome.comment 
            FROM Outcome
            JOIN Patient ON Outcome.id = Patient.id
            WHERE Outcome.id = {$patientID}",
        "radiation" => "
            SELECT
                DISTINCT HEX(Radiation.id),
                Radiation.date,
                Radiation.location,
                Radiation.type,
                Radiation.site,
                Radiation.intent,
                Radiation.comment
            FROM Radiation
            JOIN Patient ON Radiation.id = Patient.id
            WHERE Radiation.id = {$patientID}",
        "status" => "
            SELECT
                DISTINCT HEX(ClinicalCondition.id),
                ClinicalCondition.date,
                ClinicalCondition.ecog,
                ClinicalCondition.karnofsky,
                ClinicalCondition.comment
                FROM ClinicalCondition
            JOIN Patient ON ClinicalCondition.id = Patient.id
            WHERE ClinicalCondition.id LIKE {$patientID}",
        "surgery" => "
            SELECT
                DISTINCT HEX(Surgery.id),
                Surgery.date,
                Surgery.location,
                Surgery.type,
                Surgery.site,
                Surgery.intent,
                Surgery.comment
            FROM Surgery
            JOIN Patient ON Surgery.id = Patient.id
            WHERE Surgery.id = {$patientID}",
        "tumor" => "
            SELECT
                DISTINCT HEX(Tumor.id),
                Tumor.date,
                Tumor.test,
                Tumor.result,
                Tumor.comment
            FROM Tumor
            JOIN Patient ON Tumor.id = Patient.id
            WHERE Tumor.id = {$patientID}",
        "variant" => "
            SELECT
                DISTINCT HEX(Variant.id),
                Variant.date,
                Variant.test,
                Variant.gene,
                Variant.cdna,
                Variant.protein,
                Variant.variantid,
                Variant.varianthgvs,
                Variant.interpretation,
                Variant.source,
                Variant.comment
            FROM Variant
            JOIN Patient ON Variant.id = Patient.id
            WHERE Variant.id = {$patientID}"
    );
    $zip = new ZipArchive;
    if ($zip->open("files/$unencryptedID.zip", ZipArchive::CREATE) === TRUE) {
        foreach ($queries as $queryKey => $query) {
            $result = mysqli_query($conn, $query);
            if ($result->num_rows > 0) {
                $filename = "files/" . $unencryptedID . "_" . $queryKey . "_" . date("Y-m-d") . ".csv";
                $f = fopen($filename, "w");
                fputcsv($f, $fields[$queryKey], DELIMITER);
                while ($row = $result->fetch_array(MYSQLI_NUM)) {
                    $tmp = array(); // fields cannot be modified, so a temporary array is created to store the modified values
                    foreach($row as $i => $field) {
                        if (ctype_xdigit($field) === TRUE && in_array($i, $decrypt[$queryKey])) { // decrypt the field if necessary
                            if (strlen($field) % 2 != 0) { // prepend a zero so hex2bin doesn't fail
                                $field = "0" . $field;
                            }
                            $field = openssl_decrypt(hex2bin($field), $cipher, $encryption_key, 0, $iv);
                        }
                        $tmp[$i] = $field;
                    }
                    fputcsv($f, $tmp, DELIMITER);
                }
                fclose($f);
                $zip->addFile($filename, substr($filename, 6));
            }
        }
        $zip->close();
        /* 
        *   Delete the created csv files.
        *   This can only be done after closing the zip file.
        */
        $mask = "files/$unencryptedID*.csv";
        array_map("unlink", glob($mask));
        echo "files/$unencryptedID.zip";
    }
}