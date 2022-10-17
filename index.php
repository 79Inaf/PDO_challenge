<?php

require_once '_connec.php';


$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

function addFriend(array $friends): void
{
    $pdo = new \PDO(DSN, USER, PASS);

    $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':firstname', $friends['firstname'], PDO::PARAM_STR);
    $statement->bindValue(':lastname', $friends['lastname'], PDO::PARAM_STR);
    $statement->execute();

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
</head>
<body>
    <h1>Les friends de friends ;)</h1>
    <ul>
        <?php
        foreach ($friends as $friend)
        {  
            echo '<li>' . $friend['firstname'] . ' ' . $friend['lastname'] .'</li>';
        }
        ?>
    </ul>

    <form method="post">
        <p>
            <label for="firstname">fistname</label>
            <input type="text" name="firstname" id="firstname">
        </p>

        <p>
            <label for="lastname">lastname</label>
            <input type="text" name="lastname" id="lastname">
        </p>

        <p>
            <button type="submit">envoyer</button>
        </p>
    </form>
    
</body>
</html>


<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $data = array_map('trim', $_POST);
    $errors = [];

    if(!isset($data['firstname']) || empty($data['firstname']))
        $errors[] = 'The firstname is required';
    
    if(strlen($data['firstname']) > 45)
        $errors[] = 'The firstname must be less than 45 characters';

    if(!isset($data['lastname']) || empty($data['lastname']))
    $errors[] = 'The lastname is required';
    
    if(strlen($data['lastname']) > 45)
        $errors[] = 'The lastname must be less than 45 characters';

    if(!empty($errors)) 
    {
        foreach($errors as $error)
        {
            echo '<li>' . $error . '</li>';
        }
    } else {
        addFriend($data);
        header('Location: index.php');
    }

    
}


