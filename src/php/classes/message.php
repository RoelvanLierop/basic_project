<?php
/**
 * @author Roel van Lierop <roel.van.lierop@gmail.com>
 */

/**
 * Message Class
 * 
 * Class for read Messages from the database
 * 
 */
class Message {

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
     * getMessageCount Method
     * 
     * Get the message count from the messages table
     * 
     * @uses Message::$mysqli
     * @return string Returns a string value representing the total number of items in the messages table
     */
    public function getMessageCount(): string
    {
        // Initialize Database
        $this->initDatabase();

        // Select count or return zero results
        if( $result = $this->mysqli->query("SELECT COUNT(*) as messageCount FROM messages") ){
            $row = $result->fetch_assoc();
            return strval( $row['messageCount'] );
        };
        return '0';
    }

    /**
     * get Method
     * 
     * Get the messages from the messages table, based on search terms (or none)
     * 
     * @var stdClass $terms A list of terms originating from the search form, parsed with Javascript
     * @uses Message::$mysqli
     * @return array|string Returns a array of messages, or a "no results found" message
     */
    public function get( $terms = new stdClass() ): array|string
    {        
        // Set variables to use later on
        $messages = [];
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
        if( $result = $this->mysqli->query("SELECT messages.message as message, users.display_name as author FROM messages INNER JOIN users ON messages.user_id=users.id" . $searchString) ){
            if( $result->num_rows > 0 ) {
                while( $row = $result->fetch_assoc() ){
                    $messages[] = $this->sanitizeOutput( $row );
                }
                return $messages;
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
     * Get the messages from the messages table, based on search terms (or none)
     * 
     * @var stdClass $terms A list of terms originating from the search form, parsed with Javascript
     * @used-by Message::get()
     * @return string Search string to add to the basic SELECT query 
     */
    private function addSearchConstraint( stdClass $terms ): string
    {
        // set variables to use
        $searchString = ' WHERE ';
        $searchCriteria = [];

        // Check if we have a "data" search term, then add the constraints
        if( isset( $terms->data ) ) {
            $searchCriteria[] = "users.display_name LIKE'%" . htmlspecialchars( trim( $terms->data ) ) . "%'";
            $searchCriteria[] = "messages.message LIKE'%" . htmlspecialchars( trim( $terms->data ) ) . "%'";
        }

        // return the new search constraint
        return $searchString . implode(" OR ", $searchCriteria);
    }

    /**
     * Destructor
     * 
     * Let's include a destructor megic function to kill MySQLi
     */
    public function __destruct()
    {
        $this->mysqli->close();
    }
}