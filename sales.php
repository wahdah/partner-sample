<?php
    require 'config/config.php';
    require 'config/func.php';

    $reqHead = getReq($token);

	$context = stream_context_create($reqHead);

	// Open the file using the HTTP headers set above
	$saleList = file_get_contents('https://api.wahdah.my/partner/sales.json', false, $context);

	// print_r($file);exit;

	$data = json_decode($saleList, true);

	// print_r($data);exit;

    include 'templates/default/header.html';
    include 'templates/sales/index.html';
    include 'templates/default/footer.html';
?>
