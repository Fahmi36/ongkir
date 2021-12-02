<?php
class Looping{
    function form(){
        echo "<form method='post'><input type='text' name='angka'><button type='submit' name='submit' value='kirim' class='btn'>Kirim</button></form>";
    }
    function submit($angka){
        $kel35 = 0;
        for($i=1; $i<=@$angka; $i++){ 
            if($i%3==0 && $i%5==0){
                $kel35++;
                echo $i.' : Pasar 20 Belanja Pangan<br>';
            }else if($i%3==0){
                if ($kel35 >= 2) {
                    echo $i.' : Belanja pangan<br>';
                }else{
                    echo $i.' : Pasar 20<br>';
                }
            }else if($i%5==0){
                if ($kel35 >= 2) {
                    echo $i.' : pasar 20<br>';
                }else{
                    echo $i.' : Belanja pangan<br>';
                }
            }else if ($kel35 >= 5) {
                break;
            }
        }
    }
}
    $loop = new Looping();
    echo $loop->form();
    if(isset($_POST['submit']))
    {
        $angka = $_POST['angka'];
        echo $loop->submit(@$angka);
    } 
?>