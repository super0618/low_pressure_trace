<?php
$action = $_POST['action'] ?? null;

if (!$action) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'Action is required',
    ]);
}

switch ($action) {
    case 'edit':
        echo edit($_POST['id'] ?? null);
        break;

    case 'delete':
        echo delete($_POST['id'] ?? null);
        break;

    case 'add-to-defect':
        echo addToDefect();
        break;

    case 'update-scrap':
        echo updateScrap($_POST['id'] ?? null);
        break;

    case 'update-test':
        echo updateTest($_POST['id'] ?? null);
        break;

    case 'get-total':

        echo getTotal();
        break;

    case 'get-machines':
        echo getMachines();
        break;

    case 'getDefectsToday':
        echo getDefectsToday();
        break;
    case 'getDefectsReport':
        echo getDefectsReport();
        break;

    default:
        echo add();
        break;
}

function getMachines() {
    require_once('./config/config.php');

    $query = $db->query("SELECT * FROM {$tblDefectsSetting}");

    $default_start_time = isset($_POST['formData']['from_date']) && !empty($_POST['formData']['from_date']) ? date('Y-m-d 00:00:00', strtotime($_POST['formData']['from_date'])) : date('Y-m-d 00:00:00');
    $default_end_time = isset($_POST['formData']['to_date']) && !empty($_POST['formData']['to_date']) ? date('Y-m-d 23:59:59', strtotime($_POST['formData']['to_date'])) : date('Y-m-d 23:59:59');

    $defects = array();
    $defect_count = 0;

    while ($setting = mysqli_fetch_assoc($query)) {
        if($setting['id'] == $_POST['number']) {
            $defect_count = $db->query("SELECT * FROM t_defects WHERE (defect = '" . $setting['name'] . "' Or defect = '" . $setting['value'] . "') and `timestamp` >= '" . $default_start_time . "' and `timestamp` <= '" . $default_end_time . "'")->num_rows;
        }
        array_push($defects, $setting['id']);
    }

    if (!in_array(isset($_POST['number']) ? $number = intval($_POST['number']) : null, $defects)) {
        return json_encode([
            'status' => 'failed',
            'message' => 'Invalid number',
        ]);
    }

//    $queries = [
//        1 => "( SELECT CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) as serial, t_lp_quality_1.`Date/time`, t_lp_quality_1.`Date/time` as date_time, d.id as defect_id, d.defect, d.re_test, d.scrap, t_lp_quality_1.* FROM t_defects AS d JOIN t_lp_quality_1 ON d.serial = CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) WHERE ( `Serial No.4` != '0' AND `Serial No.5` != '0' AND `Serial No.6` != '0' AND `Serial No.7` != '0' AND `Serial No.8` != '0' ) AND d.scrap IS NULL OR d.scrap = 0 ORDER BY `Date/time` DESC )",
//        2 => "( SELECT CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) as serial, t_lp_quality_2.`Date/time`, t_lp_quality_2.`Date/time` as date_time, d.id as defect_id, d.defect, d.re_test, d.scrap, t_lp_quality_2.* FROM t_defects AS d JOIN t_lp_quality_2 ON d.serial = CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) WHERE ( `Serial No.4` != '0' AND `Serial No.5` != '0' AND `Serial No.6` != '0' AND `Serial No.7` != '0' AND `Serial No.8` != '0' ) AND d.scrap IS NULL OR d.scrap = 0 ORDER BY `Date/time` DESC )",
//        3 => "( SELECT CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) as serial, t_lp_quality_3.`Date/time`, t_lp_quality_3.`Date/time` as date_time, d.id as defect_id, d.defect, d.re_test, d.scrap, t_lp_quality_3.* FROM t_defects AS d JOIN t_lp_quality_3 ON d.serial = CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) WHERE ( `Serial No.4` != '0' AND `Serial No.5` != '0' AND `Serial No.6` != '0' AND `Serial No.7` != '0' AND `Serial No.8` != '0' ) AND d.scrap IS NULL OR d.scrap = 0 ORDER BY `Date/time` DESC )",
//        4 => "( SELECT CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) as serial, t_lp_quality_4.`Date/time`, t_lp_quality_4.`Date/time` as date_time, d.id as defect_id, d.defect, d.re_test, d.scrap, t_lp_quality_4.* FROM t_defects AS d JOIN t_lp_quality_4 ON d.serial = CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) WHERE ( `Serial No.4` != '0' AND `Serial No.5` != '0' AND `Serial No.6` != '0' AND `Serial No.7` != '0' AND `Serial No.8` != '0' ) AND d.scrap IS NULL OR d.scrap = 0 ORDER BY `Date/time` DESC )",
//        5 => "( SELECT CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) as serial, t_lp_quality_5.`Date/time`, t_lp_quality_5.`Date/time` as date_time, d.id as defect_id, d.defect, d.re_test, d.scrap, t_lp_quality_5.* FROM t_defects AS d JOIN t_lp_quality_5 ON d.serial = CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) WHERE ( `Serial No.4` != '0' AND `Serial No.5` != '0' AND `Serial No.6` != '0' AND `Serial No.7` != '0' AND `Serial No.8` != '0' ) AND d.scrap IS NULL OR d.scrap = 0 ORDER BY `Date/time` DESC )",
//        6 => "( SELECT CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) as serial, t_lp_quality_6.`Date/time`, t_lp_quality_6.`Date/time` as date_time, d.id as defect_id, d.defect, d.re_test, d.scrap, t_lp_quality_6.* FROM t_defects AS d JOIN t_lp_quality_6 ON d.serial = CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) WHERE ( `Serial No.4` != '0' AND `Serial No.5` != '0' AND `Serial No.6` != '0' AND `Serial No.7` != '0' AND `Serial No.8` != '0' ) AND d.scrap IS NULL OR d.scrap = 0 ORDER BY `Date/time` DESC )",
//    ];

    $json["defect_{$number}"] = $defect_count;

    return json_encode($json);
}

function getDefectsToday() {
    require_once('./config/config.php');

    $query = $db->query("SELECT * FROM {$tblDefectsSetting}");

    $defects = array();

    $default_start_time = date('Y-m-d 00:00:00');
    $default_end_time = date('Y-m-d 23:59:59');
    $total_count = 0;

    while ($setting = mysqli_fetch_assoc($query)) {
        $defect_count = $db->query("SELECT * FROM t_defects WHERE (defect = '".$setting['name']."' Or defect = '".$setting['value']."') And `timestamp` >= '".$default_start_time."' And `timestamp`<= '".$default_end_time."';")->num_rows;

        $setting['count'] = $defect_count;
        array_push($defects, $setting);
    }

    foreach([1,2,3,4,5,6] as $index) {
        $total_count += $db->query("SELECT * FROM t_lp_quality_" . $index . " WHERE `Date/time` >= '" . $default_start_time . "' And `Date/time` <= '" . $default_end_time . "';")->num_rows;
            }

    $json["defect_lp"] = $defects;
    $json["total_lp"] = $total_count;

    $db->close();
    return json_encode($json);
}

function getDefectsReport() {
    require_once('./config/config.php');

    $data_type = $_POST["data_type"];
    $int_ext = $_POST["int_ext"];
    $from_date = $_POST["from_date"];
    $to_date = $_POST["to_date"];
    $from_date2 = $_POST["from_date2"];
    $to_date2 = $_POST["to_date2"];

    $json["month_data_internal"] = [];
    $json["month_data_external"] = [];

    foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $month) {
        $month_start_time = date('Y-m-d 00:00:00', strtotime("2023-${month}-01 00:00:00"));
        $month_end_time = date('Y-m-d 00:00:00', strtotime("2023-".($month+1)."-01 00:00:00"));

        if($month_start_time > date('Y-m-d 00:00:00'))
            break;

        $count = $db->query("SELECT * FROM t_defects join defects_setting on t_defects.`defect` = defects_setting.`name` where `timestamp` >= '${month_start_time}' and `timestamp` < '${month_end_time}' and `in_ex` = 'internal';")->num_rows ;

        $json["month_data_internal"][] = $count;

        $count = $db->query("SELECT * FROM t_defects join defects_setting on t_defects.`defect` = defects_setting.`name` where `timestamp` >= '${month_start_time}' and `timestamp` < '${month_end_time}' and `in_ex` = 'external';")->num_rows ;

        $json["month_data_external"][] = $count;
    }

    $default_start_time = !empty($from_date) ? date('Y-m-d 00:00:00', strtotime($from_date)) : date('Y-m-01 00:00:00');
    $default_end_time = !empty($to_date) ? date('Y-m-d 23:59:59', strtotime($to_date)) : date('Y-m-t 23:59:59');

    $default_start_time2 = !empty($from_date2) ? date('Y-m-d 00:00:00', strtotime($from_date2)) : date('Y-m-01 00:00:00');
    $default_end_time2 = !empty($to_date2) ? date('Y-m-d 23:59:59', strtotime($to_date2)) : date('Y-m-t 23:59:59');

    $json["date_data_internal"] = [];
    $json["date_data_external"] = [];

    $current_date = $default_start_time;

    while ($current_date <= $default_end_time) {
        $end_date = new DateTime($current_date);// create a new DateTime object with the current date and time
        if($current_date > date('Y-m-d 23:59:59'))
            break;

        $end_date->modify('23:59:59'); // modify the date to the end of the day
        $end_date = $end_date->format('Y-m-d H:i:s');

        $count = $db->query("SELECT * FROM t_defects join defects_setting on t_defects.`defect` = defects_setting.`name` where `timestamp` >= '${current_date}' and `timestamp` < '${end_date}' and `in_ex` = 'internal';")->num_rows ;

        $json["date_data_internal"][] = $count;

        $count = $db->query("SELECT * FROM t_defects join defects_setting on t_defects.`defect` = defects_setting.`name` where `timestamp` >= '${current_date}' and `timestamp` < '${end_date}' and `in_ex` = 'external';")->num_rows ;

        $json["date_data_external"][] = $count;

        $current_date = new DateTime($current_date);
        $current_date->modify('+1 day');
        $current_date = $current_date->format('Y-m-d');
    }

    $json["date_data_internal2"] = [];
    $json["date_data_external2"] = [];

    $current_date = $default_start_time2;
    while ($current_date <= $default_end_time2) {
        $end_date = new DateTime($current_date); // create a new DateTime object with the current date and time
        $end_date->modify('23:59:59'); // modify the date to the end of the day
        $end_date = $end_date->format('Y-m-d H:i:s');

        $count = $db->query("SELECT * FROM t_defects join defects_setting on t_defects.`defect` = defects_setting.`name` where `timestamp` >= '${current_date}' and `timestamp` < '${end_date}' and `in_ex` = 'internal';")->num_rows ;

        $json["date_data_internal2"][] = $count;

        $count = $db->query("SELECT * FROM t_defects join defects_setting on t_defects.`defect` = defects_setting.`name` where `timestamp` >= '${current_date}' and `timestamp` < '${end_date}' and `in_ex` = 'external';")->num_rows ;

        $json["date_data_external2"][] = $count;

        $current_date = new DateTime($current_date);
        $current_date->modify('+1 day');
        $current_date = $current_date->format('Y-m-d');
    }

    $db->close();
    return json_encode($json);
}
function getTotal() {
    require_once('./config/config.php');

    $default_start_time = isset($_POST['formData']['from_date']) && !empty($_POST['formData']['from_date']) ? date('Y-m-d 00:00:00', strtotime($_POST['formData']['from_date'])) : date('Y-m-d 00:00:00');
    $default_end_time = isset($_POST['formData']['to_date']) && !empty($_POST['formData']['to_date']) ? date('Y-m-d 23:59:59', strtotime($_POST['formData']['to_date'])) : date('Y-m-d 23:59:59');

    $outstanding = $db->query("SELECT count(*) FROM t_defects WHERE (scrap = 0 OR scrap IS NULL) and `timestamp` >= '".$default_start_time."' and `timestamp` <= '".$default_end_time."'");
    $completed = $db->query("SELECT count(*) FROM t_defects WHERE scrap = 1 and `timestamp` >= '".$default_start_time."' and `timestamp` <= '".$default_end_time."'");

    $response = [0, 0];

    $response = json_encode([mysqli_fetch_array($outstanding)[0], mysqli_fetch_array($completed)[0]]);

    $db->close();
    return $response;
}

function updateTest($id) {
    require_once('./config/config.php');

    if (!$id) {
        return json_encode([
            'status' => 'failed',
            'message' => 'ID is required',
        ]);
    }

    if (!in_array($value = $_POST['value'] ?? null, [1, 0])) {
        return json_encode([
            'status' => 'failed',
            'message' => 'Value is required',
        ]);
    }

    $resolvedValue = $value !== "" ? '?' : 'NULL';
    $statementParams = $value !== "" ? ["ii", $value, $id] : ["i", $id];

    $statement = $db->prepare("UPDATE t_defects SET re_test = {$resolvedValue} WHERE id = ?");

    if (!$statement) {
        return json_encode([
            'status' => 'failed',
            'message' => 'SQL Prepare statement failed',
        ]);
    }

    $statement->bind_param(...$statementParams);

    try {
        $statement->execute();
    } catch (\Throwable $th) {
        $response = json_encode([
            'status' => 'failed',
            'message' => $statement->error,
            'extra' => $th->getMessage(),
        ]);

        return $response;
    }

    $response = json_encode([
        'status' => 'success',
        'message' => 'Saved successfully',
    ]);

    $statement->close();
    $db->close();

    return $response;
}

function updateScrap($id) {
    require_once('./config/config.php');

    if (!$id) {
        return json_encode([
            'status' => 'failed',
            'message' => 'ID is required',
        ]);
    }

    if (!in_array($value = $_POST['value'] ?? null, [1, 0])) {
        return json_encode([
            'status' => 'failed',
            'message' => 'Value is required',
        ]);
    }

    $resolvedValue = $value !== "" ? '?' : 'NULL';
    $statementParams = $value !== "" ? ["ii", $value, $id] : ["i", $id];

    $statement = $db->prepare("UPDATE t_defects SET scrap = {$resolvedValue} WHERE id = ?");

    if (!$statement) {
        return json_encode([
            'status' => 'failed',
            'message' => 'SQL Prepare statement failed',
        ]);
    }

    $statement->bind_param(...$statementParams);

    try {
        $statement->execute();
    } catch (\Throwable $th) {
        $response = json_encode([
            'status' => 'failed',
            'message' => $statement->error,
            'extra' => $th->getMessage(),
        ]);

        return $response;
    }

    $response = json_encode([
        'status' => 'success',
        'message' => 'Saved successfully',
    ]);

    $statement->close();
    $db->close();

    return $response;
}

function addToDefect() {
    require_once('./config/config.php');

    if (!($defect = $_POST['defect'] ?? null) || !($defect_value = $_POST['defect_value'] ?? null)) {
        return json_encode([
            'status' => 'failed',
            'message' => 'Defect and defect code is required',
        ]);
    }

    if ($db->query("SELECT 1 FROM {$tblDefects} WHERE serial = '{$defect}' LIMIT 1")->num_rows) {
        return json_encode([
            'status' => 'failed',
            'message' => 'Defect part already exist',
        ]);
    }

    if (!($result = $db->query("SELECT name FROM defects_setting WHERE `value` = '{$defect_value}' LIMIT 1")->fetch_assoc())) {
        return json_encode([
            'status' => 'failed',
            'message' => 'Defect code is not valid',
        ]);
    }

    $target_db = "";
    foreach ([1,2,3,4,5,6] as $casting_machine_number) {
        $tblLPQuality_[$casting_machine_number] = "t_lp_quality" . '_' . $casting_machine_number;
        $query = "Select * from {$tblLPQuality_[$casting_machine_number]} where CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) = '{$defect}' ";
        $found = $db->query($query)->num_rows;
        if($found > 0) {
            $target_db = $tblLPQuality_[$casting_machine_number];
            break;
        }
    }

    if($target_db == "") {
        $response = json_encode([
            'status' => 'failed',
            'message' => "Error has occured.",
        ]);

        return $response;
    }

//    $statement = $db->prepare("INSERT INTO t_defects (serial, defect, {$fileds}) VALUES (?, ?{$values})");
//    echo "INSERT INTO t_defects (serial, defect{$fileds}) VALUES (?, ? {$values})";

    $statement = "CREATE TEMPORARY TABLE temp AS (select * from ${target_db} where CONCAT('HZ', SUBSTR(CHAR(`Serial No.7`),2,1), SUBSTR(CHAR(`Serial No.7`),1,1), SUBSTR(CHAR(`Serial No.6`),2,1), SUBSTR(CHAR(`Serial No.6`),1,1), SUBSTR(CHAR(`Serial No.5`),2,1), SUBSTR(CHAR(`Serial No.5`),1,1), SUBSTR(CHAR(`Serial No.4`),2,1), SUBSTR(CHAR(`Serial No.4`),1,1), SUBSTR(CHAR(`Serial No.3`),2,1), SUBSTR(CHAR(`Serial No.3`),1,1), SUBSTR(CHAR(`Serial No.2`),2,1), SUBSTR(CHAR(`Serial No.2`),1,1), SUBSTR(CHAR(`Serial No.1`),2,1), SUBSTR(CHAR(`Serial No.1`),1,1)) = '${defect}') limit 1;";
    $statement.="ALTER TABLE temp DROP COLUMN id;";
    $statement.="create TEMPORARY table temp1 as (
                    SELECT
                            NULL AS `id`,
                            '${defect}' AS `serial`,
                            '${result['name']}' AS `defect`,
                            0 AS `re_test`,
                            0 AS `scrap`,
                            CURRENT_TIMESTAMP () AS `timestamp`
                    );";
    $statement.="insert into t_defects (SELECT * FROM temp1, temp);";
    $statement.="drop table temp;";
    $statement.="drop table temp1;";

    if(mysqli_multi_query($db, $statement)){
        $response = json_encode([
            'status' => 'success',
            'message' => 'Saved successfully',
            'defect' => $defect,
            'defect_value' => $defect_value
        ]);
    } else {
        $response = json_encode([
            'status' => 'failed',
            'message' => "error has occured.",
        ]);

        return $response;
    }

    $db->close();

    return $response;
}

function edit($id) {
    require_once('./config/config.php');

    if (!$id) {
        return json_encode([
            'status' => 'failed',
            'message' => 'ID is required',
        ]);
    }

    if (!($name = $_POST['name'] ?? null) || !($value = $_POST['value'] ?? null)) {
        return json_encode([
            'status' => 'failed',
            'message' => 'Name or value is required',
        ]);
    }

    $in_ex = $_POST['in_ex'] ? $_POST['in_ex'] : '';

    $statement = $db->prepare("UPDATE defects_setting SET name = ?, value = ?, in_ex = ? WHERE id = ?");

    if (!$statement) {
        return json_encode([
            'status' => 'failed',
            'message' => 'SQL Prepare statement failed',
        ]);
    }

    $statement->bind_param("sssi", $name, $value, $in_ex, $id);

    if ($statement->execute()) {
        $response = json_encode([
            'status' => 'success',
            'message' => 'Saved successfully',
        ]);
    } else {
        $response = json_encode([
            'status' => 'failed',
            'message' => $statement->error,
        ]);
    }

    $statement->close();
    $db->close();

    return $response;
}

function delete($id) {
    require_once('./config/config.php');

    if (!$id) {
        return json_encode([
            'status' => 'failed',
            'message' => 'ID is required',
        ]);
    }

    $statement = $db->prepare("DELETE FROM defects_setting WHERE id = ?");

    if (!$statement) {
        return json_encode([
            'status' => 'failed',
            'message' => 'SQL Prepare statement failed',
        ]);
    }

    $statement->bind_param("i", $id);

    if ($statement->execute()) {
        $response = json_encode([
            'status' => 'success',
            'message' => 'Deleted',
        ]);
    } else {
        $response = json_encode([
            'status' => 'failed',
            'message' => $statement->error,
        ]);
    }

    $statement->close();
    $db->close();

    return $response;
}

function add() {
    require_once('./config/config.php');

    if (!($name = $_POST['name'] ?? null) || !($value = $_POST['value'] ?? null)) {
        return json_encode([
            'status' => 'failed',
            'message' => 'Name or value is required',
        ]);
    }
    $in_ex = $_POST['in_ex'] ? $_POST['in_ex'] : '';

    $statement = $db->prepare("INSERT INTO defects_setting (name, value, in_ex) VALUES (?, ?, ?)");

    if (!$statement) {
        return json_encode([
            'status' => 'failed',
            'message' => 'SQL Prepare statement failed',
        ]);
    }

    $statement->bind_param("sss", $name, $value, $in_ex);

    if ($statement->execute()) {
        $response = json_encode([
            'status' => 'success',
            'message' => 'Saved successfully',
        ]);
    } else {
        $response = json_encode([
            'status' => 'failed',
            'message' => $statement->error,
        ]);
    }

    $statement->close();
    $db->close();

    return $response;
}