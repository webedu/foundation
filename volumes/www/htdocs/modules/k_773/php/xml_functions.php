<?php
function getConnectionObj ($rawdata) {
	ob_start();
	print($rawdata);
	$xmldocument = ob_get_contents();
	ob_end_clean();
	$xmlreader = new XMLReader();
	$xmlreader->XML($xmldocument);
	$connectionObj=array();
	$stopper=false;
	$xmlreader->read();
	while($xmlreader->read()) {
		report("name: ".$xmlreader->name.", value: ".$xmlreader->value.", nodeType: ".$xmlreader->nodeType.", hasValue: ".$xmlreader->hasValue.", depth: ".$xmlreader->depth);
		if ($xmlreader->nodeType==1){
			$nodename=$xmlreader->name;
		} else if ($xmlreader->nodeType==3){
			$connectionObj[$nodename]=$xmlreader->value;
		}
	}
	return $connectionObj;
}
function getXMLDoc ($connectionObj) {
	$xmlwriter = new xmlWriter();
	$xmlwriter->openMemory();
	$xmlwriter->startDocument('1.0','UTF-8');
	$xmlwriter->startElement('root');
	foreach ($connectionObj as $key => $value) {
		if (is_array($value)AND$connectionObj['connectiontype']=='authentication'AND$key=='data') {
			$xmlwriter->startElement('activities');
			foreach ($value as $key2=>$value2) {
				$xmlwriter->startElement('activity');
				foreach ($value2 as $key3=>$value3) {
					$xmlwriter->writeElement ($key3,$value3);
				}
				$xmlwriter->endElement();
			}
			$xmlwriter->endElement();
		} else if (is_string($value)AND$connectionObj['connectiontype']=='feedback'AND$connectionObj['feedbacktype']=='chart'AND$key=='chartobject') {
			$domobject_chartobject=new DOMDocument();
			$domobject_chartobject->loadXML($value);
			$metadata=$domobject_chartobject->getElementsByTagName('metadata');
			$data=$domobject_chartobject->getElementsByTagName('data');
			$xmlwriter->startElement('chartobject');
			$xmlwriter->startElement('metadata');
			foreach($metadata->item(0)->attributes as $attribute) {
				$xmlwriter->writeAttribute($attribute->name,$attribute->value);
			}
			$xmlwriter->endElement('metadata');
			$xmlwriter->startElement('data');
			foreach($data->item(0)->getElementsByTagName('class') as $class) {
				$xmlwriter->startElement('class');
				foreach($class->attributes as $attribute) {
					$xmlwriter->writeAttribute($attribute->name,$attribute->value);
				}
				$xmlwriter->text($class->nodeValue);
				$xmlwriter->endElement();
			}
			$xmlwriter->endElement();
			$xmlwriter->endElement();
		} else if (is_array($value)AND$key=='reports') {
			$xmlwriter->startElement('reports');
			foreach ($value as $key2=>$value2) {
				$xmlwriter->writeElement ('report',$value2);
			}
			$xmlwriter->endElement();
		} else {
			$xmlwriter->writeElement ($key,$value);
		}
	}
	$xmlwriter->endElement();
	return $xmlwriter->outputMemory(true);
}
?>
