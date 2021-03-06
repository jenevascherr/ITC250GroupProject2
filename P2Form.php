<?php
//saladTruck.php
define('THIS_PAGE', basename($_SERVER['PHP_SELF'])); //figure out the name of the current page
$greens[] = new Ingredient(1,"Iceberg Lettuce",0);
$greens[] = new Ingredient(2,"Romaine Lettuce",0);
$greens[] = new Ingredient(3,"Spinach",0);
$greens[] = new Ingredient(4,"Arugula",0);
$veggies[] = new Ingredient(5,"Tomato",0);
$veggies[] = new Ingredient(6,"Cucumber",0);
$veggies[] = new Ingredient(7,"Bell Pepper",0);
$veggies[] = new Ingredient(8,"Mushroom",0);
$meat[] = new Ingredient(9,"Chicken",1.5);
$meat[] = new Ingredient(10,"Salmon",1.5);
$meat[] = new Ingredient(15,"Shrimp",1.5);
$meat[] = new Ingredient(11,"Steak",2);
$extras[] = new Ingredient(14,"Croutons",0);
$extras[] = new Ingredient(12,"Cheddar Cheese",.25);
$extras[] = new Ingredient(13,"Feta Cheese",.5);
$order = array();
$subtotal = 0;
//create list of ingredients for reference
$db = array();
foreach ($greens as $i) {
  $db[] = $i;
}foreach ($veggies as $i) {
  $db[] = $i;
}foreach ($meat as $i) {
  $db[] = $i;
}foreach ($extras as $i) {
  $db[] = $i;
}

class Ingredient
{
    public $ID = 0;
    public $Name = '';
    public $Price = 0;


    public function __construct($ID,$Name,$Price)
    {
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Price = $Price;

    }#end Ingredient constructor

}#end Ingredient class

/**
 *
 */
class Salad
{
  private $ingredients = array();
  private $price = 0;
  private $amount = 0;
  function __construct($price,$amount)
  {
    $this->price = $price;
    $this->amount = $amount;
  }
  public function getNgrdts() {
    return $this->ingredients;
  }
  public function getPrice() {
    return $this->price;
  }
  public function getAmount() {
    return $this->amount;
  }
  public function addNgrdt($n) {
    $this->ingredients[] = $n;
  }
}

if (!isset($_POST['submit'])) //if nothing is ordered, show form
{
    echo '<form action = "' . $_SERVER['PHP_SELF'] . '"  method = "POST"> ';
    echo '
      <h1>Salads</h1>
      <h2>$5.99</h2>
    ';
    foreach($greens as $tempNgrdt)
    {
        createMenu($tempNgrdt);
    }

    echo'<br>';

    foreach($veggies as $tempNgrdt)
    {
        createMenu($tempNgrdt);
    }

    echo'<br>';

    foreach($meat as $tempNgrdt)
    {
        createMenu($tempNgrdt);
    }

    echo'<br>';

    foreach($extras as $tempNgrdt)
    {
        createMenu($tempNgrdt);
    }

    echo'<br>';
    echo '
      <br>
      <select name="amount">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
      </select>&nbsp;
      <input type = "submit" name = "submit" value ="Submit Order">
      </form><br>
    ';#end form
}//end if
else
{
    //variable to keep track of salad ingredients
    $salad = array();
    //executes if form has been submitted
    if(isset($_POST['submit'])){
        //executes if anything has been selected
        if(!empty($_POST['menuNgrdts'])) {
            //base salad price
            $saladPrice = 5.99;
            //look at selected ingredients
            foreach($_POST['menuNgrdts'] as $value){
                //search database for matches
                foreach ($db as $ngrdt) {
                    //find ingredient
                    if ($value==$ngrdt->ID) {
                        //add ingredient price to salad price
                        $saladPrice += $ngrdt->Price;
                        //add ingredient to list
                        $salad[] = $ngrdt->Name;
                        //signal end of search
                        break;
                    }//end ingredient lookup
                }//end database search
            }//end of ingredient lookup
        }//end if for selected ingredients
    }//end of check for $_POST
    //store desired number of salads
    $a = $_POST['amount'];
    //multiply salad price by number ordered
    $newSalad = new Salad($saladPrice,$a);
    foreach ($salad as $s) {
      $newSalad->addNgrdt($s);
    }
    $order[] = $newSalad;
    $sum = $saladPrice * intval($a);
    //add salad price to subtotal
    $subtotal += $sum;
    $tax = $sum*.096;
    $total = $subtotal + $tax;

    printSalad($newSalad);
    echo 'Subtotal: $' . number_format($sum, 2) . '';
    echo '<br>';
    echo 'Tax: $' . number_format($tax, 2) . '';
    echo '<br>';
    echo 'Total: $' . number_format($total, 2) . '';

    echo'<br>';
    echo '<a href='. $_SERVER['PHP_SELF'] .'> Add to order</a>';
}//ingredients ordered, display totals

function createMenu($tempNgrdt){
    $i = $tempNgrdt->ID;
    $p = $tempNgrdt->Price;
    echo'
    <input type="checkbox" name="menuNgrdts[]" value="'.$i.'">'.$tempNgrdt->Name.'
    ';
    if ($p!= 0) {
      $d = 0;
      if (floor($p)!= $p) {
          $d = 2;
      }
    echo '$'.number_format($p,$d);
    }
}
function printSalad($s){
  $i = $s->getNgrdts();
  echo
    implode(', ',$i).'&nbsp;&times;&nbsp;'.$s->getAmount().
    '&nbsp;&commat;&nbsp;$'.number_format($s->getPrice(),2).'<br>';
}
