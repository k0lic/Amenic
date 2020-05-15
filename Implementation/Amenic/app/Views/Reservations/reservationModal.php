<div class="modalWrapper" id="reservationModal" 
        onclick="
        closeResModal()
        ">
    <div class="modal centerX" id="resConfirm">
            <div class="modalHead centerX spaceBetween">
                <span>Reservation</span>
                    <img src="/assets/close.svg" class="modalClose" alt="Close form" onclick="closeResModal()"/>
            </div>
            <div class="modalColumn">
                <label for="seats" class="reservationBottom">Seats:</label>
                <span id="reservationModalSeats"></span>
            </div>

            <div class="row w100 mt-2">
                <label for="total" class="reservationBottom">Total:</label>
                <span class="reservationBottom ml-2" id="reservationModalTotal"></span>
            </div>

            <div class="modalRow centerY modalBottom mt-2">
                <button name="confirmReservation" class="modalButton reservationModalButton"
                        onclick="initiateConfirm()" id="confirmButton">
                    Confirm
                </button>
            </div>
    </div>

    <div class="modal hideModal centerX" id="resSuccess">
            <div class="modalHead centerX spaceBetween">
                <span>Success!</span>
                    <img src="/assets/close.svg" class="modalClose" alt="Close form" onclick="msgCloseModal()"/>
            </div>
            <div class="modalColumn">
                <label for="seats" class="reservationBottom">Your tickets are reserved, and you should receive an email confirmation soon.</label>
            </div>
    </div>

    <div class="modal hideModal centerX" id="resError">
            <div class="modalHead centerX spaceBetween">
                <span>Oh no!</span>
                    <img src="/assets/close.svg" class="modalClose" alt="Close form" onclick="msgCloseModal()"/>
            </div>
            <div class="modalColumn">
                <label for="seats" class="reservationBottom">You've already reserved 6 tickets for this screening.</label>
            </div>
    </div>
</div>
