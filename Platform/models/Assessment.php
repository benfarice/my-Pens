<?php
class AssessmentClass extends Model{
	public function Index(){
		//return;
		//$this->query('select * from shares order by create_date desc');
		//$rows = $this->resultSet();
		//print_r($rows);
		//return $rows;
		return;
	}
	public function home(){
		
		return;
	}

	public function add(){
		//return;
		//echo "Sanitize POST";
		$post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
		if(isset($post['submit'])){
			//echo "Submitted";
			//Insert INTO Mysql
			$this->query('INSERT INTO student (name, birth_year, Parent_id, class_id, Gender, Parent_name, parent_email, parent_phone, Wechat_ID, Existing_Customer) VALUES ( :name,NULL,:Parent_id,:class_id,:Gender,:Parent_name, :parent_email,:parent_phone,:Wechat_ID,:Existing_Customer)');
			

			$this->bind(':name',$post['fullname']);
			$this->bind(':Parent_id',1);
			$this->bind(':class_id',1);
			if (isset($post['student_gender']) && $post['student_gender']=="female"){
				$this->bind(':Gender',2);
			}else{
				$this->bind(':Gender',1);
			}
			$this->bind(':Parent_name',$post['parent_name']);
			$this->bind(':parent_email',$post['email']);
			$this->bind(':parent_phone',$post['phone_number']);
			$this->bind(':Wechat_ID',$post['Wechat']);

			if (isset($post['Existing_Customer']) && $post['Existing_Customer']=="yes"){
				$this->bind(':Existing_Customer',1);
			}else{
				$this->bind(':Existing_Customer',0);
			}

			



			$this->execute();

			//Verify

			if($this->lastInsertId()){
				//Redirect
				header('Location: '.ROOT_URL.'/dashboard/home');
			}else{
				//header('Location: '.ROOT_URL.'/dashboard/');
				$this->errorInfo_func();
				echo "\nPDO::errorInfo():\n";
				print_r($this->dbh->errorInfo());
				print_r($this->stmt->errorInfo());
				echo "yeah";
			}



		}
		return;
	}
}