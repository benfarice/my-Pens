<?php 
class Home extends Controller{
	protected function Index(){
		//echo 'home/index';
		

		if(isset($_SESSION['is_logged_in'])){
			$viewmodel = new HomeModel();
		    $this->ReturnView($viewmodel->Index(),true);

		}else{
			header('Location: '.ROOT_URL.'/users/login');
		}
	}
}

 ?>