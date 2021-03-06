<?php

require_once("utils/_init.php");

$months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

function sortByTime($a, $b){
    $a = strtotime($a['time']);
    $b = strtotime($b['time']);
    return $a - $b;
}

if(verify_get("day", "month", "year")){
    //Finds what weekday the current month started from and calculates how many cells should be left empty in the beginning (offset).
    $day = $_GET["day"];
    $month = $_GET["month"];
    $year = $_GET["year"];
    $jd = gregoriantojd($month,1,$year);
    $offset = (jddayofweek($jd,0)+6)%7;

    $cells = array_fill(1, (6 * 7) + 1 , ["day" => "", "status" => [], "times" => ["11:00", "13:00", "15:00", "17:00"], "slots"=> array_fill(0,4,["booked" => 0, "limit" => 5]) ]);
    $days_in_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);

    for ($cnt = 1; $cnt <= $days_in_month; $cnt++){
        $cells[$cnt + $offset]["day"] = $cnt;
        $postedDates = $postStorage->findMany(function ($post) use($year, $month, $cnt) {
            return $post["date"] === $year . "-" . ($month < 10 ? "0".$month : $month) . "-" . ($cnt < 10 ? "0".$cnt : $cnt); 
        });
        
        foreach($postedDates as $postedDate){
            $cells[$cnt + $offset]["times"][] = $postedDate["time"];
            array_push($cells[$cnt + $offset]["slots"], ["booked" => 0, "limit" => $postedDate["slots"]]);
        }

        for ($slots = 0; $slots <= 3 + count($postedDates); $slots++){
            //Determine how many bookings are made in each date and time
            $cells[$cnt + $offset]["slots"][$slots]["booked"] = count($userStorage->findMany( function ($user) use($auth, $year, $month, $cnt, $cells, $offset, $slots){
                return $auth->compareUserBookingDate($user, $year, $month, $cnt, $cells[$cnt + $offset]["times"][$slots] );
            }));

            //Determine functionality of links on times
            if(strtotime("today") > strtotime($cnt . " " . $months[$month-1] . " " . $year)){
                $cells[$cnt + $offset]["status"][$slots] = "btn-secondary " . ($auth->authorize(["admin"]) ? "" : "disabled"); //inactive
            }
            else if( $cells[$cnt + $offset]["slots"][$slots]["booked"] == $cells[$cnt + $offset]["slots"][$slots]["limit"] ){
                $cells[$cnt + $offset]["status"][$slots] = "btn-danger " . ($auth->authorize(["admin"]) ? "" : "disabled"); //full
            }
            else $cells[$cnt + $offset]["status"][$slots] = "btn-outline-success " . (($auth->is_authenticated() && $auth->authenticated_user()["booking"] !== "") ? "disabled" : ""); //active
        
            if( $auth->is_authenticated() && !isset($found) ){
                if( $auth->compareUserBookingDate($auth->authenticated_user(), $year, $month, $cnt, $cells[$cnt + $offset]["times"][$slots]) ){
                    $cells[$cnt + $offset]["status"][$slots] = "btn-info disabled"; //booked
                    $found = TRUE;
                }
            }
        }
    }
}

?>

<tr>
    <th>Monday</th>
    <th>Tuesday</th>
    <th>Wednesday</th>
    <th>Thursday</th>
    <th>Friday</th>
    <th>Saturday</th>
    <th>Sunday</th>
</tr>
<?php for($x = 1; $x <= 6; $x++) :?>
    <tr>
        <?php for($cnt = 1+(($x-1)*7); $cnt <= 7*$x; $cnt++) :?>
            <?php if($cells[$cnt]["day"] !== "") :?>
                <td><span class="fs-5"><?= $cells[$cnt]["day"] ?></span><br>
                    <?php for($y = 0; $y < count($cells[$cnt]["times"]); $y++) :?>
                        <?php if ($auth->is_authenticated()) $link = "booking.php?time=" . $cells[$cnt]["times"][$y] . "&date=" . $year . "." . ($month < 10 ? "0".$month : $month) . "." . ($cnt-$offset < 10 ? "0".$cnt-$offset : $cnt-$offset);
                            else $link = "login.php";
                        ?>
                        <a class="btn <?= $cells[$cnt]["status"][$y] ?>" href=<?= $link ?> ><?= $cells[$cnt]["times"][$y] . " " . $cells[$cnt]["slots"][$y]["booked"] . "/" . $cells[$cnt]["slots"][$y]["limit"] ?></a>
                        <p></p>
                    <?php endfor; ?>
                </td>
            <?php else :?>
                <td></td>  
            <?php endif; ?>
        <?php endfor; ?>
    </tr>
<?php endfor; ?>