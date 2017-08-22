<?php
/*
		jGallery1.2
		------------------------------------------------------------------
		Support forums: http://portal.kooijman-design.nl/viewforum.php?f=1
*/



error_reporting(E_ALL);



// ----------------------------------------------------------------------------------- 
// Some frontcontrolers:
// -----------------------------------------------------------------------------------
$G_JGALL = array('version' => '1.2');
$G_JGALL['GetPage'] = (IsSet($_GET['JGALL_PAGE'])) ? preg_replace('/[^0-9]/i','',$_GET['JGALL_PAGE']) : '';
$G_JGALL['GetDir'] = (IsSet($_GET['JGALL_DIR'])) ? str_replace('../','',strip_tags(str_replace('\\','/',$_GET['JGALL_DIR']))) : '';
$G_JGALL['GetImg'] = (IsSet($_GET['JGALL_IMG'])) ? str_replace('../','',strip_tags(str_replace('\\','/',$_GET['JGALL_IMG']))) : '';



// ----------------------------------------------------------------------------------- 
// Get include path string (see if script is included)
// -----------------------------------------------------------------------------------
$G_JGALL['FILEPATH'] = explode('/',str_replace('\\','/',__FILE__));
$G_JGALL['ROOTPATH'] = explode('/',str_replace('\\','/',$_SERVER['DOCUMENT_ROOT'].strip_tags(str_replace('\\','',$_SERVER['PHP_SELF']))));

$G_JGALL['IncludePath'] = '';
if ($G_JGALL['FILEPATH'] != $G_JGALL['ROOTPATH']) 
{
   for($G_JGALL['i_inc']='0'; $G_JGALL['i_inc'] < count($G_JGALL['ROOTPATH']); $G_JGALL['i_inc']++) 
   {
      if ($G_JGALL['FILEPATH'][$G_JGALL['i_inc']] == $G_JGALL['ROOTPATH'][$G_JGALL['i_inc']]) 
      {
         unset($G_JGALL['FILEPATH'][$G_JGALL['i_inc']]);
      }
   }
   unset($G_JGALL['FILEPATH'][$G_JGALL['i_inc']]);
   $G_JGALL['IncludePath'] = implode('/',$G_JGALL['FILEPATH']).'/';
}



// ----------------------------------------------------------------------------------- 
// Include files to handle
// -----------------------------------------------------------------------------------
include($G_JGALL['IncludePath'].'config.inc.php');
include($G_JGALL['IncludePath'].'functions.inc.php');
include($G_JGALL['IncludePath'].'language.inc.php');



// -----------------------------------------------------------------------------------
// General presets:
// -----------------------------------------------------------------------------------
$G_JGALL['UserGets'] = JGALL_UserGets();
$G_JGALL['tablewidth'] = ($C_JGALL['gall_thumb_size']*$C_JGALL['gall_cols']) + ($C_JGALL['gall_spacing']*($C_JGALL['gall_cols']+1));
$C_JGALL['gall_img_border_width'] = explode('px',$C_JGALL['gall_img_border']);
$C_JGALL['gall_img_border_width'] = $C_JGALL['gall_img_border_width'][0];


// -----------------------------------------------------------------------------------
// Folder strings and arrays
// -----------------------------------------------------------------------------------
$G_JGALL['GalleryImageDir'] = $C_JGALL['gall_dir'].$G_JGALL['GetDir'];
$G_JGALL['GetDirArray'] = explode ('/', $G_JGALL['GetDir']);
$G_JGALL['FolderCount'] = count($G_JGALL['GetDirArray']);
$G_JGALL['UpperFolder'] = '';
for($G_JGALL['i_up']='1'; $G_JGALL['i_up'] < $G_JGALL['FolderCount']-1; $G_JGALL['i_up']++) 
{
   $G_JGALL['UpperFolder'] .= $G_JGALL['GetDirArray'][$G_JGALL['i_up']-1].'/'; 
}



// -----------------------------------------------------------------------------------
// Read card image directory and put dir's and images into array
// -----------------------------------------------------------------------------------
$G_JGALL['DirArray'] = array ();
$G_JGALL['FileArray'] = array ();
$G_JGALL['FileSizeArray'] = array ();
if ($G_JGALL['dir'] = opendir($G_JGALL['IncludePath'] . $G_JGALL['GalleryImageDir']))
{
   while(false !== ($G_JGALL['file'] = readdir($G_JGALL['dir']))) 
   {
      if($G_JGALL['file'] != '.' && $G_JGALL['file'] != '..' && eregi($C_JGALL['extentions'],JGALL_ext($G_JGALL['file']))) 
      {
         $getimagesize = getimagesize($G_JGALL['IncludePath'] . $G_JGALL['GalleryImageDir'] . $G_JGALL['file']);
         if(substr($getimagesize['mime'],0,5) == 'image')
         {
            $G_JGALL['FileArray'][] = $G_JGALL['file'];
            $G_JGALL['FileSizeArray'][] = $getimagesize;
         }
      }
      elseif(!strstr($G_JGALL['file'],'.')) 
      {
         $G_JGALL['DirArray'][] = $G_JGALL['file'];
      }
   }
   closedir($G_JGALL['dir']);
}
sort($G_JGALL['DirArray']);
sort($G_JGALL['FileArray']);
$G_JGALL['GalleryItems'] = array_merge($G_JGALL['DirArray'],$G_JGALL['FileArray']);
$G_JGALL['FileSizeArray'] = array_merge($G_JGALL['DirArray'],$G_JGALL['FileSizeArray']);



// -----------------------------------------------------------------------------------
// Calculate number of pages ($G_JGALL['Pages'])
// -----------------------------------------------------------------------------------
$G_JGALL['CountFiles'] = count($G_JGALL['GalleryItems']);
$G_JGALL['Pages'] = ceil($G_JGALL['CountFiles'] / ($C_JGALL['gall_cols'] * $C_JGALL['gall_rows']));

if(!empty($G_JGALL['GetPage'])) 
{
   $G_JGALL['CountPage'] = $G_JGALL['GetPage']-1;
   $G_JGALL['i_pages'] = $G_JGALL['CountPage']*($C_JGALL['gall_cols']*$C_JGALL['gall_rows']);
   $G_JGALL['Max'] = $G_JGALL['i_pages']+($C_JGALL['gall_cols']*$C_JGALL['gall_rows']);

   if($G_JGALL['Max'] > $G_JGALL['CountFiles']) 
   {
      $G_JGALL['Max'] = $G_JGALL['CountFiles'];
   }
}
else {
   $G_JGALL['i_pages'] = '0';
   $G_JGALL['Max'] = $C_JGALL['gall_cols']*$C_JGALL['gall_rows'];

   if ($G_JGALL['Max'] > $G_JGALL['CountFiles']) {
      $G_JGALL['Max'] = $G_JGALL['CountFiles'];
   }
}



// -----------------------------------------------------------------------------------
// Setup style for borders, don't ask...
// -----------------------------------------------------------------------------------
foreach($C_JGALL['layout_order'] as $key => $part)
{
   $G_JGALL['style'][$part] = '';
   $G_JGALL['style'][$part] .= 'background-color:' . $C_JGALL['style'][$part]['bgcolor'] . ';';
   $G_JGALL['style'][$part] .= 'background-image:url(\'' . $C_JGALL['style'][$part]['bgimage'] . '\');';
   $G_JGALL['style'][$part] .= 'border-left:' . $C_JGALL['style'][$part]['border'] . ';';
   $G_JGALL['style'][$part] .= 'border-right:' . $C_JGALL['style'][$part]['border'] . ';';
   if($key == '0')
   {
      $G_JGALL['style'][$part] .= 'border-top:' . $C_JGALL['style'][$part]['border'] . ';';
      if(($C_JGALL['layout_spacing']['first'] > '0') OR ($part == 'main'))
      {
         $G_JGALL['style'][$part] .= 'border-bottom:' . $C_JGALL['style'][$part]['border'] . ';';
      }
      elseif(($part == 'location') && $C_JGALL['layout_order'][1] == 'pagelink')
      {
         $G_JGALL['style'][$part] .= 'border-bottom:' . $C_JGALL['style'][$part]['border'] . ';';
      }
   }
   if($key == '1')
   {
      if(($C_JGALL['layout_spacing']['first'] > '0') OR ($part == 'main'))
      {
         $G_JGALL['style'][$part] .= 'border-top:' . $C_JGALL['style'][$part]['border'] . ';';
      }
      elseif(($part == 'location') && $C_JGALL['layout_order'][0] == 'pagelink')
      {
         $G_JGALL['style'][$part] .= 'border-top:' . $C_JGALL['style'][$part]['border'] . ';';
      }
      if(($C_JGALL['layout_spacing']['second'] > '0') OR ($part == 'main'))
      {
         $G_JGALL['style'][$part] .= 'border-bottom:' . $C_JGALL['style'][$part]['border'] . ';';
      }
      elseif(($part == 'location') && $C_JGALL['layout_order'][2] == 'pagelink')
      {
         $G_JGALL['style'][$part] .= 'border-bottom:' . $C_JGALL['style'][$part]['border'] . ';';
      }
   }
   if($key == '2')
   {
      $G_JGALL['style'][$part] .= 'border-bottom:' . $C_JGALL['style'][$part]['border'] . ';';
      
      if(($C_JGALL['layout_spacing']['second'] > '0') OR ($part == 'main'))
      {
         $G_JGALL['style'][$part] .= 'border-top:' . $C_JGALL['style'][$part]['border'] . ';';
      }
      elseif(($part == 'location') && $C_JGALL['layout_order'][1] == 'pagelink')
      {
         $G_JGALL['style'][$part] .= 'border-top:' . $C_JGALL['style'][$part]['border'] . ';';
      }
   }
}



// -----------------------------------------------------------------------------------
// CREATE MENU BAR -> Output to $C_JGALL['output_location'] 
// -----------------------------------------------------------------------------------
$C_JGALL['output_location'] = '';
$G_JGALL['MainFolder'] = '<b>' . $C_JGALL['lang_mainfolder'] . '</b>';
$G_JGALL['HomeButton'] = '<img src="' . $G_JGALL['IncludePath'] . 'images/homefolder_gray.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_home'] . '">';
$G_JGALL['UpButton'] = '<img src="' . $G_JGALL['IncludePath'] . 'images/upfolder_gray.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_up'] . '">';
$G_JGALL['CurrentFolderKey'] = 0;
if((!empty($G_JGALL['GetDir'])) OR ($G_JGALL['GetPage'] > 1) OR !empty($G_JGALL['GetImg'])) 
{
   $G_JGALL['HomeButton'] = '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR"><img src="' . $G_JGALL['IncludePath'] . 'images/homefolder.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_gohome'] . '"></a>';
   if(!empty($G_JGALL['GetDir'])) 
   {
      $G_JGALL['MainFolder'] = '<a href="'.$G_JGALL['UserGets'].'JGALL_DIR">' . $C_JGALL['lang_mainfolder'] . '</a>&nbsp;/&nbsp;';
      $G_JGALL['UpButton'] = '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['UpperFolder'] . '"><img src="' . $G_JGALL['IncludePath'] . 'images/upfolder.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_goup'] . '"></a>';
      $G_JGALL['CurrentFolderKey'] = $G_JGALL['FolderCount']-2;
   }
   elseif (!empty($G_JGALL['GetImg']) && file_exists($G_JGALL['IncludePath'] . $G_JGALL['GalleryImageDir'] . $G_JGALL['GetImg']))
   {
      $G_JGALL['MainFolder'] = '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR">' . $C_JGALL['lang_mainfolder'] . '</a>';
   }
}
$C_JGALL['output_location'] .= JGALL_TBS(1) . '<tr>';
$C_JGALL['output_location'] .= JGALL_TBS(2) . '<td style="' . $G_JGALL['style']['location'] . '">';
$C_JGALL['output_location'] .= JGALL_TBS(3) . '<table width="100%" nowrap="nowrap">';
$C_JGALL['output_location'] .= JGALL_TBS(4) . '<tr>';
$C_JGALL['output_location'] .= JGALL_TBS(5) . '<td align="left">';
$C_JGALL['output_location'] .= JGALL_TBS(6) . '&nbsp;<b>' . $C_JGALL['lang_location'] . ':</b>&nbsp;&nbsp;' . $G_JGALL['MainFolder'];

$G_JGALL['TempCrumbFolder'] = '';
for($G_JGALL['i_crumb']='1'; $G_JGALL['i_crumb'] < $G_JGALL['FolderCount']-1; $G_JGALL['i_crumb']++) 
{
   $G_JGALL['TempCrumbFolder'] .= $G_JGALL['GetDirArray'][$G_JGALL['i_crumb']-1].'/';
   $C_JGALL['output_location'] .= '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['TempCrumbFolder'] . '">' . $G_JGALL['GetDirArray'][$G_JGALL['i_crumb']-1] . '</a>&nbsp;/&nbsp;';
}
if(!empty($G_JGALL['GetImg']) && file_exists($G_JGALL['IncludePath'] . $G_JGALL['GalleryImageDir'] . $G_JGALL['GetImg']))
{
   $C_JGALL['output_location'] .= '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '">' . $G_JGALL['GetDirArray'][$G_JGALL['i_crumb']-1] . '</a>';
   $C_JGALL['output_location'] .= '&nbsp;/&nbsp;<b>' . $G_JGALL['GetImg'] . '</b>';
}
else
{
   $C_JGALL['output_location'] .= '<b>' . $G_JGALL['GetDirArray'][$G_JGALL['CurrentFolderKey']] . '</b>';
}
$C_JGALL['output_location'] .= JGALL_TBS(5) . '</td>' . JGALL_TBS(5) . '<td align="right">';
$C_JGALL['output_location'] .= JGALL_TBS(6) . '&nbsp;' . $G_JGALL['HomeButton'] . '&nbsp;&nbsp;' . $G_JGALL['UpButton'] . '&nbsp;&nbsp;<a href="javascript:location.reload();" target="_self"><img src="' . $G_JGALL['IncludePath'] . 'images/refresh.gif" border="0" align="absmiddle" alt="' . $C_JGALL['lang_refresh'] . '"></a>&nbsp;';
/*		Nice to see you like this script! You're welcome!
		In return, please do not remove the info-button, 
		or place a link to us on your website. Thank you!
		http://portal.kooijman-design.nl/jGallery/				*/
$C_JGALL['output_location'] .= '&nbsp;<a href="http://portal.kooijman-design.nl/jGallery/README.html" target="_blank"><img src="' . $G_JGALL['IncludePath'] . 'images/info.gif" border="0" align="absmiddle" alt="powered by: jGallery"></a>&nbsp;';
$C_JGALL['output_location'] .= JGALL_TBS(5) . '</td>' . JGALL_TBS(4) . '</tr>' . JGALL_TBS(3) . '</table>' . JGALL_TBS(2) . '</td>' . JGALL_TBS(1) . '</tr>';



// -----------------------------------------------------------------------------------
// CREATE PAGE LINKS -> Output to $C_JGALL['output_pagelinks']
// -----------------------------------------------------------------------------------
$C_JGALL['output_pagelinks'] = '';
$C_JGALL['output_pagelinks'] .=  JGALL_TBS(1) . '<tr>' . JGALL_TBS(2) . '<td style="' . $G_JGALL['style']['pagelink'] . '" align="center">';
if(!empty($G_JGALL['GetImg']) && file_exists($G_JGALL['IncludePath'].$G_JGALL['GalleryImageDir'].$G_JGALL['GetImg']))
{
// If you are Viewing one image
   $G_JGALL['TotalImages'] = count($G_JGALL['FileArray']);
   foreach($G_JGALL['FileArray'] as $key => $value)
   {
      if($value == $G_JGALL['GetImg'])
      {
         $G_JGALL['CurrentImg'] = $key+1;
         $G_JGALL['FileSizeCurrentImg'] = $G_JGALL['FileSizeArray'][$key];
      }
   }
   if($G_JGALL['CurrentImg'] > 1)
   {
      // « first | prev
      $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&nbsp;<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '&JGALL_IMG=' . $G_JGALL['FileArray'][0] . '">&laquo;&nbsp;' . $C_JGALL['lang_first'] . '</a>&nbsp;&#124;&nbsp;<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '&JGALL_IMG=' . $G_JGALL['FileArray'][$G_JGALL['CurrentImg']-2] . '">' . $C_JGALL['lang_prev'] . '</a>&nbsp;&#124;';
   }
   $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&nbsp;' . str_replace('{CURRENT}',$G_JGALL['CurrentImg'],str_replace('{TOTAL}',$G_JGALL['TotalImages'],$C_JGALL['lang_imageinfo'])) . '&nbsp;';
   if($G_JGALL['CurrentImg'] < $G_JGALL['TotalImages'])
   {
      // next | last »
      $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&#124;&nbsp;<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '&JGALL_IMG=' . $G_JGALL['FileArray'][$G_JGALL['CurrentImg']] . '">' . $C_JGALL['lang_next'] . '</a>&nbsp;&#124;&nbsp;<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'].'&JGALL_IMG=' . $G_JGALL['FileArray'][$G_JGALL['TotalImages']-1] . '">' . $C_JGALL['lang_last'] . '&nbsp;&raquo;</a>&nbsp;';
   }
}
else
{
   // If you are viewing thumbnails
   if ($G_JGALL['Pages'] > 1) 
   {
      $G_JGALL['current'] = '1';

      if(!empty($G_JGALL['GetPage'])) 
      {
         $G_JGALL['current'] = $G_JGALL['GetPage'];
      }
      $prev = $G_JGALL['current']-1;
      $next = $G_JGALL['current']+1;
   
      if($G_JGALL['current'] > '1') 
      {
         // « first | prev
         $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&nbsp;<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '&JGALL_PAGE=1">&laquo;&nbsp;' . $C_JGALL['lang_first'] . '</a>&nbsp;&#124;&nbsp;<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '&JGALL_PAGE=' . $prev . '">' . $C_JGALL['lang_prev'] . '</a>&nbsp;&#124;' ;
      }
      for ($i=1; $i<=$G_JGALL['Pages']; $i++) 
      {
         if($i == $G_JGALL['current']) 
         {
            $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&nbsp;<b>' . $i . '</b>&nbsp;';
         }
         else 
         {
            $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&nbsp;<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '&JGALL_PAGE='. $i .'"><b>' . $i . '</b></a>&nbsp;';
         }
      }
      if($G_JGALL['current'] < $G_JGALL['Pages']) 
      {
         // next | last »
         $C_JGALL['output_pagelinks'] .=  JGALL_TBS(3) . '&#124;&nbsp;<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '&JGALL_PAGE=' . $next . '">' . $C_JGALL['lang_next'] . '</a>&nbsp;&#124;&nbsp;<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '&JGALL_PAGE=' . $G_JGALL['Pages'] . '">' . $C_JGALL['lang_last'] . '&nbsp;&raquo;</a>&nbsp;';
      } 
   }
}
$C_JGALL['output_pagelinks'] .=  JGALL_TBS(2) . '</td>' . JGALL_TBS(1) . '</tr>';



// -----------------------------------------------------------------------------------
// Build layout order:
// -----------------------------------------------------------------------------------
foreach($C_JGALL['layout_order'] as $key => $value)
{
    $C_JGALL['layout_order'][$key] = '{' . strtoupper($value) . '}';
}
$G_JGALL['output_order'] = $C_JGALL['layout_order'][0] . '{FIRST_SPACING}' . $C_JGALL['layout_order'][1] . '{SECOND_SPACING}' . $C_JGALL['layout_order'][2];

foreach($C_JGALL['layout_spacing'] as $key => $spacing)
{
   $G_JGALL['output_spacing'][$key] = '';
   if($C_JGALL['layout_spacing'][$key] > '0')
   {
      $G_JGALL['output_spacing'][$key] = JGALL_TBS(1) . '<tr name="spacing"><td height="' . $spacing . '"></td></tr>';
   }
}
$G_JGALL['output_order'] = explode('{MAIN}',$G_JGALL['output_order']);



// -----------------------------------------------------------------------------------
// Place output into position and parse template:
// -----------------------------------------------------------------------------------
$G_JGALL['nav_output_top'] = str_replace('{LOCATION}',$C_JGALL['output_location'],$G_JGALL['output_order'][0]);
$G_JGALL['nav_output_top'] = str_replace('{PAGELINK}',$C_JGALL['output_pagelinks'],$G_JGALL['nav_output_top']);
$G_JGALL['nav_output_top'] = str_replace('{FIRST_SPACING}',$G_JGALL['output_spacing']['first'],$G_JGALL['nav_output_top']);
$G_JGALL['nav_output_top'] = str_replace('{SECOND_SPACING}',$G_JGALL['output_spacing']['second'],$G_JGALL['nav_output_top']);

$G_JGALL['nav_output_bottom'] = str_replace('{LOCATION}',$C_JGALL['output_location'],$G_JGALL['output_order'][1]);
$G_JGALL['nav_output_bottom'] = str_replace('{PAGELINK}',$C_JGALL['output_pagelinks'],$G_JGALL['nav_output_bottom']);
$G_JGALL['nav_output_bottom'] = str_replace('{FIRST_SPACING}',$G_JGALL['output_spacing']['first'],$G_JGALL['nav_output_bottom']);
$G_JGALL['nav_output_bottom'] = str_replace('{SECOND_SPACING}',$G_JGALL['output_spacing']['second'],$G_JGALL['nav_output_bottom']);



// -----------------------------------------------------------------------------------
// Start output:
// -----------------------------------------------------------------------------------
if($G_JGALL['FILEPATH'] == $G_JGALL['ROOTPATH']) 
{
   echo '<html><head><title>' . $C_JGALL['lang_title'] . '</title><link rel="stylesheet" type="text/css" href="' . $C_JGALL['stylesheet'] . '" /></head><body>' . "\n\n";
}
echo '<div align="center">' . "\n";
echo '<table name="jGalery' . $G_JGALL['version'] . '" cellspacing="0" border="0" cellpadding="0" width="' . $G_JGALL['tablewidth'] . '">';



// -----------------------------------------------------------------------------------
// Output items on top of image-viewer
// -----------------------------------------------------------------------------------
echo $G_JGALL['nav_output_top'];



// -----------------------------------------------------------------------------------
// Output thumbnails or view image 
// -----------------------------------------------------------------------------------
echo JGALL_TBS(1) . '<tr>' . JGALL_TBS(2) . '<td style="' . $G_JGALL['style']['main'] . '">';
echo JGALL_TBS(3) . '<table cellspacing="' . $C_JGALL['gall_spacing'] . '" border="0" cellpadding="0" width="' . $G_JGALL['tablewidth'] . '">';
// Calculate size for viewing image (relative to table with)
$G_JGALL['view_image_size'] = (!empty($C_JGALL['gall_max_view_img_size'])) ? $C_JGALL['gall_max_view_img_size'] : $G_JGALL['tablewidth'] - ($C_JGALL['gall_spacing'] * 2) - ($C_JGALL['gall_img_border_width'] * 2);
if(!empty($G_JGALL['GetImg']) && file_exists($G_JGALL['IncludePath'].$G_JGALL['GalleryImageDir'].$G_JGALL['GetImg']))
{
   $G_JGALL['image_link_start'] = (eregi($C_JGALL['gall_link2source'],'y')) ? '<a href="' . $G_JGALL['IncludePath'] . $G_JGALL['GalleryImageDir'] . $G_JGALL['GetImg'] . '" target="_blank">' : ' ' ;
   $G_JGALL['image_link_end'] = (eregi($C_JGALL['gall_link2source'],'y')) ? '</a>' : '';
   echo JGALL_TBS(4) . '<tr>' . JGALL_TBS(5) . '<td width="100%" align="center">';
   echo JGALL_TBS(6) . $G_JGALL['image_link_start'];
   echo '<img border="0" style="border:' . $C_JGALL['gall_img_border'] . ';" src="' . $G_JGALL['IncludePath'] . 'thumb.php?MaxSize=' . $G_JGALL['view_image_size'] . '&src=' . $G_JGALL['GalleryImageDir'] . $G_JGALL['GetImg'] . '" alt="' . $C_JGALL['lang_downloadimage'] . ': ' . $G_JGALL['GetImg'] . '">';
   echo $G_JGALL['image_link_end'];
   if($C_JGALL['gall_show_img_size'] == 'y')
   {
      echo '<br />' . JGALL_TBS(6) . $C_JGALL['lang_imagesize'] . ': ' . $G_JGALL['FileSizeCurrentImg'][0] . ' x ' . $G_JGALL['FileSizeCurrentImg'][1];
   }
   echo JGALL_TBS(5) .'</td>' . JGALL_TBS(4) . '</tr>';
   
}
else
{
   if (!empty($G_JGALL['GalleryItems']))
   {
      $C_JGALL['x'] = '0'; 
      $C_JGALL['y'] = '0';
      $C_JGALL['fit_thumb_size'] = $C_JGALL['gall_thumb_size'] - ($C_JGALL['gall_img_border_width'] * 2);
      for ($G_JGALL['i_pages']=$G_JGALL['i_pages']; $G_JGALL['i_pages']<=$G_JGALL['Max']-1; $G_JGALL['i_pages']++) 
      {
         if(eregi($C_JGALL['extentions'],JGALL_ext($G_JGALL['GalleryItems'][$G_JGALL['i_pages']])) OR !strstr($G_JGALL['GalleryItems'][$G_JGALL['i_pages']],'.')) 
         {
            $C_JGALL['x']++;
   
            if($C_JGALL['x'] == 1) {
               echo JGALL_TBS(4) . '<tr>'; 
               $C_JGALL['y']++;
            }
            // For images
            if (eregi($C_JGALL['extentions'],JGALL_ext($G_JGALL['GalleryItems'][$G_JGALL['i_pages']]))) 
            {
               echo JGALL_TBS(5) . '<td align="center" valign="middle" align="center" width="' . $C_JGALL['gall_thumb_size'] . '">';
               echo JGALL_TBS(6) . '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . '&JGALL_IMG=' . $G_JGALL['GalleryItems'][$G_JGALL['i_pages']].'"><img ' . JGALL_resize($G_JGALL['FileSizeArray'][$G_JGALL['i_pages']],$C_JGALL['fit_thumb_size']) . ' border="0" style="border:' . $C_JGALL['gall_img_border'] . ';" src="' . $G_JGALL['IncludePath'] . 'thumb.php?MaxSize=' . $C_JGALL['fit_thumb_size'] . '&src=' . $G_JGALL['GalleryImageDir'] . $G_JGALL['GalleryItems'][$G_JGALL['i_pages']] . '" alt="' . $C_JGALL['lang_clickview'] . ': ' . $G_JGALL['GalleryItems'][$G_JGALL['i_pages']] . '"></a>';
               echo JGALL_TBS(5) . '</td>';
            }
            // For folders
            else 
            {
               $G_JGALL['FolderTopSpacerHeight'] = round($C_JGALL['gall_thumb_size'] / 100 * 12);
               $G_JGALL['FolderBottomSpacerHeight'] = round($C_JGALL['gall_thumb_size'] / 100 * 8);
               $G_JGALL['FolderMainSpacerHeight'] = $C_JGALL['gall_thumb_size'] - ($G_JGALL['FolderTopSpacerHeight'] + $G_JGALL['FolderBottomSpacerHeight']);
               
               echo JGALL_TBS(5) . '<td width="' . $C_JGALL['gall_thumb_size'] . '" height="' . $C_JGALL['gall_thumb_size'] . '" style="background:url(\'' . $G_JGALL['IncludePath'] . 'thumb.php?MaxSize=' . $C_JGALL['gall_thumb_size'] . '&src=folder\');background-repeat:no-repeat;cursor:hand;" onclick="top.location.href=\'' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . $G_JGALL['GalleryItems'][$G_JGALL['i_pages']] . '/' . '\'">';
               echo JGALL_TBS(6) . '<table width="' . $C_JGALL['gall_thumb_size'] . '" height="' . $C_JGALL['gall_thumb_size'] . '" cellspacing="0" cellpadding="0">' . JGALL_TBS(7) . '<tr>';
               echo JGALL_TBS(8) . '<td width="' . $C_JGALL['gall_thumb_size'] . '" height="' . $G_JGALL['FolderTopSpacerHeight'] . '"><img src="' . $G_JGALL['IncludePath'] . 'images/spacer.gif" width="' . $C_JGALL['gall_thumb_size'] . '" height="' . $G_JGALL['FolderTopSpacerHeight'] . '"></td>';
               echo JGALL_TBS(7) . '</tr>' . JGALL_TBS(7) . '<tr>';
               echo JGALL_TBS(8) . '<td width="' . $C_JGALL['gall_thumb_size'] . '" height="' . $G_JGALL['FolderMainSpacerHeight'] . '" valign="middle" align="center">';
               echo JGALL_TBS(9) . JGALL_first($G_JGALL['GetDir'] . $G_JGALL['GalleryItems'][$G_JGALL['i_pages']] . '/') . JGALL_TBS(9) . '<br />';
               echo JGALL_TBS(9) . '<a href="' . $G_JGALL['UserGets'] . 'JGALL_DIR=' . $G_JGALL['GetDir'] . $G_JGALL['GalleryItems'][$G_JGALL['i_pages']] . '/' . '"><b>' . $G_JGALL['GalleryItems'][$G_JGALL['i_pages']] . '</b></a>';
               echo JGALL_TBS(8) . '</td>' . JGALL_TBS(7) . '</tr>' . JGALL_TBS(7) . '<tr>';
               echo JGALL_TBS(8) . '<td width="' . $C_JGALL['gall_thumb_size'] . '" height="' . $G_JGALL['FolderBottomSpacerHeight'] . '"><img src="' . $G_JGALL['IncludePath'] . 'images/spacer.gif" width="' . $C_JGALL['gall_thumb_size'] . '" height="' . $G_JGALL['FolderBottomSpacerHeight'] . '"></td>';
               echo JGALL_TBS(7) . '</tr>' . JGALL_TBS(6) . '</table>' . JGALL_TBS(5) . '</td>';
            }
            if ($C_JGALL['x'] == $C_JGALL['gall_cols']) 
            {
               echo JGALL_TBS(4) . '</tr>'; 
               $C_JGALL['x'] = '0';
            }
         }
      }
      if($C_JGALL['x'] != '0') 
      {
         echo JGALL_TBS(4) . '</tr>'; 
      }
   }
   else 
   {
      echo JGALL_TBS(4) . '<tr>' . JGALL_TBS(5) . '<td colspan="' . $C_JGALL['gall_cols'] . '" align="center">';
      echo JGALL_TBS(6) . '<br>' . JGALL_TBS(6) . '<h3>' . $C_JGALL['lang_emptydir'] . '</h3>';
      echo JGALL_TBS(5) . '</td>' . JGALL_TBS(4) . '</tr>';
   }
}
echo JGALL_TBS(3) . '</table>' . JGALL_TBS(2) . '</td>' . JGALL_TBS(1) . '</tr>';



// -----------------------------------------------------------------------------------
// Output items at the bottom of image-viewer
// -----------------------------------------------------------------------------------
echo $G_JGALL['nav_output_bottom'];



// -----------------------------------------------------------------------------------
// End output
// -----------------------------------------------------------------------------------
echo "\n" . '</table>' . "\n";
echo '</div>' . "\n\n";
if($G_JGALL['FILEPATH'] ==  $G_JGALL['ROOTPATH']) 
{
   echo'</body>' . "\n" . '</html>';
}



// --> End
?>