

<!-- <?php 

include "auth.php";

function makeConn(){
    try{
        return new PDO(...PDOauth());
    } catch (PDOException $e){
       die('{"error":"'.$e->getMessage().'")');
       //die('{"error":"MYSQL ERROR"}');
    }
}

// function print_p($d){
//     echo "<pre>",print_r($d),"</pre>";
// }

// $r = PDO result
function fetchAll($r){
    $a = [];

    while ($row = $r->fetch(PDO::FETCH_OBJ)) {
        $a[] = $row;
    }
    return $a;
}


// $c = connection
// $ps = prepared statment
// $p = parameters

function makeQuery($c, $ps, $p){
    try {
        if (count($p)) {
            $stmt = $c->prepare($ps);
            $stmt->execute($p);
        } else {
            $stmt = $c->query($ps);
        }

        $r = fetchAll($stmt);

        return [
            // "statement"=>$ps,
            // "params"=>$p,
            "result"=>$r
        ];

    } catch (PDOException $e) {
        return ["error"=>"Query Failed: ".$e->getMessage()];
    }
}

function makeStatement($data) {
    $c = makeConn();
    $t = $data->type;
    $p = $data->params;

    switch($t){
        case "users_all" : return makeQuery($c,"SELECT * FROM `users`",[]);
        case "resource_all" : return makeQuery($c,"SELECT * FROM `track_resources`",[]);
        case "lcations_all" : return makeQuery($c,"SELECT * FROM `track_locations`",[]);

        case "resource_by_id" : return makeQuery($c,"SELECT * FROM `track_users`",[]);
        case "location_by_id" : return makeQuery($c,"SELECT * FROM `track_resources`",[]);
        case "location_by_id" : return makeQuery($c,"SELECT * FROM `track_locations` WHERE `id`=?",$p);

        case "resources_by_user_id" : return makeQuery($c,"SELECT * FROM `track_resources` WHERE `id`=?",$p);
        case "locations_by_user_id" : return makeQuery($c,"SELECT * FROM `track_locations` WHERE `user_id`=?",$p);

        case "check_signin":
            return makeQuery($c,"SELECT `id` FROM `track_users` WHERE `username`=?  AND `password`=md5(?)",$p);

        case "recent_locations":
            return makeQuery($c,"SELECT 
            r.*, l.* 
            FROM `track_resources` r 
            LEFT JOIN (
                SELECT * `track_locations`
                ORDER BY `date_create` DESC
                ) l
            ON r.id = l.resource.id
            WHERE `r.user_id`=?
            GROUP BY l.resource_id
            ",$p);




        case "insert_user":
            $r = makeQuery($c, "SELECT `id` FROM `track_users` WHERE `username`=? OR `email`=?",[$p[0], $p[1]]);
            if (count($r ['result'])) return["error"=>"Username or Email already exists"];

            $r = makeQuery($c, "INSERT INTO
                `track_users`
                (`username`, `email`,`password`,`img`,`date_create`)
                VALUES
                (?, ?, md5(?), 'https://via.placeholder.com/400/?text=USER', NOW())
            ",$p);
        return ["result"=>$c->lastInsertId()];

        case 
            $r = makeQuery($c, "INSERT INTO
            `track_resources`
            (`user-id`, `name`,`type`, `breed`, `description`,`img`,`date_create`)
            VALUES
            (?, ?, ?, ?, ?, md5(?), 'https://via.placeholder.com/400/?text=ANIMAL', NOW())
            ",$p);
        return ["result"=>$c->lastInsertId()];


        default: return["error"=>"No matched type"];
    }
}

$data = json_decode(file_get_contents("php://input"));

echo json_encode (
    makeStatement($data),
    JSON_NUMERIC_CHECK
); -->