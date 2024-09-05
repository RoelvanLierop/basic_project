<?php
    session_start();
    define( 'ABSPATH', dirname( __FILE__, 3 ) . '/' );

    include( '../helpers/config.php' );

    $data = new stdClass();
    foreach( $_POST as $sKey => $mValue ) {
        $data->{$sKey} = htmlspecialchars( trim( $mValue ) );
    }

    if( $data->type === 'user' ) {
        include( '../classes/user.php' );
        $user = new User();
        $result = $user->get( $data );
        if( is_string( $result ) ) {
            echo '<div class="border-border-secondary p-1 row"><div class="col-12">' . $result . '</div></div>';
        } else {
            $returnString = '';
            foreach( $result as $i => $user){
                $returnString .= '<div class="border-border-secondary p-1 row">';
                $returnString .= '<div class="col-1">' . str_replace($data->id, '<span class="fw-bold text-success">' .$data->id. '</span>', $user['id']) . '</div>';
                $returnString .= '<div class="col-5">' . str_replace($data->data, '<span class="fw-bold text-success">' .$data->data. '</span>', $user['display_name']) . '</div>';
                $returnString .= '<div class="col-6">' . str_replace($data->data, '<span class="fw-bold text-success">' .$data->data. '</span>', $user['email']) . '</div>';
                $returnString .= '</div>';
            }
            echo $returnString;
        }
    } else if( $data->type === 'message' ) {
        include( '../classes/message.php' );
        $message = new Message();
        $result = $message->get( $data );
        if( is_string( $result ) ) {
            echo '<div class="border-border-secondary p-1 row"><div class="col-12">' . $result . '</div></div>';
        } else {
            $returnString = '';
            foreach( $result as $i => $message){
                $returnString .= '<div class="border border-secondary p-1 border rounded mt-3 p-3">';
                $returnString .= '<div class="col-12 text-small mb-2 fw-lighter">Posted By: ' . str_replace($data->data, '<span class="fw-bold text-success">' .$data->data. '</span>', $message['author']) . '</div>';
                $returnString .= '<div class="col-12">' . str_replace($data->data, '<span class="fw-bold text-success">' .$data->data. '</span>', $message['message']) . '</div>';
                $returnString .= '</div>';
            }
            echo $returnString;
        }
    }