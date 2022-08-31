<?php


class BookTypeController
{
    public static function getBookTypes()
    {
        $connection = mysqli_connect('localhost', 'root', '', 'biblioteka');
        $query = "SELECT * FROM book_types";
        $result = $connection->query($query);
        if ($result->num_rows != 0) {
            $bookTypes = array();
            while ($row = $result->fetch_assoc()) {
                $bookTypes[] = $row;
            }
            return $bookTypes;
        }
    }
}