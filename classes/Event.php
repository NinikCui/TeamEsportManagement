<?php
class Event{
    private $dbCon;
    
    public int $idevent;
    public string $name;
    public string $date;
    public string $description;


    public function __construct($dbConnection) {
        $this->dbCon = $dbConnection;
    }

    public function DeleteEvent($idEvent){
        $stmt = $this->dbCon->prepare("delete from event where idevent =". $idEvent);
        $stmt->execute();
        $stmt->close();
    }

    public function AddEvent($nameEvent,$dateEvent){
        $stmt = $this->dbCon->prepare("INSERT INTO event (idevent, name, date) VALUES ('', '$nameEvent', '$dateEvent')");
        $stmt->execute();
        $stmt->close();
    }


    public function EditEvent( $nameEvent, $dateEvent,$idEvent){
        $stmt = $this->dbCon->prepare("UPDATE event SET name=?, date=? WHERE idevent = " . $idEvent );
        $stmt->bind_param("ss", $nameEvent, $dateEvent);
        $stmt->execute();
        $stmt->close();
    }

    public function ReadDataEvent($pageStart,$maxRows){
        $stmt = $this->dbCon->prepare("SELECT * FROM event limit ". $pageStart.", ". $maxRows);
        $stmt->execute();
        $res = $stmt->get_result();

        $events =[];
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $events[] = $row;
            }
        }
        
        $stmt->close();
        return $events;
    }
    public function ReadPages($maxRows){
        $q = "select count(*) as totalRows from event";
        $resCount = $this->dbCon->query($q);
        $rcount = $resCount->fetch_array();
        $totalRows = $rcount["totalRows"];
        return ceil($totalRows / $maxRows);
    }
    
}

?>