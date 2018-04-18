<?php 
class Shares extends Controller{
	/*protected function Index(){
		echo 'Shares/index';
	}*/
	protected function Index(){
		//echo 'home/index';
		$viewmodel = new ShareModel();
		$this->ReturnView($viewmodel->Index(),true);
	}

	protected function add(){
		//echo 'home/index';
		$viewmodel = new ShareModel();
		$this->ReturnView($viewmodel->add(),true);
	}
}

 ?>