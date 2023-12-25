<!-- plugins:js -->
<script src="vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="vendors/chart.js/Chart.min.js"></script>
<script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="js/dataTables.select.min.js"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="js/off-canvas.js"></script>
<script src="js/hoverable-collapse.js"></script>
<script src="js/template.js"></script>
<script src="js/settings.js"></script>
<script src="js/todolist.js"></script>
<script src="vendors/pwstabs/jquery.pwstabs.min.js"></script>
<script src="vendors/codemirror/codemirror.js"></script>
<script src="vendors/codemirror/javascript.js"></script>
<script src="vendors/codemirror/shell.js"></script>
<script src="js/tabs.js"></script>
<script src="js/print-invoice.js"></script>
<script src="js/print-reports.js"></script>
<!-- endinject -->
<script>
$(document).ready(function(){
    $('#deleteModal').on('shown.bs.modal', function (e) {
        var button = $(e.relatedTarget) // Button that triggered the modal
        var dataConf = button.data('conf');
        var dataRef  = button.data('ref');
        var dataAct  = button.data('action');

        $( '#del_ref' ).val( dataRef );
        $( '#del_conf' ).html( dataConf );
        $( '#delForm' ).attr( 'action', dataAct );
    })
});
</script>