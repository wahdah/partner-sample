<?php
    require 'config/config.php';
    require 'config/func.php';
	// Create a stream

    if(!empty($_POST)){
        $data = $_POST;
        $postOpts = postReq($token, $data);

    	$postContext = stream_context_create($postOpts);

    	$file = file_get_contents('https://api.wahdah.my/partner/sales.json', false, $postContext);

        header("Location: payment.php");
    }

    if(!empty($_GET)){
    	$getOpts = getReq($token);

    	$context = stream_context_create($getOpts);

        $partnerVehicleId = $_GET['partnerVehicleId'];


    	// Open the file using the HTTP headers set above
    	$vehicle = file_get_contents('https://api.wahdah.my/partner/vehicles/'.$partnerVehicleId.'.json', false, $context);

    	$vehicle_data = json_decode($vehicle, true);

        // ------------------------------------------------------------------

        $pickupLocation = $_GET['pickupLocation'];
        $returnLocation = $_GET['returnLocation'];

        $pickupTime = date("H:i:s", strtotime($_GET['pickupTime']));
        $returnTime = date("H:i:s", strtotime($_GET['returnTime']));

        $pickupDate = new DateTime($_GET['pickupDate'] . 'T' . $pickupTime);
        $returnDate = new DateTime($_GET['returnDate'] . 'T' . $returnTime);

        $interval = $pickupDate->diff($returnDate);

        $days = $interval->d;

        if($days > 0 && $interval->h > 4){
            $days = $days + 1;
        }else if($days == 0){
            $days = 1;
        }

        $oRate[1] = $vehicle_data['vehiclePartner']['o_rate_1'];
        $oRate[2] = $vehicle_data['vehiclePartner']['o_rate_2'];
        $oRate[3] = $vehicle_data['vehiclePartner']['o_rate_3'];
        $oRate[4] = $vehicle_data['vehiclePartner']['o_rate_4'];
        $oRate[5] = $vehicle_data['vehiclePartner']['o_rate_5'];

        $rental_amount = calculate_rental_amount($days, $oRate);


        // additional rate
        //------------------------------------------------------
        $pickupReturnFee = 10;
        $rounding = 0;
        $addOnAmount = 0;
        $depositAmount = 300;
        $discountAmount = 0;
        $taxAmount = 0;
        //------------------------------------------------------


    }


    include 'templates/default/header.html';
    include 'templates/sales/book.html';
    include 'templates/default/footer.html';
?>
