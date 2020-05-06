<?php
 
# mesaj sorunsuz bir şekilde giderse, formun altında bir yazı çıkaracağız.
# yazı mesajını da PHP'de oluşturacağız. # Kodların en altına bakarsanız, 
# <div> etiketinden önce bir PHP kodu göreceksiniz.
# bu kod PHP içinde olduğu için, daha doğrusu aşağıdaki if döngüsünde 
# üretildiği için form sayfası ilk açıldığında hata verir.
# bu nedenle ilk başta if döngüsünden önce boş değerli metin mesajını oluşturuyoruz.
# burada oluşturmazsak (isterseniz silin satırı test edin), formun altında değişen
# bulunamadı diye hata verir.
 
$gittiMesaji = " ";
 
# ilk olarak submit diye bir POST değeri var mı diye bakılıyor.
# bunun anlamı da, bu sayfa Gönder butonuna tıklandığında mı gelmiş,
# yoksa sayfa ilk defa mı yükleniyor diyedir.
# eğer sayfa ilk yükleniyorsa, değişken değerleri gelmez.
# değerler olmadığı için de mail gönderme işlemi haliyle hata verecektir.
# bu nedenle bir if döngüsü içinde işlemlerimizi yapıyoruz.
# POST ile gelen submit değeri de <button> etiketinin name değeridir.
# formda yer alan name değerlerini dikkatlice inceleyin.
 
# eğer submit değeri gelmişse...
# bu submit değeri, butonun type’ından gelmektedir.
if (isset($_POST["submit"])) {
     
    # SMTP mail gönderimi yapacak olan sınıf yükleniyor.
    require("class.phpmailer.php");
 
    # POST ile gelen değişkenler alınıyor.
    # alınan değerler yine aynı isimde PHP değişkenlerine aktarılıyor.
    $isimsoyisim = $_POST['isimsoyisim'];
    $email = $_POST['email'];
    $mesaj = $_POST['mesaj'];
 
     
    # $mail adında bir PHPMailer sınıfı oluşturuluyor.
    # bu isimle sınıfa erişilecek.
    $mail = new PHPMailer();
     
    # PHPMailer sınıfı içindeki değişkenlere bazı değerler aktarılıyor.
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
     
    # tls veya ssl olarak mı gönderileceği belirtiliyor. 
    # bu seçimlere göre port numaraları değişecek.
    $mail->SMTPSecure = 'tls';
     
    # port numaraları veriliyor.
    # tls için 587, ssl için 465
    $mail->Port = 587;
     
    # Yandex üzerinden mail göndermek istiyorum.
    # Yandex'in smtp adresini yazıyorum.
    # eğer kendi domaininiz üzerinden göndermek istiyorsanız şu şekilde yazın.
    # smtp.domainismi.com
    $mail->Host = "smtp.yandex.com";
 
    # Yandex hesabımdaki mail adresimi yazıyorum.
    $mail->Username = "paragonteknoloi@gmail.com";
     
    # mail şifremi yazıyorum.
    $mail->Password = "Ankara66.";
     
    # mail gönderilirken bu hesaba erişilecek ve bunun üzerinden gönderilecek.
     
    # mail'de görünecek ismi değişken olarak alıyoruz.
    $mail->FromName ="$isimsoyisim";
     
    # hangi adresten geldiği de görünecek. 
    # yine aynı mail adresimizi yazıyoruz.
    $mail->SetFrom("paragonteknoloi@gmail.com");
     
    # görünecek adresi yine aynı yazıyoruz.
    $mail->AddAddress("paragonteknoloi@gmail.com");
     
    # mail başlığını düzenliyoruz.
    # SİTE MESAJI -> Uğur GELİŞKEN gibi bir mesaj formatı oluşturuyorum.
    $mail->Subject = "SİTE MESAJI -> $isimsoyisim";
     
    # mail içeriğini değişkenden alıyorum.
    $mail->Body = "$mesaj"; 
     
    # mail gönderme işlemi if döngüsü koşulunda yapılıyor, buna dikkat!
    # eğer mail gönderilirse true sonucu verecektir bu işlem.
    # bu sayede if döngüsü içine girilecek.
    # amacımız ziyaretçiyi durumdan haberdar etmek.
    if(!$mail->Send()){
        # eğer mail gitmemişse hata kodunu yazdırıyoruz.
        # ziyaretçi bu mesajı anlamaz ama geliştirici için önemlidir.
        echo "Hata: ".$mail->ErrorInfo;
    } else {
        # mail gitmişse de kodun en başında bahsetmiş olduğum
        # mesaj değişkenine değer atanıyor.
        # <br> ile bir alt satıra geçilip, bootstrap stili ile bir <p> etiketi oluşturuluyor.
        # yeşil uyarı mesajı ile Sayın Uğur GELİŞKEN, mesajınız gönderildi...
        # formatı ile mesajımızı yazdırıyoruz.
        $gittiMesaji = "<br><p class='bg-success'>Sayın $isimsoyisim, 
mesajınız gönderildi...</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <title>İLETİŞİM FORMU</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
 
<div class="container">
  <h2>Bize Yazın</h2>
  <form role="form" action="smtp_mail_formu.php" method="post">
    <div class="form-group">
      <label>İsim Soyisim</label>
      <input type="isimsoyisim" class="form-control" name="isimsoyisim"
placeholder="İsim Soyisim">
    </div>
    <div class="form-group">
      <label>E-Mail</label>
      <input type="email" class="form-control" name="email" placeholder="E-Mail">
    </div>
    <div class="form-group">
      <label>Mesaj</label>
      <textarea type="mesaj" class="form-control" name="mesaj" placeholder="Mesaj..."></textarea>
    </div>
    <button type="submit" name="submit" type="submit"
class="btn btn-default">Gönder</button>
  </form>
  <?php echo "$gittiMesaji"; ?>  
</div>
</body>
</html>
