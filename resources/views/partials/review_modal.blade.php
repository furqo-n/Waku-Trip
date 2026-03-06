<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="reviewModalLabel">
                    Review <span class="text-danger" style="color: #BC002D !important;">Trip Name</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                @csrf
                <input type="hidden" name="booking_id" id="reviewBookingId">

                <div class="modal-body pt-3">
                    <p class="text-secondary small mb-4">How was your experience? Your feedback helps us improve.</p>

                    <!-- Star Rating -->
                    <div class="mb-4 text-center">
                        <label class="form-label d-block fw-bold text-dark mb-2">Rate your trip</label>

                        <div class="rating-group d-inline-flex flex-row-reverse justify-content-center">
                            <!-- 5 Stars -->
                            <input class="rating__input" name="rating" id="rating-5" value="5" type="radio" required>
                            <label aria-label="5 stars" class="rating__label material-icons" for="rating-5">star</label>

                            <!-- 4 Stars -->
                            <input class="rating__input" name="rating" id="rating-4" value="4" type="radio">
                            <label aria-label="4 stars" class="rating__label material-icons" for="rating-4">star</label>

                            <!-- 3 Stars -->
                            <input class="rating__input" name="rating" id="rating-3" value="3" type="radio">
                            <label aria-label="3 stars" class="rating__label material-icons" for="rating-3">star</label>

                            <!-- 2 Stars -->
                            <input class="rating__input" name="rating" id="rating-2" value="2" type="radio">
                            <label aria-label="2 stars" class="rating__label material-icons" for="rating-2">star</label>

                            <!-- 1 Star -->
                            <input class="rating__input" name="rating" id="rating-1" value="1" type="radio">
                            <label aria-label="1 star" class="rating__label material-icons" for="rating-1">star</label>
                        </div>
                        <div id="ratingError" class="text-danger small mt-2 d-none">Please select a rating</div>
                    </div>

                    <!-- Review Text -->
                    <div class="mb-3">
                        <label for="reviewComment" class="form-label fw-bold text-dark">Your Review</label>
                        <textarea class="form-control bg-light border-0 rounded-3 p-3" id="reviewComment" name="comment"
                            rows="4" placeholder="Tell us what you liked or disliked..." required
                            minlength="10"></textarea>
                        <div class="invalid-feedback">Please describe your experience (min 10 characters).</div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4 fw-medium"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4 text-white fw-bold shadow-sm"
                        style="background-color: #BC002D; border: none;">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Hide radio inputs */
    .rating__input {
        position: absolute !important;
        opacity: 0 !important;
        pointer-events: none;
    }

    /* Star Sizing & Spacing */
    .rating__label {
        cursor: pointer;
        font-size: 2.5rem;
        /* Large stars */
        padding: 0 0.1rem;
        color: #e5e7eb;
        /* Gray-200 default */
        transition: color 0.15s ease-in-out;
    }

    /* 
       Logic: Because of flex-row-reverse, elements are DOM 5,4,3,2,1 but Visual 1,2,3,4,5.
       Use general sibling combinator (~) to select "subsequent" (visually previous) siblings.
    */

    /* Hover State: Color hovered star AND its siblings */
    .rating__label:hover,
    .rating__label:hover~.rating__label {
        color: #fbbf24 !important;
        /* Amber-400 */
    }

    /* Checked State: Color checked star AND its siblings */
    .rating__input:checked~.rating__label {
        color: #f59e0b;
        /* Amber-500 */
    }

    /* Fix: When hovering over the group, we want the hover state to take precedence over checked state */
    .rating-group:hover .rating__label {
        color: #e5e7eb;
        /* Reset all to gray first on group hover */
    }

    .rating__label:hover,
    .rating__label:hover~.rating__label {
        color: #fbbf24 !important;
        /* Re-apply hover color */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var reviewModalEl = document.getElementById('reviewModal');

        if (reviewModalEl) {
            reviewModalEl.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                var button = event.relatedTarget;

                // Extract info from data-* attributes
                var tripName = button.getAttribute('data-trip-name');
                var bookingId = button.getAttribute('data-booking-id');

                // Update the modal's content
                var modalTitleSpan = reviewModalEl.querySelector('.modal-title span');
                var bookingInput = reviewModalEl.querySelector('#reviewBookingId');

                modalTitleSpan.textContent = tripName;
                bookingInput.value = bookingId;

                // Reset form
                document.getElementById('reviewForm').reset();
                document.getElementById('ratingError').classList.add('d-none');
            });
        }

        // Form Validation
        var reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function (e) {
                var rating = document.querySelector('input[name="rating"]:checked');
                var comment = document.getElementById('reviewComment').value.trim();
                var isValid = true;

                if (!rating) {
                    e.preventDefault();
                    document.getElementById('ratingError').classList.remove('d-none');
                    isValid = false;
                }

                if (comment.length < 10) {
                    e.preventDefault();
                    // Let native browser validation or bootstrap handle textarea if needed, 
                    // focusing will show the tooltip if native
                    document.getElementById('reviewComment').focus();
                    isValid = false;
                }
            });
        }
    });
</script>