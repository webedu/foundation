<?php
	function get_metadata ($lom_file) {
		$lom_xml = simplexml_load_file($lom_file);
		// METADATA OBJ = Ass array
		
		// lom->general-title
		$metadata['title']				= array('label'=>'Titel',							'data'=>$lom_xml->general->title->string);
		
		// lom->lifeCycle->contribute (author AND date)
		foreach ($lom_xml->lifeCycle->contribute as $contribute) {
			if ($contribute->role->value[0]=="author") {
				$authors=array();
				foreach($contribute->entity as $author) {
					$author_title=substr(strstr($author,"TITLE:"),6,strpos(strstr($author,"TITLE:"),"\n")-6);
					$author_name=substr(strstr($author,"FN:"),3,strpos(strstr($author,"FN:"),"\n")-3);
					$authors[]=$author_title.(($author_title!=""&&$author_title)?" ": "").$author_name;
				}
				$metadata['author']		= array('label'=>'Autor(in)',						'data'=>implode(", ",$authors));
				$metadata['date']		= array('label'=>'Datum',							'data'=>$contribute->date->dateTime[0]);
				break;
			}
		}
		
		// lom->lifeCycle->contribute (co-authors)
		foreach ($lom_xml->lifeCycle->contribute as $contribute) {
			if ($contribute->role->value[0]=="unknown") {
				$co_authors=array();
				foreach($contribute->entity as $co_author) {
					$author_title=substr(strstr($co_author,"TITLE:"),6,strpos(strstr($co_author,"TITLE:"),"\n")-6);
					$author_name=substr(strstr($co_author,"FN:"),3,strpos(strstr($co_author,"FN:"),"\n")-3);
					$co_authors[]=$author_title.(($author_title!="")?" ": "").$author_name;
				}
				$metadata['co_authors']	= array('label'=>'Ko-Autoren',						'data'=>implode(", ",$co_authors));
				break;
			}
		}
		
		//lom->general->description
		$descriptions=array();
		foreach ($lom_xml->general->description as $description) {
			$descriptions[]=$description->string[0];
		}
		$metadata['description']		= array('label'=>'Beschreibung',					'data'=>implode("<br/>\n",$descriptions));
		
		//lom->general->coverage
		$coverages=array();
		foreach ($lom_xml->general->coverage as $coverage) {
			$coverages[]=$coverage->string[0];
		}
		$metadata['coverage']			= array('label'=>'Räumlicher und zeitlicher Bezug',	'data'=>implode("<br/>\n",$coverages));
		
		//lom->general->relation
		$relations=array();
		foreach ($lom_xml->relation as $relation) {
			if ($relation->resource->description->string[0]!="") {
				$relations[]=$relation->resource->description->string[0];
			}
		}
		$metadata['relation']			= array('label'=>'Bezug zu anderen Lernmodulen',	'data'=>implode("<br/>\n",$relations));
		
		//lom->general->relation (WEBGEO:CAT_0)
		$cat_0=array();
		foreach ($lom_xml->relation as $relation) {
			if ($relation->resource->identifier->catalog[0]=="WEBGEO:CAT_0") {
				$cat_0[]=$relation->resource->identifier->entry[0];
			}
		}
		$metadata['cat_0']				= array('label'=>'Bereich',							'data'=>implode("<br/>\n",$cat_0));
		
		//lom->general->relation (WEBGEO:CAT_1 MAIN)
		foreach ($lom_xml->relation as $relation) {
			if ($relation->resource->identifier->catalog[0]=="WEBGEO:CAT_1") {
				$metadata['cat_1_main']	= array('label'=>'Primäres Themengebiet',			'data'=>$relation->resource->identifier->entry[0]);
				break;
			}
		}
		
		//lom->general->relation (WEBGEO:CAT_1)
		$cat_1=array();
		foreach ($lom_xml->relation as $relation) {
			if ($relation->resource->identifier->catalog[0]=="WEBGEO:CAT_1") {
				$cat_1[]=$relation->resource->identifier->entry[0];
			}
		}
		$metadata['cat_1']				= array('label'=>'Themengebiet',					'data'=>implode("<br/>\n",$cat_1));
		
		//lom->general->relation (WEBGEO:CAT_2)
		$cat_2=array();
		foreach ($lom_xml->relation as $relation) {
			if ($relation->resource->identifier->catalog[0]=="WEBGEO:CAT_2") {
				$cat_2[]=$relation->resource->identifier->entry[0];
			}
		}
		$metadata['cat_2']				= array('label'=>'Teilgebiet',						'data'=>implode("<br/>\n",$cat_2));
		
		//lom->general->relation (WEBGEO:CAT_3)
		$cat_3=array();
		foreach ($lom_xml->relation as $relation) {
			if ($relation->resource->identifier->catalog[0]=="WEBGEO:CAT_3") {
				$cat_3[]=$relation->resource->identifier->entry[0];
			}
		}
		$metadata['cat_3']				= array('label'=>'Thema',							'data'=>implode("<br/>\n",$cat_3));
		
		//lom->general->keyword (class NOT "category)
		$keywords=array();
		foreach ($lom_xml->general->keyword as $keyword) {
			$keywords[]= $keyword->string[0];
		}
		$metadata['keywords']			= array('label'=>'Schlagwörter',					'data'=>implode("<br/>\n",$keywords));
		
		// lom->general->language
		$languages=array();
		foreach ($lom_xml->general->language as $language) {
			$languages[]=$language[0];
		}
		$metadata['languages']			= array('label'=>'Sprache',							'data'=>implode(", ",$languages));
		
		// lom->technical->installationRemarks
		$metadata['installationRemarks']= array('label'=>'Technische Voraussetzungen',		'data'=>$lom_xml->technical->installationRemarks->string[0]);
		
		// lom->technical->format
		$formats=array();
		foreach ($lom_xml->technical->format as $format) {
			$formats[]=$format[0];
		}
		$metadata['formats']			= array('label'=>'Formate',							'data'=>implode(", ",$formats));
		
		// lom->general-identifier
		$metadata['id']					= array('label'=>'Interne Objektkennung',			'data'=>$lom_xml->general->identifier->entry[0]);
		
		// location (NOT lom->technical->location, BUT DYNAMIC)
		$metadata['location']			= array('label'=>'URL',								'data'=>'http://www.webgeo.de/index.php?id='.$lom_xml->general->identifier->entry[0]);
		
		//publisher
		foreach ($lom_xml->lifeCycle->contribute as $contribute) {
			if ($contribute->role->value[0]=="publisher") {
				$publishers=array();
				foreach($contribute->entity as $publisher) {
					$publishers[]=substr(strstr($publisher,"ORG:"),4,strpos(strstr($publisher,"ORG:"),"\n")-4);
				}
				$metadata['publisher']	= array('label'=>'Herausgeber(in)',					'data'=>implode(", ",$publishers));
				break;
			}
		}
		
		// lom->rights->copyrightAndOtherRestrictions
		$metadata['copyrights']			= array('label'=>'Nutzungshinweise',				'data'=>$lom_xml->rights->copyrightAndOtherRestrictions->description->string[0]);
		
		return $metadata;
	}
?>