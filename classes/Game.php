<?php 
class Game{
    private $dbCon;
    public string $name;
    public string $description;

    public function __construct($dbConnection) {
        $this->dbCon = $dbConnection;
    }

    public function DeleteGame( $idGame){
        $stmt = $this->dbCon->prepare("delete from game where idgame =". $idGame);
        $stmt->execute();
        $stmt->close();
    }

    public function AddGame($game,$description){
        $stmt = $this->dbCon->prepare("INSERT INTO game (idgame, name, description) VALUES ('', '$game', '$description')");
        $stmt->execute();
        $stmt->close();
    }

    public function EditGame($idGame,$game,$description){
        $stmt = $this->dbCon->prepare("UPDATE game SET name = ?, description = ? WHERE idgame = ". $idGame);
        $stmt->bind_param("ss", $game, $description);
        $stmt->execute();
        $stmt->close();
    }

    public function ReadDataGame( $pageStart,$maxRows){
        $stmt = $this->dbCon->prepare("SELECT * FROM game
                                        limit ". $pageStart.", ". $maxRows);
        $stmt->execute();
        $res = $stmt->get_result();

        $dataGame =[];
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $dataGame[] = $row;
            }
        }
        
        $stmt->close();
        return $dataGame;
    }
    public function ReadPages($maxRows){
        $q = " select count(*) as totalRows from game";
        $resCount = $this->dbCon->query($q);
        $rcount = $resCount->fetch_array();
        $totalRows = $rcount["totalRows"];
        return ceil($totalRows / $maxRows);
    }

    public function getTeamsByGame($gameId) {
        $query = "SELECT t.idteam, t.name as teamName 
                 FROM team t
                 WHERE t.idgame = ?";
        $stmt = $this->dbCon->prepare($query);
        $stmt->bind_param("i", $gameId);
        $stmt->execute();
        $res = $stmt->get_result();
        
        $teams = [];
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $teams[] = $row;
            }
        }
        $stmt->close();
        return $teams;
    }

    public function getGameEvents($gameId) {
        $query = "SELECT DISTINCT e.name as eventName, e.date, e.description,
                        GROUP_CONCAT(t.name) as participatingTeams
                 FROM event e
                 JOIN event_teams et ON e.idevent = et.idevent
                 JOIN team t ON et.idteam = t.idteam
                 WHERE t.idgame = ?
                 GROUP BY e.idevent
                 ORDER BY e.date DESC";
        $stmt = $this->dbCon->prepare($query);
        $stmt->bind_param("i", $gameId);
        $stmt->execute();
        $res = $stmt->get_result();
        
        $events = [];
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $events[] = $row;
            }
        }
        $stmt->close();
        return $events;
    }
}

?>