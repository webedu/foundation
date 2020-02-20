<?php
function getFeedback ($webkitconnectionobject,$db) {
	if ($webkitconnectionobject['feedbacktype']=="chart") {
		eval('$webkitconnectionobject[\'chartobject\']=$webkitconnectionobject[\'source\']($webkitconnectionobject,$db);');
	} else if ($webkitconnectionobject['feedbacktype']=="text") {
		eval('$webkitconnectionobject[\'text\']=$webkitconnectionobject[\'source\']($webkitconnectionobject,$db);');
	}
	return $webkitconnectionobject;
}
?>