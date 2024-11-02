<?php
class Team{
    private $dbCon;

    public int $idteam;
    public int $idgame;
    public string $name;

    public function __construct($dbConnection){
        $this->dbCon = $dbConnection;
    }

    public function AddTeam($game,$team){
        $stmt = $this->dbCon->prepare("SELECT idteam, isdeleted FROM team WHERE name = ? AND idgame = ?");
        $stmt->bind_param('si', $team, $game); 
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            if($row['isdeleted'] == 1){
                $idteam = $row['idteam'];
                $updateStmt = $this->dbCon->prepare("UPDATE team SET isdeleted = 0 WHERE idteam = ?");
                $updateStmt->bind_param('i', $idteam);
                $updateStmt->execute();
                $updateStmt->close();
            }
        } else {
            $insertStmt = $this->dbCon->prepare("INSERT INTO team (idteam, idgame, name) VALUES ('', ?, ?)");
            $insertStmt->bind_param('is', $game, $team); 
            $insertStmt->execute();
            $insertStmt->close();
        }
    }


    public function EditTeam($game,$name,$idTeam){
        $stmt = $this->dbCon->prepare("UPDATE team SET idgame = ?, name = ? WHERE idteam = ". $idTeam);
        $stmt->bind_param("ss", $game, $name);
        $stmt->execute();
        $stmt->close();
    }

    public function DeleteTeam($idTeam){
        $stmt = $this->dbCon->prepare("UPDATE team SET isdeleted = 1 WHERE idteam = ". $idTeam);
        $stmt->execute();
        $stmt->close();
    }
    
    public function ReadDataTeam($pageStart,$maxRows){
        $stmt = $this->dbCon->prepare("select t.idteam, g.name as gameName, t.name as teamName from team t  inner join game g on g.idgame = t.idgame where t.isdeleted = 0 limit ". $pageStart.", ". $maxRows);
        $stmt->execute();
        $res = $stmt->get_result();

        $teams= [];
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $teams[] = $row;
            }
        }
        
        $stmt->close();
        return $teams;
    }

    public function ReadPages($maxRows) {
        $q = " select count(*) as totalRows from team";
        $resCount = $this->dbCon->query($q);
        $rcount = $resCount->fetch_array();
        $totalRows = $rcount["totalRows"];
        return ceil($totalRows / $maxRows);
    }

}


?>