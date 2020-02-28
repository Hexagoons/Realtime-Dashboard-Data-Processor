<?php

/**
 * @return mysqli|null
 */
function database_connect()
{
    $conn = mysqli_connect(DATABASE['host'], DATABASE['username'], DATABASE['password'], DATABASE['name']);
    
    if (!$conn){
        die("Connection failed: " . mysqli_error($conn));
    }
    
    return $conn;
}

/**
 * @param $db
 * @param $item
 *
 * @return array|string
 */
function database_escape($db, $item)
{
    if(is_array($item)) {
        foreach ($item as $index => $value) {
            $item[$index] = database_escape($db, $value);
        }
        
        return $item;
    }
    
    return mysqli_real_escape_string($db,
        is_string($item) ? test_input($item) : $item);
}

/**
 * @param $db
 * @param $query
 *
 * @return array
 */
function database_query($db, $query)
{
    $result = mysqli_query($db, $query);
    $array = [];

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $array[] = array_map(function ($a) { return htmlspecialchars($a, ENT_QUOTES); }, $row);
        }
    }
    
    return $array;
}

/**
 * @param $db
 * @param $query
 *
 * @return int
 */
function database_num_rows($db, $query)
{
    $result = mysqli_query($db, $query);
    
    return mysqli_num_rows($result);
}

/**
 * @param     $db
 * @param     $query
 * @param     $page
 * @param int $limit
 *
 * @return array
 */
function database_paginate($db, $query, $page, $limit = 50)
{
    $page   = (is_numeric($page)) ? $page : 0;
    $page   = ($page !== 0) ? $page - 1 : $page;
    
    $limit  = database_escape($db, $limit);
    $offset = $limit * $page;
    
    $rowCount = database_num_rows($db, $query);
    
    $query .= " LIMIT $limit OFFSET $offset";
    
    return ['result' => database_query($db, $query), 'pageCount' => ceil($rowCount / $limit)];
}

/**
 * @param     $pageCount
 * @param     $page
 *
 * @return string
 */
function paginate_links($pageCount, $page)
{
    if($pageCount <= 1) return '';
    
    $html = '<ul class="pagination">';
    
    if($page - 1 > 0)
        $html .= '<li class="waves-effect"><a href="?page=' . ($page - 1) . '"><i class="material-icons">chevron_left</i></a></li>';
    
    foreach (range(1, $pageCount) as $pageNumber) {
        $class = ($pageNumber == $page) ? 'active bg-primary' : 'waves-effect';
        $searchQuery = $_GET['query'] ?? null;
        $searchQuery = !(is_null($searchQuery)) ? '&query=' . $searchQuery : null;
        
        $html .= "<li class='$class'><a href='?page={$pageNumber}{$searchQuery}'>$pageNumber</a></li>";
    }
    
    if($page < $pageCount)
        $html .=  '<li class="waves-effect"><a href="?page='. ($page + 1) .'"><i class="material-icons">chevron_right</i></a></li>';
    
    $html .= '</ul>';
    
    return $html;
}