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


    if(!$attribute_changer->Test_Create_Temp_Dir()) {
        
        print("<html><body>Error with temp directory</body></html>");
        return;
    }

//HERE HAVE A CHECK FOR GOOD SETUP
    $Current_Session = $attribute_changer->New_Session();
    //print_r($Current_Session);
    


    $target_dir = $attribute_changer->coderoot.'temp_table_uploads/';

    $target_file = $target_dir . basename($_FILES["attribute_changer_file_to_upload"]["name"]);

    $uploadOk = 1;
    $new_file_type = pathinfo($target_file,PATHINFO_EXTENSION);

    $new_html = '<html><body>';
    // Check if file already exists
    if (file_exists($target_file)) {

        while(file_exists($target_file)) {
            $new_filename =pathinfo($target_file,PATHINFO_FILENAME);

            $new_filename = $new_filename.strval(rand(0,100));

            $target_file = $target_dir.$new_filename.'.'.$new_file_type;
        }
        $new_html = $new_html."<div>File already exists, added rand value. File is: ".basename($target_file).'</div>';
    }

    // Check file size
    if ($_FILES["attribute_changer_file_to_upload"]["size"] > 1000000000) {
        $new_html = $new_html."<div>Sorry, your file is too large > 1GB. </div>";
        $uploadOk = 0;
    }
    // Allow certain file formats

    //add other comma separated
    if($new_file_type != "csv") {
        $new_html = $new_html."<div>Sorry, only csv allowed. </div>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $new_html = $new_html."<div>Sorry, your file was not uploaded. </div>".$page_print;
    // if everything is ok, try to upload file
    } else {

        if (move_uploaded_file($_FILES["attribute_changer_file_to_upload"]["tmp_name"], $target_file)) {

            $new_html = $new_html."<div>The file ". basename($target_file). " has been uploaded.</div>";

            

            $Current_Session->Set_File_Location($target_file);
            //print($Current_Session->Get_File_Location());

            //print_r($attribute_changer->Current_Session);
            $cols_match = Get_Attribute_File_Column_Match();

            if($cols_match == ('ERROR NO CURRENT SESSION'|"ERROR WITH SESSION FILE LOCATION"|'') ) {
                $new_html = $new_html.'<div>There was an error with the column select table forming.</div>'.$page_print;

            }
            else{
                $new_html= $new_html.$cols_match;
                
            }
        } 
        else {
            $error = error_get_last();
            print($error['message']);
            $new_html = $new_html."<div>Sorry, there was an error uploading your file.</div>".$page_print;
        }
    }

    $new_html = $new_html.'</body></html>';
    print($new_html);

}

?>