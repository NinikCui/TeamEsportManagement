<?php
class Join_Proposal{
    private $dbCon;

    public int $idjoin_proposal;
    public int $idmember;
    public int $idteam;
    public string $description;
    public string $status;


    public function __construct($dbConnection) {
        $this->dbCon = $dbConnection;
    }

    public function UpdateProposal($jpStatus, $jpIdMember, $jpIdTeam, $idProposal){
        if($jpStatus == 'approve'){
            $jpStatusTerpilih = 'approved';
            $stmt = $this->dbCon->prepare("UPDATE join_proposal SET status = 'rejected' WHERE idmember = $jpIdMember;"); 
            $stmt->execute();
            $stmt->close();
    
    
            $stmt = $this->dbCon->prepare("INSERT INTO team_members (idteam, idmember, description) VALUES ('$jpIdTeam', '$jpIdMember', 'DITERIMA');");
            $stmt->execute();
            $stmt->close();
        }elseif ($jpStatus == 'rejected') {
            $jpStatusTerpilih = 'rejected';
        }

        $sql = "UPDATE join_proposal SET status = ? WHERE idjoin_proposal = ?";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->bind_param('si', $jpStatusTerpilih, $idProposal);
        $stmt->execute();
        $stmt->close();
    }
    public function getProposalsByStatus($status = 'waiting', $pageStart = 0, $maxRows = 10) {
        $stmt = $this->dbCon->prepare(
            "SELECT 
                jp.idjoin_proposal, 
                m.username, 
                t.name as team, 
                g.name as game, 
                m.idmember as id_user, 
                t.idteam as id_team 
            FROM join_proposal jp
            INNER JOIN member m ON m.idmember = jp.idmember 
            INNER JOIN team t ON t.idteam = jp.idteam
            INNER JOIN game g ON g.idgame = t.idgame 
            WHERE status = ? 
            LIMIT ?, ?"
        );

        $stmt->bind_param('sii', $status, $pageStart, $maxRows);
        $stmt->execute();
        $result = $stmt->get_result();
        $proposals = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $proposals[] = $row;
            }
        }

        $stmt->close();
        return $proposals;
    }
    public function renderProposalRow($proposal, $showActions = true) {
        $html = "<tr>";
        $html .= "<td>" . htmlspecialchars($proposal["idjoin_proposal"]) . "</td>";
        $html .= "<td>" . htmlspecialchars($proposal["username"]) . "</td>";
        $html .= "<td>" . htmlspecialchars($proposal["team"]) . "</td>";
        $html .= "<td>" . htmlspecialchars($proposal["game"]) . "</td>";
        
        if ($showActions) {
            $html .= "<td>
                <form method='POST' action=''>
                    <input type='hidden' name='id_proposal' value='" . htmlspecialchars($proposal["idjoin_proposal"]) . "'>
                    <input type='hidden' name='id_user' value='" . htmlspecialchars($proposal["id_user"]) . "'>
                    <input type='hidden' name='id_team' value='" . htmlspecialchars($proposal["id_team"]) . "'>
                    <button type='submit' name='action' value='approve' style='color: #A0D683; border: none; background: none; cursor: pointer; font-size: 18px;'>✔ Approve</button>
                    <button type='submit' name='action' value='rejected' style='color: #FF474D; border: none; background: none; cursor: pointer; font-size: 18px;'>✖ Decline</button>
                </form>
            </td>";
        }
        
        $html .= "</tr>";
        return $html;
    }

    public function totPages($status, $maxRows){
        $q = " select count(*) as totalRows from join_proposal where status='".$status."'";
        $resCount = $this->dbCon->query($q);
        $rcount = $resCount->fetch_array();
        $totalRows = $rcount["totalRows"];
        $totalPages = ceil($totalRows / $maxRows);
        $resCount->close();
        return $totalPages;
    }





    public function CekTeamUser($idmember){
        $stmt = $this->dbCon->prepare("SELECT jp.*, t.name  FROM join_proposal jp inner join team t on t.idteam = jp.idteam where idmember = $idmember and  status ='approved' limit 1 ;");
        $stmt->execute();        
        $res = $stmt->get_result();
        $teamss =[];
        if ($res->num_rows > 0) {
            while ($t = $res->fetch_array()) {
                $idTeamUser = $t['idteam'];
                $namaTeamUser = $t['name'];

            }
            $teamss [] = $idTeamUser;
            $teamss [] =   $namaTeamUser;
        }
        $stmt->close();
        return $teamss;
    }

    public function JoinTeamUser($idmember,$idteam){
        $stmt = $this->dbCon->prepare("INSERT INTO join_proposal (idmember, idteam, description, status) VALUES ( '$idmember', '$idteam', 'UHUYY', 'waiting');");
        $stmt->execute();
        
    $stmt->close();
    }

    public function totPagesUser( $maxRows){
        $q = " select count(*) as totalRows from team";
        $resCount = $this->dbCon->query($q);
        $rcount = $resCount->fetch_array();
        $totalRows = $rcount["totalRows"];
        $totalPages = ceil($totalRows / $maxRows);
        return $totalPages;
    }
    public function GetProposalUser($pageStart,$maxRows){
        $stmt = $this->dbCon->prepare(
            "SELECT 
                t.idteam, 
                g.name as gameName, 
                t.name as teamName 
            FROM team t 
            INNER JOIN game g ON g.idgame = t.idgame 
            LIMIT ?, ?"
        );
        $stmt->bind_param('ii', $pageStart, $maxRows);
        $stmt->execute();
        $result = $stmt->get_result();
        $teams = [];
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $teams[] = $row;
            }
        }
        
        $stmt->close();
        return $teams;

    }
}

?>