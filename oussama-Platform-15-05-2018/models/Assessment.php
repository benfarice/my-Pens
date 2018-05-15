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
			echo "Submitted";

			//Insert INTO Mysql
			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',1);
			$this->bind(':assessment_value',$post['listeningAbility']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',2);
			$this->bind(':assessment_value',$post['informationResearch']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',3);
			$this->bind(':assessment_value',$post['teamworkAbility']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',4);
			$this->bind(':assessment_value',$post['organizingAbility']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',5);
			$this->bind(':assessment_value',$post['Empathy']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',6);
			$this->bind(':assessment_value',$post['cleanLiness']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',7);
			$this->bind(':assessment_value',$post['eating']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',8);
			$this->bind(':assessment_value',$post['firendLiness']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',9);
			$this->bind(':assessment_value',$post['imaginative']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',10);
			$this->bind(':assessment_value',$post['curiosity']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',11);
			$this->bind(':assessment_value',$post['aesthetic']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',12);
			$this->bind(':assessment_value',$post['concentration']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',13);
			$this->bind(':assessment_value',$post['readingAbility']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',14);
			$this->bind(':assessment_value',$post['expressionAbility']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',15);
			$this->bind(':assessment_value',$post['englishAbility']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',16);
			$this->bind(':assessment_value',$post['practicalAbility']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',17);
			$this->bind(':assessment_value',$post['observationalAbility']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',18);
			$this->bind(':assessment_value',$post['openness']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',19);
			$this->bind(':assessment_value',$post['extraversion']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',20);
			$this->bind(':assessment_value',$post['agreeableness']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',21);
			$this->bind(':assessment_value',$post['conscientiousness']);
			

			$this->execute();

			$this->query('INSERT INTO assessment (question_id, assessment_value, assessment_day, student_id) VALUES (:question_id, :assessment_value,:assessment_day,:student_id)');
			
			$this->bind(':student_id',$post['id_student']);
			$this->bind(':assessment_day',$post['assessment_day']);
			$this->bind(':question_id',22);
			$this->bind(':assessment_value',$post['emotionalStability']);
			

			$this->execute();

			

			//Verify

			if($this->lastInsertId()){
				//Redirect
				header('Location: '.ROOT_URL.'/dashboard/home');
			}else{
				
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