<?php


if (!defined('PHPLISTINIT')) die(); ## avoid pages being loaded directly
if ($GLOBALS["commandline"]) {
 echo 'not to oppened by command line';
 die();
}
require_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Single_Session.php');
require_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Display_Functions.php');

$javascript_src = 'plugins/AttributeChangerPlugin/Script_For_Attribute_Changer.js';
$attribute_changer = $GLOBALS['plugins']['AttributeChangerPlugin'];
//CHANGE THE PAGE PRINT TO REFLECT THE PROPER PLUGIN DIR
$page_print =  '
<div>Attribute Changer</div>
<div id="error_printing"></div>
<form action="" method="post" enctype="multipart/form-data" id="file_upload_form">
    Select file to upload:
    (must be comma separated text)
    <input type="file" name="attribute_changer_file_to_upload" id="attribute_changer_file_to_upload">
    <input type="button" value="attribute_changer_upload_file_button" name="attribute_changer_upload_file_button" id="attribute_changer_upload_file_button" onClick="Test_Upload_File()">
</form>
<form action="" method="post" enctype="multipart/form-data" id="text_upload_form">
    Copy file to upload:
    (must be comma separated text)
    <input type="text" name="attribute_changer_text_to_upload" id="attribute_changer_text_to_upload">
    <input type="button" value="attribute_changer_upload_text" name="attribute_changer_upload_text" onClick="Test_Upload_Text()">
    desired_file_name:<input type="text" name="attribute_changer_text_name">
</form>'

;
if(!isset($_POST)) {
	
    print('<html><head><script src="'.$javascript_src.'"></script></head><body>'.$page_print.'</body></html>');
}

else{
    printf('<html><head><script src="'.$javascript_src.'"></script></head><body>SOMETHING HAPPENED, HERES THE FRONT :<br>'.$page_print.'</body></html>');
}

if(isset($_FILES['attribute_changer_file_to_upload']) && !empty($_FILES['attribute_changer_file_to_upload'])) {
    include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Upload_File_Processor.php');
}


if(isset($_POST['File_Column_Match_Submit'])) {
    include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Column_Match_Processor.php')

}

if(isset($_POST['New_Entry_Form_Submitted'])) {

    include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/New_Entry_Table_Processor.php');

}

if(isset($_POST['Modify_Entry_Form_Submitted'])) {

    include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Modify_Entry_Table_Processor.php');

}


?>