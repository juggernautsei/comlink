<?php

/**
 * Patient Service
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Victor Kofia <victor.kofia@gmail.com>
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @author    Jerry Padgett <sjpadgett@gmail.com>
 * @copyright Copyright (c) 2017 Victor Kofia <victor.kofia@gmail.com>
 * @copyright Copyright (c) 2018 Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2020 Jerry Padgett <sjpadgett@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

namespace OpenEMR\Services;

use OpenEMR\Common\Uuid\UuidRegistry;
use OpenEMR\Validators\PatientValidator;
use OpenEMR\Validators\ProcessingResult;
use OpenEMR\RestControllers\EncounterRestController;

class PatientService extends BaseService
{
    const TABLE_NAME = 'patient_data';

    /**
     * In the case where a patient doesn't have a picture uploaded,
     * this value will be returned so that the document controller
     * can return an empty response.
     */
    private $patient_picture_fallback_id = -1;

    private $patientValidator;

    /**
     * Default constructor.
     */

    public function __construct()
    {

        parent::__construct(self::TABLE_NAME);
        $this->patientValidator = new PatientValidator();
    }

    /**
     * TODO: This should go in the ChartTrackerService and doesn't have to be static.
     *
     * @param  $pid unique patient id
     * @return recordset
     */
    public static function getChartTrackerInformationActivity($pid)
    {
        $sql = "SELECT ct.ct_when,
                   ct.ct_userid,
                   ct.ct_location,
                   u.username,
                   u.fname,
                   u.mname,
                   u.lname
            FROM chart_tracker AS ct
            LEFT OUTER JOIN users AS u ON u.id = ct.ct_userid
            WHERE ct.ct_pid = ?
            ORDER BY ct.ct_when DESC";
        return sqlStatement($sql, array($pid));
    }

    /**
     * TODO: This should go in the ChartTrackerService and doesn't have to be static.
     *
     * @return recordset
     */
    public static function getChartTrackerInformation()
    {
        $sql = "SELECT ct.ct_when,
                   u.username,
                   u.fname AS ufname,
                   u.mname AS umname,
                   u.lname AS ulname,
                   p.pubpid,
                   p.fname,
                   p.mname,
                   p.lname
            FROM chart_tracker AS ct
            JOIN cttemp ON cttemp.ct_pid = ct.ct_pid AND cttemp.ct_when = ct.ct_when
            LEFT OUTER JOIN users AS u ON u.id = ct.ct_userid
            LEFT OUTER JOIN patient_data AS p ON p.pid = ct.ct_pid
            WHERE ct.ct_userid != 0
            ORDER BY p.pubpid";
        return sqlStatement($sql);
    }

    public function getFreshPid()
    {
        $pid = sqlQuery("SELECT MAX(pid)+1 AS pid FROM patient_data");
        return $pid['pid'] === null ? 1 : intval($pid['pid']);
    }

    /**
     * Inserts a new patient record.
     *
     * @param $data The patient fields (array) to insert.
     * @return ProcessingResult which contains validation messages, internal error messages, and the data
     * payload.
     */
    public static function getUsernames($deviceid)
    {
        $rez = sqlStatement("SELECT pid FROM patient_devices_list WHERE deviceid='$deviceid'");
        return $rez;
    }

    public static function getuser_facility()
    {
        $rez = sqlStatement("SELECT facility.`name`, users.username, users.facility_id FROM users INNER JOIN facility ON users.facility_id=facility.id where users.`id`=1");
        return $rez;
    }
    public static function get_uuid($pid)
    {
        $rez = sqlStatement("SELECT uuid FROM `patient_data` WHERE `id`=$pid");
        return $rez;
    }
    public static function getform_encounter_id()
    {
        $rez = sqlStatement("SELECT `encounter` FROM `form_encounter`ORDER BY id DESC LIMIT 1");
        return $rez;
    }
     public function insertbulkpatient($data){
// echo 'sss';die;
        $re ['numRecords'] = count($data['bulkVitals']);
        $re_in = [];
        $re_in_total = [];
        foreach($data['bulkVitals'] as $d) {
            $deviceid = $d['subDeviceID'];
            $getpatientid = self::getUsernames($deviceid);
            $pid = $getpatientid->fields['pid'];
            $getuser_facility = self::getuser_facility();
            $d['facility'] = $getuser_facility->fields['name'];
            $d['facility_id'] = $getuser_facility->fields['facility_id'];
            $getuuid= self::get_uuid($pid);
            $puuid = UuidRegistry::uuidToString($getuuid->fields['uuid']);
            $d['sensitivity'] = "normal";
            $d["onset_date"] = date('Y-m-d h:i:s');
            $d["reason"] = 'Vitals';
            $d['provider_id'] = "1";
            $b['billing_facility'] = "3";

            $geteid = (new EncounterRestController())->post($puuid, $d);
            $getform_encounter_id = self::getform_encounter_id();

            $d['username'] = $getuser_facility->fields['username'];
            $d['groupname'] = 'Default';
            $d['bps'] = $d['vitalsData']['ctsiSystolic'];
            $d['bpd'] = $d['vitalsData']['ctsiDiastolic'];
            $d['weight'] = $d['vitalsData']['ctsiWeight'];
            $d['height'] = "";
            $d['temperature'] = $d['vitalsData']['ctsiTemperature'];
            $d['temp_method'] = "";
            $d['pulse'] = $d['vitalsData']['ctsiPulse'];
            $d['respiration'] = "";
            $d['note'] = "";
            $d['waist_circ'] = "";
            $d['head_circ'] = "";
            $d['oxygen_saturation'] = $d['vitalsData']['ctsiSpo2'];
            $d['temp_method'] = "Device";

            $serviceResult = $this->insertVital($pid, $getform_encounter_id->fields['encounter'], $d);
            $re_in['actionCode'] = 'ADD';
            $re_in['errorCode'] = '200';
            $re_in['errorDesc'] = 'Success';
            $re_in['subEhrEmrID'] = $d['subEhrEmrID'];
            $re_in['deviceID'] = $deviceid;
            array_push($re_in_total,$re_in);
        }
        $re['bulkDataResp'] = $re_in_total;
        http_response_code(200);
     echo json_encode($re);


     }
     private function insertVital($pid, $eid, $data)
    {
        $vitalSql  = " INSERT INTO form_vitals SET";
        $vitalSql .= "     date = NOW(),";
        $vitalSql .= "     activity = 1,";
        $vitalSql .= "     pid = ?,";
        $vitalSql .= "     bps = ?,";
        $vitalSql .= "     bpd = ?,";
        $vitalSql .= "     weight = ?,";
        $vitalSql .= "     height = ?,";
        $vitalSql .= "     temperature = ?,";
        $vitalSql .= "     temp_method = ?,";
        $vitalSql .= "     pulse = ?,";
        $vitalSql .= "     respiration = ?,";
        $vitalSql .= "     note = ?,";
        $vitalSql .= "     waist_circ = ?,";
        $vitalSql .= "     head_circ = ?,";
        $vitalSql .= "     oxygen_saturation = ?,";
        $vitalSql .= "     user = ?,";
        $vitalSql .= "     groupname = ?";

        $vitalResults = sqlInsert(
            $vitalSql,
            array(
                $pid,
                $data["bps"],
                $data["bpd"],
                $data["weight"],
                $data["height"],
                $data["temperature"],
                $data["temp_method"],
                $data["pulse"],
                $data["respiration"],
                $data["note"],
                $data["waist_circ"],
                $data["head_circ"],
                $data["oxygen_saturation"],
                $data['username'],
                $data['groupname'],

            )
        );

        if (!$vitalResults) {
            return false;
        }

        $formSql = "INSERT INTO forms SET";
        $formSql .= "     date = NOW(),";
        $formSql .= "     encounter = ?,";
        $formSql .= "     form_name = 'Vitals',";
        $formSql .= "     authorized = '1',";
        $formSql .= "     form_id = ?,";
        $formSql .= "     pid = ?,";
        $formSql .= "     user = ?,";
        $formSql .= "     groupname = ?,";
        $formSql .= "     formdir = 'vitals'";

        $formResults = sqlInsert(
            $formSql,
            array(
                $eid,
                $vitalResults,
                $pid,
                $data['username'],
                $data['groupname']
            )
        );

        return array($vitalResults, $formResults);
    }

    public function insert($data)
    {

        $processingResult = $this->patientValidator->validate($data, PatientValidator::DATABASE_INSERT_CONTEXT);

        if (!$processingResult->isValid()) {
            return $processingResult;
        }

        $freshPid = $this->getFreshPid();
        $data['pid'] = $freshPid;
        $data['uuid'] = (new UuidRegistry(['table_name' => 'patient_data']))->createUuid();
        $data['date'] = date("Y-m-d H:i:s");
        $data['regdate'] = date("Y-m-d H:i:s");

        $query = $this->buildInsertColumns($data);
        $sql = " INSERT INTO patient_data SET ";
        $sql .= $query['set'];

        $results = sqlInsert(
            $sql,
            $query['bind']
        );

        if ($results) {
            $processingResult->addData(array(
                'pid' => $freshPid,
                'uuid' => UuidRegistry::uuidToString($data['uuid'])
            ));
        } else {
            $processingResult->addInternalError("error processing SQL Insert");
        }

        return $processingResult;
    }

    /**
     * Updates an existing patient record.
     *
     * @param $puuidString - The patient uuid identifier in string format used for update.
     * @param $data - The updated patient data fields
     * @return ProcessingResult which contains validation messages, internal error messages, and the data
     * payload.
     */
    public function update($puuidString, $data)
    {
        $data["uuid"] = $puuidString;
        $processingResult = $this->patientValidator->validate($data, PatientValidator::DATABASE_UPDATE_CONTEXT);
        if (!$processingResult->isValid()) {
            return $processingResult;
        }
        $data['date'] = date("Y-m-d H:i:s");

        $query = $this->buildUpdateColumns($data);
        $sql = " UPDATE patient_data SET ";
        $sql .= $query['set'];
        $sql .= " WHERE `uuid` = ?";

        $puuidBinary = UuidRegistry::uuidToBytes($puuidString);
        array_push($query['bind'], $puuidBinary);
        $sqlResult = sqlStatement($sql, $query['bind']);

        if (!$sqlResult) {
            $processingResult->addErrorMessage("error processing SQL Update");
        } else {
            $processingResult = $this->getOne($puuidString);
        }
        return $processingResult;
    }

    /**
     * Returns a list of patients matching optional search criteria.
     * Search criteria is conveyed by array where key = field/column name, value = field value.
     * If no search criteria is provided, all records are returned.
     *
     * @param  $search search array parameters
     * @param  $isAndCondition specifies if AND condition is used for multiple criteria. Defaults to true.
     * @return ProcessingResult which contains validation messages, internal error messages, and the data
     * payload.
     */
    public function getAll($search = array(), $isAndCondition = true)
    {
        $sqlBindArray = array();

        //Converting _id to UUID byte
        if (isset($search['uuid'])) {
            $search['uuid'] = UuidRegistry::uuidToBytes($search['uuid']);
        }

        $sql = 'SELECT  id,
                        pid,
                        uuid,
                        pubpid,
                        title,
                        fname,
                        mname,
                        lname,
                        ss,
                        street,
                        postal_code,
                        city,
                        state,
                        county,
                        country_code,
                        drivers_license,
                        contact_relationship,
                        phone_contact,
                        phone_home,
                        phone_biz,
                        phone_cell,
                        email,
                        DOB,
                        sex,
                        race,
                        ethnicity,
                        status
                FROM patient_data';

        if (!empty($search)) {
            $sql .= ' WHERE ';
            $whereClauses = array();
            $wildcardFields = array('fname', 'mname', 'lname', 'street', 'city', 'state','postal_code','title');
            foreach ($search as $fieldName => $fieldValue) {
                // support wildcard match on specific fields
                if (in_array($fieldName, $wildcardFields)) {
                    array_push($whereClauses, $fieldName . ' LIKE ?');
                    array_push($sqlBindArray, '%' . $fieldValue . '%');
                } else {
                    // equality match
                    array_push($whereClauses, $fieldName . ' = ?');
                    array_push($sqlBindArray, $fieldValue);
                }
            }
            $sqlCondition = ($isAndCondition == true) ? 'AND' : 'OR';
            $sql .= implode(' ' . $sqlCondition . ' ', $whereClauses);
        }
        $statementResults = sqlStatement($sql, $sqlBindArray);

        $processingResult = new ProcessingResult();
        while ($row = sqlFetchArray($statementResults)) {
            $row['uuid'] = UuidRegistry::uuidToString($row['uuid']);
            $processingResult->addData($row);
        }

        return $processingResult;
    }

    /**
     * Returns a single patient record by patient id.
     * @param $puuidString - The patient uuid identifier in string format.
     * @return ProcessingResult which contains validation messages, internal error messages, and the data
     * payload.
     */
    public function getOne($puuidString)
    {
        $processingResult = new ProcessingResult();

        $isValid = $this->patientValidator->isExistingUuid($puuidString);

        if (!$isValid) {
            $validationMessages = [
                'uuid' => ["invalid or nonexisting value" => " value " . $puuidString]
            ];
            $processingResult->setValidationMessages($validationMessages);
            return $processingResult;
        }

        $sql = "SELECT  id,
                        pid,
                        uuid,
                        pubpid,
                        title,
                        fname,
                        mname,
                        lname,
                        ss,
                        street,
                        postal_code,
                        city,
                        state,
                        county,
                        country_code,
                        drivers_license,
                        contact_relationship,
                        phone_contact,
                        phone_home,
                        phone_biz,
                        phone_cell,
                        email,
                        DOB,
                        sex,
                        race,
                        ethnicity,
                        status
                FROM patient_data
                WHERE uuid = ?";

        $puuidBinary = UuidRegistry::uuidToBytes($puuidString);
        $sqlResult = sqlQuery($sql, [$puuidBinary]);

        $sqlResult['uuid'] = UuidRegistry::uuidToString($sqlResult['uuid']);
        $processingResult->addData($sqlResult);
        return $processingResult;
    }

    /**
     * @return number
     */
    public function getPatientPictureDocumentId($pid)
    {
        $sql = "SELECT doc.id AS id
                 FROM documents doc
                 JOIN categories_to_documents cate_to_doc
                   ON doc.id = cate_to_doc.document_id
                 JOIN categories cate
                   ON cate.id = cate_to_doc.category_id
                WHERE cate.name LIKE ? and doc.foreign_id = ?";

        $result = sqlQuery($sql, array($GLOBALS['patient_photo_category_name'], $pid));

        if (empty($result) || empty($result['id'])) {
            return $this->patient_picture_fallback_id;
        }

        return $result['id'];
    }

    /**
     * Fetch UUID for the patient id
     *
     * @param string $id                - ID of Patient
     * @return false if nothing found otherwise return UUID
     */
    public function getUuid($pid)
    {
        return self::getUuidById($pid, self::TABLE_NAME, 'pid');
    }
}
