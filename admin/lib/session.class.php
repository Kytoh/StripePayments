<?php
class session{

    /**
     * Defines if the session has started
     * @var boolean
     */
    public $logged;

    /**
     * Defines the Username
     * @var varchar
     */
    public $username = '';

    /**
     * Database Object Connection
     * @var object
     */
    private $dbc = null;

    function __construct()
    {
        session_set_save_handler(
            array(&$this, 'open'), array(&$this, 'close'), array(&$this, 'read'),
            array(&$this, 'write'), array(&$this, 'destroy'), array(&$this, 'clean'));

        session_start();
    }

    function __destruct()
    {
        if ($this->logged) {
            session_write_close();
            $this->logged = false;
        }
    }

    public function login($username, $password)
    {
      session_start();
        if (!$this->logged) {
            $this->username = mysqli_real_escape_string($db, $username);
            $mypassword     = mysqli_real_escape_string($db, $password);

            $sql    = "SELECT id FROM admin WHERE username = '$myusername' and passcode = '$mypassword'";
            $result = mysqli_query($db, $sql);
            $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $active = $row['active'];

            $count = mysqli_num_rows($result);
            if ($count == 1) {
                self::registerSession;
                $this->logged = true;
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    private function registerSession()
    {
        session_register($this->username);
        $_SESSION['login_user'] = $this->username;
    }

    public static function isLogged()
    {
        session_start();
        $user_check = $_SESSION['login_user'];
        $ses_sql    = mysqli_query($db, "select username from admin where username = '$user_check' ");
        $row        = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
        if (count($row) <> 0) {
            $this->username = $row['username'];
            $this->logged   = true;
        } else {
            $this->logged = false;
        }
    }
}
?>