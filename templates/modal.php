<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 30px;">
            <div class="modal-body p-4">
                <h5 class="modal-title" style="color: #2B3674; font-weight: 600; font-size: 22px">Confirm Order</h5>
                <form id="approvalForm" action="payment.php" method="POST">
                    <div class="m-content">
                        <p class="my-1">Do you agree with the estimated cost?</p>
                        <div class="form-check d-inline-flex align-items-center me-3">
                            <input class="form-check-input m-0" type="radio" name="approval" id="approve"
                                value="Approve">
                            <label class="form-check-label px-1" for="approve">
                                Approve
                            </label>
                        </div>
                        <div class="form-check d-inline-flex align-items-center">
                            <input class="form-check-input m-0" type="radio" name="approval" id="reject" value="Reject">
                            <label class="form-check-label px-1" for="reject">
                                Reject
                            </label>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <input type="submit" value="Submit" class="submit-btn">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>