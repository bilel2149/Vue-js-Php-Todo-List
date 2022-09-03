<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once 'config/Database.php';
include_once 'models/Todo.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();
$output = array();
// Instantiate blog post object
$todo = new Todo($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));
if (isset($data->id) AND isset($data->status)) {
    $todo->id = $data->id;
    $todo->status = $data->status;
    
    // Update post
    if ($data->status == 2) {
        $todo->markAsCompleted();
        if ($todo->markAsCompleted()) {
            
            $output['status'] = 200;
            $output['message'] = "Task  Completed";
        } else {
            $output['status'] = 204;
            $output['message'] = "Task Not Completed";
        }
    }
} 
// Turn to JSON & output
echo json_encode($output);