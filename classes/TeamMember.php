<?php
class TeamMember{
    private $dbCon;

    public int $idteam;
    public int $idmember;
    public string $description;

    public function __construct($dbConnection) {
        $this->dbCon = $dbConnection;
    }

    public function DeleteTeamMember($idTeam,$idMember){
        $stmt = $this->dbCon->prepare("delete from team_members where idteam = $idTeam and idmember =$idMember;");
        $stmt->execute();
        $stmt-> close();
    
        $stmt = $this->dbCon->prepare("delete from join_proposal where idmember = $idMember and idteam = $idTeam and status ='approved';");
        $stmt->execute();
        $stmt->close();
    }

    public function ReadDataTeamMember($idTeamMember,$pageStart,$maxRows){
        $stmt = $this->dbCon->prepare("select tm.* , m.username from team_members tm inner join member m on m.idmember = tm.idmember where idteam = $idTeamMember limit ". $pageStart.", ". $maxRows);
        $stmt->execute();
        $res = $stmt->get_result();

        $members = [];

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $members[] = $row;
            }
        }
        
        $stmt->close();
        return $members;
    }

}

?>