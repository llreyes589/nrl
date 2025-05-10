<!-- Modal -->
<div class="modal fade" id="new-pt-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark">
            <div class="modal-header text-light" style="border-bottom: 0;">
                <h5 class="modal-title">New Proficiency Testing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body bg-dark">
                <div class="mx-3 ">
                    <div class="card px-3">
                      <div class="card-body">
                          <p class="d-flex justify-content-between ">
                              <span>SDTL</span>
                              <strong>{{ $pt->sdtl }}</strong>
                          </p>
                          <p class="d-flex justify-content-between">
                              <span>CYCLE</span>
                              <strong>{{ $pt->cycle }}</strong>
                          </p>
                          <p class="d-flex justify-content-between">
                              <span>Total Amount</span>
                              <strong>{{ $pt->total_amount }}</strong>
                          </p>
                          <p class="d-flex justify-content-between">
                              <span>Validity</span>
                              <strong>{{ \Carbon\Carbon::parse($pt->cert_validity)->format('M d Y') }}</strong>
                          </p>
                          <p class="d-flex justify-content-between">
                              <span>Application Limit</span>
                              <strong>{{ $pt->application_limit }}</strong>
                          </p>
                          <p class="d-flex justify-content-between">
                              <span>Date added</span>
                              <strong>{{ \Carbon\Carbon::parse($pt->created_at)->diffForHumans() }}</strong>
                          </p>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-dark" style="border-top: 0;">
                <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Back to login</button>
            </div>
        </div>
    </div>
</div>