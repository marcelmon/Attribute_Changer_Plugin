    /*


can do, if class="xx" exists
or, id="hello" is checked



    Require easy to initiate operation using selects
    TYPE 2 + 1

    _Action     _Subject            _Conditional    _Subject      _Predicate
    |check      |All                |               |               |
    |uncheck    |Safe_Value         |unless         |Any            |Exists
                |Current_Value      |if             |Safe_Value     |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked
                
    TYPE 3 

    _Action     _Subject            *_Conditional   *_Subject       *_Predicate
    |check      |All                |               |               |
    |uncheck    |Checkbox_Value     |unless         |Any            |Exists
                |Current_Value      |if             |Checkbox_Value |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked

    */


//     function Process_Input_Command(the_string) {
//         var commands = the_string.split(' ');
//         if (!commands || commands.length <= 0) {
//             return -1;
//         }
//         var action = _Action(commands[0]);
//         if(!action) {
//             return -1;
//         }
//         if(commands.length == 1) {

//         }
//     }

//     function process_command(attribute_id, attribute_type, action, subject_1, conditional, subject_2, condition) {
//         if(!action || !subject){
//             return -1;
//         }
//         var this_action = _Action(action);
//         if(!this_action){
//             return -1;
//         }

//         var this_subject_1 = _Subject(subject_1);
//         if(!this_subject_1){
//             return -1;
//         }

//         if(!conditional){
//             var className = this_subject_1
//         }

//     }


//     function _Action(action) {
//         switch(action) {

//             case 'Check':
//                 return Check;

//             case 'Uncheck':
//                 return Uncheck;

//             default:
//                 return -1;
//         }
//     }

//     var Check = function (subject) {
//         this.Action = 'Check';
//         this.subject = subject;

//         this.conditional = null;

//         this.setConditional = function(conditional) {
//             this.conditional = conditional;
//         }

//         this.check_subject = function(subject){
//             for(var i=0; i<subject.length; i++) {
//                 if(subject[i].className.indexOf('Checked') < 0){
//                     subject[i].className += ' Checked';
//                     if(subject[i].type == 'checkbox' || subject[i].type == 'radio') {
//                         subject[i].checked = true;
//                     }
//                 }
//             }       
//         }

//         this.execute = function(){
//             if(!this.conditional) {

//                 this.check_subject(this.subject);
//             }
//             else{
//                 var new_subject = new array();
//                 for(var j=0; j<this.subject.length; j++){
//                     if(this.conditional.execute(this.subject[i])){
//                         new_subject.push(this.subject)
//                     }
//                 }
//                 this.check_subject(new_subject);
//             }
//             return true;
//         }
//     }

//     var Uncheck= function(subject) {
//         this.Action = 'Uncheck';
//         this.subject = subject;

//         this.conditional = null;

//         this.setConditional = function(conditional) {
//             this.conditional = conditional;
//         }

//         this.uncheck_subject = function(subject){
//             for(var i=0; i<subject.length; i++) {
                
//                 var class_names = subject[i].className.split(' ');
//                 var index = class_names.indexOf('Checked');
//                 if(index > -1){
//                     class_names.splice(index, 1);
//                     if(subject[i].type == 'checkbox' || subject[i].type == 'radio') {
//                         subject[i].checked = false;
//                     }
//                 }
//                 subject[i].className = class_names.join(' ');
//             }       
//         }

//         this.execute = function(){
//             if(!this.conditional) {

//                 this.uncheck_subject(this.subject);
//             }
//             else{
//                 var new_subject = this.conditional(this.subject);
//                 this.uncheck_subject(new_subject);
//             }
//             return true;
//         }
//     }

//     function _Subject(subject) {
//         switch(subject){

//             case 'All':
//                 return All;

//             case 'Any':
//                 return Any;

//             case 'Safe_Value':
//                 return Safe_Value;

//             case 'Current_Value':
//                 return Current_Value;

//             case 'Other_Value':
//                 return Other_Value;

//             case 'Checkbox_Value':
//                 return Checkbox_Value;

//             default:
//                 return -1;
//         }
//     }


//     var All = function (attribute_id) {
        
//         var attribute_class = 'attribute_'.concat(attribute_id);
//         var attribute_elements = document.getElementsByClassName(attribute_class);
//         return attribute_elements;
//     }


//     var Any= function(attribute_id) {
//         var attribute_class = 'attribute_'.concat(attribute_id);
//         var attribute_elements = document.getElementsByClassName(attribute_class);
//         return attribute_elements;
//     }
 

//     var Safe_Value = function(attribute_id) {
//         return Filter_Value(attribute_id, 'Safe_Value');
//     }


//     var Current_Value = function(attribute_id) {
//         return Filter_Value(attribute_id, 'Current_Value');
//     }

//     var Other_Value= function(attribute_id) {
//         var all = All(attribute_id);

//         var others = new array();
//         //now have 2 arrays to compare to all, all the reset 
//         for(var i=0; i<all.length; i++) {
//             if(all[i].className.indexOf('Current_Value') > -1){
//                 continue;
//             }
//             if(all[i].className.indexOf('Safe_Value') > -1){
//                 continue;
//             }
//             others.push(all[i]);
//         }
//         return others;
//     }


//     var Checkbox_Value= function(attribute_id) {

//         return Filter_Value(attribute_id, 'Checkbox_Value');
//     }
// //Checkbox, other, current safe
//     var Filter_Value = function(attribute_id, class_string){
//         var elements = document.getElementsByClassName('attribute_'.concat(attribute_id));
//         var return_list = new Array();

//         for(var i=0; i < elements.length; i++) {
//             if(elements[i].className.indexOf(class_string) < 0) {
//                 return_list.push(elements[i]);
//             }
//         }
//         return return_list;
//     }


//     var Safe_Value = function(attribute_id) {
//         return Filter_Value(attribute_id, 'Safe_Value');
//     }

//     function _Conditional(conditional) {
//         switch(conditional){

//             case 'Unless':
//                 return Unless;

//             case 'If':
//                 return If;

//             default:
//                 return -1;
//         }
//     }
    

//     var Unless = function (leading_subject, condition, conditioned_subject) {
//         this.Conditional = 'Unless'
//         this.leading_subject = leading_subject;
//         this.Condition = condition;
//         return this;

//         this.execute = function() {
//             if(this.Condition(this.leading_subject)) {
//                 return false;
//             }
//         }
//     }

//     var If = function(leading_subject, condition, conditioned_subject) {
//         this.Conditional = 'Unless'
//         this.leading_subject = leading_subject;
//         this.conditioned_subject = conditioned_subject;
//         //are in the same columns, so attribute id, email key are the same
//         //name would be the same, unless its checkbox

//         this.Condition = condition;
//         return this;

//         this.execute = function() {
//             if(this.Condition(this.conditioned_subject)) {
//                 return true;
//             }
//         }
//     }




//     function _Predicate(predicate) {
//         switch(predicate){

//             case 'Exists':
//                 return Exists;

//             case 'Not_Exists':
//                 return Not_Exists;

//             case 'Checked':
//                 return Checked;

//             case 'Not_Checked':
//                 return Not_Checked;

//             default:
//                 return -1;
//         }
//     }
    
    
//     var Exists = function (leading_subject, subject) {
//         Filter_Conditional(leading_subject, subject);
//     }  

//     var Not_Exists = function(leading_subject, subject) {
//         (Filter_Conditional(leading_subject, subject)) ? false : true;
//     }

//     var Checked = function(leading_subject) {
//         Filter_Conditional(leading_subject, 'Checked');
//     }

//     var Not_Checked = function(leading_subject, subject) {
//         (Filter_Conditional(leading_subject, 'Checked')) ? false : true;
//     }


//     var Filter_Conditional = function(leading_subject, subject) {

//         i=leading_subject.tagName;
//         while(i != 'ul'){
//             leading_subject = leading_subject.parentNode;
//             i = leading_subject.tagName;
//         }
//         child_subject = leading_subject.childNodes;
//         for(var i=0; i<child_subject.length; i++) {
//             if(child_subject[i].className.indexOf(subject) > -1) {
//                 return true;
//             }
//         }
//         return false;
//     }



//Now done using class structure commands and select boxes 
    // function Clear_Column_Select(column_class) {
    //     var column_radios = document.getElementsByClassName(column_class);
    //     var i;
    //     for(i=0; i<column_radios.length; i++) {
    //         column_radios[i].checked = false;
    //     }
    // }



//check or uncheck the email_block, change the class, un/check the selector
//Checked elements have class 'Checked', 'Email_Block'
    function email_block_clicked(e) {
        var classes = e.className.split(' ');
       // window.alert(e.className);
        var index = classes.indexOf('Checked');
        var selector = e.getElementsByClassName('Email_Block')[0];

        if(index > -1){
            classes.splice(index, 1);
            selector.checked= false;
        }
        else{
            classes.push('Checked');
            selector.checked = true;
        }

        e.className = classes.join(' ');
        selector.className = e.className;
    }


//check or uncheck the atribute_list block, change the class, un/check the selector
//Checked elements have class 'Checked', 'Email_Block'
    function list_element_clicked(e) {

        var classes = e.className.split(' ');
       // window.alert(e.className);
        var index = classes.indexOf('Checked');
        var selector = e.getElementsByTagName('input')[0];

        if(index > -1){
            classes.splice(index, 1);
            selector.checked= false;
        }
        else{
            classes.push('Checked');
            selector.checked = true;
        }

        e.className = classes.join(' ');
        selector.className = e.className;

       // window.alert(e.className);
    }


//Get all 'Email_Block' dom objects, add class 'Checked' and check the selector, 
//can probably simplify using email_block_clicked()
    function checkAll_Emails() {
        var elements = document.getElementsByClassName('Email_Block');
        var i;
        for(i=0; i<elements.length; i++) {
            var classes = elements[i].className.split(' ');
            var index = classes.indexOf('Checked');

            if(index < 0) {
                classes.push('Checked');
                if(elements[i].type == 'checkbox') {
                    elements[i].checked = true;
                }   
            }
            elements[i].className = classes.join(' ');
        }
    }


//Get all 'Email_Block' dom objects, remove class 'Checked' and uncheck the selector, 
//can probably simplify using email_block_clicked()
    function removeAll_Emails() {
        var elements = document.getElementsByClassName('Email_Block');
        var i;
        for(i=0; i<elements.length; i++) {
            var classes = elements[i].className.split(' ');
            var index = classes.indexOf('Checked');

            if(index > -1) {
                classes.splice(index, 1);
                if(elements[i].type == 'checkbox') {
                    elements[i].checked = false;
                }   
            }
            elements[i].className = classes.join(' ');
        }
    }

/*
    Require easy to initiate operation using selects
    TYPE 2 + 1

    _Action     _Subject            _Conditional    _Subject      _Predicate
    |check      |All                |               |               |
    |uncheck    |Safe_Value         |unless         |Any            |Exists
                |Current_Value      |if             |Safe_Value     |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked
                
    TYPE 3 

    _Action     _Subject            *_Conditional   *_Subject       *_Predicate
    |check      |All                |               |               |
    |uncheck    |Checkbox_Value     |unless         |Any            |Exists
                |Current_Value      |if             |Checkbox_Value |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked
*/

//DOM CLASSES ARE:  Safe_Value, Current_Value, Checkbox_Value, Other_Value, Email_Block, Checked
var command_0 = ['Check','Uncheck'];

var command_1_not_checkbox = ['All','Safe_Value','Current_Value', 'Other_Value'];
var command_1_checkbox = ['All','Checkbox_Value','Current_Value', 'Other_Value'];

var command_2 = ['Unless', 'If'];

var command_3_not_checkbox = ['Any', 'Safe_Value', 'Current_Value', 'Other_Value'];
var command_3_checkbox = ['Any', 'Checkbox_Value', 'Current_Value', 'Other_Value'];

var command_4 = ['Exists', 'Not_Exists', 'Checked', 'Not_Checked'];


var command_array_not_checkbox = [command_0, command_1_not_checkbox, command_2, command_3_not_checkbox, command_4];
var command_array_checkbox = [command_0, command_1_checkbox, command_2, command_3_checkbox, command_4];


//Parse input command array
//ensure that all syntax requirements will be met
    function Check_If_Good_Command(commands){
        if(commands.length != 5 && commands.length != 2) {
            return -1;
        }
        var is_checkbox = true;
        var is_good_command = true;
        for(var i=0; i<commands.length; i++) {
            if(!command_array_checkbox[i].indexOf(commands[i])){
                is_checkbox = false;
            }
        }
        if(!is_checkbox) {
            for(var i=0; i<commands.length; i++) {
                if(!command_array_not_checkbox[i].indexOf(commands[i])) {
                    is_good_command = false;
                }
            }
        }

        if(!is_good_command) {
           return -1;
        }
        return true;
    }

//check if the command string syntax is ok
//use dom class structure to execute processing
function Process_Commands(attribute_id, commandString){
    commands = commandString.split(' ');

    if(!Check_If_Good_Command(commands)) {
        return -1;
    }
    var action_function = Get_Action(commands[0]);
    var subject_function = Get_Subject(commands[1]);
    
    if(subject_function == -1 || subject_function == -1) {
        return -1;
    }

    var subject = subject_function(attribute_id);

    if(!subject || subject.length == 0) {
        return 'NONE TO ACT ON';
    }

    if(commands.length == 2) {
        action_function(subject_function);
        return true;
    }
    else{
        return Process_Long_Commands(attribute_id, commands, subject, action_function);
    }
}


//additional commands will be conditional, subject, predicate
//requires comparing each of the subject's siblings to some predicate
//if requrements are met, execute the command through action_function()
function Process_Long_Commands(attribute_id, commands, subject, action_function) {

    for(var i=0; i++; i<subject.length) {

        var subject_2_function = Get_Sibling(commands[3]);

        var subject_2 = subject_2_function(subject[i], 'td');
        var predicate_function = Get_Predicate(commands[4]);

        if(predicate_function == -1 || subject_2_function == -1){
            return -1;
        }
        var predicate_result = predicate_function(subject_2);

        switch(commands[2]) {
            case 'Unless':
                if(predicate_result == false) { action_function(subject[i]); } 
                break;
                
            case 'If':
                if(predicate_result == true) { action_function(subject[i]); }
                break;

            default:
                return -1;
        }
    }
    return true;

}


//This is an interface to return the action argument's corresponding function
//the default does not need to be checked if previously itterated all arguments
    var Get_Action = function(action_input) {
        switch(action_input){
            case 'Check':
                return check_element;

            case 'Uncheck':
                return uncheck_elements;

            default:
                return -1;
        }
    }



//for the element passed, it is assumed it is of type 'li' for the sake of this project, To become varible later
//set class to contain 'Checked' and also for contained Selector set the class and 'checked'
var check_element = function (element) {

    if(element.className.indexOf('Checked') < 0){
        element.className += ' Checked';

        var element_children = element.childNodes;

        for(var i=0; i<element_children.length; i++) {
            if(element_children[i].type == 'checkbox' || element_children[i].type == 'radio') {
                if(element_children[i].className.indexOf('Checked') < 0) {
                    element_children.className += 'Checked';
                }
                element_children[i].checked = false;
            }
        }
    }       
}



//for the element passed, it is assumed it is of type 'li' for the sake of this project, To become varible later
//set class to not contain 'Checked' and also for contained Selector remove 'Checked' class
var uncheck_elements = function (element) {

    var classes = element.className.split(' ');
    var index = classes.indexOf('Checked');
    if(index > -1){
        classes.splice(index, 1);
        elements.className = classes.join(' ');

        var element_children = element.childNodes;

        for(var i=0; i<element_children.length; i++) {

            if(element_children[i].type == 'checkbox' || element_children[i].type == 'radio') {

                var extra_classes = element_children[i].className.split(' ');
                var extra_index = extra_classes.indexOf('Checked');
                if(extra_index > -1) {
                    extra_classes.splice(extra_index, 1);
                    element_children[i].className = extra_classes.join(' ');
                }
                element_children[i].checked = false;
            }
        }
    }     
}


//This is an interface to return the action argument's corresponding function
//the default does not need to be checked if previously itterated all arguments
var Get_Subject = function(subject_type) {
    switch(subject_type) {
        case 'Any':
            return Get_All;
            break;

        case 'Safe_Value':
            return Get_Safe_Value;
            break;

        case 'Current_Value':
            return Get_Current_Value;
            break;

        case 'Other_Value':
            return Get_Other;


        case 'Checkbox_Value':
            return Get_Checkbox_Value
        
        default:
            return -1;
    }
}

            var Get_Checked = function(attribute_id) {

                var return_array = Filter_Class(attribute_id, 'Checked');
                return return_array;
            }

            var Get_Safe_Value = function(attribute_id) {

                var safe_array = Filter_Class(attribute_id, 'Safe_value');
                return return_array;
            }


            var Get_Current_Value = function(attribute_id) {

                var current_array = Filter_Class(attribute_id, 'Current_Value');
                return return_array;
            }



            var Get_Checkbox_Value = function(attribute_id) {

                var checkbox_array = Filter_Class(attribute_id, 'Checkbox_Value');
                return checkbox_array;
            }

            var Get_Current_Checkbox = function(attribute_id) {
                var current_array = Filter_Class(attribute_id, 'Checkbox_Value');
                var checkbox_array = Filter_Class(attribute_id, 'Checkbox_Value');

                var return_array = new array();

                for(var i=0; i<checkbox_array.length; i++) {
                    if(current_array.indexOf(checkbox_array[i]) > -1){
                        return_array.push(checkbox_array[i]);
                    }
                }
                return return_array;
            }

            /*


    863 

    Here I willingly ignite the candle, the night approaches and will soon arrive. There is none left, spare
    a tiny fragment, that which remains must carry itself though, reaching the end, will find it had succeeded. 


--> Margin creator , write text starting any point on screen, create boxes with =======+||
                                                                                        ||
 ---> WAY of displaying doc strings/info, also organize, write into files
            @Override,;;;
            """docstring"""



--> Highlight + Click --> drag = copy   &&  unclick = paste
















            */

            var Get_Other = function(attribute_id) {
                var attribute_array = Get_All(attribute_id);

                var current_array = Filter_Class(attribute_id, 'Current_Value');
                var safe_array = Filter_Class(attribute_id, 'Safe_Value');

                attribute_array = Remove_Matches(attribute_array, current_array);
                attribute_array = Remove_Matches(attribute_array, safe_array);

                return attribute_array;
            }


            var Filter_Class = function(attribute_id, class_to_match) {
                var attribute_array = document.getElementsByClassName('attribute_'.concat(attribute_id));
                var return_array = new array();

                for(var i=0; i<elements.length; i++) {
                    if(elements[i].className.indexOf(class_to_match) > -1) {
                        if(elements[i].tagName == 'li') {
                            return_array.push(elements[i]);
                        }
                    }
                }

                return return_array;
            }

            var Get_All = function(attribute_id) {

                var attribute_class = 'attribute_'.concat(attribute_id);
                var return_array = Filter_Class(attribute_id, attribute_class);

                return return_array;
            }

/*
    Require easy to initiate operation using selects
    TYPE 2 + 1

    _Action     _Subject            _Conditional    _Subject      _Predicate
    |check      |All                |               |               |
    |uncheck    |Safe_Value         |unless         |Any            |Exists
                |Current_Value      |if             |Safe_Value     |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked
                
    TYPE 3 

    _Action     _Subject            *_Conditional   *_Subject       *_Predicate
    |check      |All                |               |               |
    |uncheck    |Checkbox_Value     |unless         |Any            |Exists
                |Current_Value      |if             |Checkbox_Value |Not_Exists
                |Other_Value                        |Current_Value  |Checked
                                                    |Other_Value    |Not_Checked
*/


            var Get_Sibling = function(class_to_match) {
                switch(class_to_match):
                    case 'Current_Value':
                        return Get_Sibling_Current_Value;

                    case 'Safe_Value':
                        return Get_Sibling_Safe_Value;

                    case 'Other_Value'
                        return Get_Sibling_Not_Current_Not_Safe;

                    case 'Any':
                        return Get_Sibling_All;

                    case 'Checkbox_Value':
                        return Get_Sibling_Checkbox_Value;

                    default:
                        return -1;
            }



            var Get_Sibling_Checked = function(leading_subject, top_delimiter) {
                var return_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Checked');
                return return_array;
            }

            var Get_Sibling_Checkbox_Value = function(leading_subject, top_delimiter) {
                var return_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Checkbox_Value');
                return return_array;
            }

            var Get_Sibling_All = function(leading_subject, top_delimiter) {

                var attribute_class = Get_Attribute_Class(leading_subject);
                if(!attribute_class) {
                    return -1;
                }

                var return_array = Find_Sibling_Match(leading_subject, top_delimiter, attribute_class);
                return return_array;
            }

            var Get_Sibling_Current_Value = function(leading_subject, top_delimiter) {

                var current_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Current_Value');
                return current_array;
            }


            var Get_Sibling_Safe_Value = function(leading_subject, top_delimiter) {

                var safe_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Safe_Value');
                return safe_array;
            }

            var Get_Sibling_Not_Current_Value = function(leading_subject, top_delimiter) {

                var all_array = Get_Sibling_All(leading_subject, top_delimiter);
                var current_array = Get_Sibling_Current_Value(leading_subject, top_delimiter);
            }


            var Get_Sibling_Not_Other = function(leading_subject, top_delimiter) {

                var return_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Current_Value');
                var safe_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Safe_Value');

                for(var i=0; i<safe_array.length; i++) {
                    if(safe_array[i].)
                }
            }


            var Get_Sibling_Not_Current_Not_Safe = function(leading_subject, top_delimiter) {

                var current_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Current_Value');
                var safe_array = Find_Sibling_Match(leading_subject, top_delimiter, 'Safe_Value');

                var all_array = Get_Sibling_All(leading_subject, top_delimiter);

                all_array = Remove_Matches(all_array, safe_array);
                all_array = Remove_Matches(all_array, current_array);

                return all_array;
            }




            //DOM FIND SIBLINGS IN SAME CELL MATCHING CLASS TO MATCH
            var Find_Sibling_Match = function(leading_subject, top_delimiter, class_to_match) {

                var top_element = Get_Top_Element(leading_subject, top_delimiter);
                if(!top_element) {
                    return null;
                }

                var match_array = top_element.getElementsByClassName(class_to_match);

                var return_array = new array();

                for(var i=0; i<match_array.length; i++) {
                    if(match_array[i].tagName == 'li'){

                        if(match_array[i] != leading_subject) {
                            return_array.push(match_array[i]); 
                        }
                    }
                }
                return return_array;
            }






            var Get_Predicate = function(to_match) {
                switch(to_match) {
                    case 'Exists':
                        return Test_Checked;

                    case 'Not_Exists':
                        return Test_Not_hecked;
                        
                    case 'Checked':
                        return Test_Exists;

                    case 'Not_Checked':
                        return Test_Not_Exists;

                    default:
                        return -1;
                }
            }




            var Test_Checked = function(elements) {
                for(var i=0; i<elements.length; i++) {
                    if(elements[i].className.indexOf('Checked')){
                        return true;
                    }
                }
                return false;
            }
            var Test_Not_Checked = function(elements) {
                for(var i=0; i<elements.length; i++) {
                    if(elements[i].className.indexOf('Checked')){
                        return false;
                    }
                }
                return true;
            }

            var Test_Exists = function(elements) {
                if(elements && elements.length > 0){
                    return true;
                }
                return false;
            }

            var Test_Not_Exists = function(elements) {
                if(elements && elements.length > 0){
                    return false;
                }
                return true;
            }







            //ARRAY HELPER FUNCTION
            var Remove_Matches = function(to_remove_from, to_match) {
                //iterate through to_remove_from, see if theres a match in to_match
                    //if theres no match then add to filtered list 
                if(!to_remove_from || !to_match) {
                    return -1;
                }
                if(!isArray(to_remove_from) || !isArray(to_match)){
                    return -1;
                }
                var return_array = new array();
                for(var i=0; i<to_remove_from.length; i++) {

                    if(!to_match.indexOf(to_remove_from[i])) {
                        return_array.push(to_remove_from[i]);
                    }
                }
                return return_array;
            }
            
            //STRING HELPER FUNCTION
            var Get_Attribute_Class = function(leading_subject) {
                if(!leading_subject || typeof leading_subject != 'string') {
                    return -1;
                }
                var attribute_class = null;
                var classes = leading_subject.className.split(' ');

                for(var i=0; i<classes.length; i++) {
                    if(classes[i].indexOf('attribute_')){
                        attribute_class = classes[i];
                        break;
                    }
                }
                return attribute_class;
            }

            //DOM TRAVERSAL HELPER
            var Get_Top_Element = function(leading_subject, top_delimiter) {
                if(!leading_subject || typeof leading_subject != 'string') {
                    return -1;
                }
                if(!top_delimiter || typeof top_delimiter != 'string') {
                    return -1;
                }
                var tag = leading_subject.tagName;
                var top_element = null;

                while(tagName != top_delimiter) {
                    if(top_element == document.body) {
                        return null;
                    }
                    top_element = leading_subject.parentNode;
                }
                return top_element;
            }


               










    function removeAll_NewEntry_Emails() {
        var element_blocks = document.getElementsByClassName('New_Entry_Email');
        var i;
        for(i=0; i<element_blocks.length; i++) {
            element_blocks[i].checked = false;
        }
    }


    function checkAll_NewEntry_CheckboxGroup(attribute) {
        var class_string = 'New_Entry_Checkbox_Value_Attribute_'.concat(attribute);

        var checkboxgroup_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_element_blocks.length; i++) {
            checkboxgroup_element_blocks[i].checked = true;
        }
    }

    function removeAll_NewEntry_CheckboxGroup(attribute) {
        var class_string = 'New_Entry_Checkbox_Value_Attribute_'.concat(attribute);

        var checkboxgroup_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_element_blocks.length; i++) {
            checkboxgroup_element_blocks[i].checked = false;
        }
    }

    function checkAll_NewEntry_SafeValues(attribute) {
        
        var class_string = 'New_Entry_Safe_Value_Attribute_'.concat(attribute);



        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);

        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {

            checkboxgroup_safe_element_blocks[i].checked = true;    
        }
    }

    function checkAll_NewEntry_SafeValues_OrChecked(attribute) {
        var class_string = 'New_Entry_Safe_Value_Attribute_'.concat(attribute);

        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {
            var current_name = checkboxgroup_safe_element_blocks[i].name;
            var same_name_elements = document.getElementsByName(current_name);
            var has_checked = false;
            for(j=0; j < same_name_elements; j++) {
                if(same_name_elements[j].checked == true) {
                    has_checked = true;
                    break;
                }
            }
            if(has_checked == false) {
                checkboxgroup_safe_element_blocks[i].checked = true;
            }
        }
    }

    function removeAll_NewEntry_SafeValues(attribute) {
        var class_string = 'New_Entry_Safe_Value_Attribute_'.concat(attribute);

        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {
            checkboxgroup_safe_element_blocks[i].checked = false;
        }
    }
    function removeAll_NewEntry_SafeValues_OrChecked(attribute) {

        var class_string = 'New_Entry_Safe_Value_Attribute_'.concat(attribute);
        var class_string_2 = 'New_Entry_Attribute_'.concat(attribute);

        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {
            checkboxgroup_safe_element_blocks[i].checked = false;
        }

        var other_element_blocks = document.getElementsByClassName(class_string_2);
        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {
            checkboxgroup_safe_element_blocks[i].checked = false;
        }
    }








    function checkAll_ModifyEntry_CheckboxGroup(attribute) {
        var class_string = 'Modify_Entry_Checkbox_Value_Attribute_'.concat(attribute);


        var checkboxgroup_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_element_blocks.length; i++) {
            checkboxgroup_element_blocks[i].checked = true;
        }
    }

    function removeAll_ModifyEntry_CheckboxGroup(attribute) {

        var class_string = 'Modify_Entry_Checkbox_Value_Attribute_'.concat(attribute);

        var checkboxgroup_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<checkboxgroup_element_blocks.length; i++) {
            checkboxgroup_element_blocks[i].checked = false;
        }
    }

    function checkAll_ModifyEntry_SafeValues(attribute) {

        var class_string = 'Modify_Entry_Safe_Value_Attribute_'.concat(attribute);

        var checkboxgroup_safe_element_blocks = document.getElementsByClassName(class_string);

        for(i=0; i<checkboxgroup_safe_element_blocks.length; i++) {

            checkboxgroup_safe_element_blocks[i].checked = true;    
        }
    }

    function checkAll_ModifyEntry_SafeValues_OrChecked(attribute) {
        var class_string = 'Modify_Entry_Safe_Value_Attribute_'.concat(attribute);

        var safeOrChecked_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<safeOrChecked_element_blocks.length; i++) {
            var current_name = safeOrChecked_element_blocks[i].name;
            var same_name_elements = document.getElementsByName(current_name);
            var has_checked = false;
            for(j=0; j < same_name_elements; j++) {
                if(same_name_elements[j].checked == true) {
                    has_checked = true;
                    break;
                }
            }
            if(has_checked == false) {
                safeOrChecked_element_blocks[i].checked = true;
            }
        }
    }

    function removeAll_ModifyEntry_SafeValues(attribute) {
        var class_string = 'Modify_Entry_Safe_Value_Attribute_'.concat(attribute);

        var safe_element_blocks = document.getElementsByClassName(class_string);
        for(i=0; i<safe_element_blocks.length; i++) {
            safe_element_blocks[i].checked = false;
        }
    }

    function removeAll_ModifyEntry_SafeValues_OrChecked(attribute) {
        var class_string = 'Modify_Entry_Safe_Value_Attribute_'.concat(attribute);
        var safe_element_blocks = document.getElementsByClassName(class_string);


        for(i=0; i<safe_element_blocks.length; i++) {
            safe_element_blocks[i].checked = false;
        }

        var class_string_2 = 'Modify_Entry_'.concat(attribute);
        var safe_element_blocks = document.getElementsByClassName(class_string_2);
        for(i=0; i<safe_element_blocks.length; i++) {
            safe_element_blocks[i].checked = false;
        }
    }



    function checkAll_ModifyEntry_Emails() {
        var element_blocks = document.getElementsByClassName('Modify_Entry_Email');
        var i;
        for(i=0; i<element_blocks.length; i++) {
            element_blocks[i].checked = true;
        }
    }

    function removeAll_ModifyEntry_Emails() {
        var element_blocks = document.getElementsByClassName('Modify_Entry_Email');
        var i;
        for(i=0; i<element_blocks.length; i++) {
            element_blocks[i].checked = false;
        }
    }



//FOR FIRST PAGE
    function Test_Upload_Text(){
        var the_text = document.getElementById("attribute_changer_text_to_upload");
        if(the_text.innerHTML == "") {
            document.getElementById("error_printing").innerHTML="Error: No Text Input";
            return;
        }
        else{
            if(the_text.innerHTML[0].length > 1000000000) {
                document.getElementById("error_printing").innerHTML="Error: Text Cannot Exceed 1 Billion Characters";
                return;
            }
            else{
                document.getElementById("text_upload_form").submit();
            }
        }
    }

    function Test_Upload_File(){
        var the_file = document.getElementById("attribute_changer_file_to_upload");
        if(!the_file.files) {
            document.getElementById("error_printing").innerHTML="Error: Not Supported By This Browser";
            return;
        }
        if(!the_file.files[0]) {
            document.getElementById("error_printing").innerHTML="Error: Must Have File Selected";
            return;
        }
        else{
            if(the_file.files[0].size > 1000000000) {
                document.getElementById("error_printing").innerHTML="Error: File Cannot Exceed 1GB";
                return;
            }
            else{
                document.getElementById("file_upload_form").submit();
            }
        }
    }