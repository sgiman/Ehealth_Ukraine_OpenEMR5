<!-- ========================= Notification Modal ======================= -->
    <div class="modal fade modal-lg" id="NotificationModal" tabindex="-1" 
         data-focus-on="button:first" style="display: none; margin: 0 auto">
        <div class="bg-default">
            <div class="modal-header">
                <h4 class="modal-title"><span id="NotificationTitle"></span></h4>
            </div>
            <div class="modal-body">
                <span id="NotificationBodySpan" hidden></span>
                <div class="form-horizontal col-md-12" id="NotificationBodyForm">
                    <div class="form-group">
                        <div class="scrollable-content scrollable-xs scrollable-slim" id="NotificationTextList">
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
<!-- ============================================================== -->
