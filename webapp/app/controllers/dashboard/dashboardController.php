<?php
function overview()
{
    $db = database_connect();

    $sql = 'SELECT stn, name, country, latitude, longitude FROM stations WHERE latitude >= 60';

    if (isset($_GET['query'])) {
        $query = database_escape($db, $_GET['query']);
        $sql .= " AND (stn LIKE '%{$query}%' OR country LIKE '%{$query}%' OR name LIKE '%{$query}%' OR latitude LIKE '%{$query}%' OR longitude LIKE '%{$query}%')";
    }

    $sql .= ' ORDER BY country';

    $stations = database_paginate($db, $sql, $_GET['page'] ?? 1);
    $mapStations = database_query($db, $sql);

    require_page('dashboard.dashboard', [
        "stations" => $stations['result'],
        "mapStations" => $mapStations,
        "pageCount" => $stations['pageCount'],
    ]);
}

function top10()
{
    if (!isset($_GET['category']) && in_array($_GET['category'], ['temp', 'sndp', 'wdsp'])) {
        return route_link('/dashboard');
    }
    
    $top10 = exec('export PATH=$PATH:'.APP['node_path'].'; node '.PATH.'/nodeJS/top10.js ' . $_GET['category']);
    $top10 = json_decode($top10, true);
    
    $db = database_connect();
    $list = '(' . implode(',', array_keys($top10)) . ')';
    $sql = "SELECT stn, name, country, latitude, longitude FROM stations WHERE stn IN {$list} ORDER BY ";
    
    $orderBy = [];
    foreach (array_keys($top10) as $stn) {
        $orderBy[] = "stn = {$stn} DESC";
    }

    $sql .= implode(',', $orderBy);
    
    $stations = database_paginate($db, $sql, $_GET['page'] ?? 1);
    $mapStations = database_query($db, $sql);
    
    $category = null;
    switch ($_GET['category']) {
        case 'temp':
            $category = 'Temperature';
            break;
        case 'sndp':
            $category = 'Snow drop';
            break;
        case 'wdsp':
            $category = 'Wind speed';
            break;
    }
    
    require_page('dashboard.dashboard', [
        "stations" => $stations['result'],
        "top10" => $top10,
        "category" => $category,
        "mapStations" => $mapStations,
        "pageCount" => $stations['pageCount'],
    ]);
}

function show()
{
    if (!isset($_GET['id'])) {
        return route_link('/dashboard');
    }

    $db = database_connect();

    $id = database_escape($db, $_GET['id']);

    $sql = 'SELECT stn, name, country, latitude, longitude FROM stations WHERE stn = ' . $id;

    $station = database_query($db, $sql);

    if (count($station) !== 1) {
        return route_link('/dashboard');
    }

    $station = $station[0];

    require_page('dashboard.station', [
        "station" => $station
    ]);
}

function export()
{
    if(!(is_role(RESEARCHER) || is_role(ADMIN))) {
        return route_link('/dashboard');
    }
    
    if (!isset($_GET['id'])) {
        return route_link('/dashboard');
    }

    $db = database_connect();

    $id = database_escape($db, $_GET['id']);

    $sql = 'SELECT stn, name, country, latitude, longitude FROM stations WHERE stn = ' . $id;

    $station = database_query($db, $sql);

    if (count($station) !== 1) {
        return route_link('/dashboard.export');
    }

    $station = $station[0];

    //$id = $_GET['id'];
    require_page('dashboard.export', [
        "id" => $id,
        "station" => $station
    ]);
}

function xml()
{
    if (!isset($_GET['id']) && is_numeric($_GET['id'])) {
        return route_link('/dashboard.export');
    }
    
    $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $_GET['from'] . ' ' . $_GET['from-time']);
    $till = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $_GET['till'] . ' ' . $_GET['till-time']);
    $now = \Carbon\Carbon::now();
    $id = $_GET['id'];
    
    if ($from->greaterThan($till)) {
        alert('error', 'Start date higher than end date.');
        return route_link('/dashboard.export') . '?id=' . $id;
    }
    
    if($from->greaterThan($now) || $till->greaterThan($now)) {
        alert('error', 'Date can\'t lie in the future');
        return route_link('/dashboard.export') . '?id=' . $id;
    }

    $export = exec('export PATH=$PATH:'.APP['node_path'].'; node '.PATH."/nodeJS/export.js {$id} {$from->format('Y-m-d')} {$from->format('H:i')} {$till->format('Y-m-d')} {$till->format('H:i')}");
    $measurements = json_decode($export, true);
    
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"{$from->format('Y-m-d')}_tm_{$till->format('Y-m-d')}.xml\"");
    require_page('dashboard.xml.export', [
        "measurements" => $measurements
    ]);
}