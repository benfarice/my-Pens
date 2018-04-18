<?php
	session_start();
	
	$_SESSION['Lang'] = 'fr';
	$lang = 'includes/languages/';
	include_once $lang.$_SESSION['Lang'].'.php';
	//include_once $lang.'arabic.php';
	$noNavbar = '';
	$pagetitle = lang('login_to_app');
	include 'init.php';
	
	$count__Check_User=-1;


	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$username = $_POST['user_camion'];
		$password = $_POST['pass_camion'];
		
		
		$hashedPass = $password;
		
		$query_Check_User = "select login,pwd from UTILISATEUR where login = '$username' and 
		pwd = '$hashedPass'";
		$params__Check_User = array();
		$options__Check_User =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		$stmt_Check_User=sqlsrv_query($con,$query_Check_User,$params__Check_User,$options__Check_User);
		$count__Check_User = sqlsrv_num_rows($stmt_Check_User);

	
		
	}
		if($count__Check_User > 0){
			
			$_SESSION['username_camion'] = $username;
			
			header('Location: dashboard.php');
		
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

			<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="150px" 
				 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
			<g>
				<path style="fill:#EE705C;" d="M506.605,197.101l-76.712-84.383c-3.928-4.317-9.498-6.788-15.342-6.788h-96.759v300.138h44.138
					c0-29.246,23.711-52.966,52.966-52.966c29.255,0,52.966,23.72,52.966,52.966h44.138v-195.01
					C511.999,205.893,510.075,200.914,506.605,197.101"/>
				<path style="fill:#FAC75A;" d="M317.793,300.138H0V78.336c0-14.009,11.361-25.37,25.37-25.37h267.052
					c14.009,0,25.37,11.361,25.37,25.37V300.138z"/>
				<path style="fill:#E6E6E6;" d="M368.275,247.172h143.722V211.05c0-5.155-1.924-10.134-5.394-13.948l-50.776-55.861h-87.552
					c-3.505,0-6.347,2.842-6.347,6.347v93.237C361.928,244.33,364.771,247.172,368.275,247.172"/>
				<g>
					<path style="fill:#5B5B5B;" d="M467.862,406.069c0,29.255-23.711,52.966-52.966,52.966s-52.966-23.711-52.966-52.966
						s23.711-52.966,52.966-52.966S467.862,376.814,467.862,406.069"/>
					<path style="fill:#5B5B5B;" d="M150.069,406.069c0,29.255-23.711,52.966-52.966,52.966s-52.966-23.711-52.966-52.966
						s23.711-52.966,52.966-52.966S150.069,376.814,150.069,406.069"/>
				</g>
				<path style="fill:#C64533;" d="M0,300.138v83.871c0,12.182,9.878,22.06,22.06,22.06h22.078c0-29.255,23.711-52.966,52.966-52.966
					s52.966,23.711,52.966,52.966h167.724V300.138H0z"/>
				<g>
					<path style="fill:#B2B2B2;" d="M114.759,406.069c0,9.754-7.901,17.655-17.655,17.655s-17.655-7.901-17.655-17.655
						s7.901-17.655,17.655-17.655S114.759,396.314,114.759,406.069"/>
					<path style="fill:#B2B2B2;" d="M432.552,406.069c0,9.754-7.901,17.655-17.655,17.655s-17.655-7.901-17.655-17.655
						s7.901-17.655,17.655-17.655S432.552,396.314,432.552,406.069"/>
				</g>
				<path style="fill:#F4A29B;" d="M512,300.138h-52.966c-4.882,0-8.828,3.946-8.828,8.828s3.946,8.828,8.828,8.828H512V300.138z"/>
				<g>
					<path style="fill:#D8AC4E;" d="M132.414,229.517c4.873,0,8.828-3.946,8.828-8.828s-3.955-8.828-8.828-8.828H0v17.655H132.414z"/>
					<path style="fill:#D8AC4E;" d="M97.103,185.379c4.873,0,8.828-3.946,8.828-8.828c0-4.882-3.955-8.828-8.828-8.828H0v17.655H97.103
						z"/>

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
		  <input type="text" class="form-control" name="user_camion"  
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
		  <input type="password" class="form-control" name="pass_camion" 
		  placeholder="<?php  echo lang('user_password'); ?>" aria-describedby="sizing-addon1" autocomplete="off" autocomplete="new-password" >
		</div>
		<div class="alert alert-warning" role="alert" id="login_error">
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
				
			
				document.getElementById("login_error").style.display = "block";
				
			</script>
			<?php
		}

	include $tpl ."footer.php";
?>