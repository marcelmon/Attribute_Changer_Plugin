<?php


if (!defined('PHPLISTINIT')) die(); ## avoid pages being loaded directly
if ($GLOBALS["commandline"]) {
 echo 'not to oppened by command line';
 die();
}

require_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Single_Session.php');
print("ASDASDASDSADASD");
require_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Display_Functions.php');

require_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Display_Adjustment_Functions.php');

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

    if(!isset($attribute_changer->Current_Session) || $attribute_changer->Current_Session == null) {

        print("<html><html>");
    }
    if($attribute_changer->Current_Session->file_is_good == false){
        print("WATCKCKAACA");
        print('</body></html>');
    }
    else{
        $print_html = Get_Attribute_File_Column_Match();

        $attribute_changer->Serialize_And_Store();
        print('<html><body>'.$print_html.'</body></html>');
    }
    
}


if(isset($_POST['File_Column_Match_Submit'])) {

    $attribute_changer->Retreive_And_Unserialize();

    include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Column_Match_Processor.php');

    if($attribute_changer->Current_Session->column_match_good == false) {

        print('<html><body>'.$print_html.'</body></html>');

        $attribute_changer->Serialize_And_Store();
        die();
    }

    if(Initialize_New_Entries_Display()!=null) {
        
        $display_html = '<html><body>'.Get_New_Entry_Table_Block().'</body></html>';
        $attribute_changer->Serialize_And_Store();
        

                //print_r($attribute_changer->Current_Session->New_Entry_List);
        print($display_html);
    }

    else{

        if(Initialize_Modify_Entries_Display()!=null) {

            $display_html = $display_html.Get_Modify_Entry_Table_Block().'</body></html>';

        }
        else{

            $display_html = $display_html.'There is nothing new or to modify</body></html>';
        }
        

        print($display_html);
    }

    
    $attribute_changer->Serialize_And_Store();

}

if(isset($_POST['New_Entry_Form_Submitted'])) {

    $attribute_changer->Retreive_And_Unserialize();

    //print_r($attribute_changer->Current_Session->New_Entry_List);
    include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/New_Entry_Table_Processor.php');

    $attribute_changer->Serialize_And_Store();

}

if(isset($_POST['Modify_Entry_Form_Submitted'])) {

    $attribute_changer->Retreive_And_Unserialize();
    // print_r($attribute_changer->Current_Session);

    include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Modify_Entry_Table_Processor.php');


    // print_r($attribute_changer->Current_Session);
    // print("ararara<br>");
    $attribute_changer->Serialize_And_Store();

}

?>