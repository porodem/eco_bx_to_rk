<?php
#30.06.2022 Получение JSON файла с сервера CRM Битрикс содержащего обращения, для дальнейшей загрузки в РК

session_start();

#echo var_dump($_SESSION);
#echo $filename;

$date_pattern = '/\d{2}\.\d{2}\.\d{4}/';

header('Content-Type: text/html; charset=windows-1251'); 

$bx_adr = "https://bitrix.ro-nso.ru/rest/1/fxw7s0au5yj0q2jv/exchange.get.lead.data?start=";

#14.06.2022%2000:00:00&end=21.06.2022%2024:00:00
    # start and end of period must be set
   if( isset($_GET["start"]) & isset($_GET["end"]) ) {
    #check that date formats is right (ex: 01.01.2021)
    if (preg_match($date_pattern, $_GET["start"]) & preg_match($date_pattern, $_GET["end"])) {

        #save name for file to save looked like crm_01012022_02022022.json
        $dstart = preg_replace('/\./', '', $_GET["start"]); # remove all dots (.) in date
        $dend = preg_replace('/\./', '', $_GET["end"]);

        # define file name
        if(isset($_SESSION['filetodownload'])) {
            $filename = $_SESSION['filetodownload'];
        } else {
        $filename = 'crm_'. $dstart . '_' . $dend .'.json';
        }
        $_SESSION['filetodownload'] = $filename;

      $newURL =  $bx_adr. $_GET['start'] . "%2000:00:00&end=" . $_GET['end'] . "%2024:00:00";
      # receive data from bitrix URL
      $json = file_get_contents($newURL);
      #fix encoding to get rid of elements like \u0041 etc.
      $json = json_decode($json, true);
      $json = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        #$encod = mb_detect_encoding($json);
        #$data = mb_convert_encoding($json, 'UTF-8','ASCII');
        #echo $encod;
        #var_dump(json_decode($json, JSON_UNESCAPED_UNICODE));
        #var_dump(json_decode($json));

    #remove usless head of string (we need only one big array inside single json element)
    $pattern = '/,"time.*/';
    $replacement = '';
    $json = preg_replace($pattern, $replacement, $json);

    #cut uleless tail
    $pattern = '/.*result":/';
    $json = preg_replace($pattern, $replacement, $json);
      

      $file = fopen(__DIR__ .'/' . $filename,'w');
        #$file = mb_convert_encoding($file, "windows-1251", "utf-8");
      fwrite($file, $json);
      fclose($file);
        #file_put_contents('text.txt', $data);
      #exit();

      echo 'File <b>' . $filename . '</b> ready to download!';
      ?>

    <form class="form-download" action="download_query.php" align="center">
		<input class="button-download" type="submit" name="doDownload" value="Download JSON">
	</form>

      <?php
      #exit(); #don't show input after json is ready
    } else {
        echo 'Non valid adress format! Must be like: 01.04.2022';
    }
   } else {
    echo 'Input dates, for example 23.06.2022 and 23.06.2022<br>';
   }
?>
<html>
   <body>
   
      <form action = "<?php $_PHP_SELF ?>" method = "GET">
         start: <input type = "text" name = "start" />
         end: <input type = "text" name = "end" />
         <input type = "submit" />
      </form>
      
   </body>
</html>