<?php

@session_start();
require_once '../dbconnect.php';

// initilize all variable
$params = $columns = $totalRecords = $data = array();
$params = $_REQUEST;
//define index of column name
$columns = array(
    0 => 'Activities',
    1 => 'request',
    2 => 'response',
    3 => 'date_created',
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if (!empty($params['search']['value'])) {
    $where .= " WHERE ";
    $where .= " ( Activities LIKE '%" . $params['search']['value'] . "%' ";
    $where .= " OR request LIKE '%" . $params['search']['value'] . "%' ";
    $where .= " OR response LIKE '%" . $params['search']['value'] . "%' ";
    $where .= " OR date_created LIKE '%" . $params['search']['value'] . "%' )";
}

// getting total number records without any search
$sql = "SELECT * FROM Verify";
$sqlTot .= $sql;
$sqlRec .= $sql;

//concatenate search sql if value exist
if (isset($where)) {

    $sqlTot .= $where;
    $sqlRec .= $where;
}


$sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

$queryTot = mysqli_query($conn, $sqlTot) or die("database error:" . mysqli_error($conn));

$totalRecords = mysqli_num_rows($queryTot);

$queryRecords = mysqli_query($conn, $sqlRec) or die("error to fetch users data");
$countNum = 1;
while ($row = mysqli_fetch_assoc($queryRecords)) {
    $nestedData = array();
    $nestedData[] = $row['Activities'];
    $nestedData[] = str_replace(",",", ",$row['request']);
    if ($row['send_email']) {
        $nestedData[] = "<a class='response' onclick=getModelPdf('" . $row['pdf_name'] . "')>" . $row['response'] . "</a>";
    } else {
    	
        $nestedData[] = str_replace(",",", ",$row['response']);
    }
    $nestedData[] = $row['ip'];
    $nestedData[] = $row['date_created'];
    $data[] = $nestedData;
    $countNum++;
}

$json_data = array(
    "draw" => intval($params['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>