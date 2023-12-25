function printDivHandler( id, title ) {
    var data=document.getElementById(id).innerHTML;
    var myWindow = window.open('', 'my div', 'height=400,width=600');
    myWindow.document.write('<html><head><title>' + title + '</title>');
    myWindow.document.write('<link rel="stylesheet" href="vendors/css/vendor.bundle.base.css"></link>');
    myWindow.document.write('<link rel="stylesheet" href="css/vertical-layout-light/style.css"></link>');
    myWindow.document.write('<link rel="stylesheet" href="css/invoice.css" type="text/css" />');
    myWindow.document.write('</head><body >');
    myWindow.document.write(data);
    myWindow.document.write('</body></html>');
    myWindow.document.close(); // necessary for IE >= 10

    myWindow.onload=function(){ // necessary if the div contain images

        myWindow.focus(); // necessary for IE >= 10
        myWindow.print();
        myWindow.close();
    };
}