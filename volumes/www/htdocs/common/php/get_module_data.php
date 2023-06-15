<?php
include ("common/php/get_metadata.php");

function countOrNull($list) 
{
   if(is_null($list)) { return 0; }
   // better: if is countable
   return count($list);
}

function getModuleMetaData($moduleId, $protocol='http://', $host='www.webgeo.de')
{
   $moduleDir = 'modules'; 
   $metaDataPath = 'metadata/wm_lom.xml';
   $result = ['complete' => false, 'module' => $moduleId]; 
   $counter =0;
   $filePath = $moduleDir.'/'.$moduleId.'/'.$metaDataPath;
   if(file_exists($filePath)) 
   {
      $result['TOC0'] = [""];
      $result['TOC1'] = [""];
      $result['TOC2'] = [""];
      $result['TOC3'] = [""];      
      $metadata = get_metadata($filePath); 
      foreach($metadata as $item)
      {
          //var_dump($item);
          /// TOCS   
          if ('Bereich' == $item['label'])
          {  $counter++; $result['TOC0'] = explode("<br/>\n",$item['data']); }
          if ('Themengebiet' == $item['label'])
          {  $counter++; $result['TOC1'] = explode("<br/>\n",$item['data']); }
          if ('Teilgebiet' == $item['label'])
          {  $counter++; $result['TOC2'] = explode("<br/>\n",$item['data']); }
          if ('Thema' == $item['label'])
          {  $counter++; $result['TOC3'] = explode("<br/>\n",$item['data']); }
          /// ITEM
          if ('Sprache' == $item['label'])
          {  $result['Sprache'] = $item['data']; }  
          if ('URL' == $item['label'])
          {  $counter++; $result['url'] = $item['data']; }
          if ('Titel' == $item['label'])
          {  $counter++; $result['title'] = $item['data']; }  
          if ('Beschreibung' == $item['label'])
          {  $result['description'] = $item['data']; }    
          //echo $item['label']. ' ';
          if ('Schlagwörter' == $item['label'])
          {  $result['keywords'] = explode("<br/>\n",$item['data']); }        
      }
      // overwrite URL (as it is wrong in metadata)
      $result['url'] = $protocol.$host.'/'.$moduleId.'/';
      if($counter>= 6) 
        { $result['complete'] = true; }
   }
   return $result;
}


function getAllModules()
{
   $moduleDir = 'modules'; 
   //$metaDataPath = 'metadata/wm_lom.xml';
   $result = [];
   $subdirs = scandir($moduleDir);
   foreach($subdirs as $dir)
   {
      if(is_dir($moduleDir.'/'.$dir) and strpos($dir, '_') !== false)
      {
         if(strpos($dir, '__') === false) {
           $result[] = $dir; 
         }
      }
   }
   return $result;
}

function getModulesMetaData($modules = null, $protocol='http://', $host='www.webgeo.de') 
{
    $result = [];
    if(is_null($modules = null)) 
    { $modules = getAllModules(); }
    foreach($modules as $module)
    {
        $metadata = getModuleMetaData($module, $protocol, $host);        
        $no_categories = max(countOrNullv($metadata['TOC0']),
                             countOrNull($metadata['TOC1']),
                             countOrNull($metadata['TOC2']),
                             countOrNull($metadata['TOC3']));
        for($i = 0; $i < $no_categories; $i++)
        {
            $toc0 = $metadata['TOC0'][$i % countOrNull($metadata['TOC0'])];
            $toc1 = $metadata['TOC1'][$i % countOrNull($metadata['TOC1'])];
            $toc2 = $metadata['TOC2'][$i % countOrNull($metadata['TOC2'])];
            $toc3 = $metadata['TOC3'][$i % countOrNull($metadata['TOC3'])];
            $reset = false;
            if ('fw'==substr($metadata['module'],0,2) or 'fao'==substr($metadata['module'],0,3))
            {
              switch ($metadata['Sprache'])
              {
                case 'es': $reset = true; $toc1 = 'FAO spanish'; break;
                case 'fr': $reset = true; $toc1 = 'FAO french';  break;
                case 'en': $reset = true; $toc1 = 'FAO english';  break;
              }
              if($reset)
              {
               $toc0 = 'WEBGEO fao';
               $toc2 = '';
               $toc3 = ''; 
              }
            }
            if(!array_key_exists($toc0, $result)) 
            { $result[$toc0] = []; }
            if(!array_key_exists($toc1, $result[$toc0])) 
            { $result[$toc0][$toc1] = []; }
            if(!array_key_exists($toc2, $result[$toc0][$toc1])) 
            { $result[$toc0][$toc1][$toc2] = []; }    
            if(!array_key_exists($toc3, $result[$toc0][$toc1][$toc2])) 
            { $result[$toc0][$toc1][$toc2][$toc3] = []; }   
            $result[$toc0][$toc1][$toc2][$toc3][] = $metadata;  
        }
    }
    ksort($result);
    return $result;     
}

function getMainTopics($moduleData = null, $protocol='http://', $host='www.webgeo.de')
{
    $result = [];
    if(is_null($moduleData = null)) 
    { $moduleData = getModulesMetaData(null, $protocol, $host); }  
    
    foreach($moduleData as $toc0 => $innerData)
    {
       ksort($innerData); 
       $tokens = [];
       foreach($innerData as $toc1 => $deepData)
       {
           if("" != $toc1) 
           {
               $topic1 = urlencode(strtolower($toc1));
               $tokens[] = ['label' => $toc1, 'topic' => $topic1];
           }
       }
       if("" != $toc0)
       {           
           $topic0 = urlencode(strtolower($toc0));
           $result[] = ['label' => $toc0, 'topic' => $topic0, 'tokens' => $tokens];
       }
    }
    return $result;
}

function getSubTopics($topic = null, $moduleData = null, $level = 0, $protocol='http://', $host='www.webgeo.de')
{
    //var_dump($level);
    if(is_null($moduleData)) 
    { $moduleData = getModulesMetaData(null, $protocol, $host); } 
    if(is_null($topic))
    { return ['data' => $moduleData, 'level' => 0, 'breadcrums' => [] ]; }
    if(!is_string($topic) or 0 == strlen($topic))
    { return ['data' => [], 'level' => 0, 'breadcrums' => [] ]; }
    if(is_string($topic) and ('impressum' == $topic))
    { return ['data' => [], 'level' => 0, 'breadcrums' => ['Impressum'] ]; }
    if(is_string($topic) and ('kontakt' == $topic))
    { return ['data' => [], 'level' => 0, 'breadcrums' => ['Kontakt'] ]; }

    //var_dump($moduleData);
    if(is_array($moduleData)) {
      foreach($moduleData as $toc => $innerData)
      {
        $subTopic = urlencode(strtolower($toc));
        //echo "subtopic: ".$subTopic."\n";
        if($subTopic == $topic)
        {
            // sort here
            ksort($innerData);  // if level < 2 else sort by minimum inner id
            return ['data' => ['' => $innerData], 'level' =>$level+1, 'breadcrums'=>[$toc]];
        }
      }
    }
    if(is_array($moduleData)) { 
      foreach($moduleData as $toc => $innerData)
      {
        $result = getSubTopics($topic, $innerData, $level+1, $protocol, $host);
        if(!is_null($result))
        {
            $breadcrums = $result['breadcrums'];
            array_unshift($breadcrums, $toc);
            return ['data' => ['' => $result['data']], 
                    'level' => $result['level'],
                    'breadcrums' => $breadcrums
                    ];
        }
      }
    } 
    return null;
}



function getCloudTags($moduleData = null, $protocol='http://', $host='www.webgeo.de')
{
  $words = [];  
    
  if(is_null($moduleData)) 
    { $moduleData = getModulesMetaData(null, $protocol, $host); } 
  foreach ($moduleData as $toc0=>$data1)
  {
      //$words[strtolower($toc0)]++;
      foreach ($data1 as $toc1=>$data2)
      {
          if(!array_key_exists(strtolower($toc1), $words)) { $words[strtolower($toc1)] = 0; }
          $words[strtolower($toc1)]++;
          foreach ($data2 as $toc2=>$data3)
          {
              if(!array_key_exists(strtolower($toc2), $words)) { $words[strtolower($toc2)] = 0; }
              $words[strtolower($toc2)]++;
              foreach ($data3 as $toc3=>$data4)
              {
                  if(!array_key_exists(strtolower($toc3), $words)) { $words[strtolower($toc3)] = 0; }
                  $words[strtolower($toc3)]++;
                  foreach ($data4 as $item)
                  {
                    foreach ($item['keywords'] as $keyword)
                    {
                      //echo $keyword.' ';  
                      if(!array_key_exists(strtolower($keyword), $words)) { $words[strtolower($keyword)] = 0; }
                      $words[strtolower($keyword)]++;  
                    }
                  }
              } 
          } 
      }
  }
  return $words;  
}

function getSearchTopics($search = null, $moduleData = null, $protocol='http://', $host='www.webgeo.de')
{
    if(is_null($moduleData)) 
    { $moduleData = getModulesMetaData(null, $protocol, $host); } 
    if(!is_string($search) or strlen($search) < 3)
    { return ['data' => [], 'level' => 0, 'breadcrums' => [] ]; }
    $searchLower = strtolower($search);
    $found = [];
    $result0 = [];
    foreach ($moduleData as $toc0=>$data1)
    {
        $result1 = [];
        foreach ($data1 as $toc1=>$data2)
        {
            $result2 = [];
            foreach ($data2 as $toc2=>$data3)
            {
                $result3 = [];
                foreach ($data3 as $toc3=>$data4)
                {
                    $result4 = [];
                    foreach ($data4 as $item)
                    {
                      if(!in_array($item['module'],$found))
                      {                            
                        $searchText = $item['description'].' '.$item['title'].' '.implode(" ",$item['keywords']);  
                        //$searchText .= ' '.implode(" ",$item['TOC0']).' '.implode(" ",$item['TOC1']).' '.implode(" ",$item['TOC2']).' '.implode(" ",$item['TOC3']);
                        $searchText .= ' '.implode(" ",$item['TOC1']).' '.implode(" ",$item['TOC2']).' '.implode(" ",$item['TOC3']);
                        if (strpos(strtolower($searchText), $searchLower) !== false)
                        {
                           $result4[] = $item; 
                           array_unshift($found, $item['module']);
                        }
                        }
                    }
                    if (countOrNull($result4) > 0)
                    {
                        $result3[$toc3] = $result4;
                    }
                } 
                if (countOrNull($result3) > 0)
                {
                    $result2[$toc2] = $result3;
                }                
            } 
            if (countOrNull($result2) > 0)
            {
                $result1[$toc1] = $result2;
            }            
        }
        if (countOrNull($result1) > 0)
        {
            $result0[$toc0] = $result1;
        } 
    } 
    if (countOrNull($found) == 0) 
    {
       $notFound0 = 'Kein Suchergebnis';
       $notFound1 = 'Kein Modul für den Begriff "'.htmlspecialchars($search, ENT_QUOTES, "UTF-8").'" gefunden.';
       $result0[$notFound0] = [$notFound1 => []];      
    }
    return ['data' => $result0, 'level' => 0, 'breadcrums' => [], 'count' => countOrNull($found) ];
}

?>
