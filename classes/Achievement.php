<?php
class Achievement{
    private $dbCon;

    public int $idachievement;
    public int $idteam;
    public string $name;
    public string $date;
    public string $description;

    public function __construct($dbConnection) {
        $this->dbCon = $dbConnection;
    }

    public function DeleteAchievement($idAchi){
        $stmt = $this->dbCon->prepare("delete from achievement where idachievement =". $idAchi);
        $stmt->execute();
        $stmt->close();
    }
    public function AddAchievement($idteam,$namaAchi,$dateAchi,$desAchi){
        $stmt = $this->dbCon->prepare("INSERT INTO achievement (idachievement, idteam, name, date, description) VALUES ('', '$idteam', '$namaAchi', '$dateAchi', '$desAchi')");
        $stmt->execute();
        $stmt->close();
    }

    public function EditAchievement($team, $nameAchi, $dateAchi, $descAchi, $idAchi){
        $stmt = $this->dbCon->prepare("UPDATE achievement SET idteam=?, name=?, date=?, description=? WHERE idachievement = ?");
        $stmt->bind_param("ssssi", $team, $nameAchi, $dateAchi, $descAchi, $idAchi);
        $stmt->execute();
        $stmt->close();
    }
    public function ReadDataAchievement($teamNameFilter,$pageStart,$maxRows){
        if (!empty($teamNameFilter)) {
            $stmt = $this->dbCon->prepare("SELECT a.idachievement, t.name as team, a.name, DATE_FORMAT(a.date,'%d/%m/%Y') as date, a.description  
                                    FROM achievement a
                                    INNER JOIN team t ON t.idteam = a.idteam
                                    WHERE t.name LIKE ?
                                    ORDER BY a.idachievement ASC 
                                    LIMIT ?, ?");
            $searchTerm = $teamNameFilter . "%"; 
            $stmt->bind_param("ssi", $searchTerm, $pageStart, $maxRows);
        } else {
            // Jika tidak ada filter, ambil semua data
            $stmt = $this->dbCon->prepare("SELECT a.idachievement, t.name as team, a.name, DATE_FORMAT(a.date,'%d/%m/%Y') as date, a.description  FROM achievement a
                                INNER JOIN team t ON t.idteam = a.idteam ORDER BY a.idachievement ASC LIMIT ". $pageStart.", ". $maxRows);
        }
        $stmt->execute();
        $res = $stmt->get_result();

        $achievements = [];
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $achievements[] = $row;
            }
        }
        
        $stmt->close();
        return $achievements;
    }

    public function ReadPages($maxRows){
        $q = "SELECT COUNT(*) as totalRows FROM achievement";
        $resCount = $this->dbCon->query($q);
        $rcount = $resCount->fetch_array();
        $totalRows = $rcount["totalRows"];
        return ceil($totalRows / $maxRows);
    }
}

?>