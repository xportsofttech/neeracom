<?php

@session_start();
require_once '../dbconnect.php';

// initilize all variable
$params = $columns = $totalRecords = $data = array();
$params = $_REQUEST;
//define index of column name
$columns = array(
    0 => 'first_name',
    1 => 'last_name',
    2 => 'email',
    3 => 'company_name',
    4 => 'mobile',
    5 => 'address',
    6 => 'trial_expire',
    7 => 'visibility',
    8 => 'connections_available',
    9 => '',
    10 => ''
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if (!empty($params['search']['value'])) {
    $where .= " WHERE ";
    $where .= " ( first_name LIKE '%" . $params['search']['value'] . "%' ";
    $where .= " OR last_name LIKE '%" . $params['search']['value'] . "%' ";
    $where .= " OR email LIKE '%" . $params['search']['value'] . "%' ";
    $where .= " OR mobile LIKE '%" . $params['search']['value'] . "%' ";
    $where .= " OR address LIKE '%" . $params['search']['value'] . "%' ";
    $where .= " OR trial_expire LIKE '%" . $params['search']['value'] . "%' ";
    $where .= " OR company_name LIKE '%" . $params['search']['value'] . "%' )";
}

// getting total number records without any search
$sql = "SELECT * FROM user where deleted='0'";
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
$sr = 1;
while ($row = mysqli_fetch_assoc($queryRecords)) {
    $nestedData = array();
    $nestedData[] = $row['id'];
    $nestedData[] = $row['first_name'] . " " . $row['last_name'];
    $nestedData[] = $row['email'];
    $nestedData[] = $row['company_name'];
    $nestedData[] = $row['mobile'];
    $nestedData[] = $row['address'];
    $date1 = strtotime($row['trial_expire']);
    $date = date('d M Y', $date1);
    $nestedData[] = $date;
    $nestedData[] = $row['connections_available'];
    if ($row['visibility'] == '1') {
        $vis = "<a href='#' onclick='changeVisibility(" . $row['id'] . "," . $row['visibility'] . ")'>Visible</a>";
    } else {
        $vis = "<a href='#' onclick='changeVisibility(" . $row['id'] . "," . $row['visibility'] . ")'>Hidden</a>";
    }
    $nestedData[] = $vis;
    $nestedData[] = "<a href='adm_update_user.php?uid=" . $row['id'] . "'><i class='fa fa-pencil'></i></a>";
    $nestedData[] = "<a href='#' onclick='deleteUser(" . $row['id'] . ")'><i class='fa fa-trash'></i></a>";
    $data[] = $nestedData;
    $countNum++;
    $sr++;
}

$json_data = array(
    "draw" => intval($params['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>