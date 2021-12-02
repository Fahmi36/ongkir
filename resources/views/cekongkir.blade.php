<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Cek ongkir</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="antialiased">
        <!-- Pre-loader start -->
    <div class="page-loader" id="page-loader">
        <div>
            <div id="preloader">
                <div id="loadercss"></div>
            </div>
            <p id="text-loader"></p>
        </div>
    </div> 
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end --> 

    <div class="container mt-4">
        <div class="card card-details card-right">
            
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                    <select name="kurir" id="kurir" required="required" class="form-control">
                        <option value="" holder="">Pilih Kurir</option> 
                        <option value="jne">JNE</option> 
                        <option value="tiki">TIKI</option> 
                        <option value="pos">POS Indonesia</option>
                    </select> 
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" name="kota_tujuan" onkeyup="kotatujuan()" id="kotatujuan" placeholder="Pilih Kota tujuan">
                        <input type="hidden" id="idkota">
                        <div id="suggestions">
                            <div id="autoSuggestions"></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <input type="text"  class="form-control" name="berat" id="berat" placeholder="Masukan Berat KG">
                    </div>
                </div>
                <div class="col-12 table-responsive">
                    <div id="tableongkir">
                    </div>
                </div>
                    <hr>
                    <button type="submit" class="btn btn-success text-white mt-3">Proses</button>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                    $("button[type='submit']").click(function (e) { 
                        const CSRFToken = '{{csrf_token()}}';
                        $.ajax({
                        type: "POST",
                        url: "getOngkir",
                        data: {
                            _token: CSRFToken,
                            kurir:$('select[name=kurir] option').filter(':selected').val(),
                            kota:$("#idkota").val(),
                            berat:$("#berat").val(),
                        },
                        dataType: "json",
                        beforeSend:function(){
                            $("#text-loader").html('Mohon Tunggu');
				            $('#page-loader').fadeIn('slow');
                        },
                        success: function (response) {
				            $('#page-loader').fadeOut('slow');
                            var loop = '';
                            for (let i = 0; i < response.length; i++) {
                                loop += '<tr><td>'+response[i].service+'</td><td>Rp. '+parseFloat(response[i].cost[0].value, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+'</td><td>'+response[i].cost[0].etd+' Hari</td></tr>';
                                
                            }
                            $("#tableongkir").html('<table class="table table-striped"><thead><tr><td>Nama Layanan</td><td>Jumlah</td><td>Estimasi</td></tr></thead><tbody>'+loop+'</tbody></table>');
                        },error:function(e){
				        $('#page-loader').fadeOut('slow');
                       if(e.responseJSON.status == 400){
                           alert('Pastikan Isian sudah terisi');
                       }
                    }
                    });
                });
                
            });
            function kotatujuan(e) {	
                const CSRFToken = '{{csrf_token()}}';
                $.ajax({
                    url: 'https://laravel.pkkmart.com/api/getKota',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        kota: $("#kotatujuan").val(),
                        _token: CSRFToken
                        },
                    success:function(data) {
                        
                        var loop = "";
                        for (var a =0; a < data.length; a++) {
                            $('#suggestions').show();
                            $('#autoSuggestions').addClass('auto_list');
                            loop += '<a href="javascript:void(0);" onclick="pilihKota('+data[a].id_city+',\''+data[a].city_name+'\')"><li>'+data[a].city_name+',Provinsi '+data[a].province+'</li></a>';
                        }
                        $('#autoSuggestions').html(loop);      
                    }
                })
		    }
		function pilihKota(idkota,kota) {
				$("#idkota").val(idkota);           
				$("#kotatujuan").val(kota);  
				$('#suggestions').hide();  
		}

        </script>
    </body>
    </html>
