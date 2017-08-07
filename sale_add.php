<?php
    require 'config/config.php';
    require 'config/func.php';

    if(!empty($_POST)){
        $reqHead = getReq($token);

    	$context = stream_context_create($reqHead);

    	// Open the file using the HTTP headers set above
    	$vehicles = file_get_contents('https://api.wahdah.my/partner/vehicles.json', false, $context);

    	$vehicle_data = json_decode($vehicles, true);

        //------------- calculate the price --------

        $pickupLocation = $_POST['pickup_location'];
        $returnLocation = $_POST['return_location'];

        $pickupTime = date("H:i:s", strtotime($_POST['pickup_time']));
        $returnTime = date("H:i:s", strtotime($_POST['return_time']));

        $pickupDate = new DateTime($_POST['pickup_date'] . 'T' . $pickupTime);
        $returnDate = new DateTime($_POST['return_date'] . 'T' . $returnTime);

        $interval = $pickupDate->diff($returnDate);

        $days = $interval->d;

        if($days > 0 && $interval->h > 4){
            $days = $days + 1;
        }else if($days == 0){
            $days = 1;
        }

        $v =array();
        $i = 0;
        foreach($vehicle_data['vehicles'] as $v){
            $oRate[1] = $v['o_rate_1'];
            $oRate[2] = $v['o_rate_2'];
            $oRate[3] = $v['o_rate_3'];
            $oRate[4] = $v['o_rate_4'];
            $oRate[5] = $v['o_rate_5'];

            $rental_amount = calculate_rental_amount($days, $oRate);
            $vehicle_data['vehicles'][$i]['rental_amount'] = $rental_amount;
            $vehicle_data['vehicles'][$i]['days'] = $days;

            $i++;
        }
    }

    include 'templates/default/header.html';
    include 'templates/sales/add.html';
    include 'templates/default/footer.html';
?>
