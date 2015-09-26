<?php
    include_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin.php');

	function Process_All_New_And_Modify() {
        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
        print("ARARARARARARARA BOOON<br>");
        print("<br>commmm new entries<br>");
        print_r($Session->Committed_New_Entries);
        print("<br>commmm new entries<br>");
        if(count($Session->Committed_New_Entries) > 0) {
            Push_New_Entries();
        }
        if(count($Session->Committed_Modify_Entries) > 0) {
            Push_Modify_Entries();
        }
        $return_html = '<html><body>Complete</body></html>';

        $GLOBALS['plugins']['AttributeChangerPlugin']->Close_Session();

        print($return_html);
    }

    //$Failed_New_Entries;

    function Push_New_Entries() {
        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
        print("<br>commmm new entries<br>");
        print_r($Session->Committed_New_Entries);
        print("<br>commmm new entries<br>");
        foreach ($Session->Committed_New_Entries as $email_key => $new_attributes_and_values) {

            $exists = Sql_Fetch_Row_Query(sprintf('select id from %s where email = "%s"', $GLOBALS['tables']['user'],$email_key));
            if($exists[0]) {
                $Failed_New_Entries[$email_key] = $new_attributes_and_values;
            }
            else{
                //$new_user_id = addNewUser($email_key);
                $new_value_array = array();

                foreach ($new_attributes_and_values as $attribute_id => $attribute_value_id) {
                    if($Session->attribute_list[$attribute_id]['type'] === 'checkboxgroup') {
                        $new_value_array = array();
                        foreach ($attribute_value_id as $individual_id) {
                            if(array_key_exists($individual_id, $Session->attribute_list[$attribute_id]['allowed_value_ids'])) {
                                array_push($new_value_array, $individual_id);
                            }
                        }
                        $proper_this_attribute_value = implode(',', $new_value_array);

                    }
                    else if($Session->attribute_list[$attribute_id]['type'] === 'checkbox' || $Session->attribute_list[$attribute_id]['type'] === 'radio' || $Session->attribute_list[$attribute_id]['type'] === 'select') {
                        

                        if(array_key_exists($attribute_value_id, $Session->attribute_list[$attribute_id]['allowed_value_ids'])) {
                            $proper_this_attribute_value = $attribute_value_id;
                        }
                    }
                    else{///HERE IS MESSSSSSSSY
                        if( in_array($attribute_value_id, $Session->New_Entry_List[$email_key][$attribute_id]) ) {
                            $proper_this_attribute_value = $attribute_value_id;
                        }
                    }
                    print('<br>new user:  '.$email_key.' attribute id:   '.$attribute_id.' value  :'.$proper_this_attribute_value.'<br>');
                    //need a way for 'STICKY' attributes
                    //saveUserAttribute($new_user_id, $attribute_id, $proper_this_attribute_value);
                }   
            }
        }
    }

    //$Failed_Modify_Entries;
    function Push_Modify_Entries() {
        $Session = $GLOBALS['plugins']['AttributeChangerPlugin']->Current_Session;
        print("<br>commmm modify entries<br>");
        print_r($Session->Committed_Modify_Entries);
        print("<br>commmm Modify entries<br>");

        foreach ($Session->Committed_Modify_Entries as $email_key => $modify_attributes_and_values) {

            $exists = Sql_Fetch_Row_Query(sprintf('select id from %s where email = "%s"', $GLOBALS['tables']['user'],$email_key));
            if(!$exists[0]) {
                $Failed_Modify_Entries[$email_key] = $modify_attributes_and_values;
            }
            else{
                $modify_user_id = $exists[0];
                print('<br>current user id<br>'.$modify_user_id.'<br>');
                $modify_value_array = array();

                foreach ($modify_attributes_and_values as $attribute_id => $attribute_value_id) {
                    if($Session->attribute_list[$attribute_id]['type'] === 'checkboxgroup') {
                        $modify_value_array = array();
                        foreach ($attribute_value_id as $individual_id) {
                            if(array_key_exists($individual_id, $Session->attribute_list[$attribute_id]['allowed_value_ids'])) {
                                array_push($modify_value_array, $individual_id);
                            }
                        }
                        $proper_this_attribute_value = implode(',', $modify_value_array);

                    }
                    else if($Session->attribute_list[$attribute_id]['type'] === 'checkbox' || $Session->attribute_list[$attribute_id]['type'] === 'radio' || $Session->attribute_list[$attribute_id]['type'] === 'select') {
                        
                        
                        if(array_key_exists($attribute_value_id, $Session->attribute_list[$attribute_id]['allowed_value_ids'])) {
                            $proper_this_attribute_value = $attribute_value_id;
                        }
                    }
                    else{///HERE IS MESSSSSSSSY
                        if( in_array($attribute_value_id, $Session->Modify_Entry_List[$email_key][$attribute_id]) ) {
                            $proper_this_attribute_value = $attribute_value_id;
                        }
                    }
                    print('<br>modify user:  '.$email_key.' attribute id:   '.$attribute_id.' value  :'.$proper_this_attribute_value.'<br>');
                    //need a way for 'STICKY' attributes
                    print("<br>mod user id : ".$modify_user_id."<br>");

                    $current_value_query = sprintf('select value from %s where (userid,attributeid)=(%d,%d)', $GLOBALS['tables']['user_attribute'], $modify_user_id, $attribute_id);


                    $current_value_return = Sql_Fetch_Row_Query($current_value_query);
                    print("<br>WAS THIS IT".$proper_this_attribute_value.'<br>');
                    if(!$current_value_return[0]) {
                        print("ARGSAG22222");
                        $update_query = sprintf('insert into %s  (userid,attributeid, value) values (%d,%d,"%s")', $GLOBALS['tables']['user_attribute'], $modify_user_id, $attribute_id, $proper_this_attribute_value);
                    }
                    else{
                        print('<br>table: '. $GLOBALS['tables']['user_attribute'].' att val: '.$proper_this_attribute_value.' user id: '. $modify_user_id.' attribute id: '. $attribute_id.'<br>');
                        $update_query = sprintf('update %s set value= "%s" where userid = %d and attributeid = %d', $GLOBALS['tables']['user_attribute'], $proper_this_attribute_value, $modify_user_id, $attribute_id);
                        
                    }
                    Sql_Query($update_query);
                    //saveUserAttribute($modify_user_id, $attribute_id, $proper_this_attribute_value);
                }   
            }
        }
    }

?>