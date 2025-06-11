<!-- Modal User -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 30px;">
      <div class="modal-body p-4">
        <h5 class="modal-title mb-4" style="color: #2B3674; font-weight: 600; font-size: 22px">Confirm Order
        </h5>
        <form id="userApprovalForm" action="payment.php" method="POST">
          <div class="mb-3">
            <p class="mb-2">Do you agree with the estimated cost?</p>
            <div class="d-flex">
              <div class="form-check form-check-inline me-3"> <input class="form-check-input" type="radio"
                  name="userApproval" id="approve2" value="Approve">
                <label class="form-check-label" for="approve2">Approve</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="userApproval" id="reject2" value="Reject">
                <label class="form-check-label" for="reject2">Reject</label>
              </div>
            </div>
          </div>
          <input name="id" type="hidden" id="approval-id-input" value="">
          <div class="mt-4 text-end">
            <input type="submit" value="Submit" class="submit-btn" name="updateUserApproval">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Pemilik -->
<div class="modal fade" id="ownerModal" tabindex="-1" aria-labelledby="ownerModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 30px;">
      <div class="modal-body p-4">
        <h5 class="modal-title mb-4" style="color: #2B3674; font-weight: 600; font-size: 22px">Update Request
          Order
        </h5>
        <form id="OwnerApprovalForm" action="orders.php" method="POST">
          <div class="mb-3 row align-items-center">
            <label class="col-sm-5 col-form-label row-label">Approval
              Status</label>
            <div class="col-sm-7">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ownerApproval" id="approve" value="Approve">
                <label class="form-check-label" for="approve">Approve</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ownerApproval" id="reject" value="Reject">
                <label class="form-check-label" for="reject">Reject</label>
              </div>
            </div>
          </div>

          <div class="mb-3 row align-items-center">
            <label for="priceInput" class="col-sm-5 col-form-label row-label">Price</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="priceInput" name="price" placeholder="Enter Price">
            </div>
          </div>

          <div class="mb-4 row align-items-center"> <label for="completionDateInput"
              class="col-sm-5 col-form-label row-label">Estimated Completion Date</label>
            <div class="col-sm-7">
              <input type="date" class="form-control" id="completionDateInput" name="estimation"
                placeholder="Enter Date">
            </div>
          </div>
          <input name="id" type="hidden" id="owner-id-input" value="">
          <div class="text-end">
            <input type="submit" value="Submit" class="submit-btn">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Staff Modal -->
<div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 30px;">
      <div class="modal-body p-4">
        <h5 class="modal-title mb-4" style="color: #2B3674; font-weight: 600; font-size: 22px">Update Request
          Order
        </h5>
        <form id="staffUpdateForm" action="index.php" method="POST">
          <div class="mb-3"> <label for="statusSelect" class="form-label visually-hidden">Select a status</label>
            <select class="form-select" id="statusSelect" aria-label="Select a status" name="status" required>
              <option selected disabled hidden value="">Select a status</option>
              <option value="pending">Pending</option>
              <option value="ongoing">On Going</option>
              <option value="finishing">Finishing</option>
              <!-- muk nambah "shipped" kah mint? -->
              <option value="completed">Completed</option>
            </select>
          </div>
          <input name="id" type="hidden" id="staff-id-input" value="">
          <div class="text-end mt-3">
            <input type="submit" value="Submit" class="submit-btn">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>