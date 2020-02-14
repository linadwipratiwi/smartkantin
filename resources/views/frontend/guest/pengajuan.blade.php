<!--  jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- Isolated Version of Bootstrap, not needed if your site already uses Bootstrap -->
<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

<!-- Bootstrap Date-Picker Plugin -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />

<body bgcolor="#E6E6FA">

    <h1>FORMULIR KESEDIAAN PAPARAN DAN DEMO PROGRAM KSE</h1>
    <form action="/GuestController" method="POST">
        {{ csrf_field() }}
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">

                        Nama Sekolah <p></p><input class="form-control" type="text" name="Nama Sekolah" style="width:16%;">
                        <br>

                    </div>
                </div>
            </div>
        </div>



        <br><br>
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        Nama Kepala Sekolah <p></p><input type="text" name="Nama Kepala Sekolah" style="width:16%;">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>

        <br><br>
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        Alamat <p></p><input type="text" name="Alamat" style="width:16%;">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>


        <br><br>
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        Alamat email <p></p><input type="text" name="Alamat Email" style="width:16%;">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>

        <br><br>
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        Jumlah Peserta Didik <p></p><input type="text" name="Jumlah Peserta Didik" style="width:16%;">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>

        <br><br>
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        Jumlah Pedagang di Kantin Sekolah <p></p><input type="text" name="Jumlah Pedagang di Kantin Sekolah" style="width:16%;">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>

        <br><br>
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        Nama Koperasi <p></p><input type="text" name="Nama Koperasi" style="width:16%;">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>

        <br><br>
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <!-- Form code begins -->

                        <div class="form-group">
                            <!-- Date input -->
                            <label class="control-label">Rencana Jadwal Paparan</label>
                            <input class="form-control" id="Rencana Jadwal Paparan" name="Rencana Jadwal Paparan" placeholder="MM/DD/YYY" type="text" />
                        </div>
                        <!-- Form code ends -->
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                var date_input = $('input[name="Rencana Jadwal Paparan"]'); //our date input has the name "Rencana Jadwal Paparan"
                var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
                var options = {
                    format: 'mm/dd/yyyy',
                    container: container,
                    todayHighlight: true,
                    autoclose: true,
                };
                date_input.datepicker(options);
            })
        </script>

        <br>
        <p>DIISI OLEH</p>

        <br><br>
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        Nama <p></p><input type="text" name="Nama" style="width:16%;">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>

        <br><br>
        <div class="bootstrap-iso">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        No Ponsel/WA<p></p><input type="text" name="No" style="width:16%;">
                        <br><br>
                    </div>
                </div>
            </div>
        </div>



        <button type="submit">Submit</button>
    </form>