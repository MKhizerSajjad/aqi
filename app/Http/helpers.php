<?php
    function getStatusButton($status)
    {
        // return $status;
        switch ($status) {
            case '':
                return '';
            case 'Good':
                return '<span class="btn btn-success customBtn">Good</span>';
            case 'Moderate':
                return '<span class="btn btn-primary customBtn">Moderate</span>';
            case 'Bad':
                return '<span class="btn btn-danger customBtn">Good</span>';
            default:
                return '<span class="btn btn-secondary customBtn">'.$status.'</span>';
        }
    }
?>
