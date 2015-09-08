<?php
if (!defined('PHPLISTINIT')) die(); ## avoid pages being loaded directly
if ($GLOBALS["commandline"]) {
 echo 'not to oppened by command line';
 die();
}

require_once(dirname(__FILE__).'Attribute_Changer_Plugin.php');
require_once(dirname(__FILE__).'Display_Functions.php');
require_once(dirname(__FILE__).'Display_Adjustment_Functions.php');


$javascript_src = dirname(__FILE__) . '/Attribute_Changer_PLugin/Script_For_Attribute_Changer.js';

$page_print =  '
<div>Attribute Changer</div>
<div id="error_printing"></div>
<form action="upload_file.php" method="post" enctype="multipart/form-data" id="file_upload_form">
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
</form>';

$attribute_changer = $GLOBALS['plugins']['Attribute_Changer_Plugin'];

$FILE_LOCATION;

if(!isset($_POST)) {
    
    print('<html><head><script src="'.$javascript_src.'"></script></head><body>'.$page_print.'</body></html>');
}

        if(isset($_POST['Modify_Entry_Form_Submitted'])) {

            if(isset($_POST['Modify_Entry_Attribute_Column_Select'])) {
         
                $Columns_To_Accept = array();
         
                while($_POST['Modify_Entry_Attribute_Column_Select']) {
                    $Attribute_To_Parse = array_shift($_POST['Modify_Entry_Attribute_Column_Select']);
         
                    if(array_key_exists($Attribute_To_Parse, $Session->attribute_list)) {
                        array_push($Columns_To_Accept, $Attribute_To_Parse);
                    }
                }
                if(count($Columns_To_Accept) == 0) {
                    //change nothing or set to current set 

                }
                if(!isset($_POST['Hidden_Modify_Entry_List'])) {
                    //error
                    print("<html><body>THERE WAS AN ERROR WITH THE HIDDEN INPUT</body></html>");
                }
                else foreach ($_POST['Hidden_Modify_Entry_List'] as $hidden_email_key => $include_value) {
                    if(!isset($_POST['Modify_Entry_List'][$hidden_email_key]['include'])) {
                        unset($Session->Commited_Modify_Entires[$hidden_email_key]);
                    }
                    if(count($Columns_To_Accept) == 0) {
                        $Session->Commited_Modify_Entires[$hidden_email_key] = array();
                    }
                    else{
                        $attribute_values = $_POST['Modify_Entry_List'][$hidden_email_key]; 
                        if(isset($attribute_values['include']) {
                            if($attribute_values['include'] == 'include') {
         
                                unset($attribute_values['include']);
                                $Modify_entry_to_commit = array();
         
                                foreach ($attribute_values as $attribute_name => $value) {
                                    if(in_array($attribute_name, $Columns_To_Accept)) {
                                        if(is_array($value)) {
                                            if($Session->attribute_list[$attribute_name]['type'] == 'checkboxgroup') {
                                                foreach ($value as $key => $current_value) {
                                                    if(in_array($current_value, $Session->attribute_list[$attribute_name]['allowed_values'])) {
                                                        if(!is_array(($Modify_entry_to_commit[$attribute_name]))) {
                                                            $Modify_entry_to_commit[$attribute_name] = array();
                                                        }
                                                        array_push($Modify_entry_to_commit[$attribute_name], $current_value); 
                                                    }
                                                }
                                            }
                                            else{
                                                //only the checkbox group can be an array
                                            }
                                        }
                                        else{
                                            if($Session->attribute_list[$attribute_name]['type'] == 'checkboxgroup') {
                                                if(in_array($current_value, $Session->attribute_list[$attribute_name]['allowed_values'])) {
                                                    if(!is_array(($Modify_entry_to_commit[$attribute_name]))) {
                                                        $Modify_entry_to_commit[$attribute_name] = array();
                                                    }
                                                    array_push($Modify_entry_to_commit[$attribute_name], $value); 
                                                } 
                                            }
                                            else if($Session->attribute_list[$attribute_name]['type'] == "checkbox") {
                                                if(in_array($value, $Session->attribute_list[$attribute_name]['allowed_values'])) {
                                                    $Modify_entry_to_commit[$attribute_name]=$value; 
                                                }
                                            }
                                            else if($Session->attribute_list[$attribute_name]['type'] == "textarea"|"textline"|"hidden"|"date") {
                                                $Modify_entry_to_commit[$attribute_name]=$value; 
                                            }
                                            else{
         
                                            }
                                        }
                                         
                                    }
                                    else{
                                        //is not a currently acccepted column
                                    }
                                }
                                $Session->Commited_Modify_Entires[$hidden_email_key] = $Modify_entry_to_commit;
                            }
                        }
                        else{
                            //skip this email , not included
                        }
                    }
                }
            }
        }


        if(isset($_POST['Modify_Entries_Table_Submit_All']) && $_POST['Modify_Entries_Table_Submit_All'] == 'Modify_Entries_Table_Submit_All' ) {

            print(Process_All_New_And_Modify());
        }

        if(isset($_Post['Modify_Entries_Table_Next_Page']) && $_Post['Modify_Entries_Table_Next_Page'] == 'Modify_Entries_Table_Next_Page') {
            $HTML_TO_DISPLAY = Modify_Entry_Display_Next_Page();
            if($HTML_TO_DISPLAY == false) {
                $HTML_TO_DISPLAY = Get_Modify_Entry_Table_Block();
            }
               
            print('<html><body><script src="'.$javascript_src.'""></script>'.$HTML_TO_DISPLAY.'</body></html>');
        }

        if(isset($_Post['Modify_Entries_Table_Previous_Page']) && $_Post['Modify_Entries_Table_Previous_Page'] == 'Modify_Entries_Table_Previous_Page') {
            $HTML_TO_DISPLAY = Modify_Entry_Display_Previous_Page();
            if($HTML_TO_DISPLAY == false) {
                $HTML_TO_DISPLAY = Get_Modify_Entry_Table_Block();
            }
            print('<html><body><script src="'.$javascript_src.'""></script>'.$HTML_TO_DISPLAY.'</body></html>');
        }
     
        if(isset($_Post['Modify_Entry_Change_Display_Amount']) && $_Post['Modify_Entry_Change_Display_Amount'] == 'Modify_Entry_Change_Display_Amount') {

            if(isset($_POST['Modify_Entries_New_Display_Amount'])) {
                if($_POST['Modify_Entries_New_Display_Amount'] != (10|100|1000|10000|"all")){

                }
                else{
                    if(Modify_Entry_Change_Display_Amount($_POST['Modify_Entries_New_Display_Amount']) != true) {

                    }
                    else{
                        

                    }
                }
            }
            $HTML_TO_DISPLAY = Get_Modify_Entry_Table_Block();
            print('<html><body><script src="'.$javascript_src.'""></script>'.$HTML_TO_DISPLAY.'</body></html>');
            
        }


?>