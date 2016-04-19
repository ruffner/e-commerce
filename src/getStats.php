<?php

header('Content-Type: application/json');

require 'defs.php';

if( !isset($_POST['onlyShipped']) ){
    echo json_encode( Array("result" => WRONG_PARAMS) );
	exit;
}

require 'connect.php';

if( isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == True) {
  $db = $_SESSION['db'];
  
  // only managers can access sales stats
  if($_SESSION['user']['isManager'] ) {
    
    $now = date('Y-m-j');

    $oy = strtotime ( '-1 year' , strtotime ( $now ) );
    $om = strtotime ( '-1 month' , strtotime ( $now ) );
    $ow = strtotime ( '-1 week' , strtotime ( $now ) );
    
    $oy = date ( 'Y-m-d H:i:s' , $oy );
    $om = date ( 'Y-m-d H:i:s' , $om );
    $ow = date ( 'Y-m-d H:i:s' , $ow );
    
    $onlyShipped = ($_POST['onlyShipped'] == "true");
    
    $sql_all = "SELECT * FROM (SELECT Orders.status, Orders.odate, Orders.pid, Orders.cid, Item.cost, Item.discount, Item.pname FROM Orders INNER JOIN Item ON Orders.pid=Item.pid) as t1 WHERE t1.status <> 'cart'".($onlyShipped ? " AND t1.status <> 'pending'" : "");
    
    $sql_oy = "SELECT * FROM (SELECT Orders.status, Orders.odate, Orders.pid, Orders.cid, Item.cost, Item.discount, Item.pname FROM Orders INNER JOIN Item ON Orders.pid=Item.pid) as t1 WHERE t1.status <> 'cart'".($onlyShipped ? " AND t1.status <> 'pending'" : "")." AND t1.odate > '".$oy."'";
    
    $sql_om = "SELECT * FROM (SELECT Orders.status, Orders.odate, Orders.pid, Orders.cid, Item.cost, Item.discount, Item.pname FROM Orders INNER JOIN Item ON Orders.pid=Item.pid) as t1 WHERE t1.status <> 'cart'".($onlyShipped ? " AND t1.status <> 'pending'" : "")." AND t1.odate > '".$om."'";
    $sql_ow = "SELECT * FROM (SELECT Orders.status, Orders.odate, Orders.pid, Orders.cid, Item.cost, Item.discount, Item.pname FROM Orders INNER JOIN Item ON Orders.pid=Item.pid) as t1 WHERE t1.status <> 'cart'".($onlyShipped ? " AND t1.status <> 'pending'" : "")." AND t1.odate > '".$ow."'";
    
    $res_all = $db->select($sql_all);
    
    $res_oy = $db->select($sql_oy);
    $res_om = $db->select($sql_om);
    $res_ow = $db->select($sql_ow);
    
    
    // total sales counters
    $ts = 0;
    $yts = 0;
    $mts = 0;
    $wts = 0;
    
    $tot_sql_oy = "SELECT DISTINCT odate FROM Orders WHERE status <> 'cart'".($onlyShipped ? " AND status <> 'pending'" : "")." AND odate > '".$oy."'";;
    $tot_sql_om = "SELECT DISTINCT odate FROM Orders WHERE status <> 'cart'".($onlyShipped ? " AND status <> 'pending'" : "")." AND odate > '".$om."'";;
    $tot_sql_ow = "SELECT DISTINCT odate FROM Orders WHERE status <> 'cart'".($onlyShipped ? " AND status <> 'pending'" : "")." AND odate > '".$ow."'";;
    
    $res_tot_oy = $db->select($tot_sql_oy);
    $res_tot_om = $db->select($tot_sql_om);
    $res_tot_ow = $db->select($tot_sql_ow);
    
    
    
    
    $yto = count($res_tot_oy);
    $mto = count($res_tot_om);
    $wto = count($res_tot_ow);
    
    
    for( $i=0; $i<count($res_oy); $i=$i+1 ) {
      $res_oy[$i]['price'] = $res_oy[$i]['cost'] - $res_oy[$i]['cost']*$res_oy[$i]['discount'];
      $yts = $yts + $res_oy[$i]['price'];
    }
    for( $i=0; $i<count($res_om); $i=$i+1 ) {
      $res_om[$i]['price'] = $res_om[$i]['cost'] - $res_om[$i]['cost']*$res_om[$i]['discount'];
      $mts = $mts + $res_om[$i]['price'];
    }
    for( $i=0; $i<count($res_ow); $i=$i+1 ) {
      $res_ow[$i]['price'] = $res_ow[$i]['cost'] - $res_ow[$i]['cost']*$res_ow[$i]['discount'];
      $wts = $wts + $res_ow[$i]['price'];
    }
    for( $i=0; $i<count($res_all); $i=$i+1 ) {
      //$res_ow[$i]['price'] = $res_ow[$i]['cost'] - $res_ow[$i]['cost']*$res_ow[$i]['discount'];
      $ts = $ts + $res_all[$i]['cost'] - $res_all[$i]['cost']*$res_all[$i]['discount'];
    }
    
    // total # sales div. by total cash of those sales
    $avgCostPerSale_oy = $yts / $yto;
    $avgCostPerSale_om = $mts / $mto;
    $avgCostPerSale_ow = $wts / $wto;
    
    
    
    echo json_encode( Array(
        "result" => "success",
        "total" => count($res_all),
        "year" => $res_oy,
        "numInYear" => count($res_oy),
        "month" => $res_om,
        "numInMonth" => count($res_om),
        "week" => $res_ow,
        "numInWeek" => count($res_ow),
        "avgSaleYear" => $avgCostPerSale_oy,
        "avgSaleMonth" => $avgCostPerSale_om,
        "avgSaleWeek" => $avgCostPerSale_ow,
        "yearSales" => $yts,
        "monthSales" => $mts,
        "weekSales" => $wts,
        "totalSales" => $ts
    ));
    
    exit;
  }

}

?>