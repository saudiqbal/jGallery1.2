<?php
/*
		jGallery1.2
		------------------------------------------------------------------

		WARNING
		
		Changing some of these values may couse errors
		Read carefully what settings you are about to change
		
		
		Support forums: http://portal.kooijman-design.nl/viewforum.php?f=1
*/






// -----------------------------------------------------------------------------------
// General configuration section
// -----------------------------------------------------------------------------------


	// Set the dir which contains your photoalbums:
	$C_JGALL['gall_dir'] = 'albums/';


	// Set weither you want to link to original images (must be 'y' for yes):
	$C_JGALL['gall_link2source'] = 'y';
	
	
	// Set folder background image (must be in jGallery/images/):
	$C_JGALL['foldericon'] = 'folder_yellow.jpg';
	
	
	// Set question-mark image (must be in jGallery/images/):
	$C_JGALL['questionicon'] = 'question.jpg';
	
	
	// Set image extentions allowed to be shown in your abum:
	$C_JGALL['extentions'] = "jpg|jpeg|gif|png";
	
	
// --> END General configuration section
// -----------------------------------------------------------------------------------







// -----------------------------------------------------------------------------------
// Layout configuration section
// -----------------------------------------------------------------------------------
	
	
	// Set the number of thumbnails on a row (must be numeric):
	$C_JGALL['gall_cols'] = '4';
	
	
	// Set the number of rows on a page (must be numeric):
	$C_JGALL['gall_rows'] = '3';
	
	
	// Set the max-size (width or height) for thumbnails (must be numeric):
	$C_JGALL['gall_thumb_size'] = '120';
	
	
	// Set the spacing between the thumbs (must be numeric, can be 0):
	$C_JGALL['gall_spacing'] = '10';
	
	
	// Set max size for vieuwing images (must be numeric or empty)
	// By default images are sized to fill the table
	$C_JGALL['gall_max_view_img_size'] = '0';
	
	
	// Set 'y' if you want to show original image size under images (not for thums):
	$C_JGALL['gall_show_img_size'] = 'y';


	/*
	Here you can set in wich order you want the 'location'-menu,
	the 'pagelink'-menu and the 'maintable' (images) to appear.
	Just put one above the other in a different order.
	*/
	$C_JGALL['layout_order'][] = 'location';
	$C_JGALL['layout_order'][] = 'pagelink';
	$C_JGALL['layout_order'][] = 'main';


	/*
	If you want to put some spacing between the menu's or maintable
	you can set spacing (px) below. 'first' appears between the two
	on top, 'second' between the two on bottom.
	If you apply spacing you might need to change border styles to.
	*/
	$C_JGALL['layout_spacing']['first'] = '10';
	$C_JGALL['layout_spacing']['second'] = '0';
	
	
	
// --> END Layout configuration section
// -----------------------------------------------------------------------------------








// -----------------------------------------------------------------------------------
// Style configuration section
// -----------------------------------------------------------------------------------


	/*
	Here you can set up style values for jGallery. The maintable,
	or imageviewer, is concidered to be a border to.
	These values go into <td>-tags.
	*/
	
	
	// Set the border style for images (must begin with [num]px):
	$C_JGALL['gall_img_border'] = '1px #000000 solid';
	
	
    // Set up style for Location nav-bar (Albums, buttons)
    $C_JGALL['style']['location']['border'] = '1px #999999 solid'; // css
    $C_JGALL['style']['location']['bgcolor'] = '#E0E5E9'; // hex
    $C_JGALL['style']['location']['bgimage'] = 'images/cellpic.gif'; // url


    // Set up style for Pagelinks nav-bar (next, prev)
    $C_JGALL['style']['pagelink']['border'] = '1px #999999 solid'; // css
    $C_JGALL['style']['pagelink']['bgcolor'] = '#E0E5E9'; // hex
    $C_JGALL['style']['pagelink']['bgimage'] = ''; // url


    // Set up style for main table (thumbnails or imageview)
    $C_JGALL['style']['main']['border'] = '1px #999999 solid'; // css
    $C_JGALL['style']['main']['bgcolor'] = '#ffffff'; // hex
    $C_JGALL['style']['main']['bgimage'] = ''; // url


	/*
	Define location of your stylesheet, jGallery uses 'a', 'font', 'td' and 'hr'.
	Only works if jGallery is NOT included in your site's template.
	Stylesheet must be in 'jGallery/'.
	*/
	$C_JGALL['stylesheet'] = 'style.css';
	
	
// --> END Style configuration section
// -----------------------------------------------------------------------------------



// --> END
?>