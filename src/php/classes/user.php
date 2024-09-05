<?php

class User {
    private $mysqli;
    private $data;

    public function register( Object $data ):string {
        $this->data = $data;
        $validationData = Validator::check([
            'email' =>        [ $this->data->email,        "/[A-Za-z0-9\.\-_@]{8,128}/" ],
            'password' =>     [ $this->data->password,     "/[A-Za-z0-9\-_]{8,16}/" ],
            'display_name' => [ $this->data->display_name, "/[A-Za-z0-9\-_]{8,32}/" ]
        ]);
        
        if($validationData['valid'] === true ){
            $this->initDatabase();
            $this->writeUser();
            $this->authenticateUser();
            $this->mysqli->close();
        }
        return json_encode($validationData);
    }
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
    private function writeUser(){
        // Do a quick clean first
        $data = [
            'email' => htmlspecialchars(trim($this->data->email)),
            'display_name' => htmlspecialchars(trim($this->data->display_name)),
            'password' => password_hash(htmlspecialchars(trim($this->data->password)), PASSWORD_DEFAULT)
        ];

        // Insert user into database
        $this->mysqli->query("INSERT INTO users SET email='" . $data['email'] . "', display_name='" . $data['display_name'] . "', password='" . $data['password'] . "'");
    }
    public function authenticateUser() {
        $data = [
            'email' => htmlspecialchars(trim($this->data->email)),
            'password' => htmlspecialchars(trim($this->data->password))
        ];
        if( $result = $this->mysqli->query("SELECT email, password FROM users WHERE email='" . $data['email'] . "'") ){
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                if( password_verify( $data['password'], $row['password'] ) ) {
                    $_SESSION['USER'] = md5($row['email']);
                    return true;
                }
            }
        }
        return false;
    }
    public function login( Object $data ):string {
        $this->data = $data;
        $validationData = Validator::check([
            'email' =>        [ $this->data->email,        "/[A-Za-z0-9\.\-_@]{8,128}/" ],
            'password' =>     [ $this->data->password,     "/[A-Za-z0-9\-_]{8,16}/" ]
        ]);

        if($validationData['valid'] === true ){
            $this->initDatabase();
            $validationData['authenticated'] = $this->authenticateUser();
            $this->mysqli->close();
        }
        return json_encode($validationData);
    }
    public function getUserCount(){
        $this->initDatabase();
        if( $result = $this->mysqli->query("SELECT COUNT(*) as userCount FROM users") ){
            $row = $result->fetch_assoc();
            return $row['userCount'];
        };
        return '0';
    }
    public function get( $terms = new stdClass() ) {
        unset( $terms->type );
        $this->initDatabase();
        $users = [];
        $searchString = '';
        if( count( (array)$terms ) > 0 ) {
            $searchString = $this->addSearchConstraint( $terms );
        }
        if( $result = $this->mysqli->query("SELECT id, email, display_name FROM users" . $searchString) ){
            if( $result->num_rows > 0 ) {
                while( $row = $result->fetch_assoc() ){
                    $users[] = $row;
                }
                return $users;
            }
        };
        return 'No results found';
    }
    private function addSearchConstraint( $terms ) {
        $searchString = ' WHERE ';
        $searchCriteria = [];
        if( isset( $terms->id ) ) {
            $searchCriteria[] = "id='" . $terms->id . "'";
        }
        if( isset( $terms->data ) ) {
            $searchCriteria[] = "email LIKE'%" . $terms->data . "%'";
            $searchCriteria[] = "display_name LIKE'%" . $terms->data . "%'";
        }
        return $searchString . implode(" OR ", $searchCriteria);
    }
}