<?php

require_once ("database/Helper.php");

class UserForm
{
    /**
     * Display page
     */
    public static function displayUserForm() {
        echo self::getPageBeginningHTML();

        echo self::getUserFormHTML();

        echo self::getGoogleMapsWidgetHTML();

        echo self::getDatabaseOverview();

        echo self::getPageEndingHTML();
    }

    /**
     * Begin page
     * @return string
     */
    private static function getPageBeginningHTML() {
        $scriptsHTML = '';
        $scripts = [ 'jquery-3.4.1.min.js', 'userForm.js' ];
        foreach ($scripts as $script) {
            $scriptsHTML .= "<script type='text/javascript' src='scripts/$script'></script>";
        }

        $sql = <<<SQL
        SELECT apiKey FROM settings WHERE ID=:ID
SQL;
        $apiKey = Helper::executeSQL($sql, [ ':ID' => 1 ])['apiKey'];

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>User Form</title>
    $scriptsHTML
    <link href="css/userForm.css" rel="stylesheet" type="text/css" />
    <!--Load the API from the specified URL
    * The async attribute allows the browser to render the page while the API loads
    * The key parameter will contain your own API key (which is not needed for this tutorial)
    * The callback parameter executes the initMap() function
    -->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=$apiKey&callback=initMap">
    </script>
  </head>
  <body>
HTML;
    }

    /**
     * User Form
     * @return string
     */
    private static function getUserFormHTML() {
        $inputs = [
            'First Name' => 'firstName',
            'Last Name' => 'lastName',
            'Street / Number' => 'street',
            'City' => 'city',
            'Country' => 'country',
            'Add User' => 'addUser'
        ];
        $form = <<<HTML
<form class="userForm">
    <h2>User Details</h2>
    <fieldset>
HTML;
        foreach ($inputs as $attributeValue => $className) {
            if($attributeValue == 'Add User') {
                $form .= "<button type='submit'>$attributeValue</button>";
            } else {
                $form .= "<div class='$className'><label>Please enter $attributeValue</label><input type='text'  placeholder='$attributeValue'></div>";
            }
        }
        $form .= '</fieldset></form>';

        return $form;
    }

    /**
     * Google Maps
     * @return string
     */
    private static function getGoogleMapsWidgetHTML() {
        return '<div class="gMap" id="map"></div>';
    }

    /**
     * Database table overview
     * @return string
     */
    private static function getDatabaseOverview() {
        $sql = <<<SQL
            SELECT * FROM users WHERE 1=1;
SQL;
        $rows = Helper::executeSQL($sql);
        $table = <<<HTML
        <table class="table">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Street</th>
                <th>City</th>
                <th>Country</th>
                <th>Latitude</th>
                <th>Longitude</th>
            </tr>
HTML;
        foreach ($rows as $row) {
            $table .= <<<HTML
            <tr>
                <th>{$row['firstName']}</th>
                <th>{$row['lastName']}</th>
                <th>{$row['street']}</th>
                <th>{$row['city']}</th>
                <th>{$row['country']}</th>
                <th>{$row['latitude']}</th>
                <th>{$row['longitude']}</th>
            </tr>
HTML;
        }
        $table .= '</table>';
        return $table;
    }

    /**
     * End page
     * @return string
     */
    private static function getPageEndingHTML() {
        return <<<HTML
  </body>
</html>
HTML;
    }

    /**
     * @param array $data
     * @return string
     */
    public static function addUser($data = []) {
        $sql = <<<SQL
            INSERT INTO users(firstName, lastName, street, city, country, latitude, longitude)
                    VALUES (:firstName, :lastName, :street, :city, :country, :latitude, :longitude)
SQL;
        $params = [
            ':firstName' => $data['firstName'],
            ':lastName' => $data['lastName'],
            ':street' => $data['street'],
            ':city' => $data['city'],
            ':country' => $data['country'],
            ':latitude' => doubleval($data['lat']),
            ':longitude' => doubleval($data['lng'])
        ];
        $success = Helper::executeSQL($sql, $params, true);
        if ($success['success']) {
            return 'OK';
        } else {
            return 'Adding user failed.';
        }
    }
}
