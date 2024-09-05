<?php
/**
 * @author Roel van Lierop <roel.van.lierop@gmail.com>
 */

/**
 * User Class
 * 
 * Class for read/write/validate actions for Users from the database
 * 
 */
class User {

    /** @var MySQLi $mysqli MySQLi instance */
    private $mysqli;

    /** @var stdClass $data User input data */
    private $data;

    /**
     * InitDatabase Method
     * 
     * Creates a Database connection based on the configuration we provided during install
     * 
     * @uses Message::$mysqli
     */
    private function initDatabase(): void 
    {
        // Connect to MySQLi
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

    /**
     * Register Method
     * 
     * Registering and validating a user against the Database 
     * 
     * @var stdClass $data User input, new registration data
     * @uses Validator Uses Validator for validating user input
     * @uses User::initDatabase() Uses initDatabase for a new connection
     * @uses User::writeUser() Uses writeUser for inserting a user into the Database
     * @uses User::authenticateUser() Uses authenticateUser for authenticating a user
     * @uses User::$mysqli Uses mysqli variable for closing the connection
     * @uses User::$data Uses data variable for handling data
     * @return string JSON String with validation data
     */
    public function register( stdClass $data ): string {
        // Set our data globally, so we can use it in writeUser and AuthenticateUser
        $this->data = $data;

        // Run our validator with RegEX checks
        $validationData = Validator::check([
            'email' =>        [ $this->data->email,        "/[A-Za-z0-9\.\-_@]{8,128}/" ],
            'password' =>     [ $this->data->password,     "/[A-Za-z0-9\-_]{8,16}/" ],
            'display_name' => [ $this->data->display_name, "/[A-Za-z0-9\-_]{8,32}/" ]
        ]);
        
        // If the validator is succesful, connect, write, and authenticate
        if($validationData['valid'] === true ){
            $this->initDatabase();
            $this->writeUser();
            $this->authenticateUser();
            $this->mysqli->close();
        }

        // Otherwise return the Validation data with errors
        return json_encode($validationData);
    }

    /**
     * writeUser Methos
     * 
     * Method for writing a user to the Database
     * 
     * @uses User::$mysqli Uses mysqli variable for closing the connection
     * @uses User::$data Uses data variable for handling data
     */
    private function writeUser(): void 
    {
        // Do a quick clean first
        $data = [
            'email' => htmlspecialchars( trim( $this->data->email ) ),
            'display_name' => htmlspecialchars( trim( $this->data->display_name ) ),
            'password' => password_hash(htmlspecialchars( trim( $this->data->password ) ), PASSWORD_DEFAULT )
        ];

        // Insert user into database
        $this->mysqli->query("INSERT INTO users SET email='" . $data['email'] . "', display_name='" . $data['display_name'] . "', password='" . $data['password'] . "'");
    }

    /**
     * authenticateUser Method
     * 
     * Method for writing a user to the Database
     * 
     * @uses User::$mysqli Uses mysqli variable for closing the connection
     * @uses User::$data Uses data variable for handling data
     * @return bool Return whether the user authenticated or not
     */
    public function authenticateUser(): bool
    {
        // Sanitize the object's data so we run with a clean set of data
        $data = [
            'email' => htmlspecialchars(trim($this->data->email)),
            'password' => htmlspecialchars(trim($this->data->password))
        ];

        // Get the user based on login info, return if action was succesful or not, and write a MD5 of the user's email address.
        if( $result = $this->mysqli->query("SELECT email, password FROM users WHERE email='" . $data['email'] . "' LIMIT 1") ){
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                if( password_verify( $data['password'], $row['password'] ) ) {
                    $_SESSION['USER'] = md5($row['email']);
                    return true;
                }
            }
        }
        return false;
    }


    public function validate(): bool
    {
        if( isset( $_SESSION['USER'] ) && strlen( $_SESSION['USER'] ) === 32 ) {
            $this->initDatabase();
            if( $result = $this->mysqli->query("SELECT email FROM users") ){
                while( $row = $result->fetch_assoc() ){
                    if( md5( $row['email'] ) === $_SESSION['USER'] ) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * login Method
     * 
     * Method for logging in a user by means of the login form
     * 
     * @var stdClass $data takes the user's form data
     * @uses Validator Uses Validator for validating user input
     * @uses User::$mysqli Uses mysqli variable for closing the connection
     * @uses User::$data Uses data variable for handling data
     * @return string Returns validation data
     */
    public function login( stdClass $data ): string {

        // Set our data globally, so we can use it in writeUser and AuthenticateUser
        $this->data = $data;

        // Run our validator with RegEX checks
        $validationData = Validator::check([
            'email' =>        [ $this->data->email,        "/[A-Za-z0-9\.\-_@]{8,128}/" ],
            'password' =>     [ $this->data->password,     "/[A-Za-z0-9\-_]{8,16}/" ]
        ]);

        // If the validator is succesful, authenticate
        if($validationData['valid'] === true ){
            $this->initDatabase();
            $validationData['authenticated'] = $this->authenticateUser();
            $this->mysqli->close();
        }

        // Return the validation data
        return json_encode($validationData);
    }

    /**
     * getUserCount Method
     * 
     * Get the user count from the users table
     * 
     * @uses User::$mysqli
     * @return string Returns a string value representing the total number of items in the users table
     */
    public function getUserCount(): string 
    {
        // Initialize Database
        $this->initDatabase();

        // Select count or return zero results
        if( $result = $this->mysqli->query("SELECT COUNT(*) as userCount FROM users") ){
            $row = $result->fetch_assoc();
            return strval( $row['userCount'] );
        };
        return '0';
    }

    /**
     * get Method
     * 
     * Get the users from the users table, based on search terms (or none)
     * 
     * @var stdClass $terms A list of terms originating from the search form, parsed with Javascript
     * @uses User::$mysqli
     * @return array|string Returns a array of users, or a "no results found" message
     */
    public function get( $terms = new stdClass() ): array|string
    { 
        // Set variables to use later on
        $users = [];
        $searchString = '';

        // Unset the search type, we know this at this point
        unset( $terms->type );

        // Create DB connection
        $this->initDatabase();

        // if we have terms, create a search constraint.
        if( count( (array)$terms ) > 0 ) {
            $searchString = $this->addSearchConstraint( $terms );
        }

        // Run query, process result and sanitize database output, otherwise return "No results found"
        if( $result = $this->mysqli->query("SELECT id, email, display_name FROM users" . $searchString) ){
            if( $result->num_rows > 0 ) {
                while( $row = $result->fetch_assoc() ){
                    $users[] = $this->sanitizeOutput( $row );
                }
                return $users;
            }
        };
        return 'No results found';
    }

    /**
     * sanitizeOutput Method
     * 
     * Sanitizes the output from the messages table
     * 
     * @var stdClass $terms A list of terms originating from the search form, parsed with Javascript
     * @return array Returns a sanitized Row of data;
     */
    private function sanitizeOutput( array $row ): array 
    {
        // Loop through row, and sanitize the data
        foreach( $row as $key => $value ) {
            $row[$key] = htmlspecialchars( trim ( $value ) );
        }
        return $row;
    }

    /**
     * addSearchConstraint Method
     * 
     * Constraints the amount of users originating from the users table, by returning a search constraint string.
     * 
     * @var stdClass $terms A list of terms originating from the search form, parsed with Javascript
     * @used-by User::get()
     * @return string Search string to add to the basic SELECT query 
     */
    private function addSearchConstraint( stdClass $terms ): string
    {
        // set variables to use
        $searchString = ' WHERE ';
        $searchCriteria = [];

        // Check if we have a "id" search term, then add the constraints
        if( isset( $terms->id ) ) {
            $searchCriteria[] = "id='" . htmlspecialchars( trim( $terms->id ) ) . "'";
        }

        // Check if we have a "data" search term, then add the constraints
        if( isset( $terms->data ) ) {
            $searchCriteria[] = "email LIKE'%" . htmlspecialchars( trim( $terms->data ) ) . "%'";
            $searchCriteria[] = "display_name LIKE'%" . htmlspecialchars( trim( $terms->data ) ) . "%'";
        }

        // return the new search constraint
        return $searchString . implode(" OR ", $searchCriteria);
    }
}