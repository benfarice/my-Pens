<?php 
require 'classes/Database.php';

$database = new Database;

//$database->query('select * from post where id = :id');

//$database->bind(':id',2);



//print_r($rows);
$post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

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
 <h1>Posts</h1>
 <div>
 	<?php foreach ($rows as $row) : ?>
 		<h3><?php echo $row['title']; ?></h3>
 		<p><?php echo $row['body'] ?></p>
 	<?php endforeach; ?>

 </div>