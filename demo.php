<?php
$quater = 0;
$month_count=1;
$date = strtotime("2020-03-01");
for($i=0; $i<28; $i++){
    for($j=0; $j<3; $j++){
        if($month_count!=1){
            $date = date("M Y",strtotime("+1 month",$date));
        } else{
            $date = date("M Y",$date);
        }

        if($j==0){
            $first_date = date("Y-m-01",strtotime($date));
            $first_month  = date("M",strtotime($date));
        } elseif($j==2){
            $last_date = date("Y-m-t",strtotime($date));
            $last_month  = date("M",strtotime($date));
        }
        
        $date = strtotime($date);
        $month_count++;
    }
    $quater++;
    echo $quater."---".$first_date."-".$last_date."***********".$first_month."-".$last_month;
    echo nl2br("\n");
    echo nl2br("\n");
    echo nl2br("\n");
}



?>