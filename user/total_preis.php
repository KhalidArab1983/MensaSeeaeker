<?php
$selected_option = '';
if(isset($_POST['my_dropdown'])){
   $selected_option = $_POST['my_dropdown'];
}
?>

<form method="post" action="">
   <select name="my_dropdown">
      <option value="Option 1" <?php if($selected_option == 'Option 1'){echo 'selected';} ?>>Option 1</option>
      <option value="Option 2" <?php if($selected_option == 'Option 2'){echo 'selected';} ?>>Option 2</option>
      <option value="Option 3" <?php if($selected_option == 'Option 3'){echo 'selected';} ?>>Option 3</option>
   </select>
   <input type="submit" name="submit" value="Submit">
</form>

<?php
if($selected_option != ''){
   echo "Die ausgewählte Option ist: " . $selected_option;
}
?>