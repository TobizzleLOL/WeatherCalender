<?php
    class Overview {
        public $date;
        public $timespans = [];  // Initialisiere als leeres Array
        public $id;

        function __construct($id) {
            $this->id = $id;
            $this->date = array(date("Y"), date("m"), date("d"), date("l"));
        }

        function show() {
            $year = date("Y");
            $month = date("m");
          
            echo "<span class='inner-circle'></span>";
            echo "<span class='outer-circle'></span>";
        }

        public function loadTimespans() {
            require("connection.php");

            try {
                $stmt = $conn->prepare('SELECT * FROM timespans WHERE user_id=:user_id');
                $stmt->bindParam(':user_id', $this->id);
                $stmt->execute();
                $result = $stmt->fetchAll();
                foreach ($result as $row) {
                    $this->events[] = new Event($row['user_id'], $row['name'], $row['date']);
                }
            } catch (Exception $e) {
                echo "Fehler beim Laden der Zeitspanne: " . $e->getMessage();
            }
        }
        
        public function saveEvents() {
            require("connection.php");

            try {
                foreach ($this->timespans as $t) {
                    $stmt = $conn->prepare("INSERT INTO timespans(user_id, name, start, end) VALUES(:user_id, :name, :start, :end)");
                    $stmt->bindParam(':user_id', $this->id);
                    $stmt->bindParam(':name', $t->getName());
                    $stmt->bindParam(':start', $t->getStart());
                    $stmt->bindParam(':end', $t->getEnd());
                    $stmt->execute();
                }
            } catch (Exception $e) {
                echo "Fehler beim Speichern der Zeitspanne: " . $e->getMessage();
            }
        }

        public function createEvent($name, $start, $end) {
            $this->timespans[] = new Timespan($this->id, $name, $start, $end);
        }
    }

    class Timespan {
        protected $user_id;
        protected $name;
        protected $start;
        protected $end;

        function __construct($user_id, $name, $start, $end) {
            $this->user_id = $user_id;
            $this->name = $name;
            $this->start = $start;
            $this->end = $end;
        }

        public function getName() {
            return $this->name;
        }

        public function getStart() {
            return $this->start;
        }

        public function getEnd() {
            return $this->end;
        }
    }