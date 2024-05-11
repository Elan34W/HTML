<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit(); // Ensure script execution stops after redirection
}

include("connection.php"); // Assuming connection.php contains database connection code

if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];
    
    // Fetch student data based on NIM
    $query = "SELECT * FROM student WHERE nim = '$nim'";
    $result = mysqli_query($connection, $query);
    
    if (!$result) {
        die("Query Error: " . mysqli_errno($connection) . " - " . mysqli_error($connection));
    }
    
    // Check if student exists
    if (mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
        $birth_date_parts = explode('-', $data["birth_date"]);
        $year = $birth_date_parts[0];
        $month = $birth_date_parts[1];
        $day = $birth_date_parts[2];
    } else {
        echo "Student not found.";
        exit(); // Stop script execution if student is not found
    }
} else {
    echo "NIM parameter is missing.";
    exit(); // Stop script execution if NIM parameter is missing
}

if (isset($_POST["submit"])) {
    $nim = htmlentities(strip_tags(trim($_POST["nim"])));
    $name = htmlentities(strip_tags(trim($_POST["name"])));
    $birth_city = htmlentities(strip_tags(trim($_POST["birth_city"])));
    $faculty = htmlentities(strip_tags(trim($_POST["faculty"])));
    $department = htmlentities(strip_tags(trim($_POST["department"])));
    $gpa = htmlentities(strip_tags(trim($_POST["gpa"])));
    $birth_date = htmlentities(strip_tags(trim($_POST["birth_date"])));
    $birth_month = htmlentities(strip_tags(trim($_POST["birth_month"])));
    $birth_year = htmlentities(strip_tags(trim($_POST["birth_year"])));

    $error_message="";

    if (empty($nim)) {
      $error_message .= "- NIM belum diisi <br>";
    }
    else if (!preg_match("/^[0-9]{8}$/",$nim) ) {
      $error_message .= "- NIM harus berupa 8 digit angka <br>";
    }

    $nim = mysqli_real_escape_string($connection, $nim);
    $query = "SELECT * FROM student WHERE nim='$nim'";
    $query_result = mysqli_query($connection, $query);

    $data_amount = mysqli_num_rows($query_result);

    if (empty($name)) {
      $error_message .= "- Nama belum diisi <br>";
    }

    if (empty($birth_city)) {
      $error_message .= "- Tempat lahir belum diisi <br>";
    }

    if (empty($department)) {
      $error_message .= "- Jurusan belum diisi <br>";
    }

    $select_ftib=""; $select_fteic="";

    switch ($faculty) {
      case 'FTIB':
        $select_ftib = "selected";
        break;
      case 'FTEIC':
        $select_fteic = "selected";
        break;
    }

    if (!is_numeric($gpa) OR ($gpa <=0)) {
      $error_message .= "- IPK harus diisi dengan angka";
    }

    if ($error_message === "") {
      $nim = mysqli_real_escape_string($connection, $nim);
      $name = mysqli_real_escape_string($connection, $name);
      $birth_city = mysqli_real_escape_string($connection, $birth_city);
      $faculty = mysqli_real_escape_string($connection, $faculty);
      $department = mysqli_real_escape_string($connection, $department);
      $birth_date = mysqli_real_escape_string($connection, $birth_date);
      $birth_month = mysqli_real_escape_string($connection, $birth_month);
      $birth_year  = mysqli_real_escape_string($connection, $birth_year);
      $gpa = (float) $gpa;

      $birth_date_full = $birth_year."-".$birth_month."-".$birth_date;

      $query = "UPDATE student SET name = '$name', 
                      birth_city = '$birth_city', 
                      birth_date = '$birth_date_full', 
                      faculty = '$faculty', 
                      department = '$department', 
                      gpa = '$gpa' 
                      WHERE nim = '$nim'";
      
      $result = mysqli_query($connection, $query);

      if($result) {
        $message = "Mahasiswa dengan nama = \"<b>$name</b>\" sudah berhasil diupdate";
        $message = urlencode($message);
        header("Location: student_view.php?message={$message}");
        exit(); // Stop script execution after redirection
      }
      else {
        die ("Query gagal dijalankan: ".mysqli_errno($connection). " - ".mysqli_error($connection));
      }
    }
  }
  else {
    $error_message = "";
    $nim = "";
    $name = "";
    $birth_city = "";
    $select_ftib = "selected";
    $select_fteic = ""; 
    $department = "";
    $gpa = "";
    $birth_date=1; 
    $birth_month="1"; 
    $birth_year=1996;
  }

  $arr_month = [
    "1"=>"Januari",
    "2"=>"Februari",
    "3"=>"Maret",
    "4"=>"April",
    "5"=>"Mei",
    "6"=>"Juni",
    "7"=>"Juli",
    "8"=>"Agustus",
    "9"=>"September",
    "10"=>"Oktober",
    "11"=>"Nopember",
    "12"=>"Desember"
  ];

  $arr_month_reverse = [
    "Januari" => "1",
    "Februari" => "2",
    "Maret" => "3",
    "April" => "4",
    "Mei" => "5",
    "Juni" => "6",
    "Juli" => "7",
    "Agustus" => "8",
    "September" => "9",
    "Oktober" => "10",
    "Nopember" => "11",
    "Desember" => "12"
  ];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Mahasiswa</title>
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div id="header">
        <h1 id="logo">Edit Data Mahasiswa</h1>
    </div>
    <hr>
    <nav>
        <ul>
            <li><a href="student_view.php">Kembali</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <h2>Edit Data Mahasiswa</h2>
    <?php if ($error_message !== ""): ?>
    <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <p>
            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" value="<?php echo $data['nim']; ?>">
        </p>
        <p>
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>">
        </p>
        <p>
            <label for="birth_city">Tempat Lahir:</label>
            <input type="text" id="birth_city" name="birth_city" value="<?php echo $data['birth_city']; ?>">
        </p>
        <p>
          <label for="birth_date" >Tanggal Lahir : </label>
            <select name="birth_date" id="birth_date">
              <?php
                for ($i = 1; $i <= 31; $i++) {
                  if ($i == $day){
                    echo "<option value=$i selected>";
                  }
                  else {
                    echo "<option value=$i>";
                  }
                  echo str_pad($i, 2, "0", STR_PAD_LEFT);
                  echo "</option>";
                }
              ?>
            </select>
            <select name="birth_month">
              <?php
                foreach ($arr_month as $key => $value) {
                  if ($key == $month){
                    echo "<option value=\"{$key}\" selected>{$value}</option>";
                  }
                  else {
                    echo "<option value=\"{$key}\">{$value}</option>";
                  }
                }
              ?>
            </select>
            <select name="birth_year">
              <?php
                for ($i = 1990; $i <= 2005; $i++) {
                if ($i == $year){
                    echo "<option value=$i selected>";
                  }
                  else {
                    echo "<option value=$i>";
                  }
                  echo "$i </option>";
                }
              ?>
            </select>
        </p>
        <p>
            <label for="faculty">Fakultas:</label>
            <select id="faculty" name="faculty">
                <option value="FTIB" <?php if($data['faculty'] == 'FTIB') echo 'selected'; ?>>FTIB</option>
                <option value="FTEIC" <?php if($data['faculty'] == 'FTEIC') echo 'selected'; ?>>FTEIC</option>
            </select>
        </p>
        <p>
            <label for="department">Jurusan:</label>
            <input type="text" id="department" name="department" value="<?php echo $data['department']; ?>">
        </p>
        <p>
            <label for="gpa">IPK:</label>
            <input type="text" id="gpa" name="gpa" value="<?php echo $data['gpa']; ?>">
        </p>
        <input type="submit" name="submit" value="Update">
    </form>
</div>
</body>
</html>
<?php
  mysqli_close($connection);
?>
