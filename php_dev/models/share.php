<?php
class ShareModel extends Model{
	public function Index(){
		//return;
		$this->query('select * from shares');
		$rows = $this->resultSet();
		//print_r($rows);
		return $rows;
	}

	public function add(){
		//return;
		//Sanitize POST
		$post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
		if($post['submit']){
			//echo "Submitted";
			//Insert INTO Mysql
			$this->query('insert into shares (title,body,link,user_id) values(:title,:body,:link,:user_id)');
			$this->bind(':title',$post['title']);
			$this->bind(':body',$post['body']);
			$this->bind(':link',$post['link']);
			$this->bind(':user_id',1);

			$this->execute();

			//Verify

			if($this->lastInsertId()){
				//Redirect
				header('Location: '.ROOT_URL.'/shares');
			}

		}
		return;
	}
}