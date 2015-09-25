<?php
//require_once(dirname(__FILE__).'Attribute_Changer_Plugin.php');

//$attribute_changer = $GLOBALS['plugins']['Attribute_Changer_Plugin'];


// if($attribute_changer->Current_Session == null) {
//     print("ERRORROROR");
//     return;
// }

        

        function Build_New_Entry_Email_List() {

            $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

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



                    unset($Session->Committed_New_Entries[$hidden_email_key]);
                }
                else{
                    print('<br>');

                    print_r($_POST);

                    print("<br>");
                    print($hidden_email_key);
                    print("<br>");
                    $Session->Committed_New_Entries[$hidden_email_key] = array();
                    foreach ($Columns_To_Accept as $key => $attribute_id) {
                        print("<br>huur ".$attribute_id."<br>");
                        if(isset($_POST['New_Entry_List'][$hidden_email_key][$attribute_id])) {

                            print("huuuur again<br>");

                            if($Session->attribute_list[$attribute_id]['type'] === 'checkboxgroup') {

                                foreach ($_POST['New_Entry_List'][$hidden_email_key][$attribute_id] as $checkbox_key_id => $checkbox_value_id) {
                                    if(array_key_exists($checkbox_key_id, $Session->attribute_list[$attribute_id]['allowed_values'])) {

                                        if(!isset($Session->Committed_New_Entries[$hidden_email_key][$attribute_id])) {
                                            $Session->Committed_New_Entries[$hidden_email_key][$attribute_id] = array();
                                        }
                                        array_push($Session->Committed_New_Entries[$hidden_email_key][$attribute_id], $checkbox_key_id);
                                    }
                                }
                            }
                            else{
                                if($Session->attribute_list[$attribute_id]['type'] === 'radio' || $Session->attribute_list[$attribute_id]['type'] === 'select') {

                                    if(in_array($_POST['New_Entry_List'][$hidden_email_key][$attribute_id], $Session->attribute_list[$attribute_id]['allowed_value_ids'])

                                }

                                else{

                                }
                            }
                            else if(in_array($_POST['New_Entry_List'][$hidden_email_key][$attribute_id], $Session->New_Entry_List[$attribute_id])) {
                                $Session->Committed_New_Entries[$hidden_email_key][$attribute_id] = $_POST['New_Entry_List'][$hidden_email_key][$attribute_id];
                            }
                            in_array(needle, haystack)
                        }
                    }
                }
            }
            print_r($Session->Committed_New_Entries);
            return true;
        }


        // function Get_Allowed_Attributes($attribute_id) {
        //     if(isset($Session->attribute_list[$attribute_id]) && $Session->attribute_list[$attribute_id]['type'] == ('checkboxgroup'|'checkbox'|'radio'|'select')) {
        //         return $Session->attribute_list[$attribute_id]['allowed_values'];
        //     }
        //     else{
        //         return false;
        //     }
        // }


include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin.php');

        $Current_New_Entry_Block;



        if(isset($_POST['New_Entry_Form_Submitted'])) {
            

           
            if(!Build_New_Entry_Email_List()){
                
                die();
            }
        }

        if(isset($_POST['New_Entries_Table_Submit_All']) && $_POST['New_Entries_Table_Submit_All'] === 'New_Entries_Table_Submit_All' ) {

            //$GLOBALS['plugins']['AttributeChangerPlugin']->Retreive_And_Unserialize();

            if(Initialize_Modify_Entries_Display() == null) {
                print(Process_All_New_And_Modify());
            }
            else{
                $HTML_TO_DISPLAY = Get_Modify_Entry_Table_Block();
                print('<html><body><script src="'.$javascript_src.'"></script>'.$HTML_TO_DISPLAY.'</body></html>');
            }

        }

        if(isset($_POST['New_Entry_Change_Display_Amount']) && $_POST['New_Entry_Change_Display_Amount'] == 'New_Entry_Change_Display_Amount') {

            //print("ARHHHH");
            
            $new_display_amounts = array(
                10=>true,
                100=>true,
                1000=>true,
                10000=>true,
                "all"=>true,
                );

            $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;

            if(isset($_POST['New_Entries_New_Display_Amount'])) {
                //print("mades itts");
                if(!isset($new_display_amounts[$_POST['New_Entries_New_Display_Amount']]) || $new_display_amounts[$_POST['New_Entries_New_Display_Amount']] != true) {

                }
                else{
                    include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Display_Adjustment_Functions.php');

                    if(New_Entry_Change_Display_Amount($_POST['New_Entries_New_Display_Amount']) != true) {

                    }
                    else{
                         
                    }
                }
            }

            $HTML_TO_DISPLAY = Get_New_Entry_Table_Block();

            print('<html><body><script src="'.$javascript_src.'""></script>'.$HTML_TO_DISPLAY.'</body></html>');
            
        }
        if(isset($_POST['New_Entries_Table_Next_Page']) && $_POST['New_Entries_Table_Next_Page'] === 'New_Entries_Table_Next_Page') {
            $HTML_TO_DISPLAY = New_Entry_Display_Next_Page();
            if($HTML_TO_DISPLAY == false) {
                $HTML_TO_DISPLAY = Get_New_Entry_Table_Block();
            }
            print('<html><body><script src="'.$javascript_src.'""></script>'.$HTML_TO_DISPLAY.'</body></html>');
        }

        if(isset($_POST['New_Entries_Table_Previous_Page']) && $_POST['New_Entries_Table_Previous_Page'] === 'New_Entries_Table_Previous_Page') {
            $HTML_TO_DISPLAY = New_Entry_Display_Previous_Page();
            if($HTML_TO_DISPLAY == false) {
                $HTML_TO_DISPLAY = Get_New_Entry_Table_Block();
            }
            print('<html><body><script src="'.$javascript_src.'""></script>'.$HTML_TO_DISPLAY.'</body></html>');
        }
     

?>