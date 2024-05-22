<?php 
require_once '../init.php';
	if (isset($_POST) && !empty($_POST)) {
		 $issueData = $_POST['issuedate'];
		 $customer = $_POST['customer'];


		$data = explode('-', $issueData);
	  $issu_first_date = $obj->convertDateMysql($data[0]);
		$issu_end_date = $obj->convertDateMysql($data[1]);
$qty=0;
      if ($customer == 'all') {
    $stmt = $pdo->prepare("
        SELECT purchase_payment.`payment_date`,purchase_payment.`suppliar_id`, suppliar.name , purchase_payment.`payment_amount` , purchase_payment.`payment_type` FROM purchase_payment INNER JOIN suppliar ON purchase_payment.suppliar_id = suppliar.id WHERE `payment_date` BETWEEN '$issu_first_date' AND '$issu_end_date';
      ");
    $stmt->execute();
      $res = $stmt->fetchAll(PDO::FETCH_OBJ);
      if ($res) {
        ?>
          <?php 
          $i=0;
              foreach ($res as $data) {
                $i++;
                $items=""; 
                $query=$pdo->prepare("SELECT * FROM purchase_products WHERE purchase_suppliar='$data->suppliar_id' AND purchase_date='$data->payment_date'");
                $query->execute();
                $result=$query->fetchAll(PDO::FETCH_OBJ);
                if($result){
                  foreach($result as $row){
                    $items .=$row->purchase_quantity." - ".$row->product_name."<br>";;
                    $qty += $row->purchase_quantity;
                  }
                }
                ?>
                  <tr>
                    <td><?=$i;?></td>
                    <td><?=$data->payment_date;?></td>
                    <td><?=$data->suppliar_id;?></td>
                    <td><?=$data->name;?></td>
                    <td><?=$items;?></td>
                    <td><?=$data->payment_type;?></td>
                    <td><?=$data->payment_amount;?></td>
                  </tr>
                <?php 
              }
           ?>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>          
          <th>Total : <?=$qty;?></th>
          <th>
           <?php 
                  $stmt = $pdo->prepare("SELECT SUM(`payment_amount`) FROM `purchase_payment` WHERE `payment_date` BETWEEN '$issu_first_date' AND '$issu_end_date'");
                $stmt->execute();
                $res = $stmt->fetch(PDO::FETCH_NUM);
                echo $res[0];
            ?>
          </th>
        </tr>
        <?php 
      }else{
         echo "no data found"; 
      }
  }else{
          $stmt = $pdo->prepare("
        SELECT purchase_payment.`payment_date`, purchase_payment.`suppliar_id`, suppliar.`name` , purchase_payment.`payment_amount` , purchase_payment.`payment_type` FROM purchase_payment INNER JOIN suppliar ON purchase_payment.`suppliar_id` = suppliar.`id` WHERE `payment_date` BETWEEN '$issu_first_date' AND '$issu_end_date' AND purchase_payment.`suppliar_id` = '$customer'
      ");
    $stmt->execute();
      $res = $stmt->fetchAll(PDO::FETCH_OBJ);

       if ($res) {
        ?>
          <?php 
          $i=0;
             foreach ($res as $data) {
                $i++;
                $items=""; 
                $query=$pdo->prepare("SELECT * FROM purchase_products WHERE purchase_suppliar='$data->suppliar_id' AND purchase_date='$data->payment_date'");
                $query->execute();
                $result=$query->fetchAll(PDO::FETCH_OBJ);
                if($result){
                  foreach($result as $row){
                    $items .=$row->purhcase_quantity." - ".$row->product_name."<br>";;
                    $qty += $row->purchase_quantity;
                  }
                }
                ?>
                  <tr>
                    <td><?=$i;?></td>
                    <td><?=$data->payment_date;?></td>
                    <td><?=$data->suppliar_id;?></td>
                    <td><?=$data->name;?></td>
                    <td><?=$items;?></td>
                    <td><?=$data->payment_type;?></td>
                    <td><?=$data->payment_amount;?></td>
                  </tr>
                <?php 
              }
           ?>
        <tr>
          <th></th>
          <th></th>
          <th></th>          
          <th></th>
          <th>Total : <?=$qty;?></th>
          <th>
             <?php  
                $stmt = $pdo->prepare("SELECT SUM(`payment_amount`) FROM `purchase_payment` WHERE `payment_date` BETWEEN '$issu_first_date' AND '$issu_end_date' AND `suppliar_id` = $customer");
                $stmt->execute();
                $res = $stmt->fetch(PDO::FETCH_NUM);
                echo $res[0];
            ?>
          </th>
        </tr>
        <?php 
      }else{
         echo "No data found"; 
      }
  }


	   


	}
 ?>