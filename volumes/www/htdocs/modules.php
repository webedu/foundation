<?php


error_reporting (E_ALL ^ E_NOTICE);

include ("common/php/xmlparser.php");
include ("common/php/get_metadata.php");

// ******************
// Constants defined
// ******************

$uri = trim($_SERVER['REQUEST_URI'], ' /');
$topic = substr (strrchr ($uri, '/'), 1 );

$start = microtime(TRUE);
$data = getModulesMetaData();
$stop = microtime(TRUE);
//echo "<br/>\n modules: <br/>\n"; 
//var_dump($data);

header('Content-Type: application/json;charset=utf-8');
echo json_encode($data);

/*
echo "<br/>\n Dauer: ".($stop-$start)." s <br/>\n";

foreach ($data as $toc0=>$data1)
{
    echo ' * '.$toc0."<br/>\n";
    foreach ($data1 as $toc1=>$data2)
    {
        echo ' ** '.$toc1."<br/>\n";
        foreach ($data2 as $toc2=>$data3)
        {
            echo ' *** '.$toc2."<br/>\n";
            foreach ($data3 as $toc3=>$data4)
            {
                echo ' **** '.$toc3."<br/>\n";
                foreach ($data4 as $item)
                {
                    echo " ----- <a href='".$item['url']."'>".$item['module'].': '.$item['title']."</a><br/>\n";
                }      
            }     
        }     
    }    
}
*/

function getModuleMetaData($moduleId)
{
   $moduleDir = 'modules'; 
   $metaDataPath = 'metadata/wm_lom.xml';
   $result = ['complete' => false, 'module' => $moduleId]; 
   $counter =0;
   $filePath = $moduleDir.'/'.$moduleId.'/'.$metaDataPath;
   if(file_exists($filePath)) 
   {
      $metadata = get_metadata($filePath); 
      foreach($metadata as $item)
      {
          /// TOCS
          if ('Bereich' == $item['label'])
          {  $counter++; $result['TOC0'] = $item['data']; }
          if ('Themengebiet' == $item['label'])
          {  $counter++; $result['TOC1'] = $item['data']; }
          if ('Teilgebiet' == $item['label'])
          {  $counter++; $result['TOC2'] = $item['data']; }
          if ('Thema' == $item['label'])
          {  $counter++; $result['TOC3'] = $item['data']; }
          /// ITEM
          if ('Sprache' == $item['label'])
          {  $result['Sprache'] = $item['data']; }  
          if ('URL' == $item['label'])
          {  $counter++; $result['url'] = $item['data']; }
          if ('Titel' == $item['label'])
          {  $counter++; $result['title'] = $item['data']; }      
      }
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
         $result[] = $dir; 
      }
   }
   return $result;
}

function getModulesMetaData($modules) 
{
    $result = [];
    if(is_null($modules = null)) 
    { $modules = getAllModules(); }
    foreach($modules as $module)
    {
        $metadata = getModuleMetaData($module);
        if(!array_key_exists($metadata['TOC0'], $result)) 
        { $result[$metadata['TOC0']] = []; }
        if(!array_key_exists($metadata['TOC1'], $result[$metadata['TOC0']])) 
        { $result[$metadata['TOC0']][$metadata['TOC1']] = []; }
        if(!array_key_exists($metadata['TOC2'], $result[$metadata['TOC0']][$metadata['TOC1']])) 
        { $result[$metadata['TOC0']][$metadata['TOC1']][$metadata['TOC2']] = []; }    
        if(!array_key_exists($metadata['TOC3'], $result[$metadata['TOC0']][$metadata['TOC1']][$metadata['TOC2']])) 
        { $result[$metadata['TOC0']][$metadata['TOC1']][$metadata['TOC2']][$metadata['TOC3']] = []; }   
        $result[$metadata['TOC0']][$metadata['TOC1']][$metadata['TOC2']][$metadata['TOC3']][] = $metadata;    
    }
    return $result;     
}



?>