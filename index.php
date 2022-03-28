<?php

// on lie les infos de connexion
require_once "_connec.php";

// on initialise PDO
$pdo = new \PDO(DSN, USER, PASS);

////////////////////////////////////////////////////////////////////////////

// Si POST contient quelque chose, on lance la requête
if (isset($_POST["firstname"])) {

    // form-atting (lol)
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);

    // validation
    if (empty($_POST["firstname"])) {
        $errors[] = "First name is mandatory";
    }

    if (empty($_POST["lastname"])) {
        $errors[] = "Last name is mandatory";
    }

    if (strlen($_POST["firstname"]) > 45) {
        $errors[] = "First name length should be less than 45 characters";
    }

    if (strlen($_POST["lastname"]) > 45) {
        $errors[] = "Last name length should be less than 45 characters";
    }


    // préparation de la requête d'ajout de données
    $addQuery = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
    $addStatement = $pdo->prepare($addQuery);

    $addStatement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
    $addStatement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);

    // on exécute la requête préparée
    $addStatement->execute();

    // Pour éviter la resoumission du formulaire
    // (je sais pas comment ça marche mais ça marche)
    header("Location: index.php");
}

// On requiers toutes les infos de la table
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FriendsDB</title>
</head>

<body>
    <h1>LA FAMILLE</h1>
    <p><?php

        // une jolie boucle qui affiche chaque info de la table une par une
        foreach ($friends as $friend) {
            echo $friend["firstname"] . " " . $friend["lastname"] . "<br>";
        }

        ?></p>
    <h1>Add your own!</h1>
    <form action="index.php" method="post">
        <label for="first_name">First name:</label>
        <input type="text" id="firstname" name="firstname" placeholder="John" required />
        <label for="last_name">Last name:</label>
        <input type="text" id="lastname" name="lastname" placeholder="Doe" required />
        <button type="submit">Add</button>
    </form>
</body>

</html>