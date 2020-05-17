<div class="modalWrapper" id="sellTicketModalWrapper" onclick="">
    <div class="modal employeeModal centerX" id="employeeModal">
        <div class="modalHead centerX spaceBetween">
            <span>Sell ticket</span>
            <img src="/assets/close.svg" 
                onClick="
                document.getElementById('sellTicketModalWrapper').classList.remove('showModal');
                "
                class="modalClose"
                alt="Close form" />
        </div>
        
        <div class="modalRow">
            <div class="modalColumn wauto mr-3">
                <label for="movieName" class="ml-1">Movie name</label>
                <select class="formSelect" name="movieName" required id="movieName">

                </select>
            </div>
            <div class="modalColumn w15 mr-3">
                <label for="startTime" class="ml-1">Date</label>
                <select class="formSelect" name="startTime" required id="date">

                </select>
			</div>
            <div class="modalColumn w10 mr-3">
                <label for="startTime" class="ml-1">Start time</label>
                <select class="formSelect" name="startTime" required id="startTime">

                </select>
			</div>
            <div class="modalColumn w15 mr-3">
                <label for="room" class="ml-1">Room</label>
                <select class="formSelect" name="room" required id="rooms">

                </select>
            </div>     
            <div class="modalColumn w10">
                <label for="technology" class="ml-1">Technology</label>
                <select class="formSelect" name="technology" required id="technology">

                </select>
            </div>     
        </div>
        <div class="row w100 mt-5 reservationWrapper">
                <div class="column w15priceColumn">
                    <span class="reservationTitle">Your selected seats</span>
                    <div class='column mt-2 mb-3'>
                        <span class="reservationSubtitle" id="numSeats"></span>
                        <div class="reservedSeats mt-3 textCenter" id ="reservedSeats">
                        </div>
					</div>
					<div class="row">
						<img src="/assets/Reservation/dots.svg" class="hrDots" />
					</div>
                    <div class="column mt-3">
                        <div class="row spaceBetween">
                            <span class="reservationBottom">Total:</span>
                            <span class="reservationBottom" id="totalPrice"></span>
                        </div>
                    </div>
					
                </div>
                <div class="column w70 screenColumn">
					<div class="row mb-5">
						<img src="/assets/Reservation/screen.svg" class="reservationScreen" />
					</div>
					<div class="row centerRow">
						<div id="projectionSeating">

                        </div>
                        
                    </div>
					<div class="row centerRow mt-2">
						<span class="projectionError" id="projectionError">You can only select a maximum of 6 seats!</span>
					</div>
                </div>
                <div class="column w15 legendColumn">
					<div class="row">
                    	<span class="reservationTitle mb-2">Legend</span>
					</div>
                    <div class="legendStep row centerY mb-2">
                        <div class="legend legendAvailable mr-2"></div> <span class="legendName">Available</span>
                    </div>
                    <div class="legendStep row centerY mb-2">
                        <div class="legend legendReserved mr-2"></div> <span class="legendName">Reserved</span>
                    </div>
                    <div class="legendStep row centerY">
                        <div class="legend legendSelected mr-2"></div> <span class="legendName">Selected</span>
					</div>
					
					<div class="column mt-5">
						<span class="reservationCinema" id="reservationCinema"><?php echo $cinema->name ?></span>
						<span class="reservationRoom" id="reservationRoom"></span>

                    </div>
                    <div class="row centerRow mt-2">
                        <span class="formError hideModal" id="workerConfirmError">Seat taken!</span>
                        <span class="formSuccess hideModal" id="workerConfirmSuccess">Seat sold!</span>
                    </div>
                </div>
			</div>
        <div class="modalRow mt-4">
            <button type="submit" id="employeeSellConfirm" class="workerConfirmButton"
            onclick="initiateWorkerConfirm();"
            >Confirm</button>
        </div>
        <input type='hidden' id="rows" value="">
        <input type='hidden' id="cols" value="">
        <input type='hidden' id="price" value="">
    </div>
</div>