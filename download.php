<?php
require_once('./../../config.php');
require_once($CFG->libdir.'/moodlelib.php');
global $OUTPUT, $DB,$USER;
$courseid = required_param('courseid', PARAM_INT);
require_login($courseid);
$context= get_context_instance(CONTEXT_COURSE,$courseid);
if(!has_capability('moodle/course:manageactivities',$context)){
	die("Du bist kein Trainer.");
}
$query = 'select u.id as id,username,firstname,lastname,roleid from mdl_role_assignments as a,mdl_user as u where contextid=' . intval($context->id) .' and a.userid=u.id;';
$response=$DB->get_recordset_sql($query);	
$csvdata="Username,Firstname,Lastname,Login Method,First Login(course),Role\n";
foreach($response as $result){
	$csvdata .= $result->username . "," . $result->firstname . "," . $result->lastname . ",0,0," . $result->roleid . "\n";
}
header("Content-Type: text/csv");
header("Content-Length: " . strlen($csvdata));
echo $csvdata;
exit;