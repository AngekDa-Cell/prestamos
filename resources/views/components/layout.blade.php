<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos Colima</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/bootstrap-5.3.3-dist/css/bootstrap.min.css" />
    
    <!-- DataTables CSS -->
    <link href="/DataTables/datatables.css" rel="stylesheet"/>
    
    <!-- Custom CSS -->
    <link href="/assets/style.css" rel="stylesheet" />
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                @component("components.sidebar")
                @endcomponent
            </div>
            <div class="col-10">
                <div class="container">
                    @section("content")
                    @show
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="[https://code.jquery.com/jquery-3.7.1.min.js"></script>](https://code.jquery.com/jquery-3.7.1.min.js"></script>)
    
    <!-- DataTables JS -->
    <script src="/DataTables/datatables.js"></script>
</body>
</html>