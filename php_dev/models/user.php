<?php
class UserModel extends Model{
	public function register(){
		//die("submitted");
		//return;
		//Sanitize POST
		$post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);




		$password = md5($post['password']);

		if($post['submit']){
			//echo "Submitted";
			//Insert INTO Mysql

			if(    $post['name'] == '' 
				|| $post['password'] == ''
			    || $post['email'] == ''){
				messages::setMsg('Please Fill in All Fields','error');
				return;
			}

			$this->query('insert into users (name,email,password) values(:name,:email,:password)');
			$this->bind(':name',$post['name']);
			$this->bind(':email',$post['email']);
			$this->bind(':password',$password);
			

			$this->execute();

			//Verify

			if($this->lastInsertId()){
				//Redirect
				header('Location: '.ROOT_URL.'/users/login');
			}

		}
		return;
	}

	public function login(){
		//return;
			$post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

		$password = md5($post['password']);

		if($post['submit']){
			//die("submitted");
			//echo "Submitted";
			//Compare login
			$this->query('select * from users where email = :email and password = :password');
			
			$this->bind(':email',$post['email']);
			$this->bind(':password',$password);
			

			$row = $this->single();

			if($row){
				//echo 'Logged In';
				$_SESSION['is_logged_in'] = true;
				$_SESSION['user_data'] = array(
					"id"    => $row['id'],
					"name"  => $row['name'],
					"email" => $row['email']
				);
				header('Location: '.ROOT_URL.'/shares');
			}else{
				//echo 'Incorrect Login';
				messages::setMsg('Incorrect Login','error');
			}

		}
		return;
	}
}