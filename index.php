<?php
    define("PATH", "text_files");
    if(isset($_REQUEST['name']))
    {
        define("name", $_REQUEST['name']);
    }

    $activity = (isset($_REQUEST['a']) && in_array($_REQUEST['a'], [
        "landing", "edit", "read", "confirm"])) ? $_REQUEST['a'] . "View" : "landingView";
    $activity();

    /**
        Creates landing view
    */
    function landingView()
    {
        allView();
        ?>
        <form>
            <input type="hidden" name="a" value="edit">
            <input type="text" name="name" placeholder="Text File Name">
            <button>Create</button>
        </form>
            <div>
                <h2>My Files</h2>
                <table>
                    <tr><th>Filename</th><th colspan="2">Actions</th></tr>
                    <?php createTable();?>
                </table>
            </div>
        <?php
    }

    /**
        Creates edit view
    */
    function editView()
    {
       if (name == '' || ctype_space(name) || strcmp(name, preg_replace("/[^A-Za-z0-9 ]/", "", name)) != 0){
            header("Location: index.php");
       } 
        if(isset($_REQUEST['text']))
        {
            saveText($_REQUEST['text']);
        }
        allView();
        ?>
        <h2>Edit: <?php echo name;?></h2>
        <form>
            <input type="hidden" name="a" value="edit">
            <input type="hidden" name="name" value="<?php echo name. '.txt';?>">
            <button>Save</button>
            <a href="index.php"><button>Return</button></a>
        <br>
        <?php

        if(file_exists(PATH ."/". name. ".txt")){
            $file_content = file_get_contents(PATH ."/". name. ".txt");
        }
        else
        {
            $file_content = "";
        }
        ?>
        <textarea name="text" rows="10" cols="50"><?php echo $file_content;?></textarea>
        </form>
        <?php
    }

    /**
        Creates confirm view
    */
     function confirmView(){
        allView();
        if(isset($_REQUEST['delete'])){
            unlink(PATH."/".name.".txt");
            header("Location:index.php");
        }
        else{
        ?>
        <div>
            <p>Are you sure you want to delete the file: <b><?php echo name;?></b>?</p>
            <br>
            <a href="index.php?a=confirm&name=<?php echo name;?>&delete=yes"><button>Confirm</button></a>
            <a href="index.php"><button>Cancel</button></a>
        </div>
        <?php
        }
    }

    /**
        Creates read view
    */
    function readView(){
        allView();
        ?>
        <h2>Read: <?php echo name;?></h2>
        <div>
            <p><?php echo file_get_contents(PATH."/".name.".txt");?></p>
        </div>
        <?php
    }

    /**
        Loads the start of the page
    */
    function allView()
    {
        ?><!DOCTYPE html>
        <html>
           <head><title>Text Editor</title></head>
           <body><h1><a href="index.php">Simple Text Editor</a></h1></body>
        </html>
        <?php
    }

    /**
        Creates the table in the landing Page
    */
    function createTable(){
        $text_files = glob(PATH . "/*.txt");
        foreach ($text_files as $file_name) {
            $filename = basename($file_name, ".txt").PHP_EOL;
            ?>
            <tr><td> <a href="index.php?a=read&name=<?php echo $filename;?>"><?php echo $filename;?></a></td><td><a href="index.php?a=edit&name=<?php echo $filename;?>"><button>Edit</button></a></td><td><a href="index.php?a=confirm&name=<?php echo $filename;?>"><button>Delete</button></a></td></tr>
            <?php
        }
    }
 
    /**
        Saves the text file to the folder.
        $text - The text in the input
    */
    function saveText($text)
    {
        $newFile = fopen(PATH."/".name, "w");
        fwrite($newFile, $text);
        fclose($newFile);
        header("Location:index.php");
    }

    