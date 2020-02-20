<?php
//*********************************************************
function testdata1 ($webkitconnectionobject,$db) {
	// puts out a pieChart chartobject with number of clicks
	// querystrings for $data1 and $data2 are created
	$query_string1="SELECT * FROM `".$db['useractivities']."` ";
	$query_string1.="WHERE `learner_id`='".$webkitconnectionobject['learner_id']."' ";
	$query_string1.="AND `id`='dummyname1'";
	$query_string2="SELECT * FROM `".$db['useractivities']."` ";
	$query_string2.="WHERE `learner_id`='".$webkitconnectionobject['learner_id']."' ";
	$query_string2.="AND `id`='dummyname2'";
	// $data1 and $data2 are defined to be used...
	$data1=mysql_num_rows(mysql_query($query_string1));
	$data2=mysql_num_rows(mysql_query($query_string2));
	// ... when the chart xml-binding is created
	$xmlwriter = new xmlWriter();
	$xmlwriter->openMemory();
	$xmlwriter->startDocument('1.0','UTF-8');
	$xmlwriter->startElement('chartobject');
	$xmlwriter->startElement('metadata');
	$xmlwriter->writeAttribute('type','pieChart');
	$xmlwriter->writeAttribute('orientation','vertical');
	$xmlwriter->writeAttribute('label','Bar Chart');
	$xmlwriter->writeAttribute('rescaleToFit','true');
	$xmlwriter->writeAttribute('spacingRatio','0');
	$xmlwriter->endElement();
	$xmlwriter->startElement('data');
	$xmlwriter->startElement('class');
	$xmlwriter->writeAttribute('id','1');
	$xmlwriter->writeAttribute('label','Die Aktion mit dem Baum');
	$xmlwriter->writeAttribute('color','0xFF0000');
	$xmlwriter->text($data1);
	$xmlwriter->endElement();
	$xmlwriter->startElement('class');
	$xmlwriter->writeAttribute('id','2');
	$xmlwriter->writeAttribute('label','Die Aktion mit dem Textfeld');
	$xmlwriter->writeAttribute('color','0x00FF00');
	$xmlwriter->text($data2);
	$xmlwriter->endElement();
	$xmlwriter->endElement();
	$xmlwriter->endElement();
	$output=$xmlwriter->outputMemory(true);
	return $output;
}
function testdata2 ($webkitconnectionobject,$db) {
	// puts out a barChart chartobject with number of clicks
	// (same as above)
	$query_string1="SELECT * FROM `".$db['useractivities']."` ";
	$query_string1.="WHERE `learner_id`='".$webkitconnectionobject['learner_id']."' ";
	$query_string1.="AND `id`='dummyname2'";
	$query_string2="SELECT * FROM `".$db['useractivities']."` ";
	$query_string2.="WHERE `learner_id`='".$webkitconnectionobject['learner_id']."' ";
	$query_string2.="AND `id`='dummyname2'";
	$data1=mysql_num_rows(mysql_query($query_string1));
	$data2=mysql_num_rows(mysql_query($query_string2));
	$xmlwriter = new xmlWriter();
	$xmlwriter->openMemory();
	$xmlwriter->startDocument('1.0','UTF-8');
	$xmlwriter->startElement('chartobject');
	$xmlwriter->startElement('metadata');
	$xmlwriter->writeAttribute('type','barChart');
	$xmlwriter->writeAttribute('orientation','horizontal');
	$xmlwriter->writeAttribute('label','Bar Chart');
	$xmlwriter->writeAttribute('rescaleToFit','true');
	$xmlwriter->writeAttribute('spacingRatio','0');
	$xmlwriter->endElement();
	$xmlwriter->startElement('data');
	$xmlwriter->startElement('class');
	$xmlwriter->writeAttribute('id','1');
	$xmlwriter->writeAttribute('label','Die Aktion mit dem Baum');
	$xmlwriter->writeAttribute('color','0xFF0000');
	$xmlwriter->text($data1);
	$xmlwriter->endElement('class');
	$xmlwriter->startElement('class');
	$xmlwriter->writeAttribute('id','2');
	$xmlwriter->writeAttribute('label','Die Aktion mit dem Textfeld');
	$xmlwriter->writeAttribute('color','0x00FF00');
	$xmlwriter->text($data2);
	$xmlwriter->endElement();
	$xmlwriter->endElement();
	$xmlwriter->endElement();
	$output=$xmlwriter->outputMemory(true);
	return $output;
}
function testtext ($webkitconnectionobject,$db) {
	$query_string="SELECT * FROM `".$db['usertable']."` WHERE `learner_id` = '".$webkitconnectionobject['learner_id']."'";
	$query=mysql_query($query_string);
	if (!query) {
		die ("error=Query error");
	}
	$resultrow=mysql_fetch_array($query);
	$name=$resultrow['login'];
	$id=(int)dummyname1;
	$baumrotation=(String)getStatus_singleActivity ($webkitconnectionobject, $db, $id)['learner_response'];
	$returntext.="Ihr Login ist \"$name\". Der sich drehende Baum steht bei $baumrotation °. Wenn der Text größer als der Kasten wird, dann kann man scrollllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllen.\n\n\n\n\n\n\n\n\n\n\n\n\n...ein paar Umbrüche sind insgesamt leichter zu lesen als viel ells.";
	return $returntext;
}
/* The following code doesn't work, it is not yet adapted to the new datamodel
function testdata3 ($webkitconnectionobject,$db) {
	$query_string1="SELECT * FROM `".$db['useractivities']."` ";
	$query_string1.="WHERE `learner_id`='".$webkitconnectionobject['learner_id']."' ";
	$query_string1.="AND `id`='dummyname2'";
	$query_string2="SELECT * FROM `".$db['useractivities']."` ";
	$query_string2.="WHERE `user_autonumber`='".$webkitconnectionobject['metadata']['userid']."' ";
	$query_string2.="AND `id`='dummyname2'";
	$data1=mysql_num_rows(mysql_query($query_string1));
	$data2=mysql_num_rows(mysql_query($query_string2));
	$xmlwriter = new xmlWriter();
	$xmlwriter->openMemory();
	$xmlwriter->startDocument('1.0','UTF-8');
	$xmlwriter->startElement('chartobject');
	$xmlwriter->startElement('metadata');
	$xmlwriter->writeAttribute('type','graphChart');
	$xmlwriter->writeAttribute('orientation','vertical');
	$xmlwriter->writeAttribute('label','Bar Chart');
	$xmlwriter->writeAttribute('rescaleToFit','false');
	$xmlwriter->writeAttribute('spacingRatio','0');
	$xmlwriter->endElement('metadata');
	$xmlwriter->startElement('data');
	$xmlwriter->startElement('class');
	$xmlwriter->writeAttribute('id','1');
	$xmlwriter->writeAttribute('label','Die Aktion mit dem Baum');
	$xmlwriter->writeAttribute('color','0xFF0000');
	$xmlwriter->text($data1);
	$xmlwriter->endElement('class');
	$xmlwriter->startElement('class');
	$xmlwriter->writeAttribute('id','2');
	$xmlwriter->writeAttribute('label','Die Aktion mit dem Textfeld');
	$xmlwriter->writeAttribute('color','0x00FF00');
	$xmlwriter->text($data2);
	$xmlwriter->endElement('class');
	//
	//
	$xmlwriter->endElement('data');
	//
	$xmlwriter->endElement('chartobject');
	//
	$output=$xmlwriter->outputMemory(true);
	//
	return $output;
	//
}

function testdata4 ($webkitconnectionobject,$db) {
	//
	$query_string1="SELECT * FROM `".$db['useractivities']."` ";
	$query_string1.="WHERE `user_autonumber`='".$webkitconnectionobject['metadata']['userid']."' ";
	$query_string1.="AND `courseactivities_autonumber`='4'";
	//
	$query_string2="SELECT * FROM `".$db['useractivities']."` ";
	$query_string2.="WHERE `user_autonumber`='".$webkitconnectionobject['metadata']['userid']."' ";
	$query_string2.="AND `courseactivities_autonumber`='3'";
	//
	$data1=mysql_num_rows(mysql_query($query_string1));
	$data2=mysql_num_rows(mysql_query($query_string2));
	//
	$xmlwriter = new xmlWriter();
	$xmlwriter->openMemory();
	$xmlwriter->startDocument('1.0','UTF-8');
	//
	$xmlwriter->startElement('chartobject');
	//
	$xmlwriter->startElement('metadata');
	//
	$xmlwriter->writeAttribute('type','barChart');
	$xmlwriter->writeAttribute('orientation','vertical');
	$xmlwriter->writeAttribute('label','Bar Chart');
	$xmlwriter->writeAttribute('rescaleToFit','false');
	$xmlwriter->writeAttribute('spacingRatio','0');
	//
	$xmlwriter->endElement('metadata');
	//
	//
	$xmlwriter->startElement('data');
	//
	$xmlwriter->startElement('class');
	$xmlwriter->writeAttribute('id','1');
	$xmlwriter->writeAttribute('label','Die Aktion mit dem Baum');
	$xmlwriter->writeAttribute('labelText','<p><b><u>_label</u></b></p><p>_label wurde <b>_value</b> mal ausgeführt.</p>');
	$xmlwriter->writeAttribute('color','0xFF0000');
	$xmlwriter->text($data1);
	$xmlwriter->endElement('class');
	//
	$xmlwriter->startElement('class');
	$xmlwriter->writeAttribute('id','2');
	$xmlwriter->writeAttribute('label','Die Aktion mit dem Textfeld');
	$xmlwriter->writeAttribute('labelText','<p><b><u>_label</u></b></p><p>_label wurde <b>_value</b> mal ausgeführt.</p>');
	$xmlwriter->writeAttribute('color','0x00FF00');
	$xmlwriter->text($data2);
	$xmlwriter->endElement('class');
	//
	//
	$xmlwriter->endElement('data');
	//
	$xmlwriter->endElement('chartobject');
	//
	$output=$xmlwriter->outputMemory(true);
	//
	return $output;
	//
}
*/
//*********************************************************
?>