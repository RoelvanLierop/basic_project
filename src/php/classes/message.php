<?php

class Message {
    private $mysqli;
    private $data;

    private function initDatabase(){
        $this->mysqli = mysqli_connect(
            config('database.server'),
            config('database.user'), 
            config('database.password'),
            config('database.name')
        );
        
        // Check connection
        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->mysqli->connect_error;
            exit();
        }
    }
 
    public function getMessageCount(){
        $this->initDatabase();
        if( $result = $this->mysqli->query("SELECT COUNT(*) as messageCount FROM messages") ){
            $row = $result->fetch_assoc();
            return $row['messageCount'];
        };
        return '0';
    }

    public function get( $terms = new stdClass() ) {        
        unset( $terms->type );
        $this->initDatabase();
        $messages = [];
        $searchString = '';
        if( count( (array)$terms ) > 0 ) {
            $searchString = $this->addSearchConstraint( $terms );
        }

        if( $result = $this->mysqli->query("SELECT messages.message as message, users.display_name as author FROM messages INNER JOIN users ON messages.user_id=users.id" . $searchString) ){
            if( $result->num_rows > 0 ) {
                while( $row = $result->fetch_assoc() ){
                    $messages[] = $row;
                }
                return $messages;
            }
        };
        return 'No results found';
    }

    private function addSearchConstraint( $terms ) {
        $searchString = ' WHERE ';
        $searchCriteria = [];
        if( isset( $terms->data ) ) {
            $searchCriteria[] = "users.display_name LIKE'%" . $terms->data . "%'";
            $searchCriteria[] = "messages.message LIKE'%" . $terms->data . "%'";
        }
        return $searchString . implode(" OR ", $searchCriteria);
    }
}