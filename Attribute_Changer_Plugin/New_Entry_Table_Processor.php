<?php
require_once(dirname(__FILE__).'Attribute_Changer_Plugin.php');

$attribute_changer = $GLOBALS['plugins']['Attribute_Changer_Plugin'];


if($attribute_changer->Current_Session == null) {
    print("ERRORROROR");
    return;
}

        $Session = $attribute_changer->Current_Session;

        function Build_New_Entry_Email_List() {
            $Columns_To_Accept = array();
            
            foreach ($_POST['New_Entry_Attribute_Column_Select'] as $attribute_id => $include_value) {
                if(array_key_exists($attribute_id, $Session->attribute_list)) {
                    array_push($Columns_To_Accept, $attribute_id);
                }
                
            }
            if(!isset($_POST['Hidden_New_Entry_List'])) {
                //error
                print("<html><body>THERE WAS AN ERROR WITH THE HIDDEN INPUT</body></html>");
                return false;
            }
            foreach ($_POST['Hidden_New_Entry_List'] as $hidden_email_key => $include_value) {
                if(!isset($_POST['New_Entry_List'][$hidden_email_key]['include'])) {
                    unset($Session->Commited_New_Entires[$hidden_email_key]);
                }
                else{
                    $Session->Commited_New_Entires[$hidden_email_key] = array();
                    foreach ($Columns_To_Accept as $key => $attribute_id) {
                        if(isset($_POST['New_Entry_List'][$hidden_email_key][$attribute_id])) {

                            if($Session->attribute_list[$attribute_id]['type'] === 'checkboxgroup') {

                                foreach ($_POST['New_Entry_List'][$hidden_email_key][$attribute_id] as $checkbox_key_id => $checkbox_value_id) {
                                    if(array_key_exists($checkbox_key_id, $Session->attribute_list[$attribute_id]['allowed_values'])) {

                                        if(!isset($Session->Commited_New_Entires[$hidden_email_key][$attribute_id])) {
                                            $Session->Commited_New_Entires[$hidden_email_key][$attribute_id] = array();
                                        }
                                        array_push($Session->Commited_New_Entires[$hidden_email_key][$attribute_id], $checkbox_key_id);
                                    }
                                }
                            }
                            else if(in_array($_POST['New_Entry_List'][$hidden_email_key][$attribute_id], $Session->New_Entry_List[$attribute_id])) {
                                $Session->Commited_New_Entires[$hidden_email_key][$attribute_id] = $_POST['New_Entry_List'][$hidden_email_key][$attribute_id];
                            }
                        }
                    }
                }
            }
            return;
        }


        function Get_Allowed_Attributes($attribute_id) {
            if(isset($Session->attribute_list[$attribute_id]) && $Session->attribute_list[$attribute_id]['type'] == ('checkboxgroup'|'checkbox'|'radio'|'select')) {
                return $Session->attribute_list[$attribute_id]['allowed_values'];
            }
            else{
                return false;
            }
        }




        $Current_New_Entry_Block;

        if(isset($_POST['New_Entry_Form_Submitted'])) {
            
            if(!Build_New_Entry_Email_List()){
                die();
            }
        }

        if(isset($_POST['New_Entries_Table_Submit_All']) && $_POST['New_Entries_Table_Submit_All'] === 'New_Entries_Table_Submit_All' ) {
            if(Initialize_Modify_Entries_Display() == null) {
                print(Process_All_New_And_Modify());
            }
            else{
                $HTML_TO_DISPLAY = Get_Modify_Entry_Table_Block();
                print('<html><body><script src="'.$javascript_src.'"></script>'.$HTML_TO_DISPLAY.'</body></html>');
            }

        }

        if(isset($_Post['New_Entries_Table_Next_Page']) && $_Post['New_Entries_Table_Next_Page'] === 'New_Entries_Table_Next_Page') {
            $HTML_TO_DISPLAY = New_Entry_Display_Next_Page();
            if($HTML_TO_DISPLAY == false) {
                $HTML_TO_DISPLAY = Get_New_Entry_Table_Block();
            }
            print('<html><body><script src="'.$javascript_src.'""></script>'.$HTML_TO_DISPLAY.'</body></html>');
        }

        if(isset($_Post['New_Entries_Table_Previous_Page']) && $_Post['New_Entries_Table_Previous_Page'] === 'New_Entries_Table_Previous_Page') {
            $HTML_TO_DISPLAY = New_Entry_Display_Previous_Page();
            if($HTML_TO_DISPLAY == false) {
                $HTML_TO_DISPLAY = Get_New_Entry_Table_Block();
            }
            print('<html><body><script src="'.$javascript_src.'""></script>'.$HTML_TO_DISPLAY.'</body></html>');
        }
     
        if(isset($_Post['New_Entry_Change_Display_Amount']) && $_Post['New_Entry_Change_Display_Amount'] === 'New_Entry_Change_Display_Amount') {

            if(isset($_POST['New_Entries_New_Display_Amount'])) {
                if($_POST['New_Entries_New_Display_Amount'] != (10|100|1000|10000|"all")){

                }
                else{
                    if(New_Entry_Change_Display_Amount($_POST['New_Entries_New_Display_Amount']) != true) {

                    }
                    else{
                        

                    }
                }
            }
            $HTML_TO_DISPLAY = Get_New_Entry_Table_Block();
            print('<html><body><script src="'.$javascript_src.'""></script>'.$HTML_TO_DISPLAY.'</body></html>');
            
        }


?>