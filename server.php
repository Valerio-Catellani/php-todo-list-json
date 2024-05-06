<?php


$todoJson = file_get_contents("resources/js/data.json");

$call = $_SERVER['REQUEST_METHOD'];

if ($call === 'POST') {
    if (isset($_POST['text'])) {
        //prendiamo un json e lo trasformiamo in un array associativo
        $AllTasks = json_decode($todoJson, true);
        //creiamo un array degli id che utilizzeremo in un ciclo while per andare ad otternere il primo id disponibile
        $id = 1;
        foreach ($AllTasks as $task) {
            if ($task['id'] >= $id) {
                $id = $task['id'] + 1;
            }
        };
        //creiamo un nuovo array associativo che contenga il nuovo task
        $newTask = [
            "id" => (int)$id,
            "text" => $_POST["text"],
            "done" => false
        ];
        //aggiungiamo il nuovo task all'array transformato da json
        $AllTasks[] = $newTask;
        //ritrasformiamo l'array associativo in json
        $todoJson = json_encode($AllTasks);
        file_put_contents("resources/js/data.json", $todoJson);
    }
} else if ($call === 'DELETE') {
    //prendiamo un json e lo trasformiamo in un array associativo
    $AllTasks = json_decode($todoJson, true);
    //prendiamo il valore del data passato dalla chiamata axios DELETE (che ce lo passa come json) e lo trasformiamo in un array associativo
    $obj = json_decode(file_get_contents('php://input'), true);
    //ci prendiamo il suo indice
    $id = $obj["id"];
    //andiamo a rimuoverlo dal database
    array_splice($AllTasks, $id, 1);
    //ritrasformiamo l'array associativo in json
    $todoJson = json_encode($AllTasks);
    file_put_contents("resources/js/data.json", $todoJson);
} else if ($call === "PUT") {
    //prendiamo un json e lo trasformiamo in un array associativo
    $AllTasks = json_decode($todoJson, true);
    //prendiamo il valore del data passato dalla chiamata axios PUT (che ce lo passa come json) e lo trasformiamo in un array associativo
    $obj = json_decode(file_get_contents('php://input'), true);
    //ci prendiamo il suo indice
    $id = (int)$obj["id"] - 1;
    //andiamo a rimuoverlo dal database
    $AllTasks[$id]['done'] =  !$AllTasks[$id]['done'];
    //ritrasformiamo l'array associativo in json
    $todoJson = json_encode($AllTasks);
    file_put_contents("resources/js/data.json", $todoJson);
}




header('Content-Type: application/json');
echo $todoJson;
