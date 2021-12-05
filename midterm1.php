<?php
/*
 * Le Duy Vu
 * Midterm 1
 * web page where the users can upload a text file, exclusively in .txt format,
 * which contains a string of 400 numbers
 */

echo <<<_HTML_SET_UP
<html>
    <head>
        <title>Midterm 1 Form Upload
        </title>
    </head>
    <body>
        <form method='post' action='midterm1.php' enctype='multipart/form-data'>
            Select a TXT file with a string of 400 numbers:
            <input type='file' name='filename' size='10'>
            <input type='submit' value='Upload'>
        </form>
    </body>
</html>
_HTML_SET_UP;

//Handle the file and its content
if ($_FILES && $_FILES['filename']['tmp_name']) // if a file has been chosen and uploaded
{
    $name = $_FILES['filename']['name'] ;
    
    if ($_FILES['filename']['type'] != 'text/plain')    // check file type
        echo "$name is not a txt file" ;
    else
    {
        echo "$name is uploaded successfully<br><br>" ;
        processFileContent(file_get_contents($_FILES['filename']['tmp_name'])) ;
    }
}
else echo "No file has been uploaded" ;

/*
 * Check the uploaded content and print an error message or notify the result to the screen.
 * @param the content of the file as a string
 */
function processFileContent($content)
{
    $content = preg_replace("/\s+/", "", $content) ;    // strip all white spaces
    
    if ((strlen($content) == 400) && preg_match("/^[0-9]*$/", $content))    // 400 digits
    {
        $numberMatrix = array_chunk(str_split($content), 20) ;  // arrange the numbers in a grid 20x20
        $result[0] = 0 ;    // $result stores the product and 4 numbers as an array
        
        $result = checkRows($numberMatrix, $result) ;   // update the result after checking all rows
        $result = checkColumns($numberMatrix, $result) ;    // update the result after checking all rows
        $result = checkDiagonalUpDown($numberMatrix, $result) ; // check all diagonal left->right up->down
        $result = checkDiagonalBottomUp($numberMatrix, $result) ; // check all diagonal left->right bottom->up
        
        echo "The greatest product of four adjacent numbers in all the four possible directions is $result[0]<br>" ;
        echo "The four adjacent numbers are $result[1], $result[2], $result[3], $result[4]" ;
    }
    else echo "Uploaded file has wrong format. Only string of 400 digits accepted" ;
}

/*
 * Find max product in all rows
 * @param $matrix the 20x20 grid
 * @param $result the result array
 * @return the updated result array
 */
function checkRows($matrix, $result)
{
    for ($row = 0; $row < 20; $row++)
        for ($col = 0; $col < 17; $col++)
        {
            $product = $matrix[$row][$col] * $matrix[$row][$col + 1]
            * $matrix[$row][$col + 2] * $matrix[$row][$col + 3] ;
            if ($product > $result[0])
            {
                $result[0] = $product ;
                $result[1] = $matrix[$row][$col] ;
                $result[2] = $matrix[$row][$col + 1] ;
                $result[3] = $matrix[$row][$col + 2] ;
                $result[4] = $matrix[$row][$col + 3] ;
            }
        }
    return $result;
}

/*
 * Find max product in all columns
 * @param $matrix the 20x20 grid
 * @param $result the result array
 * @return the updated result array
 */
function checkColumns($matrix, $result)
{
    for ($col = 0; $col < 20; $col++)
        for ($row = 0; $row < 17; $row++)
        {
            $product = $matrix[$row][$col] * $matrix[$row + 1][$col]
            * $matrix[$row + 2][$col] * $matrix[$row + 3][$col] ;
            if ($product > $result[0])
            {
                $result[0] = $product ;
                $result[1] = $matrix[$row][$col] ;
                $result[2] = $matrix[$row + 1][$col] ;
                $result[3] = $matrix[$row + 2][$col] ;
                $result[4] = $matrix[$row + 3][$col] ;
            }
        }
    return $result;
}

/*
 * Find max product in all diagonals from left to right, up down
 * @param $matrix the 20x20 grid
 * @param $result the result array
 * @return the updated result array
 */
function checkDiagonalUpDown($matrix, $result)
{
    for ($row = 0; $row < 17; $row++)
        for ($col = 0; $col < 17; $col++)
        {
            $product = $matrix[$row][$col] * $matrix[$row + 1][$col + 1]
            * $matrix[$row + 2][$col + 2] * $matrix[$row + 3][$col + 3] ;
            if ($product > $result[0])
            {
                $result[0] = $product ;
                $result[1] = $matrix[$row][$col] ;
                $result[2] = $matrix[$row + 1][$col + 1] ;
                $result[3] = $matrix[$row + 2][$col + 2] ;
                $result[4] = $matrix[$row + 3][$col + 3] ;
            }
        }
    return $result;
}

/*
 * Find max product in all diagonals from left to right, bottom up
 * @param $matrix the 20x20 grid
 * @param $result the result array
 * @return the updated result array
 */
function checkDiagonalBottomUp($matrix, $result)
{
    for ($row = 3; $row < 20; $row++)
        for ($col = 0; $col < 17; $col++)
        {
            $product = $matrix[$row][$col] * $matrix[$row - 1][$col + 1]
            * $matrix[$row - 2][$col + 2] * $matrix[$row - 3][$col + 3] ;
            if ($product > $result[0])
            {
                $result[0] = $product ;
                $result[1] = $matrix[$row][$col] ;
                $result[2] = $matrix[$row - 1][$col + 1] ;
                $result[3] = $matrix[$row - 2][$col + 2] ;
                $result[4] = $matrix[$row - 3][$col + 3] ;
            }
        }
    return $result;
}

/*
 * Tester function processFileContent()
 */
function processFileContentTester()
{
    processFileContent("") ;
    echo "Invalid input. Should print error message:<br>" ;
    
    echo "<br>" ;
    processFileContent("312331jd123123123123123123dsd039923929329") ;
    echo "Invalid input. Should print error message:<br>" ;
    
    echo "<br>" ;
    processFileContent("71636269561882670428
                        85861560789112949495
                        65727333001053367881
                        52584907711670556013
                        53697817977
                        846174064
                        83972241375657056057
                        8216637048440  3199890
                        96983520312774506326
                        125406987
                        47158523863
                        6689664895 0445244523
                        0588611 6467109405077
                        16427171	479924442928
                        17866458359124566529
                        242190  22671055626321
                        071984038509
                        62455444
                        84580156166097919133
                        62229893423380308135
                        7316717653	1330624919
                        30358907296290491560
                        70172427121883989797") ;
    echo "Correct input. Should print max product is 5832 and 4 numbers are 9, 9, 8, 9:<br>" ;
}
?>