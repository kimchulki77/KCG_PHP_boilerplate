<?php
/**
 *  Get Config php file
 *  request a config item from javascript/ajax
 *  return json data
 */
// check request with ajax only
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    exit;
}
// include class config
require_once './_kcg_test.php';
// get all constant from class config
$configConstant = new ReflectionClass('Config');
// store as array in var
$configConstantArray = ($configConstant->getConstants());
// safe and private request item
$black_list_constant = 'SERVER|USER|PASSWORD|DATABASE';
$black_list_object = explode('|',$black_list_constant);
// make return jason format
$result = array();
// get action from ajax
$action = $_POST['config_item'];
switch ($action) {
    case '':
        $result["status"]= TRUE;
        foreach ($black_list_object as $index => $value){
            if(array_key_exists($value, $configConstantArray)){
                unset($configConstantArray["$value"]);
            }
        }
        $result["data"]= $configConstantArray;
        $result["msg"]= 'Response 200 OK';
        echo json_encode($result);
        break;
    default:
        // check valid action
        if(array_key_exists($action,$configConstantArray) && in_array($action,$black_list_object)){
            $result["status"]= FALSE;
            $result["data"]= null;
            $result["msg"]= 'Response 201 FAIL';
            echo json_encode($result);
        }else{
            $result["status"]= TRUE;
            $result["data"]= $configConstantArray["$action"];
            $result["msg"]= 'Response 200 OK';
            echo json_encode($result);
        }
        break;
}