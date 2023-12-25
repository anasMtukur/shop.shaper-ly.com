<div class="modal" tabindex="-1" role="dialog" id="deleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Please Confirm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delForm" method="post" action="#">
                <!-- <input type="text" name="reference" name="del_ref" value="" /> -->
                <div class="modal-body">
                    <p id="del_conf" class="text-danger"></p>

                    <div class="row">
                        <div class="form-group col-12">
                            <input type="text" name="reference" class="form-control" id="del_ref" required readonly />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="delete" value="submit" class="btn btn-outline-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © <?= date('Y') ?>. <a href="https://www.shaper-ly.com/" target="_blank">Shaper-ly Photography & Printing.</a> All rights reserved.</span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">By DottedI Software Solutions</span>
    </div>
</footer>

