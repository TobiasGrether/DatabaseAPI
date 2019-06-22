# DatabaseAPI


# Creating a Database Object
The Database API has some really simple mechanics. You can create one object to use for an infinite number of database connections<br />
For that you'll need to call the Method found in the DatabaseAPI, <br />
`DatabaseAPI::constructConnection`, what returns an new Instance of Connection.<br />
This method has the method header <br />`(String $host, String $user, String $password)`.<br />
`$host` is the IP-Address your MySQL-Server is running on,<br />
`$user` is the MySQL User you want to connect over<br />
`$password` is the password of the MySQL User.<br />

# Making Insertion Statements
Once you have a Connection Object,<br />
Making Insertion Statements is really simply<br />
The Method header for that is <br />
`(String $query, String $database, Closure $action, array $data)`<br />
`$query` is the MySQL Query you want to execute <br />
`$database` is the Name of the Database you want to do the Insertion in <br />
`$action` is the Closure which can be executed after the database has executed the query, for example for giving a success message<br />
`$data` is an Array of various DataTypes you can use in `$action` to access different System Parts. Please note that only primitive Data Types are allowed!<br />

# Making Selection Statements
Making Selection Statements in not really different
You have the Method Header
`(String $query, String $database, \Closure $datahandler = null, \Closure $action = null, array $data = [])`<br />
`$query` is the MySQL Query that should be executed<br />
`$database` is the Database name you want to run the Query in<br />
`$datahandler` Is the action you want to execute when the Task has received the data. The Return value of that will be passed to<br /> `$action` as $data<br />
`$action` is the Action that is executed after `$datahandler` was executed and all data was received. You can access Server and Plugin Objects with ease there, beceause that is no longer Part of the Asynchronous Thread<br />
`$data` is extra data which you can use in `$action` to Interact with different System Parts. Please not that as stated above, only primitive Data Types are allowed.<br />

## Examples

### Connection Creation
Here you have an example for an Connection Object Creation:
`$connection = DatabaseAPI::constructConnection("mysql.battlemc.de", "stats", "justanotherpassword");`<br />

### Insertion Statements
Here is an Insertion Statement example:
```
$connection->execute("INSERT INTO database_test(val) VALUES ('ABC')",
"database_test",
function($result, $extra){
  Server::getInstance()->getPlayerExact($extra["player"])->sendMessage($result);
 },
 ["player" => $player->getName()]);
 ```
 
 ### Selection Statement
 Here is an Selection Statement example:
 
```
$connection->executeQuery("SELECT * FROM players",
 "player_list", 
 function($result){
  $data = [];
   while($row = mysqli_fetch_assoc($result)){
    $data[] = $row["player_name"];
   }
   return $data;
  },
  function($result, $extra){
    Server::getInstance()->getPlayerExact($extra["player])->sendMessage("Online Players: " . implode(", ", $result));
  },
  ["player" => $player->getName()]);
  ```
