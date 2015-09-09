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

            foreach ($_POST['Hidden_New_Entry_List'] as $hidden_email_key => $include_value) {
                if(!isset($_POST['New_Entry_List'][$hidden_email_key]['include'])) {
                    unset($Session->Commited_New_Entires[$hidden_email_key]);
                }
                else{
                    $Session->Commited_New_Entires[$hidden_email_key] = array();
                    foreach ($Columns_To_Accept as $key => $attribute_id) {
                        if(isset($_POST['New_Entry_List'][$hidden_email_key][$attribute_id])) {
                            if(in_array($_POST['New_Entry_List'][$hidden_email_key][$attribute_id], $Session->New_Entry_List[$attribute_id])) {
                                $Session->Commited_New_Entires[$hidden_email_key][$attribute_id] = $_POST['New_Entry_List'][$hidden_email_key][$attribute_id];
                            }
                        }
                    }
                }
            }
        }

        $Current_New_Entry_Block;

        if(isset($_POST['New_Entry_Form_Submitted'])) {

            if(!isset($_POST['New_Entry_Attribute_Column_Select'])) {
                foreach ($_POST['Hidden_New_Entry_List'] as $hidden_email_key => $include_value) {
                    if(!isset($_POST['New_Entry_List'][$hidden_email_key]['include'])) {
                        unset($Session->Commited_New_Entires[$hidden_email_key]);

                    }
                }
            }

            else if(isset($_POST['New_Entry_Attribute_Column_Select'])) {
         
                $Columns_To_Accept = array();
         
                while($_POST['New_Entry_Attribute_Column_Select']) {
                    $Attribute_To_Parse = array_shift($_POST['New_Entry_Attribute_Column_Select']);
         
                    if(array_key_exists($Attribute_To_Parse, $Session->attribute_list)) {
                        array_push($Columns_To_Accept, $Attribute_To_Parse);
                    }
                }

                if(count($Columns_To_Accept) == 0) {
                    //email is not an attribute, might still have emails
                }
                if(!isset($_POST['Hidden_New_Entry_List'])) {
                    //error
                    print("<html><body>THERE WAS AN ERROR WITH THE HIDDEN INPUT</body></html>");

                }
                else foreach ($_POST['Hidden_New_Entry_List'] as $hidden_email_key => $include_value) {
                    if(!isset($_POST['New_Entry_List'][$hidden_email_key]['include'])) {
                        unset($Session->Commited_New_Entires[$hidden_email_key]);
                    }
                    else if(count($Columns_To_Accept) == 0) {
                        $Session->Commited_New_Entires[$hidden_email_key] = array();
                    }
                    else{
                        $attribute_values = $_POST['New_Entry_List'][$hidden_email_key]; 
                        if(isset($attribute_values['include']) {
                            if($attribute_values['include'] == 'include') {
         
                                unset($attribute_values['include']);
                                $new_entry_to_commit = array();
         
                                foreach ($attribute_values as $attribute_name => $value) {
                                    if(in_array($attribute_name, $Columns_To_Accept)) {
                                        if(is_array($value)) {
                                            if($Session->attribute_list[$attribute_name]['type'] == 'checkboxgroup') {
                                                foreach ($value as $key => $current_value) {
                                                    //consider doing a check here if value is already set (maybe redundant but, you know)
                                                    if(in_array($current_value, $Session->attribute_list[$attribute_name]['allowed_values'])) {
                                                        if(!is_array(($new_entry_to_commit[$attribute_name]))) {
                                                            $new_entry_to_commit[$attribute_name] = array();
                                                        }
                                                        array_push($new_entry_to_commit[$attribute_name], $current_value); 
                                                    }
                                                }
                                            }
                                            else{
                                                //only the checkbox group can be an array
                                            }
                                        }
                                        else{
                                            if($Session->attribute_list[$attribute_name]['type'] == 'checkboxgroup') {
                                                if(in_array($value, $Session->attribute_list[$attribute_name]['allowed_values'])) {

                                                    if(!is_array(($new_entry_to_commit[$attribute_name]))) {
                                                         $new_entry_to_commit[$attribute_name] = array();
                                                    }
                                                    array_push($new_entry_to_commit[$attribute_name], $value); 
                                                }
                                            }
                                            else if($Session->attribute_list[$attribute_name]['type'] == "checkbox") {
                                                if(in_array($value, $Session->attribute_list[$attribute_name]['allowed_values'])) {
                                                    
                                                    $new_entry_to_commit[$attribute_name]=$value; 
                                                }
                                            }
                                            else if($Session->attribute_list[$attribute_name]['type'] == "textarea"|"textline"|"hidden"|"date") {
                                                $new_entry_to_commit[$attribute_name]=$value; 
                                            }
                                            else{
         
                                            }
                                        }
                                         
                                    }
                                    else{
                                        //is not a currently acccepted column
                                    }
                                }
                                $Session->Commited_New_Entires[$hidden_email_key] = $new_entry_to_commit;
                            }
                        }
                        else{
                            //skip this email , not included
                        }
                    }
                }
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