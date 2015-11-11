<?php

	//loob AB'i �henduse
	
	require_once("../config_global.php");
	$database = "if15_mkoinc_3";
	
	//tekitatakse sessioon mis hoitakse serveris,
	//k�ik session muutujad on k�ttesaadavad kuni viimase brauseriakna sulgemiseni
	session_start();
	
	
	//t�tab andmed ja sisetab ab'i
	//v�tame vastu kaks muutujat
	
	function createUser($name, $surename, $mail, $hash){
		$mysqli = new mysqli($GLOBALS["servername"],$GLOBALS["server_username"],$GLOBALS["server_password"],$GLOBALS ["database"]);
		
		$stmt = $mysqli -> prepare("INSERT INTO users (name, surename, email, password) VALUES(?, ?, ?, ?)");
		
				$stmt ->bind_param("ssss", $name, $surename, $mail, $hash);
				$stmt ->execute();
				$stmt ->close();
		
		$mysqli->close();
	}
	
	function loginUser($email, $hash){
		$mysqli = new mysqli($GLOBALS["servername"],$GLOBALS["server_username"],$GLOBALS["server_password"],$GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT id, email FROM users WHERE email=? AND password=?");
		$stmt->bind_param("ss", $email, $hash);
		$stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute(); 
		if($stmt->fetch()){
					//ab'i oli midagi
					echo "Email ja parool �iged, kasutaja id=".$id_from_db;
					
					//tekitan sessiooni muutujad
					$_SESSION["logged_in_user_id"] = $id_from_db;
					$_SESSION["logged_in_user_email"] = $id_from_db;
					
					//suunan data.php lehele
					header("Location: data.php");

					
				}else{
					//ei leitud
					echo "Wrong credentials!";
				}
		
	$stmt->close();
		
		$mysqli->close();
	}
	
	
	function addClient($product, $product_material) {
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO addClient (user_id, product, product_material) VALUES (?,?,?)");
		$stmt->bind_param("iss", $_SESSION["logged_in_user_id"], $product, $product_material);
		
		//s�num
		$message = "";
		
		if($stmt->execute()){
			// kui on t�ene,
			//siis INSERT �nnestus
			$message = "Sai edukalt lisatud";
			 
			
		}else{
			// kui on v��rtus FALSE
			// siis kuvame errori
			echo $stmt->error;
			
		}
		
		return $message;
		
		
		$stmt->close();
		
		$mysqli->close();
		
		
	}
	
?>