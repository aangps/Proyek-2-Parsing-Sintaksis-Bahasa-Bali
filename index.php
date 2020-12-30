<?php
require 'validasi.php';
$dataKata = [];
// $hasil = [];
// $hasil2 = [];
$validator = [];
$input = [];


if (isset($_POST['submitSentenceValidation'])) {
    $input = $_POST['input'];
    $sentence = explode(PHP_EOL, $input);
    $hasil = sentenceValidation($input);
}

if (isset($_POST['submitTextValidation'])) {
    $input = getSplitText($_POST['text1']);
    $hasil2 = textValidation($_POST['text1']);
    $validator = setValidator($_POST['text2']);
    // var_dump($validator);
    // var_dump($hasil2);
    // var_dump($input);
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>PROJECT TBO</title>
</head>
<style>
    body {
    background-color: #f8f9fa;
    }

    header .section-header {
    background-color: #f8f9fa;
    }

    header .section-header .section-header-title h1 {
    font-weight: 400;
    }

    header .section-header .section-header-content p {
    font-size: 30px;
    }

    main .card .card-body .btn-elegant {
    position: relative;
    overflow: hidden;
    color: #fff;
    background-color: #2e2e2e !important;
    padding: .7rem 1.6rem;
    font-size: .7rem;
    }

    p#result span{
        font-weight: bold;
    }
    .message{
        padding: 3px 40px;
        background-color: #5dca88;
        cursor: pointer;
        box-shadow: 0 7px #1a7940;
    }
    .message:active{
        position: relative;
        top: 7px;
        box-shadow: none;
    }
</style>

<body>
    <script src="jquery.min.js"></script>

    <header>
        <div class="section-header text-center jumbotron">
            <div class="section-header-title">
                <h1 class="display-4">Application of CFG in Syntactic Parsing of Balinese Language</h1>
            </div>
            <div class="section-header-content">
                <p class="lead"> Project 2 TBO - Kelas D - Kelompok 1</p>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-5 pt-3 border">
                                     <h5 class="font-weight-bold mb-3">
                                        Validasi Berdasarkan Input List Text
                                    </h5>
                                    <div class="border-bottom mt-2 mb-3"></div>
                                    <form action="" class="px-3 py-2" method="POST">
                                        <div class="form-group">
                                            <label for="text1" class="font-weight-bolder">Silahkan masukkan list kalimat :</label>
                                            <textarea name="text1" id="text1" cols="40" rows="5" placeholder="Masukkan list kalimat yang akan divalidasi (Pisahkan dengan enter)" required></textarea>
                                            <label for="text2" class="mt-3 font-weight-bolder">Silahkan masukkan hasil validasi :</label>
                                            <textarea name="text2" id="text2" cols="40" rows="5" placeholder="Masukkan list validasi yang seharusnya dari setiap kalimat (Pisahkan dengan enter) ex : Valid | Tidak Valid" required></textarea>
                                        </div>
                                        <div class="form-group text-center">    
                                            <button type="submit" class="btn message" name="submitTextValidation">Validasi</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5 offset-1 ">
                                   <div class="border px-3 py-3">
                                        <h5 class="font-weight-bold mb-3">
                                            Validasi Berdasarkan Sentence
                                        </h5>
                                        <div class="border-bottom mt-2 mb-3"></div>
                                        <form id="formSearch" class="mb-2 text-center" action="" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-md-8">
                                                    <input type="text" class="form-control" id="input" aria-describedby="emailHelp" placeholder="Masukkan kalimat bahasa bali" name="input">
                                                </div>
                                                <div class="form-group col-md-4">    
                                                    <button id="submit" type="submit" class="btn message" name="submitSentenceValidation">Test</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3 ">
                                <div class="col-6">
                                    <p id="result" class="text-center"></p>
                                </div>
                            </div>
                            <div class="row justify-content-center mb-3">
                                <div class="col-11">
                                    <div id="result2">
                                    </div>     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
    <script>
        var grama = {};
        var validator = [];
        var dataKata = [];
        var hasilValidasi = [];
        var dataInputText = [];

        function getRules() {
            let link = 'rule.txt';
            let doc = '';
            $.ajax(link, {
                async: false,
                success: function(data) {
                    doc = data;
                },
                error: function(xhr) {
                    console.log('Error : File ' + link + '\nStatus : ' + xhr.status + ' ' + xhr.statusText);
                }
            });
            return doc;
        }

        function createRules() {
            var grama = {};
            var data = getRules().match(/[^\r\n]+/g);
            var data2 = [];
            var data3 = [];

            for (let i = 0; i < data.length; i++) {
                data2.push(data[i].split('>'));
                for (let j = 0; j < data2[i].length; j++) {
                    data3.push(data2[i][j].split(' '));
                }
            }

            // create object sesuai rule, i = key, i+1 = item
            for (var i = 0; i < data3.length; i = i + 2) {
                if (data3[i + 1].length == 4) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3]];
                } else if (data3[i + 1].length == 5) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4]];
                } else if (data3[i + 1].length == 6) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5]];
                } else if (data3[i + 1].length == 7) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6]];
                } else if (data3[i + 1].length == 8) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7]];
                } else if (data3[i + 1].length == 12) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7], data3[i + 1][8], data3[i + 1][9], data3[i + 1][10], data3[i + 1][11]];
                } else if (data3[i + 1].length == 13) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7], data3[i + 1][8], data3[i + 1][9], data3[i + 1][10], data3[i + 1][11], data3[i + 1][12]];
                } else if (data3[i + 1].length == 14) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7], data3[i + 1][8], data3[i + 1][9], data3[i + 1][10], data3[i + 1][11], data3[i + 1][12], data3[i + 1][13]];
                } else if (data3[i + 1].length == 21) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7], data3[i + 1][8], data3[i + 1][9], data3[i + 1][10], data3[i + 1][11], data3[i + 1][12], data3[i + 1][13], data3[i + 1][14], data3[i + 1][15], data3[i + 1][16], data3[i + 1][17], data3[i + 1][18], data3[i + 1][19], data3[i + 1][20]];
                }

            }
            return grama;
        }

        grama = createRules();

        //berfungsi untuk menentukan nilai setiap sel di cyk dimana baris > 0
        var iterasilanjutan = function(tab1, tab2, gram) {
            var wynik = [];
            for (var r in gram) {
                for (var i in gram[r]) {
                    if (tab1.indexOf(gram[r][i].charAt(0)) >= 0 && tab2.indexOf(gram[r][i].charAt(1)) >= 0) {
                        wynik.push(r);
                    }
                }
            }
            // console.log(wynik);
            return wynik;
        };

        function convert(tab) {
            var coba = {
                K: "A",
                S: "B",
                FN: "C",
                Nama: "D",
                P: "E",
                FA: "F",
                Kj: "G",
                O: "H",
                Ket: "I",
                Pn: "J",
                Bd: "K",
                Pr: "L",
                Pel: "M",
                Fp: "N",
                Ps: "O",
                FV: "P",
                Bil: "Q",
                Gt: "R",
                Sf: "S",
                Kt: "T"
            }
            for (let i = 0; i < tab[0].length; i++) {
                tab[0][i] = coba[tab[0][i]];

            }
            return tab;
        }


        var cyk = function(gram,tab){
            let len = dataKata.length/2;
            for (var j = 1; j <= len - 1; j++) {
                //iterasi kolom
                for (var i = 0; i <= len - j - 1; i++) {
                    tab[j][i] = [];
                    //iterasi atas pesanan yang lebih rendah dari kami
                    for (var k = 0; k < j; k++) {
                        //indeks sel yang dibandingkan
                        var baris = j - k - 1;
                        var kolom = i + k + 1;
                        //menambahkan aturan yang sesuai untuk satu pertandingan
                        tab[j][i] = tab[j][i].concat(iterasilanjutan(tab[k][i], tab[baris][kolom], gram));
                    }
                }
            }
            for (var m in tab[len - 1][0]) {
                if (tab[len - 1][0][m] === 'A') {
                    return true;
                }
            }
            return false;
        }

        function setTab(dataKata){
            var len = dataKata.length/2;
            let tab = new Array(len);
            for (var t = 0; t < len; t++) {
                tab[t] = new Array(0);
            }
            
            for(let i=0;i<len;i++){
                tab[0][i]=dataKata[i+i+1];
            }
            return convert(tab);
        }

        function doCYK(dataKata){
            return cyk(grama,setTab(dataKata));
        }

        function case1(input,dataKata) {
            try {
                if (doCYK(dataKata)) {
                   $('#result').html('"'+input+'" <br> Merupakan <span>Kalimat Baku</span>');
                } else {
                    $('#result').html('"'+input+'" <br> Merupakan <span>Kalimat Tidak Baku</span>');
                }
            } catch (error) {
                $('#result').html('"'+input+'" <br> Merupakan <span>Kalimat Tidak Baku</span>');
            }
        };

        function case2(dataKata)
        {
            try {
                if(doCYK(dataKata)){
                    return 'Valid';
                }else{
                    return 'Tidak Valid';
                }
            } catch (error) {
               return 'Tidak Valid';
            }
        }
        
        function setValidator() { 
            <?php for ($i = 0; $i<count($validator);$i++):?>
                validator.push('<?= $validator[$i]?>');
            <?php endfor; ?>
            return validator;
        }

        function setTable(a,b,c,d){
            $('#tBody').append('<tr><td class="text-center">'+(a+1)+'</td><td>'+b+'</td><td>'+c+'</td><td>'+d+'</td></tr>');
        }

        function setAkurasi(a,b){
            var totalA = a.length;
            var totalB = b.length;
            var count = 0;

            for(let i=0;i<totalA;i++){
                if(a[i]==b[i]){
                    count++;
                }
            }
            var persentase = count/totalA*100;
            $('#tBody').append(' <tr><td colspan="2" class="font-weight-bold text-center">Hasil Akurasi Dengan Menambahkan Data</td><td colspan="2" class="font-weight-bold text-center">'+count+'/'+totalA+'*100='+persentase+'%</td></tr>');
        }

        function headerValidasi(){
            $('#result2').append('<h5 class="font-weight-bold mb-3 text-center">Hasil Validasi</h5><table class="table table-hover table-bordered"><thead><tr><th scope="col" class="text-center">Nomor</th><th scope="col" class="text-center">Kalimat</th><th scope="col" class="text-center">Validasi (System)</th><th scope="col" class="text-center">Validasi (Asli)</th></tr></thead><tbody id="tBody"></tbody></table>');
        }

        <?php
            if (isset($hasil)) {
                echo "dataKata = [];";
                $dataKata = $hasil;
                for ($i=0;$i<count($dataKata);$i++) {
                    echo "
                        dataKata.push('$dataKata[$i]');
                    ";
                }
                echo "
                case1('".$input."',dataKata);
                ";
            } elseif (isset($hasil2)) {
                echo "
                    dataInputText = [];
                    setValidator();
                    headerValidasi();
                ";

                for ($k=0;$k<count($input);$k++) {
                    echo "
                        dataInputText.push('$input[$k]');
                    ";
                }

                for ($i = 0;$i<count($hasil2);$i++) {
                    echo "dataKata = [];";
                    $dataKata = $hasil2[$i];
                    for ($j=0;$j<count($dataKata);$j++) {
                        echo "
                            
                            dataKata.push('$dataKata[$j]');
                         ";
                    }
                    echo "
                        hasilValidasi.push(case2(dataKata));
                        setTable(".$i.",dataInputText[".$i."],hasilValidasi[".$i."],validator[".$i."]);
                    ";
                }
                echo "
                setAkurasi(hasilValidasi,validator);
                ";
            }
        ?>
    </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>