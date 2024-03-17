<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$host = "localhost";
$db_user = "root";
$db_pass = null;
$db_name = "news_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$request_method = $_SERVER["REQUEST_METHOD"];



    switch ($request_method) {
        case 'GET':
            if(!empty($_GET["id"])){
                $id = intval($_GET["id"]);
                $response = getNews($id);
                echo json_encode($response);
            }else{
                $response = getAllNews();
                echo json_encode($response);
            }
            break;
   case 'POST':
        if (!empty($_POST["title"]) && !empty($_POST["content"])) {
            $title = $_POST["title"];
            $content = $_POST["content"];
            $response = createNews($title, $content);
            echo json_encode($response);
        } else {
            echo json_encode(["status" => "Title and content are required"]);
        }
            break;
        case 'PUT':
            if(!empty($_GET["id"])){
                $id = intval($_GET["id"]);
                $response = toggleNewsStatus($id);
                echo json_encode($response);
            }else{
                echo json_encode([
                    "status"=>"something went wrong",
                ]);
            }
            break;
        case 'DELETE':
            if(!empty($_GET["id"])){
                $id = intval($_GET["id"]);
                $response = deleteNews($id);
                echo json_encode($response);
            }else{
                echo json_encode([
                    "status"=>"something went wrong",
                ]);
            }
            break;
        
        default:
            echo json_encode([
                "status"=>"something went wrong",
            ]);
            break;
    }

    
function getAllNews() {
    global $mysqli;
    $query = $mysqli->prepare("SELECT * FROM news_articles");
    $query->execute();
    $result = $query->get_result();

    $news = [];
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }

    return ["status" => "Success", "news" => $news];
}

function getNews($id) {
    global $mysqli;
    $query = $mysqli->prepare("SELECT * FROM news_articles WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();

    $news = $result->fetch_assoc();

    return ["status" => "Success", "news" => $news];
}

function createNews($title, $content) {
    global $mysqli;
    $query = $mysqli->prepare("INSERT INTO news_articles (title, content) VALUES (?, ?)");
    $query->bind_param("ss", $title, $content);
    if ($query->execute()) {
        return ["status" => "Success", "news_id" => $mysqli->insert_id];
    } else {
        return ["status" => "Failed", "error" => $mysqli->error];
    }
}

 

    function deleteNews($id){
        global $mysqli;
        $query = $mysqli->prepare("DELETE FROM New WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $query->store_result();

        $response["status"] = "Success";

        return $response;
    }