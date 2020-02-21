<?php
	// parsexml_metadata parses only structure.xml, NOT THE LOM FILE
	function parsexml_metadata($xmlfile){
		$xmlvars = array();
		$sxml = simplexml_load_file($xmlfile);
		// $sxml points to the <manifest> element:
		$xmlvars["schema"]				= trim($sxml->metadata->schema);
		$xmlvars["schemaversion"]		= trim($sxml->metadata->schemaversion);
		$xmlvars["name"]				= trim($sxml->metadata->name);
		$xmlvars["pname"]				= trim($sxml->metadata->project->name);
		$xmlvars["topic"]				= trim($sxml->metadata->project->topic);
		//$teilpro --> e.g. pemo; but MUST BE ".." to enscure correct pathes in e.g. p_002_05 (PHP content) or p_002_02 (SWF content)
		$xmlvars["teilpro"]				= "..";
		$xmlvars["teilpro_buchst"]		= trim($sxml->metadata->project->code);
		$xmlvars["rahmen"]				= "rahmen.php";
		$xmlvars["folder"]				= trim($sxml->metadata->folder);
		$xmlvars["total_pages"]			= (int)count($sxml->organization->children());
		$xmlvars["glossary_url"]		= trim($sxml->metadata->tools->glossary);
		$xmlvars["glossary_form"]		= trim($sxml->metadata->tools->glossary[0]['form']);
		$xmlvars["webgeo_home"]			= trim($sxml->metadata->home);
		// Init with pageid=1
		$xmlvars["pageid"]				= 1;
		
		//$meta_tags ??? --> use module_lom.xml !!!
		
		// Return vars in ass array:
		return $xmlvars;
	}
	
	function parsexml_organization($xmlfile,$pageid) {
		$xmlvars = array();
		$sxml = simplexml_load_file($xmlfile);
		// $sxml points to the <manifest> element:
		// <metadata> (as above)
		$xmlvars["schema"]					= trim($sxml->metadata->schema);
		$xmlvars["schemaversion"]			= trim($sxml->metadata->schemaversion);
		$xmlvars["name"]					= trim($sxml->metadata->name);
		$xmlvars["pname"]					= trim($sxml->metadata->project->name);
		$xmlvars["topic"]					= trim($sxml->metadata->project->topic);
		//$teilpro --> e.g. pemo; but MUST BE ".." to enscure correct pathes in e.g. p_002_05 (PHP content) or p_002_02 (SWF content)
		$xmlvars["teilpro"]					= "..";
		$xmlvars["teilpro_buchst"]			= trim($sxml->metadata->project->code);
		$xmlvars["rahmen"]					= "iframe_content.php";
		$xmlvars["folder"]					= trim($sxml->metadata->folder);
		// <organization>
		$xmlvars["content_php_file"]		= $sxml->organization->item[$pageid-1].".php";
		$xmlvars['navigation_ids']['prev']	= ($pageid > 1) ? $pageid-1 : 0;
		$xmlvars['navigation_ids']['next']	= ($pageid < count($sxml->organization->children())) ? $pageid+1 : 0;
		$xmlvars["branching"]				= ($sxml->organization->item[$pageid-1]['branching']=="true") ? true : false;
		$xmlvars["swf_filename"]			= $sxml->organization->item[$pageid-1].".swf";
		$xmlvars["total_pages"]				= count($sxml->organization->children());
		$xmlvars["glossary_url"]			= trim($sxml->metadata->tools->glossary);
		$xmlvars["glossary_form"]			= trim($sxml->metadata->tools->glossary[0]['form']);
		$xmlvars["webgeo_home"]				= trim($sxml->metadata->home);
		return $xmlvars;
	}
?>