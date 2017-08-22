<?php
/*
		jGallery1.2
		------------------------------------------------------------------
		Support forums: http://portal.kooijman-design.nl/viewforum.php?f=1
*/



// ----------------------------------------------------------------------------------- 
// Function to create tabs in front of lines
// ----------------------------------------------------------------------------------- 
function JGALL_TBS($num=0) 
{
   $tab = "   ";
   $temp = '';
   for($i=0; $i<$num; $i++)
   {
      $temp .= $tab;
   }
   return "\n" . $temp;
}



// ----------------------------------------------------------------------------------- 
// Function to calculate resized image size
// ----------------------------------------------------------------------------------- 
function JGALL_resize($srcSize,$MaxSize)
{

   $srcRatio = $srcSize[0]/$srcSize[1];
   $destRatio = $MaxSize/$MaxSize;

   if ($destRatio > $srcRatio) 
   {
      $destSize[1] = $MaxSize;
      $destSize[0] = $MaxSize*$srcRatio;
   }
   else 
   {
      $destSize[0] = $MaxSize;
      $destSize[1] = $MaxSize/$srcRatio;
   }
   return 'width="' . floor($destSize[0]) . '" height="' . floor($destSize[1]) . '"';
} 



// ----------------------------------------------------------------------------------- 
// Function to get users get-vars
// ----------------------------------------------------------------------------------- 
function JGALL_UserGets() 
{
   $UserGets = '?';
   foreach ($_GET as $key => $value) 
   { 
      if (!eregi('JGALL_',$key)) 
      { 
         $UserGets .= $key.'=' . strip_tags($value); 
         $UserGets .= ($UserGets) ? '&' : '?'; 
      } 
   } 
   return str_replace('../','',strip_tags(str_replace('\\','',$UserGets))); 
} 



// ----------------------------------------------------------------------------------- 
// Function to get extention 
// ----------------------------------------------------------------------------------- 
function JGALL_ext($filename)
{
    $FileNameArray = explode('.',$filename);
    return($FileNameArray[count($FileNameArray)-1]);
}



// ----------------------------------------------------------------------------------- 
// Function to return first image in dir 
// ----------------------------------------------------------------------------------- 



function JGALL_first($dir)
{
   global $G_JGALL;
   global $C_JGALL;

   $size = ceil($C_JGALL['gall_thumb_size'] / 100 * 80 - 20);
   if ($dirhandle = opendir($G_JGALL['IncludePath'].$C_JGALL['gall_dir'].$dir)) 
   {
      while(false !== ($file = readdir($dirhandle))) 
      {
         if($file != '.' && $file != '..' && eregi($C_JGALL['extentions'],JGALL_ext($file))) 
         {
            $getimagesize = getimagesize($G_JGALL['IncludePath'] . $C_JGALL['gall_dir'] . $dir . $file);
            if(substr($getimagesize['mime'],0,5) == 'image')
            {
               $FileArray[] = $file;
               $FileSizeArray[] = $getimagesize;
            }
         }
      }
      closedir($dirhandle);
   }
   if (empty($FileArray[0])) 
   {
      $src = 'question';
      $getimagesize = getimagesize($G_JGALL['IncludePath'] . 'images/' . $C_JGALL['questionicon']);
      $html_size = JGALL_resize($getimagesize,$size);
   }
   else {
      $src = $C_JGALL['gall_dir'].$dir.$FileArray[0];
      $html_size = JGALL_resize($FileSizeArray[0],$size);
   }
   return '<img ' . $html_size . ' border="0" style="border:' . $C_JGALL['gall_img_border'] . ';" src="' . $G_JGALL['IncludePath'] . 'thumb.php?MaxSize=' . $size . '&src=' . $src . '" alt="' . $C_JGALL['lang_opendir'] . ': ' . $dir . '">';
}



// --> END
?>