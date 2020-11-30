<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>

        .top, header {
            background-color: #f9f9f9;
            padding: 15px;
        }

        .footer {
            margin-top: 15px;
        }

    </style>

</head>

<body>
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <button id="print_b"  class="btn btn-info mb-3 mt-2 w-100" style="text-align: center" >Print this page</button>
    </div>
</div>
<div class="page_wrapper" id="printableArea">

    <div class="container">

        <!-- top row start -->
        <div class="row">
            <div class="col-md-12">

                <div class="top">

                    <h1 style="text-align: center;">Green life lab and Hospital</h1>

                    <h3 style="text-align: center;">Dinajpur road, Saidpur, Nilphamari</h3>

                    <p style="text-align: center;">Mobile: 01717584852, 01914258471</p>

                </div>

            </div>
        </div>
        <!-- top row end -->

        <header class="mt-3">

            <hr>

            <!-- invoice info row start -->
            <div class="row">
                <div class="col-md-12">

                    <div class="invoice_info">

                        <table style="border-collapse: collapse; width: 100%;" class="table table-borderless">
                            <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    <p><strong>Invoice no</strong>: {{ $service->invoice }}</p>
                                    <p><strong>Date</strong>: {{ $service->date }}</p>
                                </td>

                                <td style="width: 50%;">
                                    <div id="demo" style="float: right;"></div>

                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
            <!-- invoice info row end -->

            <!-- invoice data row start -->
            <div class="row">
                <div class="col-md-12">

                    <div class="invoive">

                        <table style="border-collapse: collapse; width: 100%;" class="table table-borderless">

                            <tbody>

                            <tr>

                                <td style="width: 50%;">
                                    <p><strong>Name</strong>: {{ $service->patient->name }}</p>
                                    <p><strong>Age</strong>: {{ $service->patient->age }}</p>
                                    <p><strong>Delivery Date</strong>: {{ $service->delivery_date }}</p>
                                </td>

                                <td style="width: 50%; text-align: right;">
                                    <p><strong>Referred By</strong>: {{ $service->doctor_id ? $service->doctor->name : '--' }}</p>
                                    <p><strong>Gender</strong>: {{ $service->patient->gender }}</p>
                                </td>

                            </tr>

                            </tbody>

                        </table>



                    </div>

                </div>
            </div>
            <!-- invoice data row end -->

        </header>

        <hr>




        <!-- invoice table row start -->
        <div class="row">
            <div class="col-md-12">

                <div class="invoice_table">

                    <table style="border-collapse: collapse; width: 100%; height: 126px;" border="1">
                        <thead>
                            <tr>
                                <td style="width: 20%; text-align: center; height: 18px;"><strong>SL</strong></td>
                                <td style="width: 20%; text-align: center; height: 18px;"><strong>Item name</strong></td>
                                <td style="width: 20%; text-align: center; height: 18px;"><strong>Qty</strong></td>
                                <td style="width: 20%; text-align: center; height: 18px;"><strong>MRP</strong></td>
                                <td style="width: 20%; text-align: center; height: 18px;"><strong>Total</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service->lists as $list)
                                <tr>
                                    <td style="width: 20%; text-align: center; height: 18px;">
                                        <strong>{{ $loop->iteration }}</strong>
                                    </td>
                                    <td style="width: 20%; text-align: center; height: 18px;">
                                        <strong>{{ $list->test->name }}</strong>
                                    </td>
                                    <td style="width: 20%; text-align: center; height: 18px;">
                                        <strong>1</strong>
                                    </td>
                                    <td style="width: 20%; text-align: center; height: 18px;">
                                        <strong>{{ $list->cost }}</strong>
                                    </td>
                                    <td style="width: 20%; text-align: center; height: 18px;">
                                        <strong>{{ $list->cost }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>

                        <tr style="height: 18px;">

                            <td style="width: 40%; height: 18px;" colspan="2">&nbsp;</td>
                            <td style="width: 40%; height: 18px;text-align: right;" colspan="2">Subtotal (Tk)</td>
                            <td style="width: 20%; height: 18px; text-align: center;">{{$service->total_amount}}</td>

                        </tr>

                        <tr style="height: 18px;">

                            <td style="width: 40%; height: 18px;" colspan="2">&nbsp;</td>
                            <td style="width: 40%; height: 18px;text-align: right;" colspan="2">Discount (Tk)</td>
                            <td style="width: 20%; height: 18px; text-align: center;">{{$service->discount}}%</td>

                        </tr>

                        <tr style="height: 18px;">

                            <td style="width: 40%; height: 18px;" colspan="2">&nbsp;</td>
                            <td style="width: 40%; height: 18px;text-align: right;" colspan="2">Net Payable (Tk)</td>
                            <td style="width: 20%; height: 18px; text-align: center;">{{$service->amount_after_discount}}</td>

                        </tr>

                        <tr style="height: 18px;">

                            <td style="width: 40%; height: 18px;" colspan="2">&nbsp;</td>
                            <td style="width: 40%; height: 18px;text-align: right;" colspan="2">Total Paid (Tk)</td>
                            <td style="width: 20%; height: 18px; text-align: center;">{{$service->paid_amount}}</td>

                        </tr>

                        </tfoot>

                    </table>

                </div>

            </div>
        </div>
        <!-- invoice table row end -->

        <!-- footer row start -->
        <div class="row">
            <div class="col-md-12">

                <div class="footer">

                    <p>&nbsp;</p>

                    <p><strong>Prepared By</strong>: {{ $service->createdBy->name }}</p>

                    <p>
                        <a href="http://www.website.com">{{\Illuminate\Support\Facades\URL::to('/')}}</a> |
                        <strong>Email</strong>: <a href="mailto:info@mail.com">info@mail.com</a> |
                        <strong>Mobile</strong>: 01747856412 |
                        <strong>Address</strong>: Road 3/A, House: 34, Dinajpur Sadar, Nilphamari
                    </p>

                </div>

            </div>
        </div>
        <!-- footer row end -->

    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="{{asset('js/jquery-barcode.js')}}"></script>

<script>
    $("#demo").barcode(
    "{{$service->invoice}}",// Value barcode (dependent on the type of barcode)
    "code128",// type (string)
    );


    $(document).ready(function(){
        $("#print_b").click(function(){
            printDiv();
        });


    });

    function printDiv() {
        var printContents = document.getElementById("printableArea").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }



</script>

</body>

</html>
