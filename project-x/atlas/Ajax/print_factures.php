<?php
session_start();

include "../connect.php";

if(isset($_REQUEST['get_factures'])){
	$searched_date = date('d-m-Y');
	$query_f = "";
	$Type="";
	if(isset($_REQUEST['searched_date'])){
		$searched_date = $_REQUEST['searched_date'];
	}
	if(isset($_REQUEST['seller'])){
		/*
		$query_f = "select Id_Fac IdFac,f.Num_facture as num_f,f.Total as t_f,f.Date_F as f_date from facture_vendeur f
		where cast(f.Date_F as date) = '$searched_date  12:00:00 AM'";
		*/

		$query_f = "select Id_Fac IdFac,f.Num_facture as num_f,f.Total as t_f,f.Date_F as f_date from facture_vendeur f
		where cast(f.Date_F as date) = 	convert(date,('$searched_date'),105)";
		

		$Type="Seller";
	}
	if(isset($_REQUEST['buyer'])){
		$date_s = date_format(date_create_from_format('d/m/Y', $searched_date), 'Y-m-d');
		/*
		$query_f = "select IdFac, f.CodeFac as num_f,f.Montant as t_f ,f.Date as f_date from factures_acht f
		where cast(f.Date as date) = '$date_s'";
		*/

		$query_f = "select IdFac, f.CodeFac as num_f,f.Montant as t_f ,f.Date as f_date from factures_acht f
		where cast(f.Date as date) = 	convert(date,('$searched_date'),105)";
			$Type="Buyer";

	}

//	echo $query_f;
	$result_query_query_f = sqlsrv_query($con,$query_f);
	while($reader_query__query_f = sqlsrv_fetch_array($result_query_query_f, SQLSRV_FETCH_ASSOC)){

		?>
		 <tr>
			      <th scope="row"><?php echo  $reader_query__query_f['num_f'] ?></th>
			      <td><?php echo  $reader_query__query_f['t_f'] ?></td>
			      <td><?php
			      if(is_a($reader_query__query_f['f_date'], 'DateTime')){
			      	echo $reader_query__query_f['f_date']->format('d/m/Y');
			      }else{
			      	echo $reader_query__query_f['f_date'];
			      }


			      ?></td>
			      <td onclick="print_bill_func('<?php echo  $reader_query__query_f["IdFac"] ;?>','<?php echo  $Type; ?>');">
			      		<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								width="50px" height="50px"
								 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">

								<path style="fill:#285680;" d="M5.209,217.071l-0.088-0.088l69.031-86.775c3.354-4.175,8.416-6.609,13.771-6.621h336.154
									c5.355,0.012,10.417,2.446,13.771,6.621l70.091,88.099L5.209,217.071z"/>
								<path style="fill:#4482C3;" d="M512,229.517v194.207c-0.029,9.739-7.916,17.627-17.655,17.655H17.655
									C7.917,441.351,0.029,433.463,0,423.724V229.517c-0.025-4.684,1.856-9.177,5.209-12.447c3.27-3.353,7.762-5.233,12.446-5.208
									h476.69c5.271-0.011,10.266,2.357,13.594,6.444l0.265,0.265C510.675,221.684,512.014,225.544,512,229.517z"/>
								<path style="fill:#35495E;" d="M379.586,397.242h26.483c9.739-0.029,17.627-7.916,17.655-17.655v-26.483
									c-0.029-9.739-7.916-17.627-17.655-17.655H105.931c-9.739,0.029-17.627,7.916-17.655,17.655v26.483
									c0.029,9.739,7.916,17.627,17.655,17.655h26.483"/>
								<path style="fill:#D1D4D1;" d="M256,282.483H185.38c-4.875,0-8.828-3.952-8.828-8.828s3.952-8.828,8.828-8.828H256
									c4.875,0,8.828,3.952,8.828,8.828S260.876,282.483,256,282.483z"/>
								<g>
									<path style="fill:#FFFFFF;" d="M291.311,282.483c-1.153-0.014-2.293-0.254-3.353-0.707c-1.091-0.407-2.083-1.038-2.914-1.854
										c-0.401-0.434-0.756-0.908-1.06-1.414c-0.352-0.449-0.62-0.957-0.793-1.5c-0.268-0.526-0.448-1.093-0.534-1.677
										c-0.1-0.554-0.158-1.114-0.172-1.676c0.031-2.338,0.945-4.577,2.56-6.268c0.829-0.818,1.822-1.451,2.914-1.858
										c3.289-1.375,7.081-0.642,9.621,1.858c1.611,1.693,2.525,3.931,2.56,6.268c-0.013,0.563-0.074,1.124-0.181,1.677
										c-0.079,0.585-0.257,1.152-0.526,1.677c-0.176,0.542-0.445,1.049-0.793,1.5c-0.353,0.53-0.707,0.97-1.06,1.414
										C295.884,281.533,293.647,282.447,291.311,282.483z"/>
									<path style="fill:#FFFFFF;" d="M326.621,282.483c-0.593-0.016-1.183-0.075-1.767-0.177c-0.552-0.098-1.086-0.277-1.586-0.53
										c-0.565-0.194-1.101-0.462-1.595-0.797c-0.44-0.35-0.879-0.703-1.319-1.056c-0.378-0.421-0.732-0.862-1.06-1.323
										c-0.336-0.492-0.603-1.027-0.793-1.591c-0.255-0.501-0.435-1.037-0.535-1.59c-0.096-0.583-0.153-1.172-0.172-1.763
										c0.031-2.338,0.945-4.577,2.56-6.268l1.319-1.06c0.494-0.335,1.03-0.604,1.595-0.797c0.499-0.254,1.034-0.431,1.587-0.526
										c1.14-0.177,2.3-0.177,3.44,0c0.586,0.081,1.154,0.259,1.681,0.526c0.562,0.194,1.095,0.462,1.586,0.797
										c0.44,0.353,0.888,0.707,1.328,1.06c1.611,1.693,2.526,3.931,2.56,6.267c-0.018,0.591-0.078,1.18-0.181,1.763
										c-0.093,0.554-0.27,1.09-0.526,1.59c-0.194,0.562-0.461,1.097-0.793,1.591c-0.353,0.44-0.707,0.879-1.06,1.323
										c-0.44,0.353-0.888,0.707-1.328,1.056c-0.491,0.335-1.024,0.603-1.586,0.797c-0.528,0.266-1.096,0.445-1.681,0.53
										C327.741,282.408,327.182,282.467,326.621,282.483z"/>
								</g>
								<g>
									<path style="fill:#FDD7AD;" d="M379.586,335.448v167.724c0,4.875-3.952,8.828-8.828,8.828H141.242
										c-4.875,0-8.828-3.952-8.828-8.828V335.448"/>
									<path style="fill:#FDD7AD;" d="M379.586,52.966v123.586H132.414V8.828c0.014-4.869,3.958-8.813,8.828-8.828h185.379v52.966
										H379.586z"/>
								</g>
								<g>
									<path style="fill:#7F6E5D;" d="M335.449,432.552H176.552c-4.875,0-8.828-3.952-8.828-8.828s3.952-8.828,8.828-8.828h158.897
										c4.875,0,8.828,3.952,8.828,8.828S340.324,432.552,335.449,432.552z"/>
									<path style="fill:#7F6E5D;" d="M335.449,388.414H176.552c-4.875,0-8.828-3.952-8.828-8.828s3.952-8.828,8.828-8.828h158.897
										c4.875,0,8.828,3.952,8.828,8.828S340.324,388.414,335.449,388.414z"/>
								</g>
								<polygon style="fill:#CBB292;" points="379.586,52.966 326.621,52.966 326.621,0 	"/>

							</svg>
			      </td>

			    </tr>
		<?php

	}
}
