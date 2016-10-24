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
$csvdata="Username,Firstname,Lastname,Login Method,First Login(course),Role\n";
$data = get_enrolled_users($context,''); // get students
foreach($data as $user){
	$role="none";
	if($roles=get_user_roles($context,$user->id)){ // read the user rights for the current
		$role="";
		foreach($roles as $r){
			$role .=$r->name . " ";		
		}
	}
	$csvdata .= $user->username . ',' . $user->firstname . "," . $user->lastname . ',' . $user->auth . ",0," . $role . "\n";
}
header("Content-Type: text/csv");
header("Content-Length: " . strlen($csvdata));
echo $csvdata ;
exit;