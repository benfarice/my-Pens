<?php 
require 'classes/Database.php';

$database = new Database;

//$database->query('select * from post where id = :id');

//$database->bind(':id',2);



//print_r($rows);
$post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

if(isset($_POST['delete'])){
	//echo $_POST['delete_id'];
	$delete_id = $_POST['delete_id'];
	$database->query('delete from post where id = :id');
	$database->bind(':id',$delete_id);
	$database->execute();
}

if(isset($post['submit'])){
	//echo "submitted";
	$title = $post['title'];
	$body  = $post['body'];
	//echo $title;
	$database->query("INSERT INTO post (title , body) VALUES 
		(:title, :body)");
	$database->bind(':title',$title);
	$database->bind(':body',$body);

	$database->execute();

	if($database->lastInsertId()){
		echo "<p>Post Added</p>";
	}
}
if(isset($post['submit'])){
	//echo "submitted";
	$id = $post['id'];
	$title = $post['title'];
	$body  = $post['body'];
	//echo $title;
	$database->query("update post set title = :title , body= :body
		 where id = :id");
	$database->bind(':title',$title);
	$database->bind(':id',$id);
	$database->bind(':body',$body);

	$database->execute();

	
}

$database->query('select * from post');

//echo "hello";

$rows = $database->resultset();
 ?>
 <h1>Add Post</h1>
 <form method="post" action="<?php $_SERVER ?>">
 	<label>Post title</label>
 	<br>
 	<input type="text" name="title" placeholder="Add a title">
 	<br>
 	<label>Post body</label>
 	<br>
 	<textarea name="body"></textarea>
 	<br>
 	<input type="submit" name="submit">
 </form>
 <h1>Add Post</h1>
 <form method="post" action="<?php $_SERVER ?>">
 	<label>Post ID</label>
 	<br>
 	<input type="text" name="id" placeholder="Specify ID">
 	<br>
 	<label>Post title</label>
 	<br>
 	<input type="text" name="title" placeholder="Add a title">
 	<br>
 	<label>Post body</label>
 	<br>
 	<textarea name="body"></textarea>
 	<br>
 	<input type="submit" name="submit">
 </form>
 <h1>Posts</h1>
 <div>
 	<?php foreach ($rows as $row) : ?>
 		<h3><?php echo $row['title']; ?></h3>
 		<p><?php echo $row['body'] ?></p>
 		<form method="post" action="<?php $_SERVER ?>">
 			<input type="hidden" name="delete_id" 
 			value="<?php echo $row['id']; ?>">
 			<input type="submit" name="delete" value="Delete">
 		</form>
 	<?php endforeach; ?>

 </div>