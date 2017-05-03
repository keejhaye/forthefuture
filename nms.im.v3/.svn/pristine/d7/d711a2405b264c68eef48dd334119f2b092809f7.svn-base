<!doctype html>
<html>
  <head>
    <title>LOOP IM API</title>
    <link rel="icon" type="image/x-icon" href="http://im2.nmsloop.com/images/favicon.ico"/>
    <link href="public/css/api.css" rel="stylesheet" />
    <?php require_once 'config/api_doc.php';?>
  </head>
  <body>
    <div id="content">
      <h1>API</h1>
      <div class="datagrid">
        <table>
          <thead>
            <tr>
              <th></th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <?php $row=0?>
            <?php //die(var_dump($config))?>
            <?php foreach($config as $key => $value):?>
              <?php $class = ($row%2 != 0) ? "alt" : ""?>             
              <tr class="<?php echo $class?>">
                <td><a href="<?php echo $value["link"]?>" target="_blank"><?php echo $value["name"]?></a></td>
                <td><?php echo $value["description"]?></td>
              </tr>
              <?php $row++?>
            <?php endforeach?>
          </tbody>
        </table>
     </div>
    </div>
  </body>
</html>