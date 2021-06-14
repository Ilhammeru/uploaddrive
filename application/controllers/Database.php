<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Database extends CI_Controller {
    public function index() {
        $this->load->view('upload');
    }

    public function getDatabase() {
        $visitor = $this->load->database('visitor', TRUE);

        $this->myutil = $this->load->dbutil($visitor, TRUE);

        // Backup your entire database and assign it to a variable
        $prefs = array(   // Array of tables to backup.                 // List of tables to omit from the backup
            'format'        => 'txt',                       // gzip, zip, txt
            'filename'      => 'ansena_department' . '.sql',              // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
            'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
            'newline'       => "\n"                         // Newline character used in backup file
        );

        $backup = $this->myutil->backup($prefs);

        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file(site_url('ansena_department.sql'), $backup);

        // Load the download helper and send the file to your desktop
        echo $backup;
    }

    public function sosial() {
        $sosial = $this->load->database('sosial', TRUE);

        $this->myutil = $this->load->dbutil($sosial, TRUE);

        // Backup your entire database and assign it to a variable
        $prefs = array(   // Array of tables to backup.                 // List of tables to omit from the backup
            'format'        => 'txt',                       // gzip, zip, txt
            'filename'      => 'ansena_department' . '.sql',              // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
            'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
            'newline'       => "\n"                         // Newline character used in backup file
        );

        $backup2 = $this->myutil->backup($prefs);

        // Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file(site_url('ansena_department.sql'), $backup2);

        // Load the download helper and send the file to your desktop
        echo $backup2;
    }

    public function putDatabase() {
        // Initialize a file URL to the variable
        $url = 'http://localhost/databaseapi/database/sosial';

        // Use basename() function to return the base name of file 
        $file_name = basename($url);

        // Use file_get_contents() function to get the file
        // from url and use file_put_contents() function to
        // save the file by using base name
        if (file_put_contents('backup-' . date('Y-m-d') . '.sql', file_get_contents($url))) {
            $name = 'backup-' . date('Y-m-d') . '.sql';
            echo $name;
            // $this->uploadToDrive($name);
        } else {
            echo "File downloading failed.";
        }
    }

    public function uploadToDrive($name) {
        include 'vendor/autoload.php';

        // setting config untuk layanan akses ke google drive
        $client = new Google_Client();
        $client->setAuthConfig("credentials.json");
        $client->addScope("https://www.googleapis.com/auth/drive");
        $service = new Google_Service_Drive($client);

        // mengecek keberadaan token session
        if (empty($_SESSION['upload_token'])) {
            // jika token belum ada, maka lakukan login via oauth
            $authUrl = $client->createAuthUrl();
            header("Location:" . $authUrl);
        } else {
            // jika token sudah ada, maka munculkan form upload file

            // jika form upload disubmit
            // menggunakan token untuk mengakses google drive  
            $filePath = 'https://backup-db.ansena-sa.com/' . $name;
            $fileSource = file_get_contents($filePath);
            $client->setAccessToken($_SESSION['upload_token']);
            // membaca token respon dari google drive
            $client->getAccessToken();

            // instansiasi obyek file yg akan diupload ke Google Drive
            $file = new Google_Service_Drive_DriveFile();
            // set nama file di Google Drive disesuaikan dg nama file aslinya
            $file->setName('ansenasa-' . date('Y-m-d' . '.sql'));
            // proses upload file ke Google Drive dg multipart
            $result = $service->files->create($file, array(
                'data' => $fileSource,
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ));

            // menampilkan nama file yang sudah diupload ke google drive
            echo $result->id. "<br>";
            echo $result->name . "<br>";
        }

        // proses membaca token pasca login
        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            // simpan token ke session
            $_SESSION['upload_token'] = $token;
        }
    }
}
?>