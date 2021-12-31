<?php

	$email= "trolleryhz@gmail.com";  /*****ENTER_FROM_EMAIL_ADDRESS*****/
	$name= "AMC commerce";				/*****ENTER_A_NAME*****/
	$body= $_POST["body"];
	$subject= $_POST["subject"];


	$headers= array(

			'Authorization: Bearer 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',  /*****ENTER_YOUR_API_KEY*****/
			'Content-Type: application/json'
	);



	$data = array(

			"personalizations" => array(

				array(

					"to" =>array(

						array(

							"email" =>"hztrollery@gmail.com", /*****ENTER_TO_EMAIL_ADDRESS*****/
							"name"  => $name
						)
					)
				)

			),


			"from" => array(

				"email"=> $email
			),


			"subject" =>$subject,
			"content" =>array(

					array(

						"type" => "text/html",
						"value" => $body
					)
			)


	);


	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$response = curl_exec($ch);

	curl_close($ch);

	echo $response;

    




?>

