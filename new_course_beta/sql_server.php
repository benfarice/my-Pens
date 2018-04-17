<?php
		$error="";
		/* --------------------Begin transaction---------------------- */
		if ( sqlsrv_begin_transaction( $con ) === false ) {
			$error="Erreur : ".sqlsrv_errors() . " <br/> ";
		}

//echo $Code;RETURN;
$reqInser1 = "INSERT INTO chauffeurs (Matricule,nom,CIN,Date_Emb) values 	(?,?,?,?)";

		$params1= array(
		$_POST['Matricule'],
		addslashes(mb_strtolower(securite_bdd($_POST['Nom']), 'UTF-8')),
		addslashes(mb_strtolower(securite_bdd($_POST['CIN']), 'UTF-8')),
		$_POST['DateEmb']
		) ;

		$stmt1 = sqlsrv_query( $con, $reqInser1, $params1 );
		if( $stmt1 === false ) {
			$errors = sqlsrv_errors();
			$error.="Erreur : ".$errors[0]['message'] . " <br/> ";
		}
//echo $error."mmmmmmmm";return;
		if( $error=="" ) {
			
			 sqlsrv_commit( $con );
		?>
				
		<?php
		} else {
			 sqlsrv_rollback( $con );
			 echo "<font style='color:red'>".$error."</font>";
		}
		/********************************************************/	

	exit;