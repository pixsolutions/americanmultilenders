<?php
header('Content-type:application/json');
$postdata = json_decode(file_get_contents('php://input'), true);
$request = $postdata['request'];
require('db.php');


if ($request == 'getSupport'){

		$sql = "SELECT * FROM settings";
		$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
            $arr = array(
        	'adminEmail' => $row["admin"]
        	);
    }
    echo json_encode($arr);
} 
}


if ($request == 'changeSupport'){
$email = $postdata['email'];
$sql = "UPDATE settings SET admin='".$email."'";
	if ($conn->query($sql) === TRUE) {
	    echo json_encode($conn->query($sql));
	} else {
	    echo json_encode(false);
	}

}


if ($request == 'getAdmin'){

		$sql = "SELECT * FROM users";
		$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
            $arr = array(
        	'adminEmail' => $row["user"],
        	'adminPass' => $row["pass"]
        	);
    }
    echo json_encode($arr);
} 
}


if ($request == 'changeAdmin'){
$email = $postdata['email'];
$pass = md5($postdata['password']);
$sql = "UPDATE users SET user='".$email."' , pass='".$pass."'";
	if ($conn->query($sql) === TRUE) {
	    echo json_encode($conn->query($sql));
	} else {
	    echo json_encode(false);
	}

}




if ($request == 'add'){
$categoryTitle = $postdata['category'];
$sql = "INSERT INTO categories (title) VALUES ('".$categoryTitle."')";
	if ($conn->query($sql) === TRUE) {
	    echo json_encode(true);
	} else {
	    echo json_encode(false);
	}
}

if ($request == 'remove'){
$category = $postdata['category'];
$categoryID = $category['id'];
$sql = "DELETE FROM categories WHERE `id`='".$categoryID."'";
	if ($conn->query($sql) === TRUE) {
	    echo json_encode(true);
	} else {
	    echo json_encode(false);
	}

 }

if ($request == 'update'){
$category = $postdata['category'];
$categoryID = $category['id'];
$categoryTitle = $category['title'];
$sql = "UPDATE categories SET title='".$categoryTitle."' WHERE `id`='".$categoryID."'";
	if ($conn->query($sql) === TRUE) {
	    echo json_encode($conn->query($sql));
	} else {
	    echo json_encode(false);
	}

 }



if ($request == 'upload'){

		$file = $postdata['file'];
		$fileURL = $file['downloadURL'];
		$fileSize = $file['size'];
		$fileExt = $file['ext'];
		$filedate = $file['date'];
		$title = $file['title'];
		$category = $postdata['category'];
		$categoryID = $category['id'];
	
	$sql = "INSERT INTO forms (`catId`,`title`, `size`, `date`, `format`, `url`) VALUES ('".$categoryID."','".$title."','".$fileSize."','".$filedate."','".$fileExt."','".$fileURL."')";
	if ($conn->query($sql) === TRUE) {
	    echo json_encode(true);
	} else {
	    echo json_encode(false);
	}



 }

if ($request == 'removeForm'){
$theForm = $postdata['form'];
$id = $postdata['id'];
$sql = "DELETE FROM forms WHERE `id`='".$id ."'";
	if ($conn->query($sql) === TRUE) {
	    echo json_encode(true);
	} else {
	    echo json_encode(false);
	}

 }


if ($request == 'listForms'){
$cat = $postdata['category'];
$id = $cat['id'];

		$sql = "SELECT * FROM forms WHERE `catId`='".$id."'";
		$result = $conn->query($sql);


$response = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
            $arr = array(
        	'id' => $row["id"],
        	'title' => $row["title"],
        	'date' => $row["date"],
        	'format' => $row["format"],
        	'url' => $row["url"],
        	'size' => $row["size"],
        	'position' => $row["position"]

    );
    array_push($response,$arr);
    }
    echo json_encode($response);
} else {
    echo "0 results";
};
}

if ($request=='saveOrder'){
	$theArray = $postdata['array'];
	$sql = '';
	foreach ($theArray as $key => $value) {
	$sql .= "UPDATE categories SET position='".$theArray[$key]["position"]."' WHERE `id`='".$theArray[$key]["id"]."'; ";

}

	echo json_encode($conn->multi_query($sql));

}




if ($request=='saveFormsOrder'){
	$theArray = $postdata['array'];
	$sql = '';
	foreach ($theArray as $key => $value) {
	$sql .= "UPDATE forms SET position='".$theArray[$key]["position"]."' WHERE `id`='".$theArray[$key]["id"]."'; ";

}

	echo json_encode($conn->multi_query($sql));

}




$conn->close();

?>