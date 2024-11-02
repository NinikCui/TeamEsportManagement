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
}

?>