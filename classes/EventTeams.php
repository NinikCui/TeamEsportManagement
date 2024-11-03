<?php 
class EventTeams{
    private $dbCon;
    
    public int $idevent;
    public int $idteam;

    public function __construct($dbConnection) {
        $this->dbCon = $dbConnection;
    }

    public function DeleteEventTeam($idEvent ,$idTeam){
        $stmt = $this->dbCon->prepare("delete from event_teams where idevent =". $idEvent ." and idteam=". $idTeam ."");
        $stmt->execute();
        $stmt->close();
    }
    public function AddEventTeam($idevent, $idteam){
        $checkStmt = $this->dbCon->prepare("SELECT * FROM event_teams WHERE idevent = ? AND idteam = ?");
            $checkStmt->bind_param("ii", $idevent, $idteam);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            
            if ($result->num_rows > 0) {
                $checkStmt->close();
                return "Team sudah terdaftar untuk event ini.";
            } else {
                
                $checkStmt->close();
                $stmt = $this->dbCon->prepare("INSERT INTO event_teams (idevent, idteam) VALUES (?, ?)");
                $stmt->bind_param("ii", $idevent, $idteam);
                if ($stmt->execute()) {
                    $stmt->close();
                    return "Team berhasil ditambahkan.";
                } else {
                    $stmt->close();
                    return "Terjadi kesalahan saat menambahkan team.";
                }
                
            }
    }

    public function ReadDataEventTeam($idEventDetail,$pageStart,$maxRows){
        $stmt = $this->dbCon->prepare("select et.idevent, et.idteam, t.name from event_teams et 
                                        inner join team t on et.idteam = t.idteam 
                                        where et.idevent = " .$idEventDetail." limit ". $pageStart.", ". $maxRows);
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
    
}

?>