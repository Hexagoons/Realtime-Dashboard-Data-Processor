<?php

/**
 * @return string
 */
function app_path()
{
    return __DIR__ . '/../../app';
}

/**
 * @return string
 */
function pages_path()
{
    return __DIR__ . '/../../pages';
}

/**
 * @return string
 */
function public_path()
{
    return __DIR__ . '/../../public';
}

/**
 * @param  int  $role
 *
 * @return bool
 */
function is_role(int $role)
{
    return (isset($_SESSION["user"]["role"])) ? $_SESSION["user"]["role"] == $role : false;
}

function is_logged_in()
{
    return isset($_SESSION["user"]["role"]);
}

/**
 * @param $string
 *
 * @return string
 */
function test_input($string)
{
    $string = trim($string);
    
    return $string;
}

/**
 * @param $array
 *
 * @return array
 */
function reorder_files_array($array) {
    $result = array();
    foreach($array as $i => $value1) {
        foreach($value1 as $ii => $value2) {
            $result[$ii][$i] = $value2;
        }
    }
    
    return $result;
}

/**
 * @param $group
 * @param $msg
 */
function alert($group, $msg)
{
    if(!isset($_SESSION['alerts']))
        $_SESSION['alerts'] = array();
    
    if(!isset($_SESSION['alerts'][$group]))
        $_SESSION['alerts'][$group] = array();
    
    $_SESSION['alerts'][$group][] = htmlspecialchars($msg, ENT_QUOTES);
}

/**
 * @param bool $group
 *
 * @return bool
 */
function isset_alert($group = false)
{
    if(!isset($_SESSION['alerts']))
        return false;
    
    if($group === false) {
        if(count( array_keys($_SESSION['alerts']) ) === 0)
            return false;
    }
    else if(count( array_keys($_SESSION['alerts'][$group]) ) === 0)
        return false;
    
    return true;
}

/**
 *
 */
function show_alerts()
{
    if(isset($_SESSION['alerts'])) {
        $alertGroups = $_SESSION['alerts'];
        
        foreach ($alertGroups as $group => $alerts) {
            foreach ($alerts as $msg) {
                echo "<div class='alert alert-{$group}'>
                          <p class='center-align'>{$msg}</p>
                      </div>";
            }
        }
    
        unset($_SESSION['alerts']);
    }
}

/**
 * @param      $index
 * @param null $item
 * @param null $option
 *
 * @return string
 */
function input_value($index, $item = null, $option = null)
{
    $formCache = $_SESSION['form-cache'][$index] ?? null;
    $value     = $_SESSION['form-cache'][$index] ?? $item[$index] ?? null;
    $value     = htmlspecialchars($value, ENT_QUOTES);
    
    if(!is_null($formCache))
        unset($_SESSION['form-cache'][$index]);
    
    switch ($option) {
        case('price'):
            return "value='".sprintf('%.2f', $value)."'";
        case('textarea'):
            return $value;
        default:
            return "value='{$value}'";
    }
}

/**
 * @param $index
 * @param $itemId
 * @param $array
 * @param $placeholder
 *
 * @return string
 */
function select_options($index, $itemId, $array, $placeholder)
{
    $formCache = $_SESSION['form-cache'][$index] ?? null;
    $id        = $_SESSION['form-cache'][$index] ?? $itemId ?? null;
    $options    = '';
    
    if(!is_null($formCache))
        unset($_SESSION['form-cache'][$index]);
    
    if(is_null($id))
        $options .= "<option selected disabled value=''>".htmlspecialchars($placeholder, ENT_QUOTES)."</option>";
    
    foreach ($array as $option) {
        if($option['id'] == $id)
            $options .= "<option selected value='". htmlspecialchars($option['id'], ENT_QUOTES) ."'>". htmlspecialchars($option['name'], ENT_QUOTES) ."</option>";
        else
            $options .= "<option value='". htmlspecialchars($option['id'], ENT_QUOTES) ."'>". htmlspecialchars($option['name'], ENT_QUOTES) ."</option>";
    }

    return $options;
}

/**
 * @param $db
 * @param $emailAddress
 *
 * @return bool
 */
function is_unique_email($db, $emailAddress)
{
    $emailAddress = database_escape($db, $emailAddress);
    
    return empty(
        database_query($db,"SELECT email FROM users WHERE email = '{$emailAddress}'")
    );
}

function dd($var) {
    var_dump($var);
    die();
}