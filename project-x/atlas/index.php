<?php
	session_start();
	/*
	session_start();
	//print_r($_SESSION);
	if(isset($_SESSION['username'])){
		header('Location: dashboard.php');
			// Redirect To Dashboard Page
		exit();
	}
	*/
	$_SESSION['Lang'] = 'ar';
	$lang = 'includes/languages/';
	include_once $lang.$_SESSION['Lang'].'.php';
	//include_once $lang.'arabic.php';
	$noNavbar = '';
	$pagetitle = lang('login_to_app');
	include 'init.php';
	
	$count__Check_User=-1;


	// Check if User Coming From HTTP Post Request
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$username = $_POST['user'];
		$password = $_POST['pass'];
		
		//*******************
		//$hashedPass = sha1($password);
		$hashedPass = $password;
		//*******************

		//echo $hashedPass;
		// Check if the user exist in database
		
		/*
		$stmt = $con->prepare("select username,password from users where username = ? and password = ? and GroupID = 1");
		$stmt->execute(array($username,$hashedPass));
		$count = $stmt->rowCount();
		*/
		$query_Check_User = "select login,pwd from UTILISATEUR where login = '$username' and 
		pwd = '$hashedPass' and etat_utilisateur = 1";
		$params__Check_User = array();
		$options__Check_User =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		$stmt_Check_User=sqlsrv_query($con,$query_Check_User,$params__Check_User,$options__Check_User);
		$count__Check_User = sqlsrv_num_rows($stmt_Check_User);

		//echo $query_Check_User;
		//echo $count;
		// If count > 0 this mean the database contain record about this username
		
	}
		if($count__Check_User > 0){
			//echo "welcome ".$username ;
			$_SESSION['username'] = $username;
			// Register Session name
			header('Location: dashboard.php');
			// Redirect To Dashboard Page
			exit();
		}
?>
	<!--Welcome to Index-->
<div class="container-fluid">
	<div class="row outer-background">
	
	<div class="col-md-2 col-sm-2 col-xs-2"></div>
	<div class="col-md-8 col-sm-8 col-xs-8">
		<div class="row jumbotron">
			<div class="col-md-2 col-sm-2 col-xs-2">
			</div>
			<div class="col-md-5 col-sm-5 col-xs-5 text-center">
				<h1><?php echo lang('app_title'); ?></h1>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4">

			<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="150px" height="150px"
			 viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999;" xml:space="preserve">
			<path style="fill:#FEDBAB;" d="M143.356,252.697c-28.169-28.242-45.217-58.648-45.217-101.159c0-36.073,14.037-69.979,39.561-95.489
				l0,0c33.173-33.217,84.836-40.337,125.803-17.275c7.121,4,9.729,12.967,5.861,20.161L167.209,249.194
				C162.401,258.126,150.442,259.783,143.356,252.697z"/>
			<path style="fill:#FEC478;" d="M360.473,413.797c-42.133,0-72.912-16.911-101.188-45.188c-7.092-7.061-5.38-19.065,3.517-23.824
				l190.274-102.126c7.18-3.883,16.147-1.274,20.161,5.847c23.033,40.88,15.942,92.617-17.26,125.818
				C429.661,400.64,395.081,413.797,360.473,413.797z"/>
			<path style="fill:#FF9923;" d="M445.434,300.017c-73.205,73.205-251.288,93.422-303.912,84.51c-4.032-0.211-7.214-1.696-9.76-4.243
				c-2.337-2.337-4.032-5.728-4.458-9.549c-0.636-8.274-6.94-212.458,84.512-303.91c21.432-21.432,47.742-36.707,75.537-47.107
				l28.435,18.251l127.949,127.949l48.802,58.561C482.141,252.276,466.652,278.799,445.434,300.017z"/>
			<path style="fill:#FE8821;" d="M445.434,300.017c-73.205,73.205-251.288,93.422-303.912,84.51c-4.032-0.211-7.214-1.696-9.76-4.243
				l263.172-263.172l48.804,48.804l48.802,58.561C482.141,252.276,466.652,278.799,445.434,300.017z"/>
			<path style="fill:#FEDBAB;" d="M183.535,433.329c0.211-26.947-10.186-53.895-30.555-74.264c-39.679-39.679-108.852-39.679-148.53,0
				c-7.426,7.426-5.093,19.521,3.818,24.187c25.888,13.156,49.654,30.134,70.023,50.503c20.369,20.369,37.344,44.132,50.501,70.02
				c4.878,9.123,16.976,11.034,24.189,3.821C173.351,487.226,183.748,460.278,183.535,433.329z"/>
			<path style="fill:#FEC478;" d="M183.535,433.329c0.212,26.948-10.185,53.896-30.555,74.266c-7.214,7.214-19.311,5.303-24.189-3.821
				c-13.156-25.888-30.132-49.651-50.501-70.02l74.69-74.69C173.35,379.435,183.747,406.384,183.535,433.329z"/>
			<path style="fill:#FEDBAB;" d="M509.302,81.677c-2.761-16.763-14.215-29.917-30.341-35.433c-3.609-1.063-7.641-1.699-11.248-1.91
				c-0.426-3.822-1.059-7.426-2.123-11.46c-5.304-15.914-18.673-27.583-35.224-30.132c-38.827-6.574-96.966-1.482-143.014,16.975
				c-17.184,55.384-2.334,120.311,41.378,163.598c41.376,41.376,103.334,59.199,163.807,41.163
				C510.363,176.949,515.88,124.752,509.302,81.677z"/>
			<path style="fill:#FEC478;" d="M492.539,224.478c-60.474,18.037-122.433,0.212-163.807-41.163L467.714,44.333
				c3.607,0.211,7.638,0.847,11.248,1.91c16.126,5.517,27.58,18.67,30.341,35.433C515.88,124.752,510.363,176.949,492.539,224.478z"/>
			<circle style="fill:#57555C;" cx="378.467" cy="67.976" r="15.004"/>
			<path style="fill:#FEDBAB;" d="M251.92,276.253l-16.126-16.126c-23.765-23.765-14.216-63.867,17.612-74.476l17.4-5.942
				c8.061-2.549,16.548,1.696,18.882,9.547c2.759,7.851-1.482,16.34-9.336,19.096l-17.612,5.728
				c-10.608,3.397-13.792,16.765-5.728,24.828l16.126,16.126c7.851,7.851,21.432,4.88,24.825-5.73l5.73-17.61
				c2.756-7.853,11.245-12.095,19.096-9.336c7.851,2.334,12.096,10.822,9.547,18.882l-5.942,17.4
				C315.787,290.469,275.472,299.805,251.92,276.253z"/>
			<path style="fill:#FEC478;" d="M251.92,276.253l-8.063-8.063l21.218-21.218l8.063,8.063c7.851,7.851,21.432,4.88,24.825-5.73
				l5.73-17.61c2.756-7.853,11.245-12.095,19.096-9.336c7.851,2.334,12.096,10.822,9.547,18.882l-5.942,17.4
				C315.787,290.469,275.472,299.805,251.92,276.253z"/>

			</svg>





			</div>
			<div class="col-md-1 col-sm-1 col-xs-1">
			</div>
		</div>
		<div class="row inner-background">
		<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>"
		method="POST">
		<h4 class="text-center"><?php echo lang('intro_user'); ?></h4>
 
 		<div class="input-group input-group-lg">
		  <span class="input-group-addon" id="sizing-addon1">

		  	<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px"
				 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
				<path style="fill:#518EF8;" d="M454.531,52.245L256,0L57.469,52.245c0,0-49.633,370.939,198.531,459.755
					C504.163,423.184,454.531,52.245,454.531,52.245z"/>
				<path style="fill:#73AAF9;" d="M256,0v512C7.837,423.184,57.469,52.245,57.469,52.245L256,0z"/>
				<circle style="fill:#3A5BBC;" cx="256" cy="242.939" r="141.061"/>
				<path style="fill:#F2F2F2;" d="M256,126.485c30.354,0,54.962,24.607,54.962,54.962S286.354,236.408,256,236.408
						s-54.975-24.607-54.975-54.962S225.646,126.485,256,126.485z"/>
				<path style="fill:#F2F2F2;" d="M365.192,332.238C339.331,363.833,300.016,384,256,384c-44.029,0-83.331-20.167-109.192-51.762
						c16.875-43.833,59.402-74.932,109.192-74.932S348.317,288.405,365.192,332.238z"/>

			</svg>

		  </span>
		  <input type="text" class="form-control" name="user"  
		  placeholder="<?php echo lang('username'); ?>" aria-describedby="sizing-addon1" autocomplete="off">
		</div>


		<div class="input-group input-group-lg">
		  <span class="input-group-addon" id="sizing-addon1">
		  	
		  	<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px"
			 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
			<circle style="fill:#273B7A;" cx="256" cy="256" r="256"/>
			<path style="fill:#121149;" d="M512,256c0-7.982-0.384-15.87-1.098-23.666L350.594,72.027l-14.489,58.515v-7.856l-171.911,44.497
				l46.282,46.28l5.875,40.239l44.229,27.583l-3.644,29.018L256,444.48l60.347,60.347C428.606,477.696,512,376.596,512,256z
				 M286.525,330.771l-0.212-0.212h0.266L286.525,330.771z"/>
			<path style="fill:#FFFFFF;" d="M305.131,377.82c5.236,0,9.481-4.246,9.481-9.481s-4.246-9.481-9.481-9.481h-39.65v-74.867
				c27.826-4.546,49.131-28.743,49.131-57.835c0-32.32-26.293-58.613-58.613-58.613s-58.613,26.293-58.613,58.613
				c0,29.091,21.306,53.288,49.131,57.835v150.973c0,5.236,4.246,9.481,9.481,9.481c5.236,0,9.481-4.246,9.481-9.481v-8.011h39.65
				c5.236,0,9.481-4.246,9.481-9.481s-4.246-9.481-9.481-9.481h-39.65v-5.603h15.084c5.236,0,9.481-4.246,9.481-9.481
				s-4.246-9.481-9.481-9.481h-15.084v-5.603H305.131L305.131,377.82z M216.35,226.156c0-21.864,17.786-39.65,39.65-39.65
				s39.65,17.786,39.65,39.65s-17.786,39.65-39.65,39.65S216.35,248.018,216.35,226.156z"/>
			<path style="fill:#D0D1D3;" d="M305.131,377.82c5.236,0,9.481-4.246,9.481-9.481s-4.246-9.481-9.481-9.481h-39.65v-74.867
				c27.826-4.546,49.131-28.743,49.131-57.835c0-32.32-26.293-58.613-58.613-58.613c-0.193,0-0.383,0.012-0.574,0.014v18.963
				c0.191-0.003,0.381-0.014,0.574-0.014c21.864,0,39.65,17.786,39.65,39.65s-17.786,39.65-39.65,39.65
				c-0.193,0-0.383-0.012-0.574-0.014v178.626c0.191,0.012,0.381,0.029,0.574,0.029c5.236,0,9.481-4.246,9.481-9.481v-8.011h39.65
				c5.236,0,9.481-4.246,9.481-9.481s-4.246-9.481-9.481-9.481h-39.65v-5.603h15.084c5.236,0,9.481-4.246,9.481-9.481
				s-4.246-9.481-9.481-9.481h-15.084v-5.603h39.65V377.82z"/>
			<path style="fill:#FFC61B;" d="M341.342,68.73H170.658c-8.089,0-14.645,6.556-14.645,14.645v70.682
				c0,8.089,6.556,14.645,14.645,14.645h65.345l13.955,26.15c2.579,4.832,9.506,4.832,12.083,0l13.957-26.15h65.345
				c8.089,0,14.645-6.556,14.645-14.645V83.375C355.987,75.286,349.431,68.73,341.342,68.73z"/>
			<path style="fill:#EAA22F;" d="M341.342,68.73h-85.916v129.708c2.56,0.21,5.222-0.976,6.616-3.587l13.955-26.15h65.345
				c8.089,0,14.645-6.556,14.645-14.645V83.375C355.987,75.286,349.431,68.73,341.342,68.73z"/>
			<path style="fill:#FFEDB5;" d="M312.889,119.811H199.111c-3.332,0-6.034-2.701-6.034-6.034c0-3.332,2.701-6.034,6.034-6.034h113.778
				c3.332,0,6.034,2.701,6.034,6.034C318.923,117.11,316.221,119.811,312.889,119.811z"/>
			<path style="fill:#FEE187;" d="M312.889,107.744h-57.463v12.067h57.463c3.332,0,6.034-2.701,6.034-6.034
				C318.923,110.445,316.221,107.744,312.889,107.744z"/>

			</svg>




		  </span>
		  <input type="password" class="form-control" name="pass" 
		  placeholder="<?php  echo lang('user_password'); ?>" aria-describedby="sizing-addon1" autocomplete="off" autocomplete="new-password" >
		</div>
		<div class="alert alert-warning text-right" role="alert" id="login_error">
		  <?php echo lang('login_error'); ?>
		</div>
		<input class="btn btn-primary btn-block btn-lg" type="submit" name="" 
		value="<?php echo lang('login'); ?>">
		
	</form>
	</div>
	</div>
	<div class="col-md-2 col-sm-2 col-xs-2"></div>

	</div>
</div>
	

<?php
		if($count__Check_User == 0){
			?>
			<script type="text/javascript">
				//$('#login_error').show();
			
				document.getElementById("login_error").style.display = "block";
				//alert("error");
			</script>
			<?php
		}
	//echo lang('Message').' '.lang('Admin');
	include $tpl ."footer.php";
?>