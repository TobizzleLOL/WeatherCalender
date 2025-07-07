<?php
    class Calender {
        public $date;
        public $events = [];  // Initialisiere als leeres Array
        public $id;

        function __construct($id) {
            $this->id = $id;
            $this->date = array(date("Y"), date("m"), date("d"), date("l"));
        }

        function show() {
            $year = date("Y");
            $month = date("m");


            $first_day_of_month = strtotime("$year-$month-01");
            $days_in_month = date("t", $first_day_of_month);
            $first_weekday = (date("w", $first_day_of_month) == 0) ? 6 : date("w", $first_day_of_month) - 1;

            $appointments_by_day = [];
            foreach ($this->events as $appointment) {
                $day = (int)date("d", strtotime($appointment->getDatetime()));
                $appointments_by_day[$day][] = $appointment->getName();
            }

            // HTML-Tabelle für den Kalender erstellen
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";

            // Wochentage als Header (Montag bis Sonntag)
            $weekdays = ["Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"];
            echo "<tr>";
            foreach ($weekdays as $weekday) {
                echo "<th style='padding: 10px; text-align: center;'>$weekday</th>";
            }
            echo "</tr>";

            // Erste Zeile mit leeren Zellen, falls der Monat nicht an einem Montag beginnt
            echo "<tr>";
            // Leere Zellen für Tage vor dem ersten Tag des Monats
            for ($i = 0; $i < $first_weekday; $i++) {
                echo "<td class='calendar-day' style='padding: 10px;'></td>";
            }

            // Tage des Monats in der ersten Zeile einfügen
            $current_day = 1;
            for ($i = $first_weekday; $i < 7; $i++) {
                echo "<td class='calendar-day' style='padding: 10px; text-align: center;'>";
                echo $current_day;

                // Wenn es an diesem Tag Termine gibt, anzeigen
                if (isset($appointments_by_day[$current_day])) {
                    foreach ($appointments_by_day[$current_day] as $appointment_title) {
                        echo "<br><small>$appointment_title</small>";
                    }
                }

                echo "</td>";
                $current_day++;
            }
            echo "</tr>";

            // Weitere Wochen
            while ($current_day <= $days_in_month) {
                echo "<tr>";
                for ($i = 0; $i < 7; $i++) {
                    if ($current_day <= $days_in_month) {
                        echo "<td class='calendar-day' style='padding: 10px; text-align: center;'>";
                        echo $current_day;

                        // Wenn es an diesem Tag Termine gibt, anzeigen
                        if (isset($appointments_by_day[$current_day])) {
                            foreach ($appointments_by_day[$current_day] as $appointment_title) {
                                echo "<br><small>$appointment_title</small>";
                            }
                        }

                        echo "</td>";
                        $current_day++;
                    } else {
                        echo "<td class='calendar-day' style='padding: 10px;'></td>";
                    }
                }
                echo "</tr>";
            }

            echo "</table>";
        }

        public function createEvent($name, $date) {
            $this->events[] = new Event($this->id, $name, $date);
        }

        public function saveEvents() {
            require("connection.php");

            try {
                foreach ($this->events as $e) {
                    $stmt = $conn->prepare("INSERT INTO events(user_id, name, date) VALUES(:user_id, :name, :datetime)");
                    $stmt->bindParam(':user_id', $this->id);
                    $stmt->bindParam(':name', $e->getName());
                    $stmt->bindParam(':datetime', $e->getDatetime());
                    $stmt->execute();
                }
            } catch (Exception $e) {
                echo "Fehler beim Speichern der Events: " . $e->getMessage();
            }
        }

        public function loadEvents() {
            require("connection.php");

            try {
                $stmt = $conn->prepare('SELECT * FROM events WHERE user_id=:user_id');
                $stmt->bindParam(':user_id', $this->id);
                $stmt->execute();
                $result = $stmt->fetchAll();
                foreach ($result as $row) {
                    $this->events[] = new Event($row['user_id'], $row['name'], $row['date']);
                }
            } catch (Exception $e) {
                echo "Fehler beim Laden der Events: " . $e->getMessage();
            }
        }
    }

    class Event {
        protected $user_id;
        protected $name;
        protected $datetime;

        function __construct($user_id, $name, $datetime) {
            $this->user_id = $user_id;
            $this->name = $name;
            $this->datetime = $datetime;
        }

        public function getName() {
            return $this->name;
        }

        public function getDatetime() {
            return $this->datetime;
        }
    }
?>
