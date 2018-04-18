<?php
    	session_start();
		if(!isset($_SESSION['username_camion'])){
	
		header('Location: index.php');
		exit();
		}
		$lang = 'includes/languages/';
		include_once $lang.$_SESSION['Lang'].'.php';
		//include_once $lang.'arabic.php';
		$pagetitle =  lang('Dashboard');
		
		include 'init.php';

		
		//*********************************
		?>
		<div class="container-fluid">
			<div class="row outer-background">
				<div class="col-md-1 col-sm-1 col-xs-1"></div>
				<div class="col-md-10 col-sm-10 col-xs-10 inner-background">
					<div class="row">
						<!--
						<div class="col-md-4 col-sm-4 col-xs-4">
							
						</div>
						-->
						<div class="col-md-12 col-sm-12 col-xs-12 text-center">
						<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="200px"
							 viewBox="0 0 469.72 469.72" style="enable-background:new 0 0 469.72 469.72;" xml:space="preserve">
						<g>
							<polygon style="fill:#76CDCE;" points="448.865,251.797 308.198,251.797 308.198,146.463 416.865,146.463 	"/>
							<path style="fill:#CCCCCC;" d="M469.72,332.205L469.72,332.205c0,5.945-4.82,10.765-10.765,10.765h-23.04
								c0.05-0.92,0.08-1.85,0.08-2.78c0-6.5-1.18-12.79-3.51-18.75h26.47C464.9,321.44,469.72,326.26,469.72,332.205z"/>
							<path style="fill:#999999;" d="M409.435,135.71h-117.43v207.26h40.56c-0.05-0.92-0.08-1.85-0.08-2.78
								c0-28.54,23.21-51.75,51.75-51.75c19.94,0,37.77,11.13,46.52,29.05c0.63,1.3,1.21,2.61,1.73,3.95h26.47v-91.602
								c0-2.727-0.526-5.428-1.55-7.955l-30.006-74.069C424.435,140.498,417.329,135.71,409.435,135.71z M313.995,229.99v-72.28h93.67
								l29.28,72.28H313.995z"/>
							<path style="fill:#666666;" d="M341.575,342.97c-0.06-0.92-0.09-1.85-0.09-2.78c0-23.61,19.14-42.75,42.75-42.75
								c16.89,0,31.49,9.79,38.43,24c2.77,5.66,4.33,12.03,4.33,18.75c0,0.93-0.03,1.86-0.09,2.77c-1.43,22.33-19.98,39.99-42.67,39.99
								C361.555,382.95,343.005,365.29,341.575,342.97z M401.775,340.19c0-9.67-7.87-17.54-17.54-17.54s-17.54,7.87-17.54,17.54
								s7.87,17.54,17.54,17.54S401.775,349.86,401.775,340.19z"/>
							<path style="fill:#E6E6E6;" d="M384.235,322.65c9.67,0,17.54,7.87,17.54,17.54s-7.87,17.54-17.54,17.54s-17.54-7.87-17.54-17.54
								C366.695,330.52,374.565,322.65,384.235,322.65z"/>
							<path style="fill:#F4581B;" d="M291.995,86.77v256.2h-153.97c0.05-0.92,0.08-1.85,0.08-2.78c0-28.54-23.21-51.75-51.75-51.75
								c-19.96,0-37.78,11.14-46.51,29.04c-0.64,1.3-1.22,2.62-1.74,3.96h-27.34V86.77H291.995z"/>
							<path style="fill:#666666;" d="M129.105,340.19c0,0.93-0.03,1.86-0.09,2.78c-1.43,22.32-19.98,39.98-42.66,39.98
								c-22.69,0-41.24-17.66-42.67-39.98c-0.06-0.92-0.09-1.85-0.09-2.78c0-6.72,1.55-13.08,4.33-18.74
								c6.93-14.22,21.54-24.01,38.43-24.01C109.965,297.44,129.105,316.58,129.105,340.19z M103.895,340.19
								c0-9.67-7.87-17.54-17.54-17.54s-17.54,7.87-17.54,17.54s7.87,17.54,17.54,17.54S103.895,349.86,103.895,340.19z"/>
							<path style="fill:#E6E6E6;" d="M86.355,322.65c9.67,0,17.54,7.87,17.54,17.54s-7.87,17.54-17.54,17.54s-17.54-7.87-17.54-17.54
								S76.685,322.65,86.355,322.65z"/>
							<path style="fill:#CCCCCC;" d="M34.595,340.19c0,0.93,0.03,1.87,0.08,2.78h-23.91C4.82,342.97,0,338.15,0,332.205l0,0
								c0-5.945,4.82-10.765,10.765-10.765h27.34C35.775,327.39,34.595,333.68,34.595,340.19z"/>
							<path style="fill:#F7CF52;" d="M444.45,294.944h-7.15c-4.336,0-7.85-3.515-7.85-7.85v-18.249c0-4.336,3.515-7.85,7.85-7.85h7.15
								V294.944z"/>
							<g>
								<rect x="33.051" y="108.611" style="fill:#DB481B;" width="236.666" height="15"/>
								<g>
									<rect x="33.051" y="157.055" style="fill:#DB481B;" width="236.666" height="15"/>
								</g>
								<g>
									<rect x="33.051" y="253.944" style="fill:#DB481B;" width="236.666" height="15"/>
								</g>
								<rect x="33.051" y="205.5" style="fill:#DB481B;" width="236.666" height="15"/>
							</g>
							<polygon style="fill:#96E0DE;" points="322.865,157.71 396.005,229.99 436.945,229.99 407.665,157.71 	"/>

						</svg>
						</div>
					
					</div>
					<div class="row menu_demarrage">
						<div class="col-md-4 col-sm-4 col-xs-4">
						
						</div>
						<div class="col-md-4 col-sm-4 col-xs-4 text-center">
							<h1 id="menu_dashboard"><?php echo lang('menu'); ?></h1>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-4">
						
						</div>
					</div>
					<div class="row menu_demarrage">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<a href="liste_des_vehicules.php" class="btn btn-lg btn-block btn-primary btn-menu">
								<?php echo lang('liste_des_vehicules'); ?>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<a href="#" class="btn btn-lg btn-block btn-primary btn-menu">
								<?php echo lang('statistique'); ?>
							</a>
						</div>
						
					</div>
				</div>
				<div class="col-md-1 col-sm-1 col-xs-1"></div>
			</div>
		</div>

		<?php
		//**********************************
		include $tpl ."footer.php";