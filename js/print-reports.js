function printReportHandler( title ) {
    var closing  = document.getElementById('printClosing').innerHTML;
    var orders   = document.getElementById('printOrders').innerHTML;
    var payment  = document.getElementById('printPayments').innerHTML;
    var expenses = document.getElementById('printExpenses').innerHTML;
    var myWindow = window.open('', 'my div', 'height=400,width=600');
    myWindow.document.write('<html><head><title>' + title + '</title>');
    myWindow.document.write('<link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">');
    myWindow.document.write('<link rel="stylesheet" href="css/vertical-layout-light/style.css">');
    myWindow.document.write('</head><body >');
    if( closing ){
        myWindow.document.write('<div class="row">');
        myWindow.document.write(closing);
        myWindow.document.write('</div>');
    }

    if( orders ){
        myWindow.document.write('<div class="row">');
        myWindow.document.write(orders);
        myWindow.document.write('</div>');
    }

    if( payment ){
        myWindow.document.write('<div class="row">');
        myWindow.document.write(payment);
        myWindow.document.write('</div>');
    }

    if( expenses ){
        myWindow.document.write('<div class="row">');
        myWindow.document.write(expenses);
        myWindow.document.write('</div>');
    }
    myWindow.document.write('<script src="vendors/js/vendor.bundle.base.js" />');
    myWindow.document.write('</body></html>');
    myWindow.document.close(); // necessary for IE >= 10

    myWindow.onload=function(){ // necessary if the div contain images

        myWindow.focus(); // necessary for IE >= 10
        myWindow.print();
        myWindow.close();
    };
}