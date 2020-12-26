<?php

// require_once 'functions.php';

// DB接続情報//作成したデータベース名を指定
$dbn = 'mysql:dbname=gsacf_d07_18;charset=utf8;port=3306;host=localhost';
$user = 'root';
$pwd = '';

// DB接続
try {
  $pdo = new PDO($dbn, $user, $pwd);
} catch (PDOException $e) {
  echo json_encode(["db error" => "{$e->getMessage()}"]);
  exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  // 画像を取得
  $sql = 'SELECT * FROM images ORDER BY created_at DESC';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $images = $stmt->fetchAll();
} else {
  // 画像を保存
  // if (!empty($_POST['name'])) {
  var_dump($_POST);
  var_dump($_FILES);
  $image_name = $_FILES['image']['name'];
  $image_type = $_FILES['image']['type'];
  $image_content = file_get_contents($_FILES['image']['tmp_name']);
  $image_size = $_FILES['image']['size'];
  $name = $_POST['name'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $distance = $_POST['distance'];
  $maximumAltitude = $_POST['maximumAltitude'];

  $sql = 'INSERT INTO images(image_name, image_type, image_content, image_size, created_at,name,date,time,distance,maximumAltitude)
                VALUES (:image_name, :image_type, :image_content, :image_size, now(),:name,:date,:time,:distance,:maximumAltitude)';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':image_name', $image_name, PDO::PARAM_STR);
  $stmt->bindValue(':image_type', $image_type, PDO::PARAM_STR);
  $stmt->bindValue(':image_content', $image_content, PDO::PARAM_STR);
  $stmt->bindValue(':image_size', $image_size, PDO::PARAM_INT);
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':date', $date, PDO::PARAM_STR);
  $stmt->bindValue(':time', $time, PDO::PARAM_STR);
  $stmt->bindValue(':distance', $distance, PDO::PARAM_STR);
  $stmt->bindValue(':maximumAltitude', $maximumAltitude, PDO::PARAM_STR);
  $stmt->execute();
  // }
  unset($pdo);
  header('Location:todo_input.php');
  exit();
}

unset($pdo);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>Image Test</title>

  <!DOCTYPE html>
  <html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登山記録</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style>
      .table {
        /* border-collapse: collapse; */
        table-layout: fixed;
      }

      .table th,
      .table td {
        /* border: 1px solid #CCCCCC; */
        padding: 5px 10px;
        text-align: left;
      }

      .table th {
        background-color: #FFFFFF;
      }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  </head>


<body>
  <!-- <form action="todo_create.php" method="POST">
    <fieldset>
      <legend>登山記録</legend>
      <a href="todo_read.php">一覧画面</a>
      <div>
        山名: <input type="text" name="name">
      </div>
      <div>
        日付: <input type="date" name="date">
      </div>
      <div>
        時間: <input type="time" value="00:00:00" step="300" name="time">
      </div>
      <div>
        距離: <input type="text" name="distance">
      </div>
      <div>
        最大標高: <input type="text" name="maximumAltitude">
      </div>
      <div>
        <button>submit</button>
      </div>
    </fieldset>
  </form> -->

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-8 border-right">
        <ul class="list-unstyled">
          <?php for ($i = 0; $i < count($images); $i++) : ?>
            <li class="media mt-5">
              <!-- <img class="image-button" src="image.php?id=<?= $images[$i]['image_id']; ?>" data-id="<?= $images[$i]['image_id'] ?>" width="500px" height="auto" class="mr-3">
              <script>
                $(".image-button").on('click', function() {

                  let A = $(this).data('id');
                  console.log(A);
                  // location.href = "read.php";
                  var postData = {
                    "first_name": "一郎",
                    "last_name": "鈴木"
                  };
                  $.post("read.php", postData);
                });
              </script> -->
              <a href="#lightbox" data-toggle="modal" data-slide-to="<?= $i; ?>">
                <img src="image.php?id=<?= $images[$i]['image_id']; ?>" data-id="<?= $images[$i]['image_id'] ?>" width="500px" height="auto" class="mr-3">
              </a>
              <div class="media-body">
                <h5><?= $images[$i]['name']; ?> (<?= $images[$i]['maximumAltitude']; ?>m)</h5>
                <h5>日時 <?= $images[$i]['date']; ?></h5>
                <h5>活動時間 <?= $images[$i]['time']; ?></h5>
                <h5>歩いた距離 <?= $images[$i]['distance'] / 1000; ?>km</h5>
                <a href="javascript:void(0);" onclick="var ok = confirm('削除しますか？'); if (ok) location.href='delete.php?id=<?= $images[$i]['image_id']; ?>'">
                  <i class="far fa-trash-alt"></i> 削除</a>
              </div>
            </li>
          <?php endfor; ?>
        </ul>
      </div>
      <div class="col-md-4 pt-4 pl-4">

        <form action="todo_create.php" method="post" enctype="multipart/form-data">
          <a href="data.php">集計画面</a><br>
          <h3>登山記録</h3>

          <table class="table">
            <thead>

            </thead>
            <tbody>
              <tr>
                <td>山名:</td>
                <td><input type="text" name="name"></td>
              </tr>
              <tr>
                <td>日付:</td>
                <td><input type="date" name="date"></td>
              </tr>
              <tr>
                <td>時間:</td>
                <td><input type="time" value="00:00:00" step="300" name="time"></td>
              </tr>
              <tr>
                <td>距離:</td>
                <td><input type="text" name="distance"></td>
              </tr>
              <tr>
                <td>最大標高:</td>
                <td><input type="text" name="maximumAltitude"></td>
              </tr>
              <tr>
                <td>画像を選択</td>
                <td><input type="file" name="image" required></td>
              </tr>
            </tbody>

          </table>
          <button type="submit" class="btn btn-primary">保存</button>

          
        </form>

      </div>
    </div>
  </div>

  <!-- モーダル -->
  <div class="modal carousel slide" id="lightbox" tabindex="-1" role="dialog" data-ride="carousel">
    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">

        <div class="modal-body">

          <!-- <ol class="carousel-indicators">
              <li data-target="#lightbox" data-slide-to="0"  class="active"></li>
              <li data-target="#lightbox" data-slide-to="1"  class="active"></li>
          </ol> -->

          <!-- <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="image.php?id=<?= $images[0]['image_id']; ?>" class="d-block w-100">
            </div>
            <div class="carousel-item">
              <img src="image.php?id=<?= $images[0]['image_id']; ?>" class="d-block w-100">
              <p><?= $images[0]['name']; ?></p>
            </div>
            <div class="carousel-item">
              <img src="image.php?id=<?= $images[0]['image_id']; ?>" class="d-block w-100">
              <p><?= $images[0]['time']; ?></p>
            </div>
          </div> -->
          <ol class="carousel-indicators">
            <?php for ($i = 0; $i < count($images); $i++) : ?>
              <li data-target="#lightbox" data-slide-to="<?= $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>
            <?php endfor; ?>
          </ol>

          <div class="carousel-inner">
            <?php for ($i = 0; $i < count($images); $i++) : ?>
              <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>">
                <img src="image.php?id=<?= $images[$i]['image_id']; ?>" class="d-block w-100">
                <p><?= $images[$i]['name']; ?></p>
              </div>
            <?php endfor; ?>
          </div>

          <a class="carousel-control-prev" href="#lightbox" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#lightbox" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>