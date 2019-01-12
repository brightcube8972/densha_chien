# densha_chien
iPhoneアプリ「電車遅延情報」のapiです。  
densha_chien.phpが本体でございまして、以下をrequire_onceしております。  
densha_chien_const_common.php  
densha_chien_const_area_tetsudokaisha.php  

本体の大まかな流れは、電車遅延用のrssを読み込み、必要な情報を取り出し配列に格納し、JSONでアプリに渡しています。
