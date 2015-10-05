<?php


/**
* 
*/
class AttributeAndValueChanger {
	
	function __construct(argument) {

	}

	function AddAttributeValue($attribute_id, $attributeValues) {

        $query = sprintf('select type, tablename from %s where id = %d', $GLOBALS['tables']['attribute'], $attribute_id);
        $type_table_return = Sql_Query($query);
        if(!$type_table_return) {
        	return -1;
        } 
        else {
            $row = Sql_Fetch_Row($type_table_return);

            if(!$row[0] || !$row[1]) { 

            }
            else{

                $type = $row[0];
                $table = $row[1];

                if($type == "radio" || $type == "checkboxgroup" || $type == "select" || $type == "checkbox") {
                	$tablename =$GLOBALS['table_prefix']."listattr_".$table;
                    $value_query = sprintf('select id, name from %s', $tablename);

                	foreach ($values as $value) {
                		$addQuery = sprintf("insert into %s unique (name) values (%s)", $tablename, $name, $value);     
                		$ret = Sql_Query($addQuery);           		
                	}

                }
            }
        }
	}


	function InsertNewValues(&$Session, $attribute_id, $values) {
		if(isset($Session->attribute_list[$attribute_id])){
			if($Session->attribute_list[$attribute_id]['type']=='checkboxgroup'|'radio'|'select'){

				foreach ($values as $key => $value) {
					if(!in_array($value, $Session->attribute_list[$attribute_id]['allowed_values'])) {

						$tablename = $Session->attribute_list[$attribute_id]['tablename'];

                    	$value_query = sprintf('insert into %s name = %s', $tablename, $value);
                    	$ret = Sql_Query($value_query);
                    	if(!$ret){
                    		if("NOT INSERTED"){

                    		}
                    	}
                    	else{
                    		$idQuery = sprintf("select id from %s where name = %s", $tablename, $value);
                    		$ret = Sql_Fetch_Array_Query($idQuery);
                    		$id = $ret[0];
                    		$Session->attribute_list[$attribute_id]['allowed_values'][$id] = $value;

                    		foreach ($Session->pendingValues[$attribute_id] as $email_key => $pendingValues) {
                    			if(isset($pendingValues[$value]) && $pendingValues[$value] == true) {
                    				if(isset($Session->Current_User_Values[$email_key])) {
                    					$Session->Modify_Entry_List[$email_key][$attribute_id][$id] = $value;
                    				}
                    				else{
                    					$Session->New_Entry_List[$email_key][$attribute_id][$id] = $value;
                    				}
                    			}
                    		}
                    	}
					}
				}
				
			}
		}
	}

	function TestNewValue(&$Session, $attributeId, $value, $email_key) {
		if(!in_array($value, $Session->New_Attribute_Values[$attributeId])) {
			if(isset($Session->attribute_List[$attributeId])) {
				if($Session->attribute_List[$attributeId]['type'] == 'checkboxgroup'|'radio'|'select') {
					if(!in_array($value, $Session->attribute_List[$attributeId]['allowed_values'])) {
						$Session->New_Attribute_Values[$attributeId][] = $value;
						$Session->pendingValues[$attribute_id][$email_key][$value] = true;
					}
				}	
			}
		}
	}
}


?>