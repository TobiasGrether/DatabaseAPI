# DatabaseAPI


# Creating a Database Object
The Database API has some really simple mechanics. You can create one object to use for an infinite number of database connections
For that you'll need to call the Method found in the DatabaseAPI, 
`DatabaseAPI::constructConnection`, what returns an new Instance of Connection.
This method has the method header `(String $host, String $user, String $password)`.
`$host` is the IP-Address your MySQL-Server is running on,
`$user` is the MySQL User you want to connect over
`$password` is the password of the MySQL User.


# Making Insertion Statements
Once you have a Connection Object,
Making Insertion Statements is really simply
The Method header for that is 
`(String $query, String $database, Closure $action, array $data)`
'$query` is the MySQL Query you want to execute <br />
`$database` is the Name of the Database you want to do the Insertion in <br />
`$action` is the Closure which can be executed after the database has executed the query, for example for giving a success message<br />
`$data` is an Array of various DataTypes you can use in `$action` to access different System Parts. Please note that only primitive Data Types are allowed!<br />

# Making Selection Statements
Making Selection Statements in not really different
You have the Method Header
`(String $query, String $database, \Closure $datahandler = null, \Closure $action = null, array $data = [])`
`$query` is the MySQL Query that should be executed
`$database` is the Database name you want to run the Query in
`$datahandler` Is the action you want to execute when the Task has received the data. The Return value of that will be passed to `$action` as $data
`$action` is the Action that is executed after `$datahandler` was executed and all data was received. You can access Server and Plugin Objects with ease there, beceause that is no longer Part of the Asynchronous Thread
`$data` is extra data which you can use in `$action` to Interact with different System Parts. Please not that as stated above, only primitive Data Types are allowed.

## Examples

### Connection Creation
Here you have an example for an Connection Object Creation:
`$connection = DatabaseAPI::constructConnection("mysql.battlemc.de", "stats", "justanotherpassword");`

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
